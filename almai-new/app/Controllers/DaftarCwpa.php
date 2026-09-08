<?php

namespace App\Controllers;

use App\Models\LevelModel;

class DaftarCwpa extends BaseController
{
    protected $cwpaSubmissionModel;
    protected $userModel;

    public function __construct()
    {
        $this->cwpaSubmissionModel = new \App\Models\CwpaSubmissionModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        return $this->multiStepIndex();
    }

    /**
     * Multi-step registration form - NEW FLOW
     */
    public function multiStepIndex()
    {
        // Don't redirect to /daftar?tab=wpa, just render the view directly
        // so the URL stays as /daftar-cwpa
        
        // Prevent logged-in users who are already CWPA/WPA from accessing
        if (session()->get('isLoggedIn')) {
            $role = session()->get('userRole');
            if ($role === 'wpa') return redirect()->to('/wpa/dashboard');
            if ($role === 'cwpa') return redirect()->to('/cwpa/dashboard');
            if ($role === 'admin') return redirect()->to('/admin/dashboard');
        }

        $legalModel = new \App\Models\LegalDocumentModel();
        $legalWpa = $legalModel->find(5);

        $currentUser = null;
        if (session()->get('isLoggedIn')) {
            $userModel = new \App\Models\UserModel();
            $currentUser = $userModel->find(session()->get('userId'));
        }

        return view('pages/auth/login', [
            'title' => 'Pendaftaran CWPA - ALMAI',
            'redirect' => null,
            'showRegister' => true,
            'activeTab' => 'wpa', // Render WPA tab
            'legalWpa' => $legalWpa,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Handle multi-step form submission
     */
    public function submitMultiStep()
    {
        // Apply rate limiting to prevent brute force registration attempts
        $throttler = \Config\Services::throttler();
        $clientId = session()->get('userId') ?? $this->request->getIPAddress();
        
        if ($throttler->check(md5($clientId . '_cwpa_register'), 10, 3600) === false) { // 10 attempts per hour
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa menit.'
            ])->setStatusCode(429);
        }

        // Initial Validation Rules
        $rules = [
            // Step 1: Buat Akun CWPA (Will be removed if logged in)
            'name' => 'required|min_length[3]|max_length[100]',
            'whatsapp' => 'required|regex_match[/^(\+62|62|0)?[0-9]{9,12}$/]',

            // Step 2: Data Diri
            'ktp_number' => 'required|regex_match[/^[0-9. ]{16,20}$/]',
            'npwp_number' => 'required',
            'address' => 'required|min_length[10]|max_length[500]',

            // Step 4: Pengalaman & Spesialisasi
            'specialties' => 'required',

            // Step 5: Persyaratan C-WPA
            'has_ijazah_s1' => 'required|in_list[yes,no]',
            'has_skck_card' => 'required|in_list[yes,no]',
            'willing_to_make_skck' => 'permit_empty|in_list[yes,no]',
            'has_felony_record' => 'required|in_list[yes,no]',
            'willing_to_make_felony_statement' => 'permit_empty|in_list[yes,no]',
            'has_bankruptcy_record' => 'required|in_list[yes,no]',

            // Step 6: Dokumen - Use FileUploadService for validation
            'cv' => 'uploaded[cv]|max_size[cv,10240]|ext_in[cv,pdf,doc,docx]',
            'ktp_file' => 'uploaded[ktp_file]|max_size[ktp_file,10240]|ext_in[ktp_file,pdf,jpg,jpeg,png]',
            'npwp_file' => 'uploaded[npwp_file]|max_size[npwp_file,10240]|ext_in[npwp_file,pdf,jpg,jpeg,png]',
            'ijazah' => 'uploaded[ijazah]|max_size[ijazah,10240]|ext_in[ijazah,pdf,jpg,jpeg,png]',
            'skck' => 'permit_empty|max_size[skck,10240]|ext_in[skck,pdf,jpg,jpeg,png]',

            // Step 8: Pembayaran
            'transfer_proof' => 'permit_empty|max_size[transfer_proof,10240]|ext_in[transfer_proof,pdf,jpg,jpeg,png]',
        ];

        // If user is logged in, remove password validation
        if (session()->get('isLoggedIn')) {
            unset($rules['name']);
            unset($rules['whatsapp']);

            // Name, email, whatsapp are readonly/disabled in frontend but still sent?
            // If they are disabled, they won't be in POST. But we might need them for validation?
            // Actually, for logged in user we use session ID, so we don't need to validate account creation fields STRICTLY
            // BUT, the frontend might still send them if we just make them readonly (not disabled).
            // Let's keep name/email/whatsapp validation loose or rely on session data later.
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()),
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Validate at least one social media
        $ig = $this->request->getPost('instagram');
        $fb = $this->request->getPost('facebook');
        $youtube = $this->request->getPost('youtube');
        $tiktok = $this->request->getPost('tiktok');
        $linkedin = $this->request->getPost('linkedin');

        if (empty($ig) && empty($fb) && empty($youtube) && empty($tiktok) && empty($linkedin)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Setidaknya isi satu akun media sosial.'
            ]);
        }

        $userId = null;
        $isAuthenticated = session()->get('isLoggedIn');

        if ($isAuthenticated) {
            // User is logged in, use existing user ID
            $userId = session()->get('userId');
            
            // If they registered previously but missed the affiliator code, update it now
            $affiliatorCode = $this->request->getPost('kode_affiliator');
            if (!empty($affiliatorCode) && preg_match('/^[a-zA-Z0-9\-_]{1,50}$/', $affiliatorCode)) {
                $currentUser = $this->userModel->find($userId);
                if ($currentUser && empty($currentUser['affiliator_code'])) {
                    $this->userModel->update($userId, ['affiliator_code' => $affiliatorCode]);
                }
            }
        } else {
            // Guest user - Validate and Create Account
            $name = $this->request->getPost('name');
            $phone = $this->request->getPost('whatsapp');

            if (!$isAuthenticated) {
                if (!session()->get('otp_wa_verified')) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Silakan verifikasi WhatsApp terlebih dahulu.'
                    ]);
                }
            }

            if (empty($name) || empty($phone)) {
                return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Data akun (Nama, WhatsApp) wajib diisi untuk pendaftar baru.'
                ]);
            }

            // Sanitize inputs


            $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $email = $this->request->getPost('email');
            
            $affiliatorCode = $this->request->getPost('kode_affiliator') ?: 'daftar-cwpa';
            // Validate affiliator code format (alphanumeric, dash, underscore only)
            if (!preg_match('/^[a-zA-Z0-9\-_]{1,50}$/', $affiliatorCode)) {
                $affiliatorCode = 'daftar-cwpa';
            }

            // Auto generate password
            $password = '';
            if (empty($password)) {
                $randomHash = strtoupper(substr(hash('sha256', uniqid(rand(), true)), 0, 4));
                $password = 'ALMA-' . $randomHash;
            }

            // Create new user
            $userData = [
                'name' => $name,
                'email' => !empty($email) ? $email : null,
                'phone' => $phone,
                'password' => $password, // Will handled by Model hashPassword
                'role' => 'user',
                'status' => 'active',
                'affiliator_code' => $affiliatorCode
            ];

            // Insert user (Model handles hashing)
            if (!$this->userModel->insert($userData)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal membuat akun user. ' . implode(', ', $this->userModel->errors())
                ]);
            }
            $userId = $this->userModel->getInsertID();

            // Regenerate session ID after user creation (security best practice)
            session_regenerate_id(true);

            // Auto-login for new user
            session()->set([
                'isLoggedIn' => true,
                'userId' => $userId,
                'userName' => $userData['name'],
                'userEmail' => $userData['email'],
                'userRole' => 'user',
                'role' => 'user',
                'level_id' => \App\Models\LevelModel::LEVEL_USER
            ]);
        }

        // Ensure userId is available
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: User ID tidak ditemukan.'
            ]);
        }

        // Prepare social media data
        $socialMedia = [
            'ig' => $ig,
            'fb' => $fb,
            'youtube' => $youtube,
            'tiktok' => $tiktok,
            'linkedin' => $linkedin,
        ];

        // Prepare specialties
        $specialties = $this->request->getPost('specialties');
        if (empty($specialties)) $specialties = [];
        if (!is_array($specialties)) $specialties = [$specialties];
        $specialtiesStr = json_encode($specialties);

        // Initialize FileUploadService for secure uploads
        $uploadService = new \App\Libraries\FileUploadService();
        
        // Upload files helper - Uses FileUploadService for secure handling
        $upload = function ($fileKey, $category = 'document') use ($userId, $uploadService) {
            $file = $this->request->getFile($fileKey);
            if (!$file) {
                return null;
            }

            // Save file securely with validation
            $filePath = $uploadService->save($file, $userId, $category);
            
            if (!$filePath) {
                log_message('warning', "File upload failed for {$fileKey}: " . $uploadService->getLastError());
                return null;
            }

            return $filePath;
        };

        // Handle Boolean fields
        $hasIjazah = $this->request->getPost('has_ijazah_s1') === 'yes' ? 1 : 0;
        $hasSkck = $this->request->getPost('has_skck_card') === 'yes' ? 1 : 0;

        // Fix for skipped fields (NULL) causing DB errors
        $willingSkck = $this->request->getPost('willing_to_make_skck');
        if ($hasSkck) {
            $willingSkck = 1; // Already has SKCK, implies compliance
        } else {
            $willingSkck = $willingSkck === 'yes' ? 1 : 0;
        }

        $hasFelony = $this->request->getPost('has_felony_record') === 'yes' ? 1 : 0;
        $willingFelony = $this->request->getPost('willing_to_make_felony_statement');
        if ($hasFelony === 0) {
            $willingFelony = $willingFelony === 'yes' ? 1 : 0;
        } else {
            $willingFelony = 1; // Logic: if they have record, this is irrelevant but set to 1 for completeness if needed, or 0
        }

        $hasBankruptcy = $this->request->getPost('has_bankruptcy_record') === 'yes' ? 1 : 0;

        $whatsapp = $this->request->getPost('whatsapp');
        if (empty($whatsapp) && $isAuthenticated) {
            $user = $this->userModel->find($userId);
            $whatsapp = $user['phone'] ?? '';
        }

        // Prepare CWPA submission data
        $submissionData = [
            'user_id' => $userId,
            'whatsapp' => $whatsapp,
            'ktp_number' => $this->request->getPost('ktp_number'),
            'npwp_number' => str_replace(['.', '-'], '', $this->request->getPost('npwp_number')),
            'address' => $this->request->getPost('address'),
            'specialties' => $specialtiesStr,
            'trading_experience' => implode(', ', $specialties),
            'social_media' => json_encode($socialMedia),
            'has_ijazah_s1' => $hasIjazah,
            'has_skck_card' => $hasSkck,
            'willing_to_make_skck' => $willingSkck,
            'has_felony_record' => $hasFelony,
            'willing_to_make_felony_statement' => $willingFelony,
            'has_bankruptcy_record' => $hasBankruptcy,
            'has_clean_record' => ($hasFelony === 0 && $hasBankruptcy === 0) ? 1 : 0,
            'status' => 'pending',
            'payment_status' => 'pending_verification'
        ];

        // Upload documents
        $files = ['cv', 'ktp_file' => 'ktp', 'npwp_file' => 'npwp', 'ijazah', 'skck'];
        foreach ($files as $key => $field) {
            $fileKey = is_numeric($key) ? $field : $key;
            $dbField = is_numeric($key) ? $field : $field;
            
            // Determine file category for validation
            $category = 'document'; // Default: PDF/Word documents
            if (in_array($fileKey, ['ktp_file', 'npwp_file', 'ijazah', 'skck'])) {
                // These can also be images (jpg, png, pdf)
                // FileUploadService handles mixed validation
                $category = 'document'; // Use document category as it's most permissive
            }
            
            $path = $upload($fileKey, $category);
            if ($path) {
                $submissionData[$dbField] = $path;
            }
        }

        // Handle Transfer Proof
        $transferProofPath = $upload('transfer_proof', 'transfer_proof');
        if ($transferProofPath) {
            $submissionData['transfer_proof'] = $transferProofPath;
        }

        // Validate that all required files were uploaded
        if (empty($submissionData['cv']) || empty($submissionData['ktp']) || 
            empty($submissionData['npwp']) || empty($submissionData['ijazah'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal upload dokumen. Pastikan semua file dokumen wajib dipilih dengan benar.'
            ]);
        }

        // Save submission
        try {
            if (!$this->cwpaSubmissionModel->save($submissionData)) {
                $errors = $this->cwpaSubmissionModel->errors();
                log_message('error', 'CWPA Submission Insert Failed: ' . json_encode($errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data pendaftaran: ' . implode(', ', $errors)
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'CWPA Submission Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }

        // Hardcoded CWPA Service Data
        $cwpaService = [
            'id' => 0, // Virtual ID for CWPA service
            'name' => 'Pendampingan CWPA',
            'slug' => 'pendampingan-cwpa',
            'description' => 'Program pendampingan lengkap untuk menjadi Wakil Penasihat Berjangka (WPA) yang tersertifikasi',
            'price' => 23500000, // Rp 23.5 juta
            'image' => '/uploads/cwpa.jpg',
            'features' => [
                'Pembekalan materi oleh WPA PT Alma Indonesia Raya yang telah berpengalaman',
                'Pendampingan selama proses memperoleh izin WPA berupa konsultasi hingga asistensi/koordinasi pendaftaran sertifikasi pada setiap tahap dengan instansi terkait',
                'Biaya-biaya sertifikasi pada tiap instansi',
                'Biaya-biaya keanggotaan',
                'Biaya administratif dokumen selama pengajuan izin CWPA (print, kirim dokumen, materai, dll)',
                'Kontrak kerja sebagai WPA pada PT Alma Indonesia Raya setelah memperoleh sertifikasi lengkap dan asistensi pendaftaran izin WPA ke instansi yang berwenang',
                'Event-event (offline dan online) yang didukung oleh PT Alma Indonesia Raya, dalam rangka meningkatkan pengetahuan mengenai PBK, WPA, dan legalitas industri'
            ]
        ];

        // Send Confirmation Email
        try {
            $userEmail = '';
            $userName = '';

            if ($isAuthenticated) {
                $user = $this->userModel->find($userId);
                $userEmail = $user['email'] ?? '';
                $userName = $user['name'] ?? '';
            } else {
                $userEmail = null; // Email is not collected during WPA guest registration anymore
                $userName = $name; // from post
            }

            if (!empty($userEmail)) {
                $emailService = new \App\Libraries\EmailService();
                $emailService->sendCwpaRegistrationConfirmation($userEmail, $userName, $submissionData);
                log_message('debug', 'CWPA confirmation email sent to: ' . $userEmail);
            } else {
                log_message('error', 'Failed to send CWPA confirmation email: User email is empty.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to send CWPA confirmation email: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            // Don't block registration if email fails
        }

        // Send WhatsApp Notification with Password if newly created
        if (!$isAuthenticated && !empty($password)) {
            try {
                $dateNow = date('d M Y H:i');
                $loginLink = base_url('login');
                $waMessage = "✅ Pendaftaran WPA Berhasil!\n\n"
                    . "Tanggal   : {$dateNow}\n"
                    . "Nama      : {$name}\n"
                    . "No. HP    : {$phone}\n"
                    . "Password  : {$password}\n\n"
                    . "Silahkan login ke dashboard Anda, untuk melanjutkan kelayanan berikutnya.\n\n"
                    . "Login : {$loginLink}\n\n"
                    . "Selamat bergabung di Almai | Platform Resmi Penasihat Perdagangan Derivatif & Aset Keuangan Digital 🇮🇩";

                $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                $waApiKey = trim(env('WAGW_API_KEY', ''));

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $waUrl . '/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'phone' => $phone,
                        'message' => $waMessage
                    ]),
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'x-api-key: ' . $waApiKey
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            } catch (\Exception $e) {
                log_message('error', 'Failed to send WPA registration WA: ' . $e->getMessage());
            }
        }

        // Return success and redirect to checkout
        // Transaction will be created in Checkout process


        // Create Manual Transaction in TransaksiModel
        $transaksiModel = new \App\Models\TransaksiModel();
        $invoiceNumber = $transaksiModel->generateInvoiceNumber();

        $transaksiData = [
            'invoice_number' => $invoiceNumber,
            'user_id' => $userId,
            'product_type' => 'cwpa',
            'product_name' => $cwpaService['name'],
            'amount' => $cwpaService['price'],
            'total' => $cwpaService['price'],
            'status' => 'paid', // 'paid' in this system often means 'pending verification' for manual
            'payment_method' => 'transfer',
            'transfer_proof' => $submissionData['transfer_proof'] ?? null,
            'notes' => 'Pendaftaran Program Pendampingan CWPA (Manual Transfer)'
        ];

        if (!$transaksiModel->insert($transaksiData)) {
             log_message('error', 'CWPA Transaction Insert Failed: ' . json_encode($transaksiModel->errors()));
        }

        // Return success and redirect to success page
        $redirectUrl = base_url('user/invoice/' . $invoiceNumber);
        log_message('debug', 'CWPA Submission Successful. Redirecting to: ' . $redirectUrl);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pendaftaran berhasil! Bukti transfer telah kami terima dan sedang dalam proses verifikasi.',
            'redirect_url' => $redirectUrl
        ]);
    }


    public function pricing()
    {
        $data = [
            'title' => 'Paket Layanan CWPA - Almai WPA Platform',
            'meta_title' => 'Paket Layanan CWPA',
            'meta_description' => 'Paket layanan dan investasi karir untuk CWPA',
        ];
        return view('pages/daftar-cwpa/pricing', $data);
    }

    /**
     * Check if user exists (AJAX)
     */
    public function checkUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $email = $this->request->getVar('email');
        $whatsapp = $this->request->getVar('whatsapp');

        $userModel = new \App\Models\UserModel();

        if ($email) {
            $checkEmail = $userModel->where('email', $email)->first();
            if ($checkEmail) {
                return $this->response->setJSON([
                    'status' => 'exists',
                    'field' => 'email',
                    'message' => 'Email ini sudah terdaftar! <br>Silakan <a href="' . base_url('login') . '" class="text-accent hover:underline font-bold">Login Disini</a> jika Anda sudah memiliki akun.'
                ]);
            }
        }

        if ($whatsapp) {
            $checkWa = $userModel->where('phone', $whatsapp)->first();
            if ($checkWa) {
                return $this->response->setJSON([
                    'status' => 'exists',
                    'field' => 'whatsapp',
                    'message' => 'No WhatsApp ini sudah terdaftar! <br>Silakan <a href="' . base_url('login') . '" class="text-accent hover:underline font-bold">Login Disini</a> jika Anda sudah memiliki akun.'
                ]);
            }
        }

        return $this->response->setJSON(['status' => 'available']);
    }

    /**
     * Send OTP for CWPA Registration
     */
    public function sendOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';
            $name = $json['name'] ?? 'Pendaftar CWPA';
            $channel = $json['channel'] ?? 'whatsapp';

            $isWhatsApp = ($channel === 'whatsapp' || $channel === 'wpa_whatsapp');
            $identifier = $isWhatsApp ? $phone : $email;

            if (empty($identifier)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email atau Nomor WhatsApp wajib diisi.']);
            }

            // Save temp data to session for auto-registration after OTP verification
            session()->set('temp_wpa_reg', [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'kode_affiliator' => $json['kode_affiliator'] ?? '',
            ]);

            // Check if user already exists
            if ($isWhatsApp) {
                $phoneClean = preg_replace('/[^0-9]/', '', $phone);
                $phoneVariants = [$phone, $phoneClean];
                if (substr($phoneClean, 0, 1) === '0') {
                    $phoneVariants[] = '62' . substr($phoneClean, 1);
                } elseif (substr($phoneClean, 0, 2) === '62') {
                    $phoneVariants[] = '0' . substr($phoneClean, 2);
                }
                $existing = $this->userModel->whereIn('phone', $phoneVariants)->first();
            } else {
                $existing = $this->userModel->where('email', $identifier)->first();
            }
            
            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => ($isWhatsApp ? 'Nomor WhatsApp' : 'Email') . ' sudah terdaftar. Silakan login.'
                ]);
            }

            $otpModel = new \App\Models\OtpModel();
            $otp = $otpModel->generateOtp($identifier, 'registration', $channel);

            if ($isWhatsApp) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'OTP berhasil dibuat',
                    'channel' => 'whatsapp',
                    'otp_code' => $otp,
                    'admin_wa' => env('NO_OTP')
                ]);
            } else {
                $emailService = new \App\Libraries\EmailService();
                $resp = $emailService->sendOtp($email, $name, $otp);
                if (!$resp['success']) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim Email.']);
                }
                $msg = 'OTP berhasil dikirim ke Email';
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $msg,
                'otp_dev' => (ENVIRONMENT === 'development' ? $otp : null)
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    /**
     * Polling endpoint for WhatsApp registration verification (used by frontend polling)
     */
    public function checkWaRegistration()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $identifier = $this->request->getGet('identifier') ?? $this->request->getPost('identifier') ?? '';
            if (empty($identifier)) {
                return $this->response->setJSON(['success' => false, 'verified' => false, 'message' => 'Identifier missing']);
            }

            $otpModel = new \App\Models\OtpModel();
            $isPhone = is_numeric(str_replace(['+', '-', ' '], '', $identifier)) && strlen(preg_replace('/\D/', '', $identifier)) >= 10;

            $verified = false;

            if ($isPhone) {
                // For phone numbers, reuse existing OTP helper
                if (method_exists($otpModel, 'isWaVerified')) {
                    $verified = $otpModel->isWaVerified($identifier);
                }
            } else {
                // For email identifier, consider registration complete when a user record exists
                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('email', $identifier)->first();
                if ($user) $verified = true;
            }

            return $this->response->setJSON(['success' => true, 'verified' => (bool) $verified]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'verified' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Verify OTP for CWPA Registration
     */
    public function verifyOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $json = $this->request->getJSON(true);
            $identifier = $json['identifier'] ?? '';
            $otp = $json['otp'] ?? '';
            $isWhatsAppPolling = isset($json['otp']) && $json['otp'] === '';

            if (empty($identifier)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap.']);
            }

            $otpModel = new \App\Models\OtpModel();
            $isPhone = is_numeric(str_replace(['+', '-', ' '], '', $identifier)) && strlen($identifier) >= 10;
            $isValid = false;

            if ($isWhatsAppPolling) {
                if ($isPhone) {
                    if ($otpModel->isWaVerified($identifier)) {
                        $isValid = true;
                    } else {
                        return $this->response->setJSON(['success' => false, 'message' => 'WhatsApp belum terverifikasi.']);
                    }
                } else {
                    // Polling by email: consider verified when a user record exists (webhook may create user)
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->where('email', $identifier)->first();
                    if ($user) {
                        $isValid = true;
                    } else {
                        return $this->response->setJSON(['success' => false, 'message' => 'WhatsApp belum terverifikasi.']);
                    }
                }
            } else {
                if (empty($otp)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap.']);
                }
                if ($otpModel->verifyOtp($identifier, $otp, 'registration')) {
                    $isValid = true;
                }
            }

            if ($isValid) {
                if ($isPhone) {
                    session()->set('cwpa_whatsapp_verified', true);
                    session()->set('otp_wa_verified', true);
                    session()->set('cwpa_verified_phone', $identifier);
                } else {
                    session()->set('cwpa_email_verified', true);
                    session()->set('cwpa_verified_email', $identifier);
                }

                // Retrieve temp reg data from session
                $tempData = session()->get('temp_wpa_reg');
                
                if ($tempData) {
                    $userModel = new \App\Models\UserModel();
                    $name = $tempData['name'];
                    $email = $tempData['email'];
                    $phone = $tempData['phone'];
                    $affiliateCode = $tempData['kode_affiliator'];
                    
                    // Check if already registered just in case
                    $existingPhone = $userModel->where('phone', $phone)->first();
                    $existingEmail = $userModel->where('email', $email)->first();
                    $userToLogin = $existingPhone ?: $existingEmail;
                    
                    if (!$userToLogin) {
                        // Create new user
                        $password = 'ALMA-' . strtoupper(substr(md5(uniqid()), 0, 4));
                        
                        $userId = $userModel->insert([
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                            'password' => password_hash($password, PASSWORD_BCRYPT),
                            'level_id' => \App\Models\LevelModel::LEVEL_USER,
                            'status' => 'active',
                            'affiliator_code' => $affiliateCode,
                            'otp_status' => ($isPhone ? 1 : 0),
                            'email_verified_at' => (!$isPhone ? date('Y-m-d H:i:s') : null)
                        ]);
                        
                        $userToLogin = $userModel->find($userId);
                        
                        // Handle affiliate logic
                        $affiliatorName = '-';
                        $affiliatorRole = 'Direct';
                        $referrerUserId = null;
                        
                        if (!empty($affiliateCode)) {
                            // Cek tabel wpa
                            $wpaModel = new \App\Models\WpaModel();
                            $wpa = $wpaModel->where('slug', $affiliateCode)->first();
                            
                            if ($wpa) {
                                $referrerUserId = $wpa['user_id'];
                            } else {
                                // Cek cwpa
                                $cwpaModel = new \App\Models\CwpaModel();
                                $cwpa = $cwpaModel->where('slug', $affiliateCode)->where('status', 'approved')->first();
                                if ($cwpa) {
                                    $referrerUserId = $cwpa['user_id'];
                                }
                            }
                            
                            if ($referrerUserId) {
                                $userDataModel = new \App\Models\UserDataModel();
                                $userDataModel->insert([
                                    'user_id' => $userId,
                                    'referrer_id' => $referrerUserId
                                ]);
                                
                                $refUser = $userModel->find($referrerUserId);
                                if ($refUser) {
                                    $affiliatorName = $refUser['name'];
                                    if ($refUser['level_id'] == \App\Models\LevelModel::LEVEL_WPA) $affiliatorRole = 'WPA';
                                    elseif ($refUser['level_id'] == \App\Models\LevelModel::LEVEL_CWPA) $affiliatorRole = 'CWPA';
                                    elseif (in_array($refUser['level_id'], [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_SUPER_ADMIN])) $affiliatorRole = 'Admin';
                                    else $affiliatorRole = 'User';
                                }
                            } else {
                                $userDataModel = new \App\Models\UserDataModel();
                                $userDataModel->insert(['user_id' => $userId]);
                            }
                        } else {
                            $userDataModel = new \App\Models\UserDataModel();
                            $userDataModel->insert(['user_id' => $userId]);
                        }
                        
                        // Process Referral Registration Bonus if any
                        if ($referrerUserId) {
                            try {
                                $poinService = new \App\Libraries\PoinService();
                                $poinService->processReferralRegistration($userId, $referrerUserId);
                            } catch (\Exception $e) {}
                        }
                        
                        // Insert Point logic (Optional, default 100 registration point)
                        try {
                            $poinService = new \App\Libraries\PoinService();
                            $poinService->processRegistrationBonus($userId);
                        } catch (\Exception $e) {}

                        // Send WA Message
                        $dateNow = date('d M Y H:i');
                        $loginLink = base_url('login');
                        $message = "✅ Registrasi Berhasil!\n\n"
                            . "Tanggal   : {$dateNow}\n"
                            . "Nama      : {$name}\n"
                            . "No. HP    : {$phone}\n"
                            . "Email     : {$email}\n"
                            . "Password  : {$password}\n"
                            . "{$affiliatorRole} : {$affiliatorName}\n"
                            . "Bonus Registrasi : 100 poin\n\n"
                            . "Silahkan login ke dashboard Anda, untuk melanjutkan kelayanan berikutnya.\n\n"
                            . "Login : {$loginLink}\n\n"
                            . "Selamat bergabung di Almai | Platform Resmi Penasihat Perdagangan Derivatif & Aset Keuangan Digital 🇮🇩";

                        $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                        $waApiKey = trim(env('WAGW_API_KEY', ''));

                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => $waUrl . '/send',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_TIMEOUT => 5,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode([
                                'phone' => preg_replace('/[^0-9]/', '', $phone),
                                'message' => $message
                            ]),
                            CURLOPT_HTTPHEADER => [
                                'Content-Type: application/json',
                                'x-api-key: ' . $waApiKey
                            ],
                        ]);
                        curl_exec($curl);
                        curl_close($curl);
                    }
                    
                    $role = \App\Models\LevelModel::roleStringFromLevel((int) ($userToLogin['level_id'] ?? \App\Models\LevelModel::LEVEL_USER));
                    
                    // Set login session
                    session()->set([
                        'isLoggedIn' => true,
                        'userId'     => $userToLogin['id'],
                        'userName'   => $userToLogin['name'],
                        'userRole'   => $role,
                        'role'       => $role,
                        'level_id'   => $userToLogin['level_id'] ?? \App\Models\LevelModel::LEVEL_USER
                    ]);
                }

                return $this->response->setJSON(['success' => true, 'message' => 'Verifikasi berhasil!']);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Kode OTP salah atau sudah kadaluarsa.']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    public function store()
    {
        // Apply rate limiting to prevent brute force
        $throttler = \Config\Services::throttler();
        $clientId = $this->request->getIPAddress();
        
        if ($throttler->check(md5($clientId . '_cwpa_store'), 20, 3600) === false) { // 20 attempts per hour
            return redirect()->back()->with('error', 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa menit.');
        }

        // Validation Rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'whatsapp' => 'required|regex_match[/^(\+62|62|0)?[0-9]{9,12}$/]',
            'specialties' => 'required',
            'has_ijazah_s1' => 'required|in_list[yes,no]',
            'has_skck_card' => 'required|in_list[yes,no]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate at least one social media
        $ig = $this->request->getPost('ig');
        $fb = $this->request->getPost('fb');
        $youtube = $this->request->getPost('youtube');
        $tiktok = $this->request->getPost('tiktok');
        $linkedin = $this->request->getPost('linkedin');

        if (empty($ig) && empty($fb) && empty($youtube) && empty($tiktok) && empty($linkedin)) {
            return redirect()->back()->withInput()->with('error', 'Setidaknya isi satu akun media sosial.');
        }

        // Sanitize inputs
        $email = filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL);
        $name = htmlspecialchars($this->request->getPost('name'), ENT_QUOTES, 'UTF-8');
        $whatsapp = $this->request->getPost('whatsapp');

        // Handle User Logic (Create or Link)
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            // New user
            $password = bin2hex(random_bytes(8));
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $email,
                'phone' => $this->request->getPost('whatsapp'), // Save phone number
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user',
                'active' => 1,
                'affiliator_code' => 'daftar-cwpa' // Automatic assignment
            ];
            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();

            // Auto-login
            session()->set([
                'isLoggedIn' => true,
                'userId' => $userId,
                'userName' => $userData['name'],
                'userEmail' => $userData['email'],
                'userRole' => 'user',
                'role' => 'user',
                'level_id' => \App\Models\LevelModel::LEVEL_USER
            ]);
        } else {
            // Existing user
            $userId = $user['id'];

            // Update user data: affiliator code and phone number
            $this->userModel->update($userId, [
                'affiliator_code' => 'daftar-cwpa',
                'phone' => $this->request->getPost('whatsapp') // Update phone number
            ]);

            if (!session()->get('isLoggedIn')) {
                return redirect()->to('/login')->with('error', 'Email sudah terdaftar. Silakan login terlebih dahulu untuk melanjutkan pendaftaran.');
            }

            if (session()->get('userEmail') !== $email) {
                $userId = session()->get('userId');
            }
        }

        // Prepare Data
        $socialMedia = [
            'ig' => $ig,
            'fb' => $fb,
            'youtube' => $youtube,
            'tiktok' => $tiktok,
            'linkedin' => $linkedin,
        ];

        $specialties = $this->request->getPost('specialties');
        if (is_array($specialties)) {
            // Filter "Pilih semua" if present, though logic handles it on frontend usually
            $specialties = array_values(array_filter($specialties, function ($v) {
                return $v !== 'all';
            }));
            $specialtiesStr = json_encode($specialties);
        } else {
            $specialtiesStr = json_encode([]);
        }

        // Handle Boolean fields from radio/checkbox
        $hasIjazah = $this->request->getPost('has_ijazah_s1') === 'yes' ? 1 : 0;
        $hasSkck = $this->request->getPost('has_skck_card') === 'yes' ? 1 : 0;

        // Conditional logic for willing_to_make_skck
        $willingSkck = 0;
        if ($hasSkck === 0) {
            $willingSkck = $this->request->getPost('willing_to_make_skck') === 'yes' ? 1 : 0;
        }

        $hasFelony = $this->request->getPost('has_felony_record') === 'yes' ? 1 : 0;
        $hasBankruptcy = $this->request->getPost('has_bankruptcy_record') === 'yes' ? 1 : 0;

        // Store Submission
        $submissionData = [
            'user_id' => $userId,
            'whatsapp' => $this->request->getPost('whatsapp'),
            'specialties' => $specialtiesStr,
            // Use specialties as trading_experience label too if needed, or leave empty
            'trading_experience' => implode(', ', $specialties ?? []),
            'social_media' => json_encode($socialMedia),
            'has_ijazah_s1' => $hasIjazah,
            'has_skck_card' => $hasSkck,
            'willing_to_make_skck' => $willingSkck,
            'has_felony_record' => $hasFelony,
            'has_bankruptcy_record' => $hasBankruptcy,
            'has_clean_record' => ($hasFelony === 0 && $hasBankruptcy === 0) ? 1 : 0, // Deducing generic clean record
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        // Check if submission exists
        $existing = $this->cwpaSubmissionModel->where('user_id', $userId)->first();

        if ($existing) {
            $this->cwpaSubmissionModel->update($existing['id'], $submissionData);
        } else {
            $this->cwpaSubmissionModel->insert($submissionData);
        }

        return redirect()->to('/daftar-cwpa/pricing');
    }

    public function checkout()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = session()->get('userId');
        $submission = $this->cwpaSubmissionModel->where('user_id', $userId)->first();

        // Allow checkout if submission exists OR if we allow creating a dummy one automatically for existing users 
        // But for CWPA, data is crucial. Let's insist on submission data.
        if (!$submission) {
            return redirect()->to('/daftar-cwpa')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Get CWPA service price from layanan_subscription
        $subscriptionModel = new \App\Models\LayananSubscriptionModel();
        $cwpaService = $subscriptionModel->where('type', 'pendampingan')
            ->where('status', 'published')
            ->where('price >', 0) // Get the paid service, not the free one
            ->orderBy('price', 'DESC') // Get the most expensive (premium) package
            ->first();

        if (!$cwpaService) {
            return redirect()->back()->with('error', 'Layanan CWPA tidak ditemukan. Silakan hubungi admin.');
        }

        $amount = $cwpaService['price']; // Get actual price from database

        // 1. Prepare Transaction Data
        $transaksiModel = new \App\Models\TransaksiModel();
        $invoiceNumber = $transaksiModel->generateInvoiceNumber();

        $transaksiData = [
            'invoice_number' => $invoiceNumber,
            'user_id' => $userId,
            'product_type' => 'cwpa',
            'product_name' => $cwpaService['name'],
            'amount' => $amount,
            'total' => $amount,
            'status' => 'pending',
            'payment_method' => 'xendit',
            'notes' => 'Pendaftaran Program Pendampingan CWPA'
        ];

        $transaksiModel->insert($transaksiData);

        // 2. Create Xendit Invoice
        $xendit = new \App\Libraries\XenditService();
        $user = $this->userModel->find($userId);

        $invoiceData = [
            'external_id' => $invoiceNumber,
            'amount' => $amount,
            'email' => $user['email'],
            'customer_name' => $user['name'],
            'phone' => $submission['whatsapp'] ?? $user['phone'] ?? '',
            'description' => 'Pembayaran ' . $cwpaService['name'] . ' - ' . $user['name'],
            'item_name' => $cwpaService['name'],
            'success_url' => base_url('daftar-cwpa/success?invoice=' . $invoiceNumber),
            'failure_url' => base_url('daftar-cwpa/failure?invoice=' . $invoiceNumber),
        ];

        $result = $xendit->createInvoice($invoiceData);

        if ($result['success']) {
            return redirect()->to($result['data']['invoice_url']);
        } else {
            return redirect()->back()->with('error', 'Gagal membuat invoice pembayaran: ' . ($result['error'] ?? 'Unknown error'));
        }
    }

    public function success()
    {
        $invoiceNumber = $this->request->getGet('invoice');

        // Restore session from transaction if needed
        $transaksiModel = new \App\Models\TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if ($transaksi && !session()->get('isLoggedIn')) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($transaksi['user_id']);
            if ($user) {
                session()->set([
                    'isLoggedIn' => true,
                    'userId' => (int)$user['id'],
                    'userName' => $user['name'],
                    'userEmail' => $user['email'],
                    'userRole' => $user['role'] ?? 'user',
                    'role' => $user['role'] ?? 'user',
                    'level_id' => $user['level_id'] ?? \App\Models\LevelModel::LEVEL_USER
                ]);
            }
        }

        // Verify payment status with Xendit directly to ensure immediate access
        if ($transaksi && $transaksi['status'] === 'pending') {
            try {
                $xenditService = new \App\Libraries\XenditService();
                // Note: Xendit API get by external_id returns a list
                $xenditRes = $xenditService->getInvoiceByExternalId($invoiceNumber);

                if (isset($xenditRes['data']) && is_array($xenditRes['data']) && !empty($xenditRes['data'])) {
                    // response data is an array of invoices, pick the first one
                    $invoiceData = $xenditRes['data'][0] ?? null;

                    if ($invoiceData && in_array($invoiceData['status'], ['PAID', 'SETTLED'])) {
                        $transaksiModel->update($transaksi['id'], ['status' => 'confirmed']);

                        // Update Submission
                        $cwpaModel = new \App\Models\CwpaSubmissionModel();
                        $cwpaModel->where('user_id', $transaksi['user_id'])->update(null, ['payment_status' => 'paid']);
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'CWPA Automatic Status Check Failed: ' . $e->getMessage());
            }
        }

        return redirect()->to('/user/layanan-saya')->with('success', 'Pembayaran berhasil! Paket Pendampingan CWPA telah aktif. Silakan cek detail di bawah.');
    }

    public function failure()
    {
        $invoiceNumber = $this->request->getGet('invoice');

        // Restore session from transaction if needed
        $transaksiModel = new \App\Models\TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if ($transaksi && !session()->get('isLoggedIn')) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($transaksi['user_id']);
            if ($user) {
                session()->set([
                    'isLoggedIn' => true,
                    'userId' => (int)$user['id'],
                    'userName' => $user['name'],
                    'userEmail' => $user['email'],
                    'userRole' => $user['role'] ?? 'user',
                    'role' => $user['role'] ?? 'user',
                    'level_id' => $user['level_id'] ?? \App\Models\LevelModel::LEVEL_USER
                ]);
            }
        }

        $data = [
            'title' => 'Pembayaran Gagal - Almai',
            'invoice' => $invoiceNumber
        ];
        return view('pages/daftar-cwpa/failure', $data);
    }

    public function documents()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Upload Dokumen - Almai WPA Platform',
            'meta_title' => 'Upload Dokumen CWPA',
            'meta_description' => 'Lengkapi dokumen pendaftaran CWPA Anda.',
            'validation' => \Config\Services::validation(),
        ];
        return view('pages/daftar-cwpa/documents', $data);
    }

    public function storeDocuments()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('userId');
        $submission = $this->cwpaSubmissionModel->where('user_id', $userId)->first();

        if (!$submission) {
            return redirect()->to('/daftar-cwpa');
        }

        $rules = [
            'cv' => 'uploaded[cv]|max_size[cv,10240]|ext_in[cv,pdf,doc,docx]',
            'ijazah' => 'uploaded[ijazah]|max_size[ijazah,10240]|ext_in[ijazah,pdf,jpg,jpeg,png]',
            'ktp' => 'uploaded[ktp]|max_size[ktp,10240]|ext_in[ktp,pdf,jpg,jpeg,png]',
            'npwp' => 'uploaded[npwp]|max_size[npwp,10240]|ext_in[npwp,pdf,jpg,jpeg,png]',
            'skck' => 'permit_empty|max_size[skck,10240]|ext_in[skck,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Initialize FileUploadService for secure uploads
        $uploadService = new \App\Libraries\FileUploadService();

        // Upload Helper - Uses FileUploadService for secure handling
        $upload = function ($fileKey) use ($userId, $uploadService) {
            $file = $this->request->getFile($fileKey);
            if (!$file) {
                return null;
            }

            // Save file securely with validation
            $filePath = $uploadService->save($file, $userId, 'document');
            
            if (!$filePath) {
                log_message('warning', "File upload failed for {$fileKey}: " . $uploadService->getLastError());
                return null;
            }

            return $filePath;
        };

        $dataToUpdate = [];

        // Process each file
        $files = ['cv', 'ijazah', 'ktp', 'npwp', 'skck'];
        foreach ($files as $f) {
            $path = $upload($f);
            if ($path) {
                $dataToUpdate[$f] = $path;
            }
        }

        if (!empty($dataToUpdate)) {
            $this->cwpaSubmissionModel->update($submission['id'], $dataToUpdate);
        }

        return redirect()->to('/daftar-cwpa/agreement')->with('success', 'Dokumen berhasil diunggah. Silakan setujui Perjanjian Pengguna.');
    }

    public function agreement()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Persetujuan CWPA - Almai WPA Platform',
            'meta_title' => 'Persetujuan Pendaftaran CWPA',
            'meta_description' => 'Persetujuan Syarat dan Ketentuan Pendaftaran Calon Wakil Penasihat Berjangka.',
            'validation' => \Config\Services::validation(),
        ];
        return view('pages/daftar-cwpa/agreement', $data);
    }

    public function processAgreement()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('userId');
        $submission = $this->cwpaSubmissionModel->where('user_id', $userId)->first();

        // Ensure user ticked the agreement
        if (!$this->request->getPost('agreement')) {
            return redirect()->back()->with('error', 'Anda harus menyetujui perjanjian terlebih dahulu.');
        }

        // Mark agreement timestamp? Maybe redundant if we move payment next.
        // But good for audit logs.
        // For now, proceed to payment.

        return redirect()->to('/daftar-cwpa/checkout');
    }

    public function submitConsolidated()
    {
        // 1. Rate Limiting (Prevent abuse)
        $throttler = \Config\Services::throttler();
        $clientId = $this->request->getIPAddress();
        
        if ($throttler->check(md5($clientId . '_cwpa_consolidated'), 10, 3600) === false) { // 10 attempts per hour
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa menit.'
            ])->setStatusCode(429);
        }

        // Validation Rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'whatsapp' => 'required|regex_match[/^(\+62|62|0)?[0-9]{9,12}$/]',
            'ktp_number' => 'required|regex_match[/^[0-9. ]{16,20}$/]',
            // File rules - all required except SKCK optional
            'cv' => 'uploaded[cv]|max_size[cv,10240]|ext_in[cv,pdf,doc,docx]',
            'ijazah' => 'uploaded[ijazah]|max_size[ijazah,10240]|ext_in[ijazah,pdf,jpg,jpeg,png]',
            'ktp' => 'uploaded[ktp]|max_size[ktp,10240]|ext_in[ktp,pdf,jpg,jpeg,png]',
            'npwp' => 'uploaded[npwp]|max_size[npwp,10240]|ext_in[npwp,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            // Return JSON error for AJAX
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Sanitize email and name
        $email = filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL);
        $name = htmlspecialchars($this->request->getPost('name'), ENT_QUOTES, 'UTF-8');

        // 1. User Logic
        // Check if user exists by email
        $user = $this->userModel->where('email', $email)->first();
        if (!$user) {
            // Register new user logic...
            // Similar to store()
            $password = bin2hex(random_bytes(8));
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $email,
                'phone' => $this->request->getPost('whatsapp'),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user',
                'active' => 1,
                'affiliator_code' => 'daftar-cwpa'
            ];
            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();

            // Auto-login
            session()->set([
                'isLoggedIn' => true,
                'userId' => $userId,
                'userName' => $userData['name'],
                'userEmail' => $userData['email'],
                'userRole' => 'user',
                'role' => 'user',
                'level_id' => \App\Models\LevelModel::LEVEL_USER
            ]);
        } else {
            $userId = $user['id'];
            // Update user data: affiliator code and phone number
            $this->userModel->update($userId, [
                'affiliator_code' => 'daftar-cwpa',
                'phone' => $this->request->getPost('whatsapp')
            ]);

            // If logged in as someone else, error out?
            // Assuming the modal is filled by current user or we switch session?
            // "If not logged in, but email exists, require login" -> this complicates AJAX flow.
            // For now, assume if email matches existing but not logged in, we force login or simple adopt.
            if (!session()->get('isLoggedIn')) {
                // For security, we should probably require password.
                // But simplification: Let's assume this flow allows quick purchase.
                // We can auto-login if strict security isn't priority here compared to conversion,
                // BUT BETTER: Return error asking to login.
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email sudah terdaftar. Silakan login terlebih dahulu.'
                ]);
            }
        }

        // 2. Prepare CWPA Data
        $socialMedia = [
            'ig' => $this->request->getPost('ig'),
            'fb' => $this->request->getPost('fb'),
            'youtube' => $this->request->getPost('youtube'),
            'tiktok' => $this->request->getPost('tiktok'),
            'linkedin' => $this->request->getPost('linkedin'),
        ];

        $specialties = $this->request->getPost('specialties'); // array or csv?
        // If coming from FormData with name="specialties[]"
        if (empty($specialties)) $specialties = [];
        if (!is_array($specialties)) $specialties = [$specialties];
        $specialtiesStr = json_encode($specialties);

        // Initialize FileUploadService for secure uploads
        $uploadService = new \App\Libraries\FileUploadService();

        // Upload Helper - Uses FileUploadService for secure handling
        $upload = function ($fileKey) use ($userId, $uploadService) {
            $file = $this->request->getFile($fileKey);
            if (!$file) {
                return null;
            }

            // Save file securely with validation
            $filePath = $uploadService->save($file, $userId, 'document');
            
            if (!$filePath) {
                log_message('warning', "File upload failed for {$fileKey}: " . $uploadService->getLastError());
                return null;
            }

            return $filePath;
        };

        // Handle Boolean
        $hasIjazah = $this->request->getPost('has_ijazah_s1') === 'yes' ? 1 : 0;
        $hasSkck = $this->request->getPost('has_skck_card') === 'yes' ? 1 : 0;
        $hasFelony = $this->request->getPost('has_felony_record') === 'yes' ? 1 : 0;
        $hasBankruptcy = $this->request->getPost('has_bankruptcy_record') === 'yes' ? 1 : 0;
        $ktpNumber = $this->request->getPost('ktp_number');

        $submissionData = [
            'user_id' => $userId,
            'whatsapp' => $this->request->getPost('whatsapp'),
            'ktp_number' => $ktpNumber,
            'specialties' => $specialtiesStr,
            'trading_experience' => implode(', ', $specialties), // Just use specialties list as experience summary
            'social_media' => json_encode($socialMedia),
            'has_ijazah_s1' => $hasIjazah,
            'has_skck_card' => $hasSkck,
            'has_felony_record' => $hasFelony,
            'has_bankruptcy_record' => $hasBankruptcy,
            'has_clean_record' => ($hasFelony === 0 && $hasBankruptcy === 0) ? 1 : 0,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        // Handle Files
        $files = ['cv', 'ijazah', 'ktp', 'npwp', 'skck'];
        foreach ($files as $f) {
            $path = $upload($f);
            if ($path) {
                $submissionData[$f] = $path;
            }
        }

        // Validate required files were uploaded
        if (empty($submissionData['cv']) || empty($submissionData['ktp']) || 
            empty($submissionData['npwp']) || empty($submissionData['ijazah'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal upload dokumen. Pastikan semua file dokumen dipilih dengan benar.'
            ]);
        }

        // Save/Update Submission
        $existing = $this->cwpaSubmissionModel->where('user_id', $userId)->first();
        if ($existing) {
            $this->cwpaSubmissionModel->update($existing['id'], $submissionData);
        } else {
            $this->cwpaSubmissionModel->insert($submissionData);
        }

        // 3. Create Transaction (without Xendit yet - will be created in checkout page)
        $transaksiModel = new \App\Models\TransaksiModel();

        // Get CWPA service price from layanan_subscription
        $subscriptionModel = new \App\Models\LayananSubscriptionModel();
        $cwpaService = $subscriptionModel->where('type', 'pendampingan')
            ->where('status', 'published')
            ->where('price >', 0) // Get the paid service, not the free one
            ->orderBy('price', 'DESC') // Get the most expensive (premium) package
            ->first();

        if (!$cwpaService) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Layanan CWPA tidak ditemukan. Silakan hubungi admin.'
            ]);
        }

        $amount = $cwpaService['price']; // Get actual price from database

        // Check if user already has a pending transaction for CWPA
        $existingTransaction = $transaksiModel->where('user_id', $userId)
            ->where('product_type', 'pendampingan')
            ->whereIn('status', ['pending', 'waiting'])
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($existingTransaction) {
            // Use existing transaction
            $invoiceNumber = $existingTransaction['invoice_number'];
        } else {
            // Create new transaction
            $invoiceNumber = $transaksiModel->generateInvoiceNumber();

            $transaksiData = [
                'invoice_number' => $invoiceNumber,
                'user_id' => $userId,
                'product_type' => 'pendampingan',
                'product_id' => $cwpaService['id'],
                'product_name' => $cwpaService['name'],
                'amount' => $amount,
                'total' => $amount,
                'status' => 'pending',
                'payment_method' => null, // Will be selected in checkout page
                'notes' => 'Pendaftaran Program Pendampingan CWPA'
            ];

            $transaksiModel->insert($transaksiData);
        }

        // Redirect to checkout page with layanan slug format
        return $this->response->setJSON([
            'success' => true,
            'redirect_url' => base_url('user/checkout/layanan/' . $cwpaService['slug'])
        ]);
    }
}
