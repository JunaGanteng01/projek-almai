<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminAuth extends BaseController
{
    public function login()
    {
        // Check if logged in and has admin level
        $isLoggedIn = $this->session->get('isLoggedIn');

        if ($isLoggedIn && \App\Models\LevelModel::isAdminLevel(\App\Models\LevelModel::resolveLevelId($this->session))) {
            $levelId = \App\Models\LevelModel::resolveLevelId($this->session);
            $target = ($levelId === \App\Models\LevelModel::LEVEL_SUPER_ADMIN) ? '/superadmin/dashboard' : '/admin/dashboard';
            return redirect()->to($target);
        }

        return view('admin/login', [
            'title' => 'Admin Login - Almai'
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

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // SECURITY: Rate Limiting anti-brute-force (3 percobaan per menit untuk admin)
        $throttler = \Config\Services::throttler();
        $ipAddress = $this->request->getIPAddress();
        if ($throttler->check(md5('admin-login-' . $ipAddress), 3, 60) === false) {
            log_message('warning', 'Admin login brute-force attempt from IP: ' . $ipAddress);
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan login. Demi keamanan, tunggu 1 menit sebelum mencoba lagi.');
        }

        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // 1. Fetch user by email first to check their level
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // 2. Verify Admin Permissions
        $isAdminAllowed = \App\Models\LevelModel::isAdminLevel((int) ($user['level_id'] ?? 0));

        if (!$isAdminAllowed) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki akses ke area Admin');
        }

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => 'admin', // Keep string for legacy check compatibility
            'level_id' => $user['level_id'] ?? \App\Models\LevelModel::LEVEL_ADMIN, // Add level_id to session
        ]);

        $levelId = (int) ($user['level_id'] ?? \App\Models\LevelModel::LEVEL_ADMIN);
        $target = ($levelId === \App\Models\LevelModel::LEVEL_SUPER_ADMIN) ? '/superadmin/dashboard' : '/admin/dashboard';
        return redirect()->to($target)->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin/login')->with('success', 'Logout berhasil!');
    }
}
