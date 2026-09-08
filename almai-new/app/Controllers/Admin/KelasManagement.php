<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\WpaModel;

class KelasManagement extends BaseController
{
    protected $kelasModel;
    protected $wpaModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->wpaModel = new WpaModel();
    }

    public function index()
    {
        $kelasList = $this->kelasModel->select('kelas.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                                      ->join('wpa', 'wpa.id = kelas.wpa_id', 'left')
                                      ->orderBy('kelas.created_at', 'DESC')
                                      ->paginate(20);
        $pager = $this->kelasModel->pager;

        // Get real student count from confirmed transactions
        $db = \Config\Database::connect();
        foreach ($kelasList as &$kelas) {
            $result = $db->query("SELECT COUNT(DISTINCT user_id) as count FROM transaksi WHERE product_type = 'kelas' AND kelas_id = ? AND status = 'confirmed'", [$kelas['id']])->getRow();
            $kelas['real_students'] = (int) ($result->count ?? 0);
        }

        return view('admin/kelas/index', [
            'title' => 'Kelola Kelas - Admin Dashboard',
            'kelasList' => $kelasList,
            'pager' => $pager,
            'activeMenu' => 'kelas'
        ]);
    }

    public function create()
    {
        $wpaList = $this->wpaModel->where('status', 'active')->findAll();

        return view('admin/kelas/create', [
            'title' => 'Tambah Kelas - Admin Dashboard',
            'wpaList' => $wpaList,
            'categories' => $this->kelasModel->getCategories(),
            'activeMenu' => 'kelas'
        ]);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[5]',
            'wpa_id' => 'required|numeric',
            'category' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|min_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);

        $highlights = $this->request->getPost('highlights');
        if ($highlights) {
            $highlights = json_encode(array_map('trim', explode(',', $highlights)));
        }

        // Handle thumbnail upload
        $thumbnail = $this->request->getPost('thumbnail') ?: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600';
        $thumbnailFile = $this->request->getFile('thumbnail_file');
        
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            // Validate file
            if ($thumbnailFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('errors', ['thumbnail_file' => 'Ukuran file maksimal 2MB']);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (!in_array($thumbnailFile->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('errors', ['thumbnail_file' => 'Format file harus JPG, PNG, atau WebP']);
            }
            
            // Generate unique filename
            $newName = 'kelas_' . time() . '_' . $thumbnailFile->getRandomName();
            
            // Move to public/uploads/kelas
            $uploadPath = FCPATH . 'uploads/kelas';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $thumbnailFile->move($uploadPath, $newName);
            $thumbnail = base_url('uploads/kelas/' . $newName);
        }

        $this->kelasModel->insert([
            'wpa_id' => $this->request->getPost('wpa_id'),
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $thumbnail,
            'price' => $this->request->getPost('price'),
            'original_price' => $this->request->getPost('original_price') ?: $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration') ?: '10 Jam',
            'modules' => $this->request->getPost('modules') ?: 20,
            'level' => $this->request->getPost('level') ?: 'Beginner',
            'category' => $this->request->getPost('category'),
            'mode' => $this->request->getPost('mode') ?: 'Online',
            'location' => $this->request->getPost('location') ?: 'Zoom',
            'type' => $this->request->getPost('type') ?: 'recorded',
            'zoom_link' => $this->request->getPost('zoom_link'),
            'schedule' => $this->request->getPost('schedule'),
            'description' => $this->request->getPost('description'),
            'highlights' => $highlights,
            'status' => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = $this->kelasModel->find($id);
        if (!$kelas) {
            return redirect()->to('/admin/kelas')->with('error', 'Kelas tidak ditemukan');
        }

        $wpaList = $this->wpaModel->where('status', 'active')->findAll();

        return view('admin/kelas/edit', [
            'title' => 'Edit Kelas - Admin Dashboard',
            'kelas' => $kelas,
            'wpaList' => $wpaList,
            'categories' => $this->kelasModel->getCategories(),
            'activeMenu' => 'kelas'
        ]);
    }

    public function update($id)
    {
        $kelas = $this->kelasModel->find($id);
        if (!$kelas) {
            return redirect()->to('/admin/kelas')->with('error', 'Kelas tidak ditemukan');
        }

        $rules = [
            'title' => 'required|min_length[5]',
            'wpa_id' => 'required|numeric',
            'category' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|min_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);

        $highlights = $this->request->getPost('highlights');
        if ($highlights) {
            $highlights = json_encode(array_map('trim', explode(',', $highlights)));
        }

        // Handle thumbnail upload
        $thumbnail = $this->request->getPost('thumbnail') ?: $kelas['thumbnail'];
        $thumbnailFile = $this->request->getFile('thumbnail_file');
        
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            // Validate file
            if ($thumbnailFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('errors', ['thumbnail_file' => 'Ukuran file maksimal 2MB']);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (!in_array($thumbnailFile->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('errors', ['thumbnail_file' => 'Format file harus JPG, PNG, atau WebP']);
            }
            
            // Generate unique filename
            $newName = 'kelas_' . time() . '_' . $thumbnailFile->getRandomName();
            
            // Move to public/uploads/kelas
            $uploadPath = FCPATH . 'uploads/kelas';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $thumbnailFile->move($uploadPath, $newName);
            $thumbnail = base_url('uploads/kelas/' . $newName);
            
            // Delete old file if it's a local upload
            if (strpos($kelas['thumbnail'], 'uploads/kelas/') !== false) {
                $oldFile = FCPATH . str_replace(base_url(), '', $kelas['thumbnail']);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }

        $this->kelasModel->update($id, [
            'wpa_id' => $this->request->getPost('wpa_id'),
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $thumbnail,
            'price' => $this->request->getPost('price'),
            'original_price' => $this->request->getPost('original_price') ?: $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration'),
            'modules' => $this->request->getPost('modules'),
            'level' => $this->request->getPost('level'),
            'category' => $this->request->getPost('category'),
            'mode' => $this->request->getPost('mode'),
            'location' => $this->request->getPost('location'),
            'type' => $this->request->getPost('type'),
            'zoom_link' => $this->request->getPost('zoom_link'),
            'schedule' => $this->request->getPost('schedule'),
            'description' => $this->request->getPost('description'),
            'highlights' => $highlights,
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil diupdate!');
    }

    public function delete($id)
    {
        $kelas = $this->kelasModel->find($id);
        if (!$kelas) {
            return redirect()->to('/admin/kelas')->with('error', 'Kelas tidak ditemukan');
        }

        $this->kelasModel->delete($id);

        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil dihapus!');
    }
}
