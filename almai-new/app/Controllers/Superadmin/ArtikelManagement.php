<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;
use App\Models\WpaModel;
use App\Models\NotificationModel;
use App\Models\UserModel;

class ArtikelManagement extends BaseController
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
        $filter = $this->request->getGet('filter') ?? 'all';
        
        $builder = $this->artikelModel->select('artikel.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.user_id as wpa_user_id')
                                      ->join('wpa', 'wpa.id = artikel.wpa_id', 'left');
        
        if ($filter === 'pending') {
            $builder->where('artikel.verification_status', 'pending');
        } elseif ($filter === 'approved') {
            $builder->where('artikel.verification_status', 'approved');
        } elseif ($filter === 'rejected') {
            $builder->where('artikel.verification_status', 'rejected');
        }
        
        $artikelList = $builder->orderBy('artikel.created_at', 'DESC')->paginate(20);
        $pager = $this->artikelModel->pager;
        
        // Count by status
        $pendingCount = $this->artikelModel->where('verification_status', 'pending')->countAllResults();
        $approvedCount = $this->artikelModel->where('verification_status', 'approved')->countAllResults();
        $rejectedCount = $this->artikelModel->where('verification_status', 'rejected')->countAllResults();

        return view('superadmin/artikel/index', [
            'title' => 'Kelola Artikel - Admin Dashboard',
            'pageTitle' => 'Kelola Artikel',
            'pageSubtitle' => 'Verifikasi dan kelola artikel WPA',
            'artikelList' => $artikelList,
            'pager' => $pager,
            'activeMenu' => 'artikel',
            'currentFilter' => $filter,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }

    public function create()
    {
        $wpaList = $this->wpaModel->where('status', 'active')->findAll();

        return view('superadmin/artikel/create', [
            'title' => 'Tambah Artikel - Admin Dashboard',
            'pageTitle' => 'Tambah Artikel',
            'pageSubtitle' => 'Buat artikel baru',
            'wpaList' => $wpaList,
            'activeMenu' => 'artikel'
        ]);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[10]',
            'wpa_id' => 'required|numeric',
            'category' => 'required',
            'excerpt' => 'required|min_length[20]',
            'content' => 'required|min_length[50]',
            'poin_price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true) . '-' . time();

        $this->artikelModel->insert([
            'wpa_id' => $this->request->getPost('wpa_id'),
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $this->request->getPost('thumbnail') ?: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category'),
            'read_time' => $this->request->getPost('read_time') ?: '5 min',
            'poin_price' => $this->request->getPost('poin_price'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
            'status' => $this->request->getPost('status') ?: 'draft',
            'verification_status' => 'approved', // Admin created = auto approved
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => session()->get('userId'),
        ]);

        return redirect()->to('/superadmin/artikel')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $artikel = $this->artikelModel->find($id);
        if (!$artikel) {
            return redirect()->to('/superadmin/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $wpaList = $this->wpaModel->where('status', 'active')->findAll();

        return view('superadmin/artikel/edit', [
            'title' => 'Edit Artikel - Admin Dashboard',
            'pageTitle' => 'Edit Artikel',
            'pageSubtitle' => 'Ubah data artikel',
            'artikel' => $artikel,
            'wpaList' => $wpaList,
            'activeMenu' => 'artikel'
        ]);
    }

    public function update($id)
    {
        $artikel = $this->artikelModel->find($id);
        if (!$artikel) {
            return redirect()->to('/superadmin/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $rules = [
            'title' => 'required|min_length[10]',
            'wpa_id' => 'required|numeric',
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

        $this->artikelModel->update($id, [
            'wpa_id' => $this->request->getPost('wpa_id'),
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $this->request->getPost('thumbnail') ?: $artikel['thumbnail'],
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category'),
            'read_time' => $this->request->getPost('read_time') ?: $artikel['read_time'],
            'poin_price' => $this->request->getPost('poin_price'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/superadmin/artikel')->with('success', 'Artikel berhasil diupdate!');
    }

    public function approve($id)
    {
        $artikel = $this->artikelModel->select('artikel.*, wpa.user_id as wpa_user_id, wpa.name as wpa_name')
                                      ->join('wpa', 'wpa.id = artikel.wpa_id')
                                      ->where('artikel.id', $id)
                                      ->first();
        
        if (!$artikel) {
            return redirect()->to('/superadmin/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $this->artikelModel->update($id, [
            'verification_status' => 'approved',
            'status' => 'published',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => session()->get('userId'),
            'rejection_reason' => null,
        ]);

        // Send notification to WPA
        if ($artikel['wpa_user_id']) {
            $notifModel = new NotificationModel();
            $notifModel->createNotification(
                $artikel['wpa_user_id'],
                'Artikel Disetujui! ✅',
                "Artikel \"{$artikel['title']}\" telah disetujui dan dipublikasikan.",
                'success',
                '/wpa/dashboard/artikel'
            );
        }

        return redirect()->to('/superadmin/artikel?filter=pending')->with('success', 'Artikel berhasil disetujui!');
    }

    public function reject($id)
    {
        $artikel = $this->artikelModel->select('artikel.*, wpa.user_id as wpa_user_id, wpa.name as wpa_name')
                                      ->join('wpa', 'wpa.id = artikel.wpa_id')
                                      ->where('artikel.id', $id)
                                      ->first();
        
        if (!$artikel) {
            return redirect()->to('/superadmin/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $reason = $this->request->getPost('reason') ?: 'Tidak memenuhi standar konten';

        $this->artikelModel->update($id, [
            'verification_status' => 'rejected',
            'status' => 'draft',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => session()->get('userId'),
            'rejection_reason' => $reason,
        ]);

        // Send notification to WPA
        if ($artikel['wpa_user_id']) {
            $notifModel = new NotificationModel();
            $notifModel->createNotification(
                $artikel['wpa_user_id'],
                'Artikel Ditolak ❌',
                "Artikel \"{$artikel['title']}\" ditolak. Alasan: {$reason}",
                'error',
                '/wpa/dashboard/artikel'
            );
        }

        return redirect()->to('/superadmin/artikel?filter=pending')->with('success', 'Artikel berhasil ditolak!');
    }

    public function delete($id)
    {
        $artikel = $this->artikelModel->find($id);
        if (!$artikel) {
            return redirect()->to('/superadmin/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $this->artikelModel->delete($id);

        return redirect()->to('/superadmin/artikel')->with('success', 'Artikel berhasil dihapus!');
    }
}
