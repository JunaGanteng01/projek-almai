<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PoinModel;
use App\Models\MerchandiseModel;
use App\Models\MerchandiseRedemptionModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Models\PoinPackageModel;
use App\Models\TransaksiModel;
use App\Libraries\XenditService;

class Poin extends BaseController
{
    /**
     * Show merchandise exchange page
     */
    public function merchandise()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $merchandiseModel = new MerchandiseModel();
        $poinModel = new PoinModel();

        $user = $userModel->find($userId);

        // Check if user is PRO
        if (empty($user['level_id']) || $user['level_id'] != \App\Models\LevelModel::LEVEL_PRO) {
            return redirect()->to('/user/poin')->with('error', 'Fitur Tukar Merchandise hanya tersedia untuk Member PRO. Silakan upgrade akun Anda.');
        }

        $merchandise = $merchandiseModel->getActive();
        $poinBalance = $poinModel->getUserBalance($userId);

        return view('user/poin/merchandise', [
            'title' => 'Tukar Merchandise',
            'user' => $user,
            'merchandise' => $merchandise,
            'poinBalance' => $poinBalance,
        ]);
    }

    /**
     * Show point purchase page
     */
    public function buy()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $poinModel = new PoinModel();
        $packageModel = new PoinPackageModel(); // Ensure this model is used

        $user = $userModel->find($userId);
        $poinBalance = $poinModel->getUserBalance($userId);
        $packages = $packageModel->getActive();

        return view('user/poin/buy', [
            'title' => 'Beli Poin',
            'user' => $user,
            'poinBalance' => $poinBalance,
            'packages' => $packages,
        ]);
    }

    public function redeemMerchandise()
    {
        $userId = session()->get('userId');
        $userName = session()->get('userName');
        $poinModel = new PoinModel();
        $merchandiseModel = new MerchandiseModel();
        $redemptionModel = new MerchandiseRedemptionModel();
        $userModel = new UserModel();
        $notifModel = new NotificationModel();

        // Check if user is PRO
        $user = $userModel->find($userId);
        if (empty($user['level_id']) || $user['level_id'] != \App\Models\LevelModel::LEVEL_PRO) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Fitur Tukar Merchandise hanya tersedia untuk Member PRO'
            ]);
        }

        // Get JSON input
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $merchandiseId = $json->merchandise_id ?? 0;

        // Get merchandise from database
        $merchandise = $merchandiseModel->find($merchandiseId);

        if (!$merchandise || $merchandise['status'] !== 'active') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Merchandise tidak tersedia'
            ]);
        }

        // Check stock
        if (!$merchandise['unlimited_stock'] && $merchandise['stock'] <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stok merchandise habis'
            ]);
        }

        $pointsRequired = $merchandise['points_required'];

        // Check user balance
        $userBalance = $poinModel->getUserBalance($userId);

        if ($userBalance < $pointsRequired) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Poin tidak mencukupi'
            ]);
        }

        // Get user address
        $user = $userModel->find($userId);
        $address = $user['address'] ?? 'Alamat belum diisi';

        // Deduct points
        // Deduct points
        $poinModel->insert([
            'user_id' => $userId,
            'point' => $pointsRequired,
            'type' => 'redeem',
            'description' => 'Tukar merchandise: ' . $merchandise['name'],
            'pointable_type' => 'merchandise',
            'pointable_id' => $merchandiseId,
        ]);

        // Create redemption record
        $redemptionModel->insert([
            'user_id' => $userId,
            'merchandise_id' => $merchandiseId,
            'points_used' => $pointsRequired,
            'status' => 'pending',
            'shipping_address' => $address,
        ]);

        // Reduce stock if not unlimited
        if (!$merchandise['unlimited_stock']) {
            $merchandiseModel->update($merchandiseId, [
                'stock' => $merchandise['stock'] - 1
            ]);
        }

        // Calculate new balance
        $newBalance = $userBalance - $pointsRequired;

        // Notification to USER: Poin berkurang
        $notifModel->createNotification(
            $userId,
            'Poin Berkurang',
            'Poin Anda berkurang ' . number_format($pointsRequired) . ' untuk penukaran ' . $merchandise['name'] . '. Sisa poin: ' . number_format($newBalance),
            'info',
            '/user/poin'
        );

        // Notification to USER: Redemption created
        $notifModel->createNotification(
            $userId,
            'Penukaran Merchandise Berhasil',
            'Penukaran ' . $merchandise['name'] . ' sedang diproses. Kami akan mengirimkan ke alamat Anda dalam 7-14 hari kerja.',
            'success',
            '/user/poin'
        );

        // Notification to ALL ADMINS: New redemption
        $admins = $userModel->where('role', 'admin')->findAll();
        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Penukaran Merchandise Baru',
                $userName . ' menukarkan ' . number_format($pointsRequired) . ' poin untuk ' . $merchandise['name'],
                'info',
                '/admin/merchandise/redemptions'
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Penukaran berhasil! Merchandise akan dikirim ke alamat Anda.'
        ]);
    }

    /**
     * Purchase poin package
     */
    public function purchase()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Get JSON input
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $packageId = $json->package_id ?? 0;

        // Get package
        $packageModel = new PoinPackageModel();
        $package = $packageModel->find($packageId);

        if (!$package || $package['is_enabled'] != 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paket tidak tersedia'
            ]);
        }

        // Create transaction
        $transaksiModel = new TransaksiModel();
        $invoiceNumber = $transaksiModel->generateInvoiceNumber();

        $transaksiData = [
            'invoice_number' => $invoiceNumber,
            'user_id' => $userId,
            'kelas_id' => null,
            'product_type' => 'poin',
            'product_name' => 'Service Fee: ' . number_format($package['amount']) . ' Poin',
            'amount' => $package['price'],
            'discount' => 0,
            'total' => $package['price'],
            'payment_method' => 'xendit',
            'status' => 'pending',
            'notes' => 'Poin: ' . $package['amount'],
        ];

        $transaksiId = $transaksiModel->insert($transaksiData);

        // Create Xendit invoice
        $xendit = new XenditService();

        $invoiceData = [
            'external_id' => $invoiceNumber,
            'amount' => $package['price'],
            'email' => $user['email'],
            'customer_name' => $user['name'] ?? 'Guest', // Add null check for name
            'phone' => $user['phone'] ?? null,
            'description' => 'Pembelian Service Fee: ' . ($package['name'] ?? 'Paket Poin') . ' - ' . ($user['name'] ?? 'Guest') . ' (' . $invoiceNumber . ')',
            'item_name' => 'Service Fee: ' . ($package['name'] ?? 'Paket Poin'),
            'success_url' => base_url('user/poin/purchase-success?invoice=' . $invoiceNumber),
            'failure_url' => base_url('user/poin/purchase-failed?invoice=' . $invoiceNumber),
        ];

        $result = $xendit->createInvoice($invoiceData);

        if ($result['success'] && isset($result['data']['invoice_url'])) {
            // Update transaksi with xendit invoice id
            $transaksiModel->update($transaksiId, [
                'notes' => $transaksiData['notes'] . ' | Xendit ID: ' . $result['data']['id'],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'redirect_url' => $result['data']['invoice_url']
            ]);
        } else {
            // Xendit failed
            $transaksiModel->update($transaksiId, [
                'status' => 'cancelled',
                'notes' => $transaksiData['notes'] . ' | Xendit error: ' . ($result['error'] ?? 'Unknown'),
            ]);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment gateway tidak tersedia. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Purchase success callback
     */
    public function purchaseSuccess()
    {
        $invoiceNumber = $this->request->getGet('invoice');
        return redirect()->to('/user/poin')->with('success', 'Pembayaran sedang diproses. Poin akan ditambahkan setelah pembayaran dikonfirmasi.');
    }

    /**
     * Purchase failed callback
     */
    public function purchaseFailed()
    {
        $invoiceNumber = $this->request->getGet('invoice');
        return redirect()->to('/user/poin')->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
    }

    /**
     * Handle daily check-in action
     */
    public function checkin()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesi kadaluarsa.']);
        }

        $poinService = new \App\Libraries\PoinService();
        $success = $poinService->processDailyCheckin($userId);

        if ($success) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Check-in berhasil! Anda telah menerima bonus poin harian.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Anda sudah melakukan check-in hari ini. Coba lagi besok.'
            ]);
        }
    }
}
