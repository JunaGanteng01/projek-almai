<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\ArtikelPurchaseModel;
use App\Models\PoinModel;
use App\Models\WpaModel;
use App\Models\NotificationModel;
use App\Libraries\PoinService;

class Artikel extends BaseController
{
    protected $artikelModel;
    protected $purchaseModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
        $this->purchaseModel = new ArtikelPurchaseModel();
    }

    public function index()
    {
        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');

        $artikelList = $this->artikelModel->getFiltered($category, $search);
        
        // Check which articles user has purchased
        $purchasedIds = [];
        if (session()->get('isLoggedIn')) {
            $purchasedIds = $this->purchaseModel->getPurchasedArtikelIds(session()->get('userId'));
        }

        $data = [
            'title' => 'Artikel - Almai E-Learning',
            'artikelList' => $artikelList,
            'purchasedIds' => $purchasedIds,
            'categories' => ['Tips Trading', 'Fundamental', 'Crypto', 'Commodity', 'Strategy', 'Technology'],
            'currentCategory' => $category,
            'searchQuery' => $search,
        ];

        return view('pages/artikel/index', $data);
    }

    public function detail($id)
    {
        $artikel = $this->artikelModel->select('artikel.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.user_id as wpa_user_id')
                                      ->join('wpa', 'wpa.id = artikel.wpa_id')
                                      ->where('artikel.verification_status', 'approved')
                                      ->find($id);

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $userId = session()->get('userId');
        $hasPurchased = false;
        $userPoin = 0;
        
        if ($userId) {
            $hasPurchased = $this->purchaseModel->hasPurchased($userId, $id);
            $poinModel = new PoinModel();
            $userPoin = $poinModel->getUserBalance($userId);
        }
        
        // Check if article is free or user has purchased
        $canRead = $artikel['is_free'] || $artikel['poin_price'] == 0 || $hasPurchased;

        $data = [
            'title' => $artikel['title'] . ' - Almai E-Learning',
            'artikel' => $artikel,
            'canRead' => $canRead,
            'hasPurchased' => $hasPurchased,
            'userPoin' => $userPoin,
            'relatedArtikel' => $this->artikelModel->getFiltered($artikel['category'], null),
        ];

        return view('pages/artikel/detail', $data);
    }

    public function purchase($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('userId');
        
        $artikel = $this->artikelModel->select('artikel.*, wpa.user_id as wpa_user_id')
                                      ->join('wpa', 'wpa.id = artikel.wpa_id')
                                      ->where('artikel.verification_status', 'approved')
                                      ->find($id);

        if (!$artikel) {
            return redirect()->back()->with('error', 'Artikel tidak ditemukan');
        }

        // Check if already purchased
        if ($this->purchaseModel->hasPurchased($userId, $id)) {
            return redirect()->to('/artikel/' . $id)->with('info', 'Anda sudah memiliki akses ke artikel ini');
        }

        // Check if free
        if ($artikel['is_free'] || $artikel['poin_price'] == 0) {
            $this->purchaseModel->insert([
                'user_id' => $userId,
                'artikel_id' => $id,
                'poin_spent' => 0,
            ]);
            return redirect()->to('/artikel/' . $id)->with('success', 'Artikel gratis! Selamat membaca.');
        }

        // Check user poin balance
        $poinModel = new PoinModel();
        $userPoin = $poinModel->getUserBalance($userId);

        if ($userPoin < $artikel['poin_price']) {
            return redirect()->back()->with('error', 'Poin Anda tidak cukup. Butuh ' . number_format($artikel['poin_price']) . ' poin.');
        }

        // Deduct poin from user
        $poinService = new PoinService();
        $poinService->deductPoin(
            $userId,
            $artikel['poin_price'],
            "Beli artikel: {$artikel['title']}",
            'artikel',
            $id
        );

        // Record purchase
        $this->purchaseModel->insert([
            'user_id' => $userId,
            'artikel_id' => $id,
            'poin_spent' => $artikel['poin_price'],
        ]);

        // Give commission to WPA
        if ($artikel['wpa_user_id']) {
            $poinService->processWpaCommission(
                $artikel['wpa_user_id'],
                $id,
                $artikel['poin_price'],
                $artikel['title'],
                $userId
            );
        }

        // Send notification to user
        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $userId,
            'Artikel Dibeli! 📖',
            "Anda berhasil membeli artikel \"{$artikel['title']}\" dengan {$artikel['poin_price']} poin.",
            'success',
            '/artikel/' . $id
        );

        return redirect()->to('/artikel/' . $id)->with('success', 'Artikel berhasil dibeli! Selamat membaca.');
    }
}
