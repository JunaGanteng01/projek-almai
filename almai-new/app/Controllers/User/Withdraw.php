<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserDataModel;
use App\Models\WithdrawalModel;
use App\Models\NotificationModel;
use App\Models\LevelModel;

class Withdraw extends BaseController
{
    /**
     * Halaman Tarik Dana.
     * - Non-PRO  → redirect ke halaman upgrade/KYC dengan pesan
     * - PRO      → tampilkan form tarik dana
     */
    public function index()
    {
        $userId = session()->get('userId');

        $userModel     = new UserModel();
        $userDataModel = new UserDataModel();

        $user     = $userModel->find($userId);
        $userData = $userDataModel->where('user_id', $userId)->first();

        // Cek apakah user adalah PRO (level_id = 2 exactly)
        $isPro = !empty($user['level_id']) && (int)$user['level_id'] === LevelModel::LEVEL_PRO;

        if (!$isPro) {
            // Arahkan ke halaman upgrade / KYC
            return redirect()->to('/user/kyc')
                ->with('error', 'Fitur Tarik Dana hanya tersedia untuk Member PRO. Silakan upgrade akun Anda terlebih dahulu.');
        }

        $wdModel = new WithdrawalModel();

        // Saldo dari kolom balance di tabel users
        $availableBalance = $user['balance'] ?? 0;

        // Total sudah ditarik (completed)
        $totalWithdrawn = $wdModel->where('user_id', $userId)
            ->where('status', 'completed')
            ->selectSum('amount')
            ->first()['amount'] ?? 0;

        // Total dalam proses (pending/approved)
        $pendingWithdrawal = $wdModel->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->selectSum('amount')
            ->first()['amount'] ?? 0;

        // Riwayat penarikan
        $withdrawals = $wdModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/withdraw', [
            'title'             => 'Tarik Dana - Almai',
            'pageTitle'         => 'Tarik Dana',
            'activeMenu'        => 'withdraw',
            'currentUser'       => $user,
            'availableBalance'  => $availableBalance,
            'totalWithdrawn'    => $totalWithdrawn,
            'pendingWithdrawal' => $pendingWithdrawal,
            'withdrawals'       => $withdrawals,
            'userBank'          => [
                'bank_name'      => $userData['bank_name'] ?? '',
                'account_number' => $userData['account_number'] ?? '',
                'account_holder' => $userData['account_name'] ?? $user['name'] ?? '',
            ],
            'unreadCount' => (new NotificationModel())->getUnreadCount($userId),
        ]);
    }

    /**
     * Proses pengajuan penarikan dana.
     * Guard: hanya PRO (level_id = 2).
     */
    public function store()
    {
        $userId = session()->get('userId');

        $userModel     = new UserModel();
        $userDataModel = new UserDataModel();

        $user     = $userModel->find($userId);
        $userData = $userDataModel->where('user_id', $userId)->first();

        // Guard: hanya PRO (level_id = 2 exactly)
        $isPro = !empty($user['level_id']) && (int)$user['level_id'] === LevelModel::LEVEL_PRO;
        if (!$isPro) {
            return redirect()->to('/user/kyc')
                ->with('error', 'Fitur Tarik Dana hanya tersedia untuk Member PRO. Silakan upgrade akun Anda terlebih dahulu.');
        }

        // Validasi input
        $rules = [
            'amount'         => 'required|numeric|greater_than_equal_to[1000000]',
            'bank_name'      => 'required',
            'account_number' => 'required',
            'account_holder' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Data tidak valid atau jumlah penarikan terlalu kecil (Min Rp 1.000.000).');
        }

        $amount = (float) $this->request->getPost('amount');

        // Cek saldo mencukupi
        $availableBalance = $user['balance'] ?? 0;

        $wdModel = new WithdrawalModel();
        $pendingWithdrawal = $wdModel->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->selectSum('amount')
            ->first()['amount'] ?? 0;

        $realAvailable = $availableBalance - $pendingWithdrawal;

        if ($amount > $realAvailable) {
            return redirect()->back()->withInput()
                ->with('error', 'Saldo tidak mencukupi. Saldo tersedia: Rp ' . number_format($realAvailable, 0, ',', '.'));
        }

        // Simpan pengajuan
        $wdModel->insert([
            'user_id'        => $userId,
            'amount'         => $amount,
            'bank_name'      => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_holder' => $this->request->getPost('account_holder'),
            'status'         => 'pending',
        ]);

        // Notifikasi ke user
        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $userId,
            'Penarikan Dana Diajukan 💸',
            'Pengajuan penarikan sebesar Rp ' . number_format($amount, 0, ',', '.') . ' telah kami terima dan sedang menunggu verifikasi admin.',
            'info',
            '/user/withdraw'
        );

        // Notifikasi ke semua admin
        $admins = $userModel->whereIn('level_id', [
            LevelModel::LEVEL_ADMIN,
            LevelModel::LEVEL_SUPER_ADMIN,
        ])->findAll();

        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Withdraw Baru (User PRO) 🔔',
                'Ada pengajuan penarikan baru dari User PRO: ' . ($user['name'] ?? '-') . ' sebesar Rp ' . number_format($amount, 0, ',', '.') . '.',
                'warning',
                '/admin/withdrawals'
            );
        }

        return redirect()->to('/user/withdraw')
            ->with('success', 'Permintaan penarikan berhasil diajukan! Tunggu konfirmasi admin dalam 1–3 hari kerja.');
    }
}
