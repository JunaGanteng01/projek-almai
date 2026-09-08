<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\WpaModel;
use App\Models\UlasanModel;
use App\Models\TransaksiModel;

class Kelas extends BaseController
{
    protected $kelasModel;
    protected $ulasanModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->ulasanModel = new UlasanModel();
    }

    public function index()
    {
        $category = $this->request->getGet('category');
        $mode = $this->request->getGet('mode');
        $search = $this->request->getGet('search');

        $kelasList = $this->kelasModel->getFiltered($category, $mode, $search);

        $data = [
            'title' => 'Kelas Trading - Almai E-Learning',
            'kelasList' => $kelasList,
            'categories' => $this->kelasModel->getCategories(),
            'modes' => ['Online', 'Offline', 'Hybrid'],
            'currentCategory' => $category,
            'currentMode' => $mode,
            'searchQuery' => $search,
        ];

        return view('pages/kelas/index', $data);
    }

    public function detail($id)
    {
        $kelas = $this->kelasModel->find($id);

        if (!$kelas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Store referral code if present in URL
        $refCode = $this->request->getGet('ref');
        if ($refCode) {
            session()->set('checkout_ref', $refCode);
        }

        $wpaModel = new WpaModel();
        $transaksiModel = new TransaksiModel();

        // Check if user has purchased this class
        $hasPurchased = false;
        $hasReviewed = false;
        $userId = session()->get('userId');
        
        if ($userId) {
            $purchase = $transaksiModel->where('user_id', $userId)
                                       ->where('kelas_id', $id)
                                       ->where('status', 'confirmed')
                                       ->first();
            $hasPurchased = $purchase !== null;
            $hasReviewed = $this->ulasanModel->hasUserReviewed($id, $userId);
        }

        // Get reviews
        $ulasan = $this->ulasanModel->getByKelas($id);

        $data = [
            'title' => $kelas['title'] . ' - Almai E-Learning',
            'kelas' => $kelas,
            'wpa' => $wpaModel->find($kelas['wpa_id']),
            'relatedKelas' => $this->kelasModel->getRelated($id, $kelas['category'], 3),
            'ulasan' => $ulasan,
            'hasPurchased' => $hasPurchased,
            'hasReviewed' => $hasReviewed,
            'averageRating' => $this->ulasanModel->getAverageRating($id),
            'reviewCount' => $this->ulasanModel->getReviewCount($id),
        ];

        return view('pages/kelas/detail', $data);
    }

    public function submitUlasan($kelasId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('userId');
        
        // Check if user has purchased this class
        $transaksiModel = new TransaksiModel();
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('kelas_id', $kelasId)
                                   ->where('status', 'confirmed')
                                   ->first();
        
        if (!$purchase) {
            return redirect()->back()->with('error', 'Anda harus membeli kelas ini terlebih dahulu untuk memberikan ulasan');
        }

        // Check if user already reviewed
        if ($this->ulasanModel->hasUserReviewed($kelasId, $userId)) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk kelas ini');
        }

        // Validate input
        $rules = [
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'ulasan' => 'required|min_length[10]|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Rating dan ulasan wajib diisi (minimal 10 karakter)');
        }

        // Save review
        $this->ulasanModel->insert([
            'kelas_id' => $kelasId,
            'user_id' => $userId,
            'rating' => $this->request->getPost('rating'),
            'ulasan' => $this->request->getPost('ulasan'),
            'status' => 'approved',
        ]);

        // Update kelas rating
        $newRating = $this->ulasanModel->getAverageRating($kelasId);
        $this->kelasModel->update($kelasId, ['rating' => $newRating]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan');
    }
}
