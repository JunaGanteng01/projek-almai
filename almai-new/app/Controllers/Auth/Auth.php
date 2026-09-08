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
                'secure'   => true,     // SECURITY: Wajib HTTPS
                'httponly' => true,     // SECURITY: Tidak bisa diakses JavaScript
                'samesite' => 'Strict'
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
            'level_id' => \App\Models\LevelModel::LEVEL_USER, // Set default level to 1
            'status' => 'active',
            'affiliator_code' => $affiliateCode, // ADDED: store ref code string
        ]);

        // Process referral bonus if has referrer
        if ($referrerId && $userId) {
            $poinService = new PoinService();
            $poinService->processReferralRegistration($userId, $referrerId);
        }

        // Auto login after registration
        $user = $userModel->find($userId);

        // Get user poin balance
        $poinModel = new \App\Models\PoinModel();
        $userPoin = $poinModel->getUserBalance($user['id']);

        // Determine role based on level_id
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

        // Redirect to intended page or dashboard
        $redirect = $this->session->get('redirectAfterLogin') ?? '/user/dashboard';
        $this->session->remove('redirectAfterLogin');

        return redirect()->to($redirect)->with('success', 'Registrasi berhasil! Selamat datang di Almai.');
    }

    /**
     * Send OTP / Generate WA Verification Code for registration
     */
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

            // Verify Cloudflare Turnstile
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
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'OTP berhasil dibuat',
                    'channel' => 'whatsapp',
                    'otp_code' => $otp,
                    'admin_wa' => env('NO_OTP')
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

            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database Error: Tabel OTP belum siap. Hubungi admin.'
                ]);
            }

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
        $isVerified = $otpModel->isWaVerified($phone);
        
        return $this->response->setJSON(['verified' => $isVerified]);
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
            $code = $json['code'] ?? '';
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
            } else {
                $weakPassword = $this->checkWeakPassword($password, $name, $email);
                if ($weakPassword) {
                    return $this->response->setJSON(['success' => false, 'message' => $weakPassword]);
                }
            }

            $userModel = new UserModel();
            if (!empty($phone) && $userModel->where('phone', $phone)->first()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Nomor HP sudah terdaftar']);
            }

            if (empty($affiliateCode)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Kode Referral wajib diisi. Silakan pilih salah satu WPA jika belum memiliki kode.']);
            }

            $referrerId = null;
            $referrer = $userModel->findByReferralCode($affiliateCode);
            if ($referrer) {
                $referrerId = $referrer['id'];
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Kode Referral tidak valid atau tidak ditemukan.']);
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'level_id' => \App\Models\LevelModel::LEVEL_USER,
                'status' => 'active',
                'affiliator_code' => $affiliateCode,
            ];

            if ($channel === 'whatsapp' || $channel === 'wpa_whatsapp') {
                $userData['phone_verified_at'] = date('Y-m-d H:i:s');
                $userData['email_verified_at'] = null;
            } else {
                $userData['email_verified_at'] = date('Y-m-d H:i:s');
                $userData['phone_verified_at'] = null;
            }

            $userId = $userModel->insert($userData);

            if (!$userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat akun. Silakan coba lagi.']);
            }

            if ($channel === 'whatsapp' || $channel === 'wpa_whatsapp') {
                $otpModel->where('phone', $phone)->where('used', 2)->set(['used' => 1])->update();
            }

            if ($referrerId) {
                $poinService = new PoinService();
                $poinService->processReferralRegistration($userId, $referrerId);
            }

            $user = $userModel->find($userId);
            $poinModel = new \App\Models\PoinModel();
            $userPoin = $poinModel->getUserBalance($user['id']);

            $role = 'user';
            if (in_array($user['level_id'], [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_SUPER_ADMIN, \App\Models\LevelModel::LEVEL_ACCOUNTING])) {
                $role = 'admin';
            } elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_WPA) {
                $role = 'wpa';
            } elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_CWPA) {
                $role = 'cwpa';
            }

            if (!empty($email)) {
                try {
                    $emailService = new EmailService();
                    $emailService->sendAccountCredentials($email, $name ?: 'User', $password, 'ALMAI');
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to send Reg Success Email: ' . $e->getMessage());
                }
            }
            
            if ($channel === 'whatsapp') {
                try {
                    $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                    $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
                    if ($secretKey && $licensesKey) {
                        \App\Libraries\Balesotomatis::configure($secretKey, $licensesKey);
                        
                        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        $tanggal = date('d') . ' ' . $months[(int)date('m')] . ' ' . date('Y, H.i');

                        $message = "✅ Registrasi Berhasil!\n";
                        $message .= "Terima kasih telah bergabung di Almai.id.\n\n";
                        $message .= "Data Registrasi\n";
                        $message .= "👤 Nama: $name\n";
                        $message .= "📅 Tanggal: $tanggal WIB\n";
                        $message .= "📱 No. HP: $phone\n";
                        if (!empty($email)) {
                            $message .= "📧 Email: $email\n";
                        }
                        $message .= "🔑 Password: *$password*\n";
                        if ($referrer) {
                            $roleName = strtoupper(\App\Models\LevelModel::roleStringFromLevel((int)$referrer['level_id']));
                            $message .= "🤝 $roleName: {$referrer['name']} \n";
                        }
                        $message .= "🎁 Bonus Registrasi: 100 Almai Poin\n\n";
                        $message .= "Silakan login ke Dashboard Anda untuk mulai mengakses seluruh layanan Almai.\n";
                        $message .= "🔗 Login Dashboard\n" . base_url('login') . "\n\n";
                        $message .= "💎 Pelajari manfaat Almai Poin\n" . base_url('almai-poin') . "\n\n";
                        $message .= "Selamat bergabung dan semoga sukses bersama Almai! 🚀";
                        
                        \App\Libraries\Balesotomatis::sendPersonalMessage($phone, $message);
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to send Reg Success WhatsApp: ' . $e->getMessage());
                }
            }

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
            
            $this->session->setFlashdata('error', 'Silakan ubah kata sandi (password) default Anda melalui menu Profil untuk keamanan akun.');

            $redirectPath = $this->session->get('redirectAfterLogin') ?? '/user/dashboard';
            $this->session->remove('redirectAfterLogin');
            $finalRedirect = strpos($redirectPath, 'http') === 0 ? $redirectPath : base_url($redirectPath);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'redirect' => $finalRedirect
            ]);
        } catch (\Exception $e) {
            log_message('error', 'VerifyOTP Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . (ENVIRONMENT === 'development' ? $e->getMessage() : 'Silakan coba lagi.')
            ]);
        }
    }

    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/user/dashboard');
        }

        return view('pages/auth/forgot-password', [
            'title' => 'Lupa Password - Almai E-Learning'
        ]);
    }

    public function sendResetLink()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email tidak valid');
        }

        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('success', 'Jika email terdaftar, link reset password akan dikirim ke email Anda.');
        }

        $resetModel = new PasswordResetModel();
        $token = $resetModel->createToken($email);

        $resetLink = base_url('reset-password/' . $token);

        $emailService = new EmailService();
        $result = $emailService->sendPasswordReset($email, $user['name'], $resetLink);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } else {
            log_message('error', 'Failed to send reset email: ' . ($result['error'] ?? $result['message']));

            if (ENVIRONMENT === 'development') {
                session()->setFlashdata('reset_link', $resetLink);
                return redirect()->back()->with('success', 'Email gagal dikirim (development mode). Gunakan link di bawah.');
            }

            return redirect()->back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti.');
        }
    }

    public function resetPassword($token)
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/user/dashboard');
        }

        $resetModel = new PasswordResetModel();
        $reset = $resetModel->validateToken($token);

        if (!$reset) {
            return redirect()->to('/login')->with('error', 'Link reset password tidak valid atau sudah kadaluarsa.');
        }

        return view('pages/auth/reset-password', [
            'title' => 'Reset Password - Almai E-Learning',
            'token' => $token,
            'email' => $reset['email']
        ]);
    }

    public function processResetPassword($token)
    {
        $resetModel = new PasswordResetModel();
        $reset = $resetModel->validateToken($token);

        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Link reset password tidak valid atau sudah kadaluarsa.');
        }

        $rules = [
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $reset['email'])->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'User tidak ditemukan.');
        }

        $userModel->update($user['id'], [
            'password' => $this->request->getPost('password')
        ]);

        $resetModel->markAsUsed($token);

        return redirect()->to('/login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'Logout berhasil!');
    }

    private function checkWeakPassword($password, $name, $email)
    {
        $passwordLower = strtolower($password);
        $nameParts = explode(' ', strtolower($name));
        $emailPart = explode('@', strtolower($email))[0];

        $blacklist = [
            '12345678',
            '123456789',
            '1234567890',
            '0123456789',
            'qwerty',
            'qwertyuiop',
            'password',
            'password123',
            'admin123',
            '11111111',
            '123123123',
            'aA123456',
            'indonesia',
            'bismillah',
            'rahasia',
            'sayang',
            'doraemon',
            'naruto',
            'google'
        ];

        if (in_array($password, $blacklist) || in_array($passwordLower, $blacklist)) {
            return 'Password terlalu umum dan mudah ditebak (contoh: 12345678, password, qwerty).';
        }

        foreach ($nameParts as $part) {
            if (strlen($part) >= 3 && strpos($passwordLower, $part) !== false) {
                return 'Password tidak boleh mengandung potongan nama Anda (' . $part . ').';
            }
        }

        if (strlen($emailPart) >= 3 && strpos($passwordLower, $emailPart) !== false) {
            return 'Password tidak boleh mengandung username email Anda.';
        }

        if (preg_match('/(01234|12345|23456|34567|45678|56789|98765|87654|76543|65432|54321|43210)/', $password)) {
            return 'Password mengandung urutan angka yang terlalu mudah.';
        }

        if (preg_match('/(.)\1{4,}/', $password)) {
            return 'Password tidak boleh menggunakan karakter berulang lebih dari 4 kali.';
        }

        return null;
    }

    public function referral($code)
    {
        if (empty($code)) {
            return redirect()->to('/');
        }

        $userModel = new UserModel();
        $referrer = $userModel->findByReferralCode($code);

        session()->set('affiliate_code', $code);

        set_cookie([
            'name'     => 'affiliate_code',
            'value'    => $code,
            'expire'   => 3600 * 24 * 30,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        return redirect()->to('/register?ref=' . $code)->with('success', 'Kode referral "' . strtoupper($code) . '" berhasil diterapkan! Silakan lengkapi pendaftaran Anda.');
    }
}
