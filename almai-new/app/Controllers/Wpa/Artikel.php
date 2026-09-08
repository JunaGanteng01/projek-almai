<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;
use App\Models\WpaModel;
use App\Models\NotificationModel;
use App\Models\UserModel;

class Artikel extends BaseController
{
    protected $artikelModel;
    protected $wpaModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
        $this->wpaModel = new WpaModel();
    }

    public function index()
    {
        $wpaId = session()->get('wpaId');
        
        $artikelList = $this->artikelModel->where('wpa_id', $wpaId)
                                          ->orderBy('created_at', 'DESC')
                                          ->findAll();
        
        // Count by status
        $pendingCount = $this->artikelModel->where('wpa_id', $wpaId)->where('verification_status', 'pending')->countAllResults();
        $approvedCount = $this->artikelModel->where('wpa_id', $wpaId)->where('verification_status', 'approved')->countAllResults();
        $rejectedCount = $this->artikelModel->where('wpa_id', $wpaId)->where('verification_status', 'rejected')->countAllResults();

        return view('wpa/artikel/index', [
            'title' => 'Artikel Saya - WPA Dashboard',
            'artikelList' => $artikelList,
            'activeMenu' => 'artikel',
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }

    public function create()
    {
        return view('wpa/artikel/create', [
            'title' => 'Buat Artikel - WPA Dashboard',
            'activeMenu' => 'artikel'
        ]);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[10]',
            'category' => 'required',
            'excerpt' => 'required|min_length[20]',
            'content' => 'required|min_length[50]',
            'poin_price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $wpaId = session()->get('wpaId');
        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true) . '-' . time();

        $this->artikelModel->insert([
            'wpa_id' => $wpaId,
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $this->request->getPost('thumbnail') ?: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category'),
            'read_time' => $this->request->getPost('read_time') ?: '5 min',
            'poin_price' => $this->request->getPost('poin_price'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
            'status' => 'draft',
            'verification_status' => 'pending',
        ]);

        // Send notification to all admins
        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        $notifModel = new NotificationModel();
        $wpaName = session()->get('wpaName');
        
        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Artikel Baru Menunggu Verifikasi 📝',
                "WPA {$wpaName} mengirimkan artikel baru: \"{$title}\"",
                'info',
                '/admin/artikel?filter=pending'
            );
        }

        return redirect()->to('/wpa/dashboard/artikel')->with('success', 'Artikel berhasil dikirim untuk verifikasi!');
    }

    public function edit($id)
    {
        $wpaId = session()->get('wpaId');
        $artikel = $this->artikelModel->where('id', $id)->where('wpa_id', $wpaId)->first();
        
        if (!$artikel) {
            return redirect()->to('/wpa/dashboard/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        return view('wpa/artikel/edit', [
            'title' => 'Edit Artikel - WPA Dashboard',
            'artikel' => $artikel,
            'activeMenu' => 'artikel'
        ]);
    }

    public function update($id)
    {
        $wpaId = session()->get('wpaId');
        $artikel = $this->artikelModel->where('id', $id)->where('wpa_id', $wpaId)->first();
        
        if (!$artikel) {
            return redirect()->to('/wpa/dashboard/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $rules = [
            'title' => 'required|min_length[10]',
            'category' => 'required',
            'excerpt' => 'required|min_length[20]',
            'content' => 'required|min_length[50]',
            'poin_price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);

        $updateData = [
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $this->request->getPost('thumbnail') ?: $artikel['thumbnail'],
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category'),
            'read_time' => $this->request->getPost('read_time') ?: $artikel['read_time'],
            'poin_price' => $this->request->getPost('poin_price'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
        ];

        // If article was rejected, resubmit for verification
        if ($artikel['verification_status'] === 'rejected') {
            $updateData['verification_status'] = 'pending';
            $updateData['rejection_reason'] = null;
            
            // Notify admins
            $userModel = new UserModel();
            $admins = $userModel->where('role', 'admin')->findAll();
            $notifModel = new NotificationModel();
            $wpaName = session()->get('wpaName');
            
            foreach ($admins as $admin) {
                $notifModel->createNotification(
                    $admin['id'],
                    'Artikel Direvisi 📝',
                    "WPA {$wpaName} merevisi artikel: \"{$title}\"",
                    'info',
                    '/admin/artikel?filter=pending'
                );
            }
        }

        $this->artikelModel->update($id, $updateData);

        return redirect()->to('/wpa/dashboard/artikel')->with('success', 'Artikel berhasil diupdate!');
    }

    public function delete($id)
    {
        $wpaId = session()->get('wpaId');
        $artikel = $this->artikelModel->where('id', $id)->where('wpa_id', $wpaId)->first();
        
        if (!$artikel) {
            return redirect()->to('/wpa/dashboard/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        // Only allow delete if not approved
        if ($artikel['verification_status'] === 'approved') {
            return redirect()->to('/wpa/dashboard/artikel')->with('error', 'Artikel yang sudah disetujui tidak dapat dihapus');
        }

        $this->artikelModel->delete($id);

        return redirect()->to('/wpa/dashboard/artikel')->with('success', 'Artikel berhasil dihapus!');
    }
}
