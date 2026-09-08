<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\LayananPengaduanModel;
use App\Models\UserModel;

class Advokasi extends BaseController
{
    public function index()
    {
        $model = new LayananPengaduanModel();
        $settingModel = new \App\Models\AdvokasiSettingModel();
        
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // CWPA/WPA Bypass Logic
        $isCwpa = \App\Models\LevelModel::isStaffLevel((int) ($user['level_id'] ?? 0))
            || \App\Models\LevelModel::isStaffLevel((int) session()->get('level_id'));

        // Fetch Advokasi Settings from DB
        $settings = $settingModel->getSettings();
        $advokasiLayananId = 9999;
        $layananType = 'layanan';

        // Certificate & Completion Logic
        $completionModel = new \App\Models\LayananCompletionModel();
        $certificateModel = new \App\Models\CertificateModel();
        
        $isCompleted = $completionModel->isCompleted($userId, $advokasiLayananId, $layananType);
        $certificate = null;
        if ($isCompleted) {
            $certificate = $certificateModel->getCertificateForLayanan($userId, $advokasiLayananId, $layananType);
        }
        
        // Check for EA License Capability
        $license = null;
        $purchase = null;
        $transaksiModel = new \App\Models\TransaksiModel();
        
        // Find confirmed transaction for this layanan
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->orderBy('id', 'DESC')
                                   ->first();

        // Determine current step for roadmap visual (New Order: Info(0), Pelatihan(1), Aktivasi(2), Selesai(3), Sertifikat(4))
        $currentStep = 0;
        if ($isCompleted) {
            $currentStep = 4; // Sertifikat
        } elseif ($purchase || $isCwpa) {
            $eaLicenseModel = new \App\Models\EaLicenseModel();
            // Check for existing license first
            $existingLicense = $eaLicenseModel->where('user_id', $userId)->where('status', 'active')->first();
            if ($existingLicense) {
                $license = $existingLicense;
                $currentStep = 3; // Move to Pelatihan Selesai if license active
            } else {
                $currentStep = 1; // On Pelatihan step
            }
        }
        
        $data = [
            'title' => 'Advokasi - CWPA Dashboard',
            'activeMenu' => 'advokasi',
            'advokasiList' => $model->getByUser($userId),
            'user' => $user,
            'isCompleted' => $isCompleted,
            'certificate' => $certificate,
            'advokasiLayananId' => $advokasiLayananId,
            'currentStep' => $currentStep,
            'settings' => $settings,
            'purchase' => $purchase,
            'license' => $license,
            'isCwpa' => $isCwpa
        ];

        return view('cwpa/advokasi/index', $data);
    }

    /**
     * Activate EA License for Advokasi
     */
    public function activateLicense()
    {
        $userId = session()->get('userId');
        $brokerName = $this->request->getPost('broker_name');
        $accountNumber = $this->request->getPost('account_number');
        $advokasiLayananId = 9999;

        if (!$brokerName || !$accountNumber) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lengkapi data broker dan nomor akun.']);
        }

        $transaksiModel = new \App\Models\TransaksiModel();
        // Get the latest confirmed purchase for this layanan
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->orderBy('id', 'DESC')
                                   ->first();

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $userRole = session()->get('role') ?? session()->get('userRole');
        $sessionLevel = (int)session()->get('level_id');
        $isCwpa = (in_array($userRole, ['wpa', 'cwpa', 'admin']) || ($user['level_id'] ?? 0) >= 3 || $sessionLevel >= 3);

        if (!$purchase && !$isCwpa) {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda belum memiliki transaksi aktif (Confirmed) untuk program ini.']);
        }

        $eaLicenseModel = new \App\Models\EaLicenseModel();
        
        // Check if a license already exists
        $licenseQuery = $eaLicenseModel->where('user_id', $userId);
        if ($purchase) {
            $licenseQuery->where('order_id', $purchase['id']);
        }
        $exist = $licenseQuery->first();
        if ($exist) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lisensi sudah diaktivasi sebelumnya untuk transaksi ini.']);
        }

        $settingModel = new \App\Models\AdvokasiSettingModel();
        $settings = $settingModel->getSettings();
        $duration = $settings['ea_duration'] ?? 30;

        // Generate License Key - Format: ADV-AccountNo-4Random
        $random = strtoupper(substr(md5(uniqid()), 0, 4));
        $licenseKey = "ADV-{$accountNumber}-{$random}";
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        $insertData = [
            'order_id'               => $purchase['id'] ?? 0,
            'user_id'                => $userId,
            'broker_name'            => $brokerName,
            'account_trading_number' => $accountNumber,
            'license_key'            => $licenseKey,
            'status'                 => 'active',
            'expires_at'             => $expiresAt,
            'license_activated_at'   => date('Y-m-d H:i:s')
        ];

        try {
            if ($eaLicenseModel->insert($insertData)) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Lisensi EA berhasil diaktivasi!',
                    'license_key' => $licenseKey
                ]);
            } else {
                $errors = $eaLicenseModel->errors();
                $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Gagal menyimpan ke database.';
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengaktivasi lisensi: ' . $errorMessage]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error sistem: ' . $e->getMessage()]);
        }
    }

    public function belajar()
    {
        $model = new LayananPengaduanModel();
        $userId = session()->get('userId');
        
        $data = [
            'title' => 'Academy Advokasi - CWPA',
            'activeMenu' => 'advokasi',
            'isPaid' => true // CWPA has full access
        ];

        return view('cwpa/advokasi/belajar', $data);
    }

    public function update($id)
    {
        $model = new LayananPengaduanModel();
        $userId = session()->get('userId');
        
        $advokasi = $model->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$advokasi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        $rules = [
            'ktp_number'    => 'required|exact_length[16]',
            'address'       => 'required',
            'chronology'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lengkapi data wajib (*).']);
        }

        $updateData = [
            'ktp_number'        => $this->request->getPost('ktp_number'),
            'address'           => $this->request->getPost('address'),
            'broker_name'       => $this->request->getPost('broker_name'),
            'category_problem'  => $this->request->getPost('category_problem'),
            'loss_amount'       => $this->request->getPost('loss_amount'),
            'incident_date'     => $this->request->getPost('incident_date'),
            'chronology'        => $this->request->getPost('chronology'),
        ];

        // Handle File
        $file = $this->request->getFile('complaint_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pengaduan', $fileName);
            $updateData['file_attachment'] = $fileName;
        }

        if ($model->update($id, $updateData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diperbarui.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui data.']);
    }

    public function complete()
    {
        $userId = session()->get('userId');
        $advokasiLayananId = 9999;
        $layananType = 'layanan';

        $testimonial = $this->request->getPost('testimonial');
        $confirmed = $this->request->getPost('confirmed');

        if (!$confirmed) {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda harus menyetujui pernyataan penyelesaian program.']);
        }

        $transaksiModel = new \App\Models\TransaksiModel();
        $completionModel = new \App\Models\LayananCompletionModel();
        $certificateModel = new \App\Models\CertificateModel();
        $layananModel = new \App\Models\LayananModel();
        $ulasanModel = new \App\Models\LayananUlasanModel();

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $userRole = session()->get('role') ?? session()->get('userRole');
        $sessionLevel = (int)session()->get('level_id');
        $isCwpa = (in_array($userRole, ['wpa', 'cwpa', 'admin']) || ($user['level_id'] ?? 0) >= 3 || $sessionLevel >= 3);

        if ($purchase || $isCwpa) {
            // Save testimonial if provided
            if ($testimonial) {
                if (!$ulasanModel->hasUserReviewed($userId, $advokasiLayananId, $layananType)) {
                    $ulasanModel->insert([
                        'user_id' => $userId,
                        'layanan_id' => $advokasiLayananId,
                        'layanan_type' => $layananType,
                        'rating' => 5, // Default for advocacy completion
                        'ulasan' => $testimonial,
                        'status' => 'approved'
                    ]);
                }
            }

            $completion = $completionModel->markComplete($userId, $advokasiLayananId, $layananType, $purchase['id'] ?? null);
            
            $userName = session()->get('cwpaName') ?? session()->get('userName');
            $layananInfo = $layananModel->find($advokasiLayananId);
            $wpaName = 'ALMAI Team';
            if ($layananInfo && !empty($layananInfo['wpa_id'])) {
                $wpaModel = new \App\Models\WpaModel();
                $wpa = $wpaModel->find($layananInfo['wpa_id']);
                $wpaName = $wpa['name'] ?? $wpaName;
            }

            $certificateModel->createCertificate(
                $userId, 
                $advokasiLayananId, 
                $layananType, 
                $purchase['product_name'] ?? 'Program Advokasi', 
                $userName, 
                $wpaName, 
                $completion['id'] ?? null
            );

            return $this->response->setJSON(['success' => true, 'message' => 'Selamat! Program Advokasi telah diselesaikan.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Transaksi tidak ditemukan atau belum dikonfirmasi.']);
    }

    public function createReport()
    {
        $userId = session()->get('userId');
        $userModel = model('UserModel');
        $user = $userModel->find($userId);

        // Check if user has purchased
        // Check if user has purchased
        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', 9999)
                                   ->where('status', 'confirmed')
                                   ->first();
        $userRole = session()->get('role') ?? session()->get('userRole');
        $sessionLevel = (int)session()->get('level_id');
        $isCwpa = (in_array($userRole, ['wpa', 'cwpa', 'admin']) || ($user['level_id'] ?? 0) >= 3 || $sessionLevel >= 3);

        if (!$purchase && !$isCwpa) {
            return redirect()->to('/cwpa/dashboard/advokasi')->with('error', 'Anda harus mendaftar Program Advokasi terlebih dahulu.');
        }

        // Check if CWPA/PRO (Level ID >= 2)
        if (($user['level_id'] ?? 0) < 2) {
            return redirect()->to('/cwpa/dashboard/kyc')->with('error', 'Laporan Advokasi hanya tersedia untuk Akun terverifikasi (PRO). Silakan selesaikan verifikasi terlebih dahulu.');
        }

        // Fetch additional data from user_data table
        $db = \Config\Database::connect();
        $userData = $db->table('user_data')->where('user_id', $userId)->get()->getRowArray();
        
        // Merge data for the view
        $fullAddress = ($userData['address'] ?? '');
        if (!empty($userData['village'])) $fullAddress .= ', ' . $userData['village'];
        if (!empty($userData['district'])) $fullAddress .= ', ' . $userData['district'];
        if (!empty($userData['city'])) $fullAddress .= ', ' . $userData['city'];
        if (!empty($userData['province'])) $fullAddress .= ', ' . $userData['province'];
        if (!empty($userData['postal_code'])) $fullAddress .= ' ' . $userData['postal_code'];

        $user['ktp_number'] = $userData['identity_number'] ?? '';
        $user['address'] = $fullAddress;

        return view('cwpa/advokasi/form', [
            'title' => 'Buat Laporan Baru',
            'activeMenu' => 'advokasi',
            'user' => $user
        ]);
    }

    public function submitReport()
    {
        $postData = $this->request->getPost();
        
        // Validate fields (Only Broker Name and Chronology are mandatory)
        $rules = [
            'broker_name'       => 'required',
            'chronology'        => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            $errors = implode(' ', $validation->getErrors());
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $errors);
        }

        // Handle File Upload
        $evidencePath = null;
        $file = $this->request->getFile('evidence');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/advokasi', $newName);
            $evidencePath = 'uploads/advokasi/' . $newName;
        }

        $userId = session()->get('userId');
        $userModel = model('UserModel');
        $user = $userModel->find($userId);

        // Prepare data for model
        $complaintData = [
            'user_id'           => $userId,
            'name'              => $user['name'] ?? session()->get('userName') ?? '',
            'email'             => $user['email'] ?? session()->get('userEmail') ?? '',
            'whatsapp'          => $user['phone'] ?? session()->get('userPhone') ?? '',
            'ktp_number'        => $postData['ktp_number'] ?? $user['ktp_number'] ?? '',
            'address'           => $postData['address'] ?? $user['address'] ?? '',
            'broker_name'       => $postData['broker_name'],
            'trading_type'      => $postData['trading_type'] ?? null,
            'category_problem'  => $postData['category_problem'] ?? null,
            'sub_category'      => $postData['sub_category'] ?? null,
            'loss_amount'       => str_replace(['.', ','], '', $postData['loss_amount'] ?? '0'),
            'incident_date'     => $postData['incident_date'] ?: null,
            'chronology'        => $postData['chronology'],
            'trading_account'   => $postData['trading_account'] ?? null,
            'trading_password'  => $postData['trading_password'] ?? null,
            'broker_server'     => $postData['broker_server'] ?? null,
            'file_attachment'   => $evidencePath,
            'referrer_id'       => $user['referrer_id'] ?? null,
            'referral_code'     => $user['referral_code'] ?? null,
            'status'            => 'pending'
        ];

        $model = new \App\Models\LayananPengaduanModel();
        if ($model->insert($complaintData)) {
            return redirect()->to('/cwpa/dashboard/advokasi')->with('success', 'Laporan Anda telah berhasil dikirim.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengirim laporan.');
    }

    public function showDetail($id)
    {
        $userId = session()->get('userId');
        $model = new \App\Models\LayananPengaduanModel();
        
        $report = $model->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$report) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Laporan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        return view('cwpa/advokasi/detail', [
            'title' => 'Detail Laporan #' . $id,
            'activeMenu' => 'advokasi',
            'report' => $report
        ]);
    }

    public function materi()
    {
        $userId = session()->get('userId');
        $advokasiLayananId = 9999;

        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->first();

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $userRole = session()->get('role') ?? session()->get('userRole');
        $sessionLevel = (int)session()->get('level_id');
        $isCwpa = (in_array($userRole, ['wpa', 'cwpa', 'admin']) || ($user['level_id'] ?? 0) >= 3 || $sessionLevel >= 3);

        if (!$purchase && !$isCwpa) {
            return redirect()->to('/cwpa/dashboard/advokasi')->with('error', 'Anda harus membeli layanan Advokasi terlebih dahulu untuk mengakses materi.');
        }

        $settingModel = new \App\Models\AdvokasiSettingModel();
        $settings = $settingModel->getSettings();
        
        $canvaLinks = json_decode($settings['canva_embed_url'] ?? '[]', true) ?: [];
        $dayKey = strtolower($this->request->getGet('day') ?: 'senin');
        $activeLink = $canvaLinks[$dayKey] ?? '';

        return view('cwpa/advokasi/materi', [
            'title' => 'Materi Pelatihan Advokasi',
            'activeMenu' => 'advokasi',
            'settings' => $settings,
            'activeLink' => $activeLink,
            'day' => $this->request->getGet('day')
        ]);
    }
}
