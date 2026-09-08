<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PoinSharingModel;
use App\Models\PoinPackageModel;
use App\Models\PoinModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

class PoinSharing extends BaseController
{
    protected $sharingModel;
    protected $packageModel;
    protected $poinModel;

    public function __construct()
    {
        $this->sharingModel = new PoinSharingModel();
        $this->packageModel = new PoinPackageModel();
        $this->poinModel = new PoinModel();
    }

    /**
     * Create share poin link
     */
    public function create()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->back()->with('error', 'Session expired');
        
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Get poin packages for selection
        $packages = $this->packageModel->where('is_enabled', 1)
            ->orderBy('amount', 'ASC')
            ->findAll();

        // Get Superadmin (ID 1) points
        $poinBalance = $this->poinModel->getUserBalance(1);

        // Get pending sharings (waiting for receiver)
        $pendingSharings = $this->sharingModel->where('sender_id', $userId)
            ->where('status', 'pending')
            ->where('receiver_id IS NULL')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Get sharings waiting for verification
        $waitingVerification = $this->sharingModel->getPendingVerification($userId);

        $userDataModel = new \App\Models\UserDataModel();
        $userData = $userDataModel->where('user_id', $userId)->first();

        return view('admin/poin/share', [
            'title' => 'Berbagi Poin (Admin)',
            'user' => $user,
            'userData' => $userData,
            'packages' => $packages,
            'poinBalance' => $poinBalance,
            'pendingSharings' => $pendingSharings,
            'waitingVerification' => $waitingVerification,
            'activeMenu' => 'poin_share'
        ]);
    }

    /**
     * Store new share link
     */
    public function store()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->back()->with('error', 'Session expired');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $packageId = $this->request->getPost('package_id');
        $customPoin = (int) $this->request->getPost('custom_poin');
        $customPrice = (float) $this->request->getPost('custom_price');
        $bankName = $this->request->getPost('bank_name');
        $bankAccountName = $this->request->getPost('bank_account_name');
        $bankAccountNumber = $this->request->getPost('bank_account_number');

        // Determine poin and price
        if ($packageId) {
            $package = $this->packageModel->find($packageId);
            if (!$package) {
                return redirect()->back()->with('error', 'Paket tidak ditemukan');
            }
            $poinAmount = $package['amount'];
            $price = $package['price'];
        } else {
            if ($customPoin < 100) {
                return redirect()->back()->with('error', 'Minimal 100 poin');
            }
            $poinAmount = $customPoin;
            $price = $customPrice > 0 ? $customPrice : ($customPoin * 100); // Default 1 poin = Rp 100
        }

        // Validate bank info
        if (empty($bankName) || empty($bankAccountName) || empty($bankAccountNumber)) {
            return redirect()->back()->with('error', 'Informasi rekening bank harus diisi');
        }

        // Generate share code
        $shareCode = $this->sharingModel->generateShareCode();

        // Create sharing record
        $this->sharingModel->insert([
            'share_code' => $shareCode,
            'sender_id' => $userId,
            'package_id' => $packageId,
            'poin_amount' => $poinAmount,
            'price' => $price,
            'bank_name' => $bankName,
            'bank_account_name' => $bankAccountName,
            'bank_account_number' => $bankAccountNumber,
            'status' => 'pending',
            'expired_at' => date('Y-m-d H:i:s', strtotime('+7 days')),
        ]);

        return redirect()->to('/admin/poin/share')->with('success', 'Link berbagi poin berhasil dibuat!');
    }

    /**
     * Delete/cancel share link
     */
    public function delete($id)
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->back()->with('error', 'Session expired');
        
        $sharing = $this->sharingModel->find($id);

        if (!$sharing || $sharing['sender_id'] != $userId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($sharing['status'] !== 'pending' || $sharing['receiver_id']) {
            return redirect()->back()->with('error', 'Link tidak dapat dihapus karena sudah digunakan');
        }

        $this->sharingModel->delete($id);

        return redirect()->back()->with('success', 'Link berbagi poin berhasil dihapus');
    }

    /**
     * Verify payment from receiver
     */
    public function verify($id)
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->back()->with('error', 'Session expired');
        
        $sharing = $this->sharingModel->find($id);

        if (!$sharing || $sharing['sender_id'] != $userId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($sharing['status'] !== 'paid') {
            return redirect()->back()->with('error', 'Status tidak valid untuk verifikasi');
        }

        // Admin might not have poin balance limits technically, but we keep the logic the same as user for system balance tracking.
        $senderBalance = $this->poinModel->getUserBalance($userId);
        $poinAmount = (int) $sharing['poin_amount'];

        $receiverId = (int) $sharing['receiver_id'];
        $sharingId = (int) $sharing['id'];

        // Deduct poin from sender (Admin)
        $senderInsert = $this->poinModel->insert([
            'user_id' => (int) $userId,
            'type' => 'share_out',
            'point' => $poinAmount,
            'description' => 'Berbagi poin ke user #' . $receiverId,
            'pointable_type' => 'poin_sharing',
            'pointable_id' => $sharingId,
        ]);

        if (!$senderInsert) {
            log_message('error', 'Failed to insert share_out poin: ' . json_encode($this->poinModel->errors()));
            return redirect()->back()->with('error', 'Gagal mengurangi poin pengirim');
        }

        // Add poin to receiver
        $receiverInsert = $this->poinModel->insert([
            'user_id' => $receiverId,
            'type' => 'share_in',
            'point' => $poinAmount,
            'description' => 'Menerima berbagi poin dari Admin',
            'pointable_type' => 'poin_sharing',
            'pointable_id' => $sharingId,
        ]);

        if (!$receiverInsert) {
            log_message('error', 'Failed to insert share_in poin: ' . json_encode($this->poinModel->errors()));
            return redirect()->back()->with('error', 'Gagal menambah poin penerima');
        }

        // Update sharing status
        $this->sharingModel->update($id, [
            'status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        // Send notification to receiver
        $notifModel = new NotificationModel();
        
        $notifModel->createNotification(
            $receiverId,
            'Poin Diterima!',
            'Selamat! ' . number_format($poinAmount) . ' poin dari Admin telah masuk ke akun Anda.',
            'success',
            '/user/poin'
        );

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi! Poin telah ditransfer.');
    }

    /**
     * Reject payment
     */
    public function reject($id)
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->back()->with('error', 'Session expired');
        
        $sharing = $this->sharingModel->find($id);
        $reason = $this->request->getPost('reason') ?? 'Bukti transfer tidak valid';

        if (!$sharing || $sharing['sender_id'] != $userId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($sharing['status'] !== 'paid') {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Update status
        $this->sharingModel->update($id, [
            'status' => 'rejected',
            'notes' => $reason,
        ]);

        // Notify receiver
        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $sharing['receiver_id'],
            'Pembayaran Ditolak',
            'Pembayaran berbagi poin Anda ditolak. Alasan: ' . $reason,
            'error',
            '/user/poin'
        );

        return redirect()->back()->with('success', 'Pembayaran ditolak');
    }
}
