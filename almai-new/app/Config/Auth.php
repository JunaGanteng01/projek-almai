<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PasswordResetModel;
use App\Models\OtpModel;
use App\Models\WpaModel;
use App\Models\CwpaModel;
use App\Libraries\PoinService;
use App\Libraries\EmailService;
use App\Models\LegalDocumentModel;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->isLoggedIn()) {
            $role = $this->session->get('userRole');
            $tab = $this->request->getGet('tab');
            
            return redirect()->to(\App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::levelFromRoleString($role)));
            
            // If they are a normal user but they want to register for WPA, don't redirect them
            if ($role === 'user' && $tab === 'wpa') {
                // Let them pass to render the WPA form
            } else {
                return redirect()->to('/user/dashboard');
            }
        }

        // Store redirect URL if provided — SECURITY: Hanya izinkan relative path
        $redirect = $this->request->getGet('redirect');
        if ($redirect) {
            // Sanitasi: hapus domain luar, hanya izinkan path relatif
            $redirect = '/' . ltrim(str_replace(['http://', 'https://'], '', $redirect), '/');
            // Hanya izinkan karakter aman untuk URL path
            if (preg_match('/^[\/a-zA-Z0-9\-_\.\?=&%#]+$/', $redirect) && !str_starts_with($redirect, '//')) {
                $this->session->set('redirectAfterLogin', $redirect);
            }
        }

        // Auto switch to register tab if redirecting from checkout
        $showRegister = false;
        if ($redirect && strpos($redirect, 'checkout') !== false) {
            $showRegister = true;
        }

        $activeTab = $this->request->getGet('tab');

        $legalModel = new LegalDocumentModel();
        $legalWpa = $legalModel->find(5);

        $currentUser = null;
        if ($this->isLoggedIn()) {
            $userModel = new UserModel();
            $currentUser = $userModel->find($this->session->get('userId'));
        }

        return view('pages/auth/login', [
            'title' => $showRegister || $activeTab ? 'Daftar - Almai E-Learning' : 'Login - Almai E-Learning',
            'redirect' => $redirect,
            'showRegister' => $showRegister,
            'activeTab' => $activeTab,
            'legalWpa' => $legalWpa,
            'currentUser' => $currentUser
        ]);
    }

    public function attemptLogin()
    {
        // Verify Cloudflare Turnstile
        $turnstileToken = $this->request->getPost('cf-turnstile-response');
        $turnstileService = new \App\Libraries\TurnstileService();

        if ($turnstileService->isEnabled()) {
            $verification = $turnstileService->verify(
                $turnstileToken ?? '',
                $this->request->getIPAddress()
            );

            if (!$verification['success']) {
                return redirect()->back()->withInput()->with('error', $verification['error'] ?? 'Verifikasi CAPTCHA gagal. Silakan coba lagi.');
            }
        }

        // Rate Limiting (Throttling) against Brute Force
        $throttler = \Config\Services::throttler();
        $ipAddress = $this->request->getIPAddress();

        // Allow 5 attempts per minute
        if ($throttler->check(md5('login-' . $ipAddress), 5, 60) === false) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan login. Demi keamanan, silakan tunggu 1 menit sebelum mencoba lagi.');
        }

        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $loginIdentifier = $this->request->getPost('email');
        
        $query = $userModel->groupStart();
        if (strpos($loginIdentifier, '@') !== false) {
            $query->where('email', $loginIdentifier);
        } else {
            // Clean phone number
            $phoneClean = preg_replace('/[^0-9]/', '', $loginIdentifier);
            $phoneVariants = [$loginIdentifier, $phoneClean];
            if (substr($phoneClean, 0, 1) === '0') {
                $phoneVariants[] = '62' . substr($phoneClean, 1);
            } elseif (substr($phoneClean, 0, 2) === '62') {
                $phoneVariants[] = '0' . substr($phoneClean, 2);
            }
            $query->whereIn('phone', $phoneVariants);
        }
        $query->groupEnd();

        // Allow Regular User, Pro, and CWPA (Levels 1, 2, 3)
        $user = $query->whereIn('level_id', [
                \App\Models\LevelModel::LEVEL_USER,
                \App\Models\LevelModel::LEVEL_PRO,
                \App\Models\LevelModel::LEVEL_CWPA,
                \App\Models\LevelModel::LEVEL_WPA,
                \App\Models\LevelModel::LEVEL_ADMIN,
                \App\Models\LevelModel::LEVEL_ACCOUNTING,
                \App\Models\LevelModel::LEVEL_SUPER_ADMIN,
            ])
            ->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // Get user poin balance
        $poinModel = new \App\Models\PoinModel();
        $userPoin = $poinModel->getUserBalance($user['id']);

        // Determine role based on level_id
        $role = \App\Models\LevelModel::roleStringFromLevel((int) $user['level_id']);

        $sessionData = [
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => $role,
            'level_id' => $user['level_id'],
            'userAvatar' => $user['avatar'] ?? null,
            'userPoin' => $userPoin,
            'role' => $role,
            'is_pro' => $user['is_pro'] ?? 0,
            'userPhone' => $user['phone'] ?? '',
            'whatsapp' => $user['phone'] ?? '',
            'referralCode' => $user['code_referral'] ?? null,
        ];

        // Add WPA specific data if user is WPA
        if ($role === 'wpa') {
            $wpaModel = new WpaModel();
            $wpa = $wpaModel->where('user_id', $user['id'])->first();
            if ($wpa) {
                $sessionData['wpaId'] = $wpa['id'];
                $sessionData['wpaName'] = $wpa['name'] ?? $user['name'];
                $sessionData['wpaPhoto'] = $wpa['photo'] ?? null;
                $sessionData['wpaSpecialty'] = $wpa['specialty'] ?? null;
            }
        } elseif ($role === 'cwpa') {
            $cwpaModel = new CwpaModel();
            // Remove status filter to allow inactive CWPA to login
            $cwpa = $cwpaModel->where('user_id', $user['id'])->first();
            if ($cwpa) {
                $sessionData['cwpaId'] = $cwpa['id'];
                $sessionData['cwpaName'] = $cwpa['name'] ?? $user['name'];
                $sessionData['cwpaPhoto'] = $cwpa['photo'] ?? null;
                $sessionData['cwpaPhase'] = $cwpa['current_phase'] ?? 1;
                $sessionData['cwpaStatus'] = $cwpa['status'] ?? 'inactive';
            } else {
                // If no CWPA record found, fallback to standard user role
                $role = 'user';
                $sessionData['userRole'] = 'user';
                $sessionData['role'] = 'user';
            }
        }

        $this->session->set($sessionData);

        // SECURITY: Regenerasi session ID setelah login berhasil untuk mencegah session fixation attack.
        // Session lama dihancurkan dan ID baru dibuat, data session dipertahankan.
        $this->session->regenerate(true);

        // Clear redirectAfterLogin if it points to user dashboard for non-standard users
        $redirectAfterLogin = $this->session->get('redirectAfterLogin');
        if ($redirectAfterLogin && (str_contains($redirectAfterLogin, '/user/dashboard') || str_contains($redirectAfterLogin, '/user/kyc'))) {
            if ($role === 'cwpa' || $role === 'wpa') {
                $this->session->remove('redirectAfterLogin');
            }
        }

        $defaultDashboard = \App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::levelFromRoleString($role));

        $redirectPath = $this->session->get('redirectAfterLogin') ?? $defaultDashboard;
        $this->session->remove('redirectAfterLogin');

        return redirect()->to($redirectPath)->with('success', 'Login berhasil!');
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/user/dashboard');
        }

        // Get referral code from URL or from redirect URL
        $refCode = $this->request->getGet('ref');
        
        $redirect = $this->request->getGet('redirect');
        if (empty($refCode) && $redirect) {
            $parsedRedirect = parse_url($redirect);
            if (isset($parsedRedirect['query'])) {
                parse_str($parsedRedirect['query'], $redirectQuery);
                $refCode = $redirectQuery['ref'] ?? $redirectQuery['reff'] ?? null;
            }
        }

        if ($redirect) {
            // Ensure it's a relative path (remove base_url if present)
            $redirect = str_replace(base_url(), '', $redirect);
            $this->session->set('redirectAfterLogin', '/' . ltrim($redirect, '/'));
        }

        if ($refCode) {
            // Save to session
            session()->set('affiliate_code', $refCode);
            // Save to cookie
            set_cookie([
                'name'     => 'affiliate_code',
                'value'    => $refCode,
                'expire'   => 3600 * 24 * 30, // 30 days
                'path'     => '/',
                'secure'   => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        $activeTab = $this->request->getGet('tab');

        $legalModel = new LegalDocumentModel();
        $legalWpa = $legalModel->find(5);

        return view('pages/auth/login', [
            'title' => 'Daftar - Almai E-Learning',
            'showRegister' => true,
            'redirect' => $redirect,
            'refCode' => $refCode ?? session()->get('affiliate_code') ?? get_cookie('affiliate_code'),
            'activeTab' => $activeTab,
            'legalWpa' => $legalWpa
        ]);
    }

    /**
     * ALMAI REGISTRATION SYSTEM v2.0 - INITIATE REGISTER
     */
    public function initiateRegisterV2()
    {
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Request tidak valid.']);
        }

        try {
            $json = $this->request->getJSON(true) ?: $this->request->getPost();
            $name = trim($json['name'] ?? '');
            $email = trim(filter_var($json['email'] ?? '', FILTER_SANITIZE_EMAIL));
            $password = $json['password'] ?? '';
            $affiliatorCode = trim($json['affiliator_code'] ?? '');

            if (empty($name) || empty($email) || empty($password)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Lengkapi seluruh data pendaftaran.']);
            }

            if (empty($affiliatorCode)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Wajib memilih WPA / Affiliator pendamping.']);
            }

            // Check if email already registered in `users`
            $userModel = new UserModel();
            if ($userModel->where('email', $email)->first()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email ini sudah terdaftar. Silakan login.']);
            }

            // Create Pending Registration Token
            $pendingModel = new \App\Models\PendingRegistrationModel();
            $token = $pendingModel->createPendingRegistration([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'affiliator_code' => $affiliatorCode,
                'ip_address' => $this->request->getIPAddress()
            ]);

            // Construct WA Message
            $waMessage = "Halo ALMAI,\n\n"
                . "Saya ingin melakukan registrasi.\n\n"
                . "Registration Token\n"
                . "{$token}\n\n"
                . "Nama Lengkap\n"
                . "{$name}\n\n"
                . "Email\n"
                . "{$email}\n\n"
                . "Referral\n"
                . "{$affiliatorCode}\n\n"
                . "Mohon diproses.\n"
                . "Terima kasih.";

            $adminNumber = trim(env('WA_ADMIN_NUMBER', '628123456789'));
            $adminNumberClean = preg_replace('/[^0-9]/', '', $adminNumber);

            $waUrl = "https://wa.me/{$adminNumberClean}?text=" . urlencode($waMessage);

            return $this->response->setJSON([
                'success' => true,
                'token' => $token,
                'wa_url' => $waUrl,
                'message' => 'Registrasi diawali. Silakan kirim pesan WhatsApp untuk verifikasi.'
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'RegisterV2 Initiate Error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    /**
     * ALMAI REGISTRATION SYSTEM v2.0 - CHECK STATUS (POLLING)
     */
    public function checkRegisterStatusV2()
    {
        $token = $this->request->getGet('token');
        if (empty($token)) {
            return $this->response->setJSON(['status' => 'INVALID_TOKEN']);
        }

        $pendingModel = new \App\Models\PendingRegistrationModel();
        $statusInfo = $pendingModel->checkStatus($token);

        if ($statusInfo['status'] === 'VERIFIED') {
            // Auto Login Session
            $userModel = new UserModel();
            $pendingRecord = $pendingModel->where('registration_token', $token)->first();
            
            if ($pendingRecord && !empty($pendingRecord['user_id'])) {
                $user = $userModel->find($pendingRecord['user_id']);
                if ($user) {
                    $role = \App\Models\LevelModel::roleStringFromLevel((int) $user['level_id']);
                    
                    $this->session->set([
                        'isLoggedIn' => true,
                        'userId'     => $user['id'],
                        'userName'   => $user['name'],
                        'userEmail'  => $user['email'],
                        'userRole'   => $role,
                        'level_id'   => $user['level_id'],
                        'userPhone'  => $user['phone'],
                        'role'       => $role
                    ]);
                }
            }

            return $this->response->setJSON([
                'status' => 'VERIFIED',
                'redirect' => base_url('welcome')
            ]);
        }

        return $this->response->setJSON(['status' => $statusInfo['status']]);
    }

    /**
     * ALMAI REGISTRATION SYSTEM v2.0 - WELCOME PAGE
     */
    public function welcomePage()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $affiliatorName = 'Direct ALMAI';
        if (!empty($user['affiliator_code'])) {
            $refUser = $userModel->where('code_referral', $user['affiliator_code'])->first();
            if ($refUser) {
                $affiliatorName = $refUser['name'];
            }
        }

        return view('pages/auth/welcome', [
            'title' => 'Selamat Datang di ALMAI',
            'userName' => $user['name'] ?? $this->session->get('userName'),
            'userEmail' => $user['email'] ?? $this->session->get('userEmail'),
            'userPhone' => $user['phone'] ?? '',
            'affiliatorName' => $affiliatorName
        ]);
    }

    public function attemptRegister()
    {
        // Verify Cloudflare Turnstile
        $turnstileToken = $this->request->getPost('cf-turnstile-response');
        $turnstileService = new \App\Libraries\TurnstileService();

        if ($turnstileService->isEnabled()) {
            $verification = $turnstileService->verify(
                $turnstileToken ?? '',
                $this->request->getIPAddress()
            );

            if (!$verification['success']) {
                return redirect()->back()->withInput()->with('error', $verification['error'] ?? 'Verifikasi CAPTCHA gagal. Silakan coba lagi.');
            }
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required|min_length[10]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check weak password
        $weakPassword = $this->checkWeakPassword($this->request->getPost('password'), $this->request->getPost('name'), $this->request->getPost('email'));
        if ($weakPassword) {
            return redirect()->back()->withInput()->with('error', $weakPassword);
        }

        $userModel = new UserModel();

        // Check referral code
        $affiliateCode = $this->request->getPost('affiliate_code');
        if (empty($affiliateCode)) {
            return redirect()->back()->withInput()->with('error', 'Kode Referral wajib diisi. Silakan isi kode referral.');
        }

        $referrer = $userModel->findByReferralCode($affiliateCode);
        if (!$referrer) {
            return redirect()->back()->withInput()->with('error', 'Kode Referral tidak valid atau tidak ditemukan.');
        }
        $referrerId = $referrer['id'];

        $userId = $userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'password' => $this->request->getPost('password'),
            'level_id' => \App\Models\LevelModel::LEVEL_USER,
            'status' => 'active',
            'affiliator_code' => $affiliateCode,
        ]);

        if ($referrerId && $userId) {
            $poinService = new PoinService();
            $poinService->processReferralRegistration($userId, $referrerId);
        }

        $user = $userModel->find($userId);
        $poinModel = new \App\Models\PoinModel();
        $userPoin = $poinModel->getUserBalance($user['id']);
        $role = \App\Models\LevelModel::roleStringFromLevel((int) $user['level_id']);

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => $role,
            'level_id' => $user['level_id'],
            'userAvatar' => $user['avatar'] ?? null,
            'userPoin' => $userPoin,
            'role' => $role,
            'is_pro' => $user['is_pro'] ?? 0,
            'referralCode' => $user['code_referral'] ?? null,
        ]);

        $redirect = $this->session->get('redirectAfterLogin') ?? '/user/dashboard';
        $this->session->remove('redirectAfterLogin');

        return redirect()->to($redirect)->with('success', 'Registrasi berhasil! Selamat datang di Almai.');
    }

    public function sendOtp()
    {
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';
            $name = $json['name'] ?? '';
            $channel = $json['channel'] ?? 'whatsapp';
            $turnstileToken = $json['turnstile_token'] ?? '';

            $turnstileService = new \App\Libraries\TurnstileService();
            if ($turnstileService->isEnabled()) {
                $verification = $turnstileService->verify(
                    $turnstileToken,
                    $this->request->getIPAddress()
                );
                if (!$verification['success']) {
                    return $this->response->setJSON(['success' => false, 'message' => $verification['error'] ?? 'Verifikasi CAPTCHA gagal.']);
                }
            }

            if ($channel === 'whatsapp' || $channel === 'wpa_whatsapp') {
                if (empty($phone) || strlen($phone) < 10) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Nomor WhatsApp tidak valid']);
                }
                
                $otpModel = new OtpModel();
                $otp = $otpModel->generateOtp($phone, 'registration', $channel);
                log_message('info', 'OTP generated for ' . $phone . ': ' . $otp);
                
                $secretKey = env('BALESOTOMATIS_SECRET_KEY', '');
                $licensesKey = env('BALESOTOMATIS_LICENSES_KEY', '');
                
                if (empty($secretKey) || empty($licensesKey)) {
                    log_message('error', 'SendOTP Error: Balesotomatis secret_key or licenses_key not configured in .env');
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Layanan WhatsApp belum dikonfigurasi. Hubungi admin.'
                    ]);
                }

                \App\Libraries\Balesotomatis::configure($secretKey, $licensesKey);
                $waMessage = "Kode OTP Almai Anda adalah: *{$otp}*\n\nKode berlaku 10 menit.\n\nwww.almai.id";
                
                log_message('info', 'Sending OTP via Balesotomatis to ' . $phone);
                $res = \App\Libraries\Balesotomatis::sendPersonalMessage($phone, $waMessage, 'whatsapp');
                log_message('info', 'Balesotomatis Response: ' . json_encode($res));

                if (!$res['success']) {
                    $errorMsg = $res['error'] ?? 'Gagal mengirim OTP via WhatsApp.';
                    log_message('error', 'Balesotomatis SendOTP Failed for ' . $phone . ': ' . $errorMsg);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal mengirim OTP WhatsApp: ' . $errorMsg
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kode OTP berhasil dikirim ke WhatsApp Anda',
                    'channel' => 'whatsapp'
                ]);
            } else {
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Email tidak valid']);
                }
                $otpModel = new OtpModel();
                $otp = $otpModel->generateOtp($email, 'registration', 'email');

                $emailService = new EmailService();
                $resp = $emailService->sendOtp($email, $name ?: 'User', $otp);

                if ($resp['success']) {
                    $responseData = [
                        'success' => true,
                        'message' => 'OTP berhasil dikirim ke Email',
                        'channel' => 'email'
                    ];
                    if (ENVIRONMENT === 'development') {
                        $responseData['otp_dev'] = $otp;
                    }
                    return $this->response->setJSON($responseData);
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim Email OTP']);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'SendOTP Fatal Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . (ENVIRONMENT === 'development' ? $e->getMessage() : 'Silakan coba lagi.')
            ]);
        }
    }

    public function checkWaVerification()
    {
        $phone = $this->request->getGet('phone');
        if (empty($phone)) {
            return $this->response->setJSON(['verified' => false]);
        }
        $otpModel = new OtpModel();
        return $this->response->setJSON(['verified' => $otpModel->isWaVerified($phone)]);
    }

    public function verifyOtp()
    {
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';
            $otp = $json['otp'] ?? '';
            $name = $json['name'] ?? '';
            $password = $json['password'] ?? '';
            $affiliateCode = $json['affiliate_code'] ?? '';
            $channel = $json['channel'] ?? 'email';

            $valid = false;
            $otpModel = new OtpModel();

            if ($channel === 'whatsapp') {
                if (!empty($phone) && $otpModel->isWaVerified($phone)) {
                    $valid = true;
                }
            } else {
                if (!empty($otp) && !empty($email) && $otpModel->verifyOtp($email, $otp, 'registration')) {
                    $valid = true;
                }
            }

            if (!$valid) {
                return $this->response->setJSON(['success' => false, 'message' => 'Verifikasi gagal. Kode tidak valid atau belum dikonfirmasi oleh server.']);
            }

            if (empty($password)) {
                $randomHash = strtoupper(substr(hash('sha256', uniqid(rand(), true)), 0, 4));
                $password = 'ALMA-' . $randomHash;
            }

            $userModel = new UserModel();
            if (!empty($phone) && $userModel->where('phone', $phone)->first()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Nomor HP sudah terdaftar']);
            }

            if (empty($affiliateCode)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Kode Referral wajib diisi.']);
            }

            $referrerId = null;
            $referrer = $userModel->findByReferralCode($affiliateCode);
            if ($referrer) {
                $referrerId = $referrer['id'];
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'level_id' => \App\Models\LevelModel::LEVEL_USER,
                'status' => 'active',
                'affiliator_code' => $affiliateCode,
                'phone_verified_at' => ($channel === 'whatsapp' ? date('Y-m-d H:i:s') : null),
                'email_verified_at' => ($channel !== 'whatsapp' ? date('Y-m-d H:i:s') : null),
            ];

            $userId = $userModel->insert($userData);
            if (!$userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat akun.']);
            }

            if ($referrerId) {
                $poinService = new PoinService();
                $poinService->processReferralRegistration($userId, $referrerId);
            }

            $user = $userModel->find($userId);
            $poinModel = new \App\Models\PoinModel();
            $userPoin = $poinModel->getUserBalance($user['id']);

            $this->session->set([
                'isLoggedIn' => true,
                'userId' => $user['id'],
                'userName' => $user['name'],
                'userEmail' => $user['email'],
                'userRole' => 'user',
                'level_id' => $user['level_id'],
                'userPoin' => $userPoin,
                'role' => 'user',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'redirect' => base_url('/user/dashboard')
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/user/dashboard');
        }
        return view('pages/auth/forgot-password', ['title' => 'Lupa Password - Almai E-Learning']);
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->with('success', 'Jika email terdaftar, link reset password akan dikirim.');
        }
        $resetModel = new PasswordResetModel();
        $token = $resetModel->createToken($email);
        $resetLink = base_url('reset-password/' . $token);
        $emailService = new EmailService();
        $result = $emailService->sendPasswordReset($email, $user['name'], $resetLink);
        return redirect()->back()->with('success', 'Link reset password telah dikirim ke email.');
    }

    public function resetPassword($token)
    {
        $resetModel = new PasswordResetModel();
        $reset = $resetModel->validateToken($token);
        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Link reset password tidak valid.');
        }
        return view('pages/auth/reset-password', ['title' => 'Reset Password - Almai E-Learning', 'token' => $token, 'email' => $reset['email']]);
    }

    public function processResetPassword($token)
    {
        $resetModel = new PasswordResetModel();
        $reset = $resetModel->validateToken($token);
        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Link reset password tidak valid.');
        }
        $userModel = new UserModel();
        $user = $userModel->where('email', $reset['email'])->first();
        if ($user) {
            $userModel->update($user['id'], ['password' => $this->request->getPost('password')]);
            $resetModel->markAsUsed($token);
        }
        return redirect()->to('/login')->with('success', 'Password berhasil direset!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'Logout berhasil!');
    }

    private function checkWeakPassword($password, $name, $email)
    {
        $blacklist = ['12345678', '123456789', 'password', 'admin123'];
        if (in_array(strtolower($password), $blacklist)) {
            return 'Password terlalu umum.';
        }
        return null;
    }

    public function referral($code)
    {
        if (empty($code)) return redirect()->to('/');
        session()->set('affiliate_code', $code);
        set_cookie(['name' => 'affiliate_code', 'value' => $code, 'expire' => 3600 * 24 * 30, 'path' => '/', 'secure' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'), 'httponly' => true, 'samesite' => 'Lax']);
        return redirect()->to('/register?ref=' . $code)->with('success', 'Kode referral diterapkan!');
    }
}
