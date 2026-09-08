<?php

namespace App\Controllers;

use App\Models\PoinSharingModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

class ReceivePoin extends BaseController
{
    protected $sharingModel;

    public function __construct()
    {
        $this->sharingModel = new PoinSharingModel();
    }

    /**
     * Show receive poin page
     */
    public function index($code)
    {
        $sharing = $this->sharingModel->getByCode($code);

        if (!$sharing) {
            return view('pages/receive-poin/not-found', [
                'title' => 'Link Tidak Ditemukan'
            ]);
        }

        // Check if expired
        if ($sharing['expired_at'] && strtotime($sharing['expired_at']) < time()) {
            $this->sharingModel->update($sharing['id'], ['status' => 'expired']);
            return view('pages/receive-poin/expired', [
                'title' => 'Link Kadaluarsa',
                'sharing' => $sharing
            ]);
        }

        // Check if already used
        if ($sharing['receiver_id'] && $sharing['status'] !== 'pending') {
            return view('pages/receive-poin/used', [
                'title' => 'Link Sudah Digunakan',
                'sharing' => $sharing
            ]);
        }

        // Get current user if logged in
        $user = null;
        if (session()->get('isLoggedIn')) {
            $userModel = new UserModel();
            $user = $userModel->find(session()->get('userId'));

            // Can't receive own sharing
            if ($user && $user['id'] == $sharing['sender_id']) {
                return view('pages/receive-poin/own-link', [
                    'title' => 'Link Milik Anda',
                    'sharing' => $sharing
                ]);
            }
        }

        return view('pages/receive-poin/index', [
            'title' => 'Terima Berbagi Poin',
            'sharing' => $sharing,
            'user' => $user,
        ]);
    }

    /**
     * Claim the poin sharing
     */
    public function claim($code)
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->set('redirectAfterLogin', '/receive-poin/' . $code);
            return redirect()->to('/login?redirect=receive-poin/' . $code);
        }

        $userId = session()->get('userId');
        $sharing = $this->sharingModel->getByCode($code);

        if (!$sharing) {
            return redirect()->to('/')->with('error', 'Link tidak ditemukan');
        }

        // Validations
        if ($sharing['sender_id'] == $userId) {
            return redirect()->back()->with('error', 'Anda tidak bisa mengklaim link milik sendiri');
        }

        if ($sharing['receiver_id']) {
            return redirect()->back()->with('error', 'Link sudah diklaim oleh user lain');
        }

        if ($sharing['expired_at'] && strtotime($sharing['expired_at']) < time()) {
            return redirect()->back()->with('error', 'Link sudah kadaluarsa');
        }

        // Claim the sharing
        $this->sharingModel->update($sharing['id'], [
            'receiver_id' => $userId,
        ]);

        // Notify sender
        $notifModel = new NotificationModel();
        $userModel = new UserModel();
        $receiver = $userModel->find($userId);

        $notifModel->createNotification(
            $sharing['sender_id'],
            'Link Berbagi Poin Diklaim',
            $receiver['name'] . ' telah mengklaim link berbagi poin Anda (' . number_format($sharing['poin_amount']) . ' poin). Menunggu pembayaran.',
            'info',
            '/user/poin/share'
        );

        return redirect()->to('/receive-poin/' . $code)->with('success', 'Berhasil mengklaim! Silakan lakukan pembayaran.');
    }

    /**
     * Upload transfer proof
     */
    public function uploadProof($code)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('userId');
        $sharing = $this->sharingModel->getByCode($code);

        if (!$sharing || $sharing['receiver_id'] != $userId) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        if ($sharing['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Handle file upload
        $proofFile = $this->request->getFile('transfer_proof');

        if (!$proofFile || !$proofFile->isValid()) {
            return redirect()->back()->with('error', 'File bukti transfer harus diupload');
        }

        // Validate file
        if ($proofFile->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($proofFile->getMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Format file harus JPG, PNG, atau WebP');
        }

        // Save file
        $newName = 'proof_' . $sharing['id'] . '_' . time() . '.' . $proofFile->getExtension();
        $uploadPath = WRITEPATH . 'uploads/transfer_proofs';
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $proofFile->move($uploadPath, $newName);

        // Update sharing
        $this->sharingModel->update($sharing['id'], [
            'transfer_proof' => $newName,
            'status' => 'paid',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);

        // Notify sender
        $notifModel = new NotificationModel();
        $userModel = new UserModel();
        $receiver = $userModel->find($userId);

        $notifModel->createNotification(
            $sharing['sender_id'],
            'Bukti Transfer Diterima',
            $receiver['name'] . ' telah mengupload bukti transfer untuk berbagi poin (' . number_format($sharing['poin_amount']) . ' poin). Silakan verifikasi.',
            'warning',
            '/user/poin/share'
        );

        return redirect()->back()->with('success', 'Bukti transfer berhasil diupload! Menunggu verifikasi dari pengirim.');
    }
}
