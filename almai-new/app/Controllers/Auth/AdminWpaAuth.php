<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LevelModel;

class AdminWpaAuth extends BaseController
{
    public function login()
    {
        $isLoggedIn = $this->session->get('isLoggedIn');

        if ($isLoggedIn && \App\Models\LevelModel::resolveLevelId($this->session) === \App\Models\LevelModel::LEVEL_ADMIN_WPA) {
            return redirect()->to('/admin-wpa/dashboard');
        }

        return view('admin_wpa/login', [
            'title' => 'Admin WPA Login - Almai'
        ]);
    }

    public function attemptLogin()
    {
        // Verify Cloudflare Turnstile if available
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
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // Specifically check for Level 8 (Admin WPA) or Level 7 (Super Admin)
        $levelId = (int)$user['level_id'];
        if (!in_array($levelId, [LevelModel::LEVEL_ADMIN_WPA, LevelModel::LEVEL_SUPER_ADMIN], true)) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki akses ke area Admin WPA');
        }

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => LevelModel::roleStringFromLevel($levelId),
            'level_id' => $levelId,
        ]);

        return redirect()->to('/admin-wpa/dashboard')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin-wpa/login')->with('success', 'Logout berhasil!');
    }
}
