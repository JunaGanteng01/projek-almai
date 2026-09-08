<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\EventBannerModel;

class EventBannerManagement extends BaseController
{
    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new EventBannerModel();
    }

    public function index()
    {
        $banners = $this->bannerModel->orderBy('order', 'ASC')->findAll();

        return view('superadmin/event_banner/index', [
            'title' => 'Kelola Hero Banner Event',
            'banners' => $banners,
            'activeMenu' => 'event_banner'
        ]);
    }

    public function create()
    {
        return view('superadmin/event_banner/create', [
            'title' => 'Tambah Hero Banner Event',
            'activeMenu' => 'event_banner'
        ]);
    }

    public function store()
    {
        $rules = [
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageFile = $this->request->getFile('image');
        $imageName = 'banner_' . time() . '_' . $imageFile->getRandomName();
        $uploadPath = FCPATH . 'uploads/banners';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $imageFile->move($uploadPath, $imageName);
        $imageUrl = 'uploads/banners/' . $imageName;

        $this->bannerModel->insert([
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'url' => $this->request->getPost('url'),
            'image' => $imageUrl,
            'is_active' => $this->request->getPost('is_active') ?? 1,
            'order' => $this->request->getPost('order') ?? 0,
        ]);

        return redirect()->to('/superadmin/event-banner')->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            return redirect()->to('/superadmin/event-banner')->with('error', 'Banner tidak ditemukan');
        }

        return view('superadmin/event_banner/edit', [
            'title' => 'Edit Hero Banner Event',
            'banner' => $banner,
            'activeMenu' => 'event_banner'
        ]);
    }

    public function update($id)
    {
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            return redirect()->to('/superadmin/event-banner')->with('error', 'Banner tidak ditemukan');
        }

        $rules = [];

        // Image is optional on update
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]';
        }

        if (!empty($rules)) {
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'url' => $this->request->getPost('url'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
            'order' => $this->request->getPost('order') ?? 0,
        ];

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = 'banner_' . time() . '_' . $imageFile->getRandomName();
            $uploadPath = FCPATH . 'uploads/banners';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageFile->move($uploadPath, $imageName);
            $data['image'] = 'uploads/banners/' . $imageName;

            // Delete old file
            $oldFile = FCPATH . $banner['image'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->bannerModel->update($id, $data);

        return redirect()->to('/superadmin/event-banner')->with('success', 'Banner berhasil diupdate!');
    }

    public function delete($id)
    {
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            return redirect()->to('/superadmin/event-banner')->with('error', 'Banner tidak ditemukan');
        }

        // Delete file
        $file = FCPATH . $banner['image'];
        if (file_exists($file)) {
            unlink($file);
        }

        $this->bannerModel->delete($id);

        return redirect()->to('/superadmin/event-banner')->with('success', 'Banner berhasil dihapus!');
    }
}
