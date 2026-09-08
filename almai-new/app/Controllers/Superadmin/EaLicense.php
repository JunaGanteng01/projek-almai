<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\EaLicenseModel;
use App\Models\UserModel;

class EaLicense extends BaseController
{
    protected $eaLicenseModel;
    protected $userModel;

    public function __construct()
    {
        $this->eaLicenseModel = new EaLicenseModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_ea_license') ? $this->request->getVar('page_ea_license') : 1;
        $search = $this->request->getVar('search');

        // Auto-update status to expired if the date has passed and notify admins & users
        $now = date('Y-m-d H:i:s');
        $newlyExpired = $this->eaLicenseModel->select('ea_licenses.*, users.name as user_name, users.phone as user_phone')
            ->join('users', 'users.id = ea_licenses.user_id', 'left')
            ->where('ea_licenses.expires_at IS NOT NULL')
            ->where('ea_licenses.expires_at <', $now)
            ->where('ea_licenses.status', 'active')
            ->findAll();

        if (!empty($newlyExpired)) {
            $notifModel = new \App\Models\NotificationModel();
            $levelModel = new \App\Models\LevelModel();
            $waService = new \App\Libraries\IvosightsService();
            
            // Fetch admins to send notification
            $admins = $this->userModel->whereIn('level_id', [$levelModel::LEVEL_ADMIN, $levelModel::LEVEL_SUPER_ADMIN])->findAll();

            foreach ($newlyExpired as $lic) {
                // Update status to expired
                $this->eaLicenseModel->update($lic['id'], ['status' => 'expired']);

                // Notify Admins via System Notification
                foreach ($admins as $admin) {
                    $notifModel->createNotification(
                        $admin['id'],
                        'Lisensi EA Expired',
                        'Lisensi EA milik ' . $lic['user_name'] . ' untuk akun ' . $lic['account_trading_number'] . ' telah expired.',
                        'warning',
                        '/admin/ea-license'
                    );
                }

                // Notify User via WhatsApp Notification
                if (!empty($lic['user_phone'])) {
                    // Berdasarkan design template Ivosights, variabel {{1}} hanyalah nama user
                    $waResponse = $waService->sendLicenseExpiredNotification($lic['user_phone'], $lic['user_name']);
                    
                    if (!$waResponse['success']) {
                        log_message('error', 'Failed to send WA Notification for expired license: ' . $lic['user_phone'] . ' | Error: ' . ($waResponse['message'] ?? 'Unknown Error'));
                    }
                }
            }
        }

        $builder = $this->eaLicenseModel->select('ea_licenses.*, users.name as user_name, users.email as user_email')
                                       ->join('users', 'users.id = ea_licenses.user_id', 'left');

        if ($search) {
            $builder->groupStart()
                    ->like('ea_licenses.license_key', $search)
                    ->orLike('ea_licenses.account_trading_number', $search)
                    ->orLike('ea_licenses.broker_name', $search)
                    ->orLike('users.name', $search)
                    ->orLike('users.email', $search)
                    ->groupEnd();
        }

        $licenses = $builder->orderBy('ea_licenses.created_at', 'DESC')->paginate(20, 'ea_license');
        $pager = $this->eaLicenseModel->pager;

        $data = [
            'title' => 'Daftar Lisensi EA',
            'activeMenu' => 'ea_license',
            'licenses' => $licenses,
            'pager' => $pager,
            'currentPage' => $currentPage,
            'search' => $search
        ];

        return view('superadmin/ea_license/index', $data);
    }

    public function extend($id)
    {
        $additionalDays = (int)$this->request->getPost('days');
        
        if ($additionalDays <= 0) {
            return redirect()->back()->with('error', 'Jumlah hari tidak valid.');
        }

        $license = $this->eaLicenseModel->find($id);
        if (!$license) {
            return redirect()->back()->with('error', 'Lisensi tidak ditemukan.');
        }

        $currentExpiry = $license['expires_at'] ? strtotime($license['expires_at']) : time();
        
        // If it's already expired or current time is past expiry, start from now
        if ($currentExpiry < time()) {
            $currentExpiry = time();
        }

        $newExpiry = date('Y-m-d H:i:s', $currentExpiry + ($additionalDays * 86400));

        $this->eaLicenseModel->update($id, [
            'expires_at' => $newExpiry,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Masa aktif lisensi berhasil diperpanjang hingga ' . date('d M Y', strtotime($newExpiry)));
    }

    public function update($id)
    {
        $licenseKey = $this->request->getPost('license_key');
        $accountTradingNumber = $this->request->getPost('account_trading_number');
        
        if (empty($licenseKey) || empty($accountTradingNumber)) {
            return redirect()->back()->with('error', 'Semua data harus diisi.');
        }

        $license = $this->eaLicenseModel->find($id);
        if (!$license) {
            return redirect()->back()->with('error', 'Lisensi tidak ditemukan.');
        }

        $this->eaLicenseModel->update($id, [
            'license_key' => $licenseKey,
            'account_trading_number' => $accountTradingNumber
        ]);

        return redirect()->back()->with('success', 'Data lisensi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $license = $this->eaLicenseModel->find($id);
        if (!$license) {
            return redirect()->back()->with('error', 'Lisensi tidak ditemukan.');
        }

        $this->eaLicenseModel->delete($id);

        return redirect()->back()->with('success', 'Lisensi berhasil dihapus.');
    }
}
