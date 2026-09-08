<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\NotificationModel;

class Kyc extends BaseController
{
    public function index()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        return view('user/kyc', [
            'title' => 'Upgrade PRO - KYC',
            'pageTitle' => 'Upgrade ke PRO',
            'pageSubtitle' => 'Lengkapi data KYC',
            'activeMenu' => 'profile',
            'user' => $user
        ]);
    }

    public function submit()
    {
        $userId = session()->get('userId');
        $userDataModel = new \App\Models\UserDataModel();
        $existingUserData = $userDataModel->where('user_id', $userId)->first();

        $rules = [
            'full_name' => 'required',
            'nik' => 'required|exact_length[16]|numeric',
            'npwp' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required|valid_date',
            'gender' => 'required|in_list[male,female]',
            'phone' => 'required',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'village' => 'required',
            'postal_code' => 'required',
            'profession' => 'required|in_list[pelajar,swasta,pns,wiraswasta,profesional,lainnya]',
            'registration_purpose' => 'required|in_list[pelatihan,pendampingan,lainnya]',
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
            'experience' => 'required|in_list[none,less_1_year,1_3_years,more_3_years]',
            'trading_goal' => 'required|in_list[hedging,spekulasi,investasi]',
            'monthly_income' => 'required|in_list[less_5m,5m_15m,15m_50m,more_50m]',
            'risk_profile' => 'required|in_list[conservative,moderate,aggressive]',
            'agree_profil' => 'required',
            'agree_resiko' => 'required',
            'agree_snk' => 'required',
        ];

        // Only require KTP photo if no existing submission
        if (!$existingUserData || empty($existingUserData['photo_identity_card'])) {
            $rules['ktp_photo'] = 'uploaded[ktp_photo]|max_size[ktp_photo,5120]|mime_in[ktp_photo,image/jpg,image/jpeg,image/png]|ext_in[ktp_photo,jpg,jpeg,png]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMessages = implode(', ', array_keys($errors));
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . $errorMessages);
        }

        // Handle KTP photo upload
        $ktpPhoto = $this->request->getFile('ktp_photo');
        $ktpPath = null;

        if ($ktpPhoto && $ktpPhoto->isValid() && !$ktpPhoto->hasMoved()) {
            $newName = $ktpPhoto->getRandomName();
            $ktpPhoto->move(WRITEPATH . 'uploads/kyc', $newName);
            $ktpPath = 'uploads/kyc/' . $newName;
        }

        $userModel = new UserModel();

        // Map KYC form data to user_data table columns
        $userData = [
            'user_id' => $userId,

            // Personal Data
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'gender' => $this->request->getPost('gender'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'birth_place_and_date' => $this->request->getPost('birth_place') . ', ' . $this->request->getPost('birth_date'),

            // Address
            'address' => $this->request->getPost('address'),
            'province' => $this->request->getPost('province'),
            'city' => $this->request->getPost('city'),
            'district' => $this->request->getPost('district'),
            'village' => $this->request->getPost('village'),
            'postal_code' => $this->request->getPost('postal_code'),

            // Identity
            'identity_type' => 'KTP',
            'identity_number' => $this->request->getPost('nik'),
            'npwp' => $this->request->getPost('npwp'),

            // Profession & Purpose
            'profession' => $this->request->getPost('profession'),
            'registration_purpose' => $this->request->getPost('registration_purpose'),

            // Bank details
            'bank_name' => $this->request->getPost('bank_name'),
            'bank_branch' => $this->request->getPost('bank_branch'),
            'account_number' => $this->request->getPost('account_number'),
            'account_name' => $this->request->getPost('account_name'),

            'investment_experience' => $this->mapExperience($this->request->getPost('experience')),
            'trading_experience' => $this->mapExperience($this->request->getPost('experience')),
            'investment_goals' => $this->request->getPost('trading_goal'), // hedging/spekulasi/investasi
            'type_of_risk' => $this->request->getPost('risk_profile'),
            'annually_income' => $this->request->getPost('monthly_income'), // Mapping monthly to annually column for now
            'monthly_income' => $this->request->getPost('monthly_income'),

            // Docs
            'photo_identity_card' => $ktpPath,

            // Agreements
            'statement_of_truth' => 1,
            'agreed' => 1,
            'agreed_at' => date('Y-m-d H:i:s'),
        ];

        // Create a copy for DB insert/update and remove non-existent columns
        $dbData = $userData;

        // Only update photo if new one uploaded
        if (!$ktpPath) {
            unset($dbData['photo_identity_card']);
        }

        // The database uses annually_income instead of monthly_income based on user schema
        $dbData['annually_income'] = $userData['monthly_income'];
        unset($dbData['monthly_income']);

        // Perform automatic validation based on Risk-Based Approach SOP
        $validationService = new \App\Libraries\KycValidationService();
        $validationResult = $validationService->validateAndDeterminStatus($userData);

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            if ($existingUserData) {
                $userDataModel->update($existingUserData['id'], $dbData);
            } else {
                $userDataModel->insert($dbData);
            }

            // Update user's name and phone in users table
            $userModel->update($userId, [
                'name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone'),
                'kyc_status' => $validationResult['status'],
                'kyc_submitted_at' => date('Y-m-d H:i:s'),
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'KYC DB Transaction Failed for user ' . $userId);
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            log_message('error', 'KYC Exception: ' . $e->getMessage() . ' for user ' . $userId);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }

        $redirectPath = \App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::resolveLevelId(session()));

        // Handle auto-approval
        if ($validationResult['status'] === 'approved') {
            // Auto-approve: Update level to PRO ONLY for standard users
            $currentUser = $userModel->find($userId);
            if ($currentUser['level_id'] == \App\Models\LevelModel::LEVEL_USER) {
                $userModel->update($userId, [
                    'level_id' => \App\Models\LevelModel::LEVEL_PRO,
                ]);

                // Give upgrade PRO points (100 for user, rest to 8 level upline)
                $poinService = new \App\Libraries\PoinService();
                $poinService->processUpgradeProBonus($userId);
            }

            // Send success notification
            $notifModel = new NotificationModel();
            $notifModel->createNotification(
                $userId,
                'KYC Disetujui Otomatis! 🎉',
                'Selamat! Pengajuan KYC Anda telah disetujui secara otomatis oleh sistem. Anda sekarang menjadi Member PRO dengan akses ke semua fitur premium.',
                'success',
                $redirectPath
            );

            return redirect()->to($redirectPath)->with('success', 'Pengajuan KYC berhasil! Anda telah disetujui secara otomatis. 🎉');
        }

        // Handle manual review (pending)
        if ($validationResult['status'] === 'pending') {
            // Send notification to all admins
            $this->notifyAdmins($this->request->getPost('full_name'), $validationResult['risk_level']);

            return redirect()->to($redirectPath)->with('success', 'Pengajuan KYC berhasil dikirim! Tim kami akan memverifikasi data Anda dalam 1-3 hari kerja.');
        }

        // Handle rejection
        if ($validationResult['status'] === 'rejected') {
            // Send rejection notification
            $notifModel = new NotificationModel();
            $notifModel->createNotification(
                $userId,
                'Pengajuan KYC Ditolak',
                'Mohon maaf, pengajuan KYC Anda ditolak. Alasan: ' . $validationResult['reason'],
                'error',
                '/user/kyc'
            );

            return redirect()->to('/user/kyc')->with('error', 'Pengajuan KYC ditolak: ' . $validationResult['reason']);
        }
    }

    /**
     * Map experience string to tinyint value for database
     */
    private function mapExperience($experience)
    {
        $map = [
            'none' => 0,
            'less_1_year' => 1,
            '1_3_years' => 2,
            'more_3_years' => 3,
        ];
        return $map[$experience] ?? 0;
    }

    public function wilayahProxy()
    {
        $uri = $this->request->getUri();
        $segments = $uri->getSegments();
        
        // Find 'wilayah' and get everything after it
        $startIndex = array_search('wilayah', $segments);
        $pathSegments = ($startIndex !== false) ? array_slice($segments, $startIndex + 1) : [];
        $path = implode('/', $pathSegments);
        
        $url = "https://wilayah.id/api/" . ($path ? $path : "");
        log_message('debug', 'Wilayah Proxy URL: ' . $url);
        
        // Use cURL or file_get_contents
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'CodeIgniter/4.x Proxy'
                ],
                'timeout' => 5,
                'allow_redirects' => true
            ]);
            
            return $this->response
                ->setStatusCode($response->getStatusCode())
                ->setContentType('application/json')
                ->setBody($response->getBody());
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => $e->getMessage(),
                'url' => $url,
                'path' => $path
            ]);
        }
    }

    private function notifyAdmins($userName, $riskLevel = 'sedang')
    {
        $userModel = new UserModel();
        $notifModel = new NotificationModel();

        // Get all admin and super admin users
        $admins = $userModel->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ])->findAll();

        // Map risk level to display name
        $riskLevelDisplay = [
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi'
        ][$riskLevel] ?? 'Sedang';

        // Log for debugging
        log_message('info', 'KYC Notification: Found ' . count($admins) . ' admins to notify');

        foreach ($admins as $admin) {
            try {
                $notifModel->createNotification(
                    $admin['id'],
                    'Pengajuan Upgrade PRO Baru 🔔',
                    $userName . ' mengajukan upgrade ke akun PRO (Risiko: ' . $riskLevelDisplay . '). Silakan verifikasi data KYC.',
                    'info',
                    '/admin/kyc'
                );
                log_message('info', 'KYC Notification sent to admin ID: ' . $admin['id']);
            } catch (\Exception $e) {
                log_message('error', 'Failed to send KYC notification to admin ' . $admin['id'] . ': ' . $e->getMessage());
            }
        }

    }

}
