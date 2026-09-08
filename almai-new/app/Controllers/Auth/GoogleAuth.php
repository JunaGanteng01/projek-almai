<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Libraries\GoogleAuthService;
use App\Models\UserModel;

class GoogleAuth extends BaseController
{
    protected $googleAuth;
    protected $userModel;

    public function __construct()
    {
        $this->googleAuth = new GoogleAuthService();
        $this->userModel = new UserModel();
    }

    /**
     * Redirect to Google OAuth
     */
    public function login()
    {
        if (!$this->googleAuth->isConfigured()) {
            return redirect()->to('/login')->with('error', 'Login dengan Google belum dikonfigurasi.');
        }

        // Store redirect URL if any
        $redirect = $this->request->getGet('redirect');
        if ($redirect) {
            session()->set('google_redirect', $redirect);
        }

        return redirect()->to($this->googleAuth->getAuthUrl());
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        $code = $this->request->getGet('code');
        $error = $this->request->getGet('error');

        if ($error) {
            return redirect()->to('/login')->with('error', 'Login dengan Google dibatalkan.');
        }

        if (!$code) {
            return redirect()->to('/login')->with('error', 'Kode otorisasi tidak ditemukan.');
        }

        // Get user info from Google
        $googleUser = $this->googleAuth->getUserFromCode($code);

        if (!$googleUser) {
            return redirect()->to('/login')->with('error', 'Gagal mendapatkan informasi dari Google.');
        }

        // Check if email is verified
        if (!$googleUser['verified_email']) {
            return redirect()->to('/login')->with('error', 'Email Google Anda belum diverifikasi.');
        }

        // Find or create user
        $user = $this->userModel->where('email', $googleUser['email'])->first();

        if ($user) {
            // Existing user - update google_id if not set
            if (empty($user['google_id'])) {
                $this->userModel->update($user['id'], [
                    'google_id' => $googleUser['google_id'],
                    'email_verified_at' => $user['email_verified_at'] ?? date('Y-m-d H:i:s')
                ]);
            }
        } else {
            // New user - create account
            $userData = [
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['google_id'],
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Random password
                'role' => 'user',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => $googleUser['picture'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Check for affiliate code in session
            $affiliateCode = session()->get('affiliate_code');
            if ($affiliateCode) {
                $affiliate = $this->userModel->where('affiliate_code', $affiliateCode)->first();
                if ($affiliate) {
                    $userData['referred_by'] = $affiliate['id'];
                }
            }

            $userId = $this->userModel->insert($userData);
            $user = $this->userModel->find($userId);

            // Clear affiliate code from session
            session()->remove('affiliate_code');
        }

        // Check if user is banned
        if (isset($user['status']) && $user['status'] === 'banned') {
            return redirect()->to('/login')->with('error', 'Akun Anda telah diblokir. Hubungi admin untuk informasi lebih lanjut.');
        }

        // Set session (match with AuthFilter check)
        $sessionData = [
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => $user['role'] ?? 'user',
            'isLoggedIn' => true
        ];
        session()->set($sessionData);

        // Get redirect URL
        $redirect = session()->get('google_redirect') ?? '/user/dashboard';
        session()->remove('google_redirect');

        return redirect()->to($redirect)->with('success', 'Berhasil login dengan Google!');
    }
}
