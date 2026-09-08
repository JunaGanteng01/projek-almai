<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\LayananPengaduanModel;
use App\Models\UserModel;

class Advokasi extends BaseController
{
    protected $advokasiLayananId = 9999;
    protected $layananType = 'layanan';

    public function index()
    {
        $model = new LayananPengaduanModel();
        $settingModel = new \App\Models\AdvokasiSettingModel();
        
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Fetch Advokasi Settings from DB
        $settings = $settingModel->getSettings();
        $advokasiLayananId = $this->advokasiLayananId;
        $layananType = $this->layananType;

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

        if ($purchase) {
            $eaLicenseModel = new \App\Models\EaLicenseModel();
            $licenses = $eaLicenseModel->where('order_id', $purchase['id'])->findAll();
        }

        // Handle roadmap current step
        // Order: 0=Informasi, 1=Pelatihan, 2=Aktivasi EA, 3=Selesai, 4=Sertifikat
        $currentStep = 0; // Not purchased
        if ($isCompleted) {
            $currentStep = 4;
        } elseif ($purchase) {
            if (!empty($licenses)) {
                $currentStep = 3; // Selesai (after EA activated)
            } else {
                $currentStep = 2; // Aktivasi EA
            }
        }

        // Recent reports (consistent with CWPA naming)
        $advokasiList = $model->where('user_id', $userId)
                               ->orderBy('created_at', 'DESC')
                               ->limit(5)
                               ->findAll();

        return view('user/advokasi/index', [
            'title' => 'Program Advokasi',
            'activeMenu' => 'advokasi',
            'user' => $user,
            'settings' => $settings,
            'purchase' => $purchase,
            'licenses' => $licenses ?? [],
            'advokasiList' => $advokasiList,
            'isCompleted' => $isCompleted,
            'certificate' => $certificate,
            'currentStep' => $currentStep
        ]);
    }

    public function activateLicense()
    {
        $userId = session()->get('userId');
        $brokerNameMt4 = $this->request->getPost('broker_name_mt4');
        $accountMt4 = $this->request->getPost('account_number_mt4');
        
        $brokerNameMt5 = $this->request->getPost('broker_name_mt5');
        $accountMt5 = $this->request->getPost('account_number_mt5');
        
        $advokasiLayananId = $this->advokasiLayananId;

        // Basic validation: at least one side must be fully filled
        $hasMt4 = !empty($brokerNameMt4) && !empty($accountMt4);
        $hasMt5 = !empty($brokerNameMt5) && !empty($accountMt5);

        if (!$hasMt4 && !$hasMt5) {
            return $this->response->setJSON(['success' => false, 'message' => 'Harap lengkapi setidaknya satu form aktivasi (Broker dan Nomor Akun) untuk MT4 atau MT5.']);
        }
        
        // Detailed validation if partially filled
        if (!empty($brokerNameMt4) && empty($accountMt4)) return $this->response->setJSON(['success' => false, 'message' => 'Nomor akun MT4 belum diisi.']);
        if (empty($brokerNameMt4) && !empty($accountMt4)) return $this->response->setJSON(['success' => false, 'message' => 'Nama broker MT4 belum diisi.']);
        
        if (!empty($brokerNameMt5) && empty($accountMt5)) return $this->response->setJSON(['success' => false, 'message' => 'Nomor akun MT5 belum diisi.']);
        if (empty($brokerNameMt5) && !empty($accountMt5)) return $this->response->setJSON(['success' => false, 'message' => 'Nama broker MT5 belum diisi.']);

        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->orderBy('id', 'DESC')
                                   ->first();

        if (!$purchase) {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda belum memiliki transaksi aktif (Confirmed) untuk program ini.']);
        }

        $eaLicenseModel = new \App\Models\EaLicenseModel();
        $exist = $eaLicenseModel->where('order_id', $purchase['id'])->first();
        if ($exist) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lisensi sudah diaktivasi sebelumnya untuk transaksi ini.']);
        }

        $settingModel = new \App\Models\AdvokasiSettingModel();
        $settings = $settingModel->getSettings();
        $duration = $settings['ea_duration'] ?? 30;
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        $accountsToProcess = [];
        if ($hasMt4) $accountsToProcess['MT4'] = ['broker' => $brokerNameMt4, 'account' => $accountMt4];
        if ($hasMt5) $accountsToProcess['MT5'] = ['broker' => $brokerNameMt5, 'account' => $accountMt5];

        try {
            $insertedCount = 0;
            foreach ($accountsToProcess as $type => $data) {
                // Generate License Key - Format: ADV-AccountNo-4Random
                $random = strtoupper(substr(md5(uniqid()), 0, 4));
                $licenseKey = "ADV-{$data['account']}-{$random}";
                
                $insertData = [
                    'order_id'               => $purchase['id'],
                    'user_id'                => $userId,
                    'broker_name'            => "[{$type}] " . $data['broker'],
                    'account_trading_number' => $data['account'],
                    'license_key'            => $licenseKey,
                    'status'                 => 'active',
                    'expires_at'             => $expiresAt,
                    'license_activated_at'   => date('Y-m-d H:i:s')
                ];
                
                if ($eaLicenseModel->insert($insertData)) {
                    $insertedCount++;
                }
            }

            if ($insertedCount > 0) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => "{$insertedCount} Lisensi EA berhasil diaktivasi!"
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengaktivasi lisensi.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error sistem: ' . $e->getMessage()]);
        }
    }

    public function complete()
    {
        $userId = session()->get('userId');
        $advokasiLayananId = $this->advokasiLayananId;
        $layananType = $this->layananType;

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

        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->orderBy('id', 'DESC')
                                   ->first();

        if ($purchase) {
            if ($testimonial) {
                if (!$ulasanModel->hasUserReviewed($userId, $advokasiLayananId, $layananType)) {
                    $ulasanModel->insert([
                        'user_id' => $userId,
                        'layanan_id' => $advokasiLayananId,
                        'layanan_type' => $layananType,
                        'rating' => 5,
                        'ulasan' => $testimonial,
                        'status' => 'approved'
                    ]);
                }
            }

            $completion = $completionModel->markComplete($userId, $advokasiLayananId, $layananType, $purchase['id']);
            
            $userName = session()->get('userName');
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

    public function materi()
    {
        $userId = session()->get('userId');
        $advokasiLayananId = $this->advokasiLayananId;


        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->first();

        if (!$purchase) {
            return redirect()->to('/user/advokasi')->with('error', 'Anda harus membeli layanan Advokasi terlebih dahulu untuk mengakses materi.');
        }

        $settingModel = new \App\Models\AdvokasiSettingModel();
        $settings = $settingModel->getSettings();
        
        $canvaLinks = json_decode($settings['canva_embed_url'] ?? '[]', true) ?: [];
        $dayKey = strtolower($this->request->getGet('day') ?: 'senin');
        $activeLink = $canvaLinks[$dayKey] ?? '';

        return view('user/advokasi/materi', [
            'title' => 'Materi Pelatihan Advokasi',
            'activeMenu' => 'advokasi',
            'settings' => $settings,
            'activeLink' => $activeLink,
            'day' => $this->request->getGet('day')
        ]);
    }
    public function createReport()
    {
        $userId = session()->get('userId');
        $userModel = model('UserModel');
        $user = $userModel->find($userId);

        // Check if user has purchased
        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', $userId)
                                   ->where('layanan_id', $this->advokasiLayananId)
                                   ->where('status', 'confirmed')
                                   ->first();
        if (!$purchase) {
            return redirect()->to('/user/advokasi')->with('error', 'Anda harus mendaftar Program Advokasi terlebih dahulu.');
        }

        // Check if user is PRO (Level ID = 2)
        if (($user['level_id'] ?? 0) < 2) {
            return redirect()->to('/user/kyc')->with('error', 'Laporan Advokasi hanya tersedia untuk Akun PRO. Silakan selesaikan verifikasi terlebih dahulu.');
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

        return view('user/advokasi/form', [
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
            'category_problem'  => 'permit_empty',
            'sub_category'      => 'permit_empty',
            'loss_amount'       => 'permit_empty',
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
            'file_attachment'   => $evidencePath, // DB column is file_attachment
            'referrer_id'       => $user['referrer_id'] ?? null,
            'referral_code'     => $user['referral_code'] ?? null,
            'status'            => 'pending'
        ];

        $model = new \App\Models\LayananPengaduanModel();
        if ($model->insert($complaintData)) {
            return redirect()->to('/user/advokasi')->with('success', 'Laporan Anda telah berhasil dikirim dan akan segera diproses.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengirim laporan. Silakan coba lagi.');
    }
    public function showDetail($id)
    {
        $userId = session()->get('userId');
        $model = new \App\Models\LayananPengaduanModel();
        
        $report = $model->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$report) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Laporan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        return view('user/advokasi/detail', [
            'title' => 'Detail Laporan #' . $id,
            'activeMenu' => 'advokasi',
            'report' => $report
        ]);
    }

}
