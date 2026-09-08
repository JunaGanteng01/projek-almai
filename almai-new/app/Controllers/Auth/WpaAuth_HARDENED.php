<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WpaModel;
use App\Libraries\RateLimiter;

class WpaAuth extends BaseController
{
    public function login()
    {
        // Check if logged in and has WPA level
        $levelWpa = \App\Models\LevelModel::LEVEL_WPA;
        if ($this->session->get('isLoggedIn') && $this->session->get('level_id') == $levelWpa) {
            return redirect()->to('/wpa/dashboard');
        }

        $wpaModel = new WpaModel();
        $wpaList = $wpaModel->select('wpa.*, users.email as user_email')
            ->join('users', 'users.id = wpa.user_id', 'left')
            ->where('wpa.status', 'active')
            ->where('wpa.user_id IS NOT NULL')
            ->limit(4)
            ->findAll();

        return view('wpa/login', [
            'title' => 'WPA Login - Almai',
            'wpaList' => $wpaList
        ]);
    }

    /**
     * HARDENED: Login with Rate Limiting & Session Security
     */
    public function attemptLogin()
    {
        // ✅ Initialize rate limiter
        $limiter = new RateLimiter();
        $clientIP = $this->request->getIPAddress();
        $email = $this->request->getPost('email');
        $identifier = "login:{$clientIP}:" . ($email ?? 'unknown');

        // ✅ Check IP-level rate limiting (prevent distributed attacks)
        if ($limiter->isLimited("login:ip:{$clientIP}", limit: 20, window: 300)) {
            return redirect()->back()
                ->with('error', 'Too many login attempts from your IP address. Try again in 5 minutes.');
        }

        // ✅ Check email-specific rate limiting
        if ($limiter->isLimited($identifier, limit: 5, window: 900)) {
            $limiter->lockIdentifier($identifier, 1800); // Lock for 30 minutes
            return redirect()->back()
                ->with('error', 'Account temporarily locked due to too many failed attempts. Try again in 30 minutes.');
        }

        // ✅ Validate input
        $rules = [
            'email' => 'required|valid_email|max_length[255]',
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            $limiter->recordAttempt($identifier);
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // ✅ Check database for user (WPA level only)
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)
            ->where('level_id', \App\Models\LevelModel::LEVEL_WPA)
            ->first();

        // ✅ Validate credentials
        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            // Record failed attempt
            $limiter->recordAttempt($identifier);
            $remaining = $limiter->getRemainingAttempts($identifier, limit: 5);
            
            return redirect()->back()
                ->withInput()
                ->with('error', "Email atau password salah (Attempt {$limiter->getAttempts($identifier)}/5). " .
                    ($remaining > 0 ? "Sisa percobaan: $remaining" : ''));
        }

        // ✅ Check if account is locked
        if ($limiter->isLocked($identifier)) {
            return redirect()->back()
                ->with('error', 'Account temporarily locked. Please try again later.');
        }

        // ✅ Clear rate limit on successful login
        $limiter->clearAttempts($identifier);
        $limiter->clearAttempts("login:ip:{$clientIP}");

        // ✅ Get WPA data
        $wpaModel = new WpaModel();
        $wpa = $wpaModel->where('user_id', $user['id'])->first();

        // ✅ CRITICAL: Regenerate session ID before setting data (prevent session fixation)
        session()->regenerate(true); // true = destroy old session

        // ✅ Set new session data
        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => 'wpa',
            'level_id' => $user['level_id'],
            'wpaId' => $wpa['id'] ?? null,
            'wpaName' => $wpa['name'] ?? $user['name'],
            'wpaPhoto' => $wpa['photo'] ?? null,
            'wpaSpecialty' => $wpa['specialty'] ?? null,
            'referralCode' => $user['code_referral'] ?? null,
            'loginTime' => time(),
            'loginIP' => $clientIP,  // Store for additional security checks
        ]);

        // ✅ Log successful login
        log_message('info', "User logged in: {$user['email']} from {$clientIP}");

        return redirect()->to('/wpa/dashboard')->with('success', 'Login berhasil!');
    }

    /**
     * HARDENED: Logout with Session Destruction
     */
    public function logout()
    {
        $userId = session()->get('userId');
        $clientIP = $this->request->getIPAddress();
        
        // Log logout
        if ($userId) {
            log_message('info', "User logged out: ID {$userId} from {$clientIP}");
        }

        // ✅ Proper session destruction
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Logout berhasil!');
    }
}
