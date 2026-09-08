<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MerchandiseModel;
use App\Models\MerchandiseRedemptionModel;

class Merchandise extends BaseController
{
    public function index()
    {
        $model = new MerchandiseModel();
        $redemptionModel = new MerchandiseRedemptionModel();
        
        $merchandise = $model->orderBy('created_at', 'DESC')->findAll();
        
        // Get stats
        $totalMerchandise = count($merchandise);
        $activeMerchandise = count(array_filter($merchandise, fn($m) => $m['status'] === 'active'));
        $totalRedemptions = $redemptionModel->countAllResults();
        $pendingRedemptions = $redemptionModel->where('status', 'pending')->countAllResults();
        
        return view('admin/merchandise/index', [
            'title' => 'Merchandise - Admin',
            'pageTitle' => 'Merchandise',
            'activeMenu' => 'merchandise',
            'merchandise' => $merchandise,
            'stats' => [
                'total' => $totalMerchandise,
                'active' => $activeMerchandise,
                'redemptions' => $totalRedemptions,
                'pending' => $pendingRedemptions,
            ]
        ]);
    }

    public function create()
    {
        return view('admin/merchandise/create', [
            'title' => 'Tambah Merchandise - Admin',
            'pageTitle' => 'Tambah Merchandise',
            'activeMenu' => 'merchandise',
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'points_required' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle image
        $imagePath = $this->request->getPost('image_path');
        $imageFile = $this->request->getFile('image');
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'images/merchandise', $newName);
            $imagePath = '/images/merchandise/' . $newName;
        }

        $model = new MerchandiseModel();
        $model->insert([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image' => $imagePath,
            'points_required' => $this->request->getPost('points_required'),
            'stock' => $this->request->getPost('stock') ?: 0,
            'unlimited_stock' => $this->request->getPost('unlimited_stock') ? 1 : 0,
            'status' => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/admin/merchandise')->with('success', 'Merchandise berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = new MerchandiseModel();
        $merchandise = $model->find($id);

        if (!$merchandise) {
            return redirect()->to('/admin/merchandise')->with('error', 'Merchandise tidak ditemukan');
        }

        return view('admin/merchandise/edit', [
            'title' => 'Edit Merchandise - Admin',
            'pageTitle' => 'Edit Merchandise',
            'activeMenu' => 'merchandise',
            'merchandise' => $merchandise,
        ]);
    }

    public function update($id)
    {
        $model = new MerchandiseModel();
        $merchandise = $model->find($id);

        if (!$merchandise) {
            return redirect()->to('/admin/merchandise')->with('error', 'Merchandise tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'points_required' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle image
        $imagePath = $this->request->getPost('image_path') ?: $merchandise['image'];
        $imageFile = $this->request->getFile('image');
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'images/merchandise', $newName);
            $imagePath = '/images/merchandise/' . $newName;
        }

        $model->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image' => $imagePath,
            'points_required' => $this->request->getPost('points_required'),
            'stock' => $this->request->getPost('stock') ?: 0,
            'unlimited_stock' => $this->request->getPost('unlimited_stock') ? 1 : 0,
            'status' => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/admin/merchandise')->with('success', 'Merchandise berhasil diupdate');
    }

    public function delete($id)
    {
        $model = new MerchandiseModel();
        $merchandise = $model->find($id);

        if (!$merchandise) {
            return redirect()->to('/admin/merchandise')->with('error', 'Merchandise tidak ditemukan');
        }

        $model->delete($id);
        return redirect()->to('/admin/merchandise')->with('success', 'Merchandise berhasil dihapus');
    }

    // Redemptions
    public function redemptions()
    {
        $model = new MerchandiseRedemptionModel();
        $filter = $this->request->getGet('status');
        
        if ($filter && $filter !== 'all') {
            $redemptions = $model->getByStatus($filter);
        } else {
            $redemptions = $model->getWithDetails();
        }
        
        // Stats
        $stats = [
            'total' => $model->countAllResults(false),
            'pending' => $model->where('status', 'pending')->countAllResults(false),
            'processing' => $model->where('status', 'processing')->countAllResults(false),
            'shipped' => $model->where('status', 'shipped')->countAllResults(false),
            'completed' => $model->where('status', 'completed')->countAllResults(false),
        ];

        return view('admin/merchandise/redemptions', [
            'title' => 'Penukaran Merchandise - Admin',
            'pageTitle' => 'Penukaran Merchandise',
            'activeMenu' => 'merchandise',
            'redemptions' => $redemptions,
            'stats' => $stats,
            'filter' => $filter,
        ]);
    }

    public function updateRedemptionStatus($id)
    {
        $model = new MerchandiseRedemptionModel();
        $notifModel = new \App\Models\NotificationModel();
        $merchandiseModel = new MerchandiseModel();
        
        $redemption = $model->find($id);

        if (!$redemption) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $status = $this->request->getPost('status');
        $trackingNumber = $this->request->getPost('tracking_number');
        $notes = $this->request->getPost('notes');

        $updateData = ['status' => $status];
        if ($trackingNumber) $updateData['tracking_number'] = $trackingNumber;
        if ($notes) $updateData['notes'] = $notes;

        $model->update($id, $updateData);
        
        // Get merchandise name
        $merchandise = $merchandiseModel->find($redemption['merchandise_id']);
        $merchName = $merchandise ? $merchandise['name'] : 'Merchandise';
        
        // Send notification to user based on status
        $statusMessages = [
            'processing' => [
                'title' => 'Penukaran Sedang Diproses',
                'message' => 'Penukaran ' . $merchName . ' Anda sedang diproses oleh tim kami.',
                'type' => 'info'
            ],
            'shipped' => [
                'title' => 'Merchandise Dikirim',
                'message' => 'Penukaran ' . $merchName . ' Anda sudah dikirim.' . ($trackingNumber ? ' No. Resi: ' . $trackingNumber : ''),
                'type' => 'success'
            ],
            'completed' => [
                'title' => 'Penukaran Selesai',
                'message' => 'Penukaran ' . $merchName . ' Anda telah selesai. Terima kasih!',
                'type' => 'success'
            ],
            'cancelled' => [
                'title' => 'Penukaran Dibatalkan',
                'message' => 'Penukaran ' . $merchName . ' Anda dibatalkan.' . ($notes ? ' Alasan: ' . $notes : '') . ' Poin akan dikembalikan.',
                'type' => 'error'
            ],
        ];
        
        if (isset($statusMessages[$status])) {
            $notifModel->createNotification(
                $redemption['user_id'],
                $statusMessages[$status]['title'],
                $statusMessages[$status]['message'],
                $statusMessages[$status]['type'],
                '/user/poin'
            );
        }
        
        // If cancelled, refund points
        if ($status === 'cancelled') {
            $poinModel = new \App\Models\PoinModel();
            $poinModel->insert([
                'user_id' => $redemption['user_id'],
                'point' => $redemption['points_used'],
                'type' => 'bonus',
                'description' => 'Refund penukaran dibatalkan: ' . $merchName,
                'pointable_type' => 'merchandise_refund',
                'pointable_id' => $id,
            ]);
            
            // Notify user about refund
            $notifModel->createNotification(
                $redemption['user_id'],
                'Poin Dikembalikan',
                'Poin sebesar ' . number_format($redemption['points_used']) . ' telah dikembalikan ke akun Anda.',
                'success',
                '/user/poin'
            );
        }

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }
}
