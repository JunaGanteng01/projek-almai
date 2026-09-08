<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KycSubmissionModel;
use App\Models\NotificationModel;
use App\Models\LevelModel;
use App\Models\UserDataModel;

class Kyc extends BaseController
{
    protected $userModel;
    protected $kycModel;
    protected $notifModel;
    protected $userDataModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kycModel = new KycSubmissionModel();
        $this->notifModel = new NotificationModel();
        $this->userDataModel = new UserDataModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');

        // Default to pending if not set
        if ($status === null) {
            $status = 'pending';
        }

        $search = $this->request->getGet('search');

        // Main Query
        // Now selecting directly from users table since kyc_status is added there
        $builder = $this->userModel->select('users.*')
            // ->whereIn('users.level_id', [LevelModel::LEVEL_USER, LevelModel::LEVEL_PRO])
            ->orderBy('id', 'DESC');

        // Filter by Status
        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $builder->where('users.kyc_status', $status);
        }

        // Search
        if ($search) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('users.phone', $search)
                ->groupEnd();
        }

        $kycList = $builder->paginate(20);
        $pager = $this->userModel->pager;

        // Count by status
        $totalPending = $this->countByStatus('pending');
        $totalApproved = $this->countByStatus('approved');
        $totalRejected = $this->countByStatus('rejected');

        return view('superadmin/kyc/index', [
            'title' => 'Verifikasi KYC PRO - Admin Dashboard',
            'kycList' => $kycList,
            'pager' => $pager,
            'totalPending' => $totalPending,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
            'currentStatus' => $status,
            'currentSearch' => $search,
            'activeMenu' => 'kyc'
        ]);
    }

    private function countByStatus($status)
    {
        // Use a new instance to avoid query builder state conflicts
        $model = new UserModel();
        return $model->where('kyc_status', $status) // Direct column query
            ->whereIn('level_id', [LevelModel::LEVEL_USER, LevelModel::LEVEL_PRO])
            ->countAllResults(false);
    }

    public function detail($id)
    {
        // Find the user
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/superadmin/kyc')->with('error', 'User tidak ditemukan');
        }

        // Fetch KYC details from user_data table (now main storage)
        $kycData = $this->userDataModel->where('user_id', $id)->first();

        // Fallback or Merge if needed, but migration ensures user_data has everything now
        if (!$kycData) {
            // Optional: Check kyc_submissions if user_data is empty (legacy support)
            $kycData = $this->kycModel->where('user_id', $id)->first();
        }

        return view('superadmin/kyc/detail', [
            'title' => 'Detail KYC - Admin Dashboard',
            'user' => $user,
            'kyc' => $kycData,
            'activeMenu' => 'kyc'
        ]);
    }

    public function approve($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/superadmin/kyc')->with('error', 'User tidak ditemukan');
        }

        // Build update data
        $updateData = ['kyc_status' => 'approved'];
        $isLevelUp = false;

        // ONLY update level to PRO if user is a standard user
        if ($user['level_id'] == LevelModel::LEVEL_USER) {
            $updateData['level_id'] = LevelModel::LEVEL_PRO;
            $isLevelUp = true;
        }

        $this->userModel->update($id, $updateData);

        // Process Upgrade PRO points if level went up
        if ($isLevelUp) {
            $poinService = new \App\Libraries\PoinService();
            $poinService->processUpgradeProBonus($id);
        }

        // Send notification to user
        $notifTitle = 'KYC Disetujui! 🎉';
        $notifMsg = 'Selamat! Pengajuan KYC Anda telah disetujui. Data identitas dan rekening Anda telah diverifikasi.';
        
        if ($isLevelUp) {
            $notifMsg .= ' Anda sekarang menjadi Member PRO dengan akses ke semua fitur premium.';
        }

        $this->notifModel->createNotification(
            $id,
            $notifTitle,
            $notifMsg,
            'success',
            ($user['level_id'] == LevelModel::LEVEL_CWPA ? '/cwpa/dashboard' : ($user['level_id'] == LevelModel::LEVEL_WPA ? '/wpa/dashboard' : '/user/profile'))
        );

        $successMsg = 'KYC berhasil disetujui untuk ' . $user['name'] . '.';
        if ($isLevelUp) $successMsg .= ' User sekarang menjadi Member PRO.';

        return redirect()->to('/superadmin/kyc')->with('success', $successMsg);
    }

    public function reject($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/superadmin/kyc')->with('error', 'User tidak ditemukan');
        }

        $reason = $this->request->getPost('reason') ?? 'Data tidak lengkap atau tidak valid';

        // Update KYC Status to rejected
        $updateData = ['kyc_status' => 'rejected'];

        // Downgrade if currently PRO
        if ($user['level_id'] == LevelModel::LEVEL_PRO) {
            $updateData['level_id'] = LevelModel::LEVEL_USER;
        }

        $this->userModel->update($id, $updateData);

        // Send notification to user
        $this->notifModel->createNotification(
            $id,
            'KYC Ditolak',
            'Mohon maaf, pengajuan KYC Anda ditolak. Alasan: ' . $reason . '. Silakan ajukan ulang dengan data yang benar.',
            'error',
            '/user/kyc'
        );

        return redirect()->to('/superadmin/kyc')->with('success', 'KYC ditolak untuk user ' . $user['name']);
    }
}
