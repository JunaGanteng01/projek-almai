<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LevelModel;

class LaporanKegiatanAuth extends BaseController
{
    public function login()
    {
        $isLoggedIn = $this->session->get('isLoggedIn');
        $levelId = $this->session->get('level_id');

        if ($isLoggedIn && ($levelId == LevelModel::LEVEL_PARTNERSHIP || $levelId == LevelModel::LEVEL_SUPER_ADMIN)) {
            return redirect()->to('/laporan-kegiatan/dashboard');
        }

        return view('laporan-kegiatan/login', [
            'title' => 'Partnership Admin Login - Almai'
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

        $isAllowed = in_array($user['level_id'] ?? 0, [
            LevelModel::LEVEL_PARTNERSHIP,
            LevelModel::LEVEL_SUPER_ADMIN
        ]);

        if (!$isAllowed) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki akses ke area Partnership Admin');
        }

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => 'partnership_admin',
            'level_id' => $user['level_id'],
        ]);

        return redirect()->to('/laporan-kegiatan/dashboard')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/laporan-kegiatan/login')->with('success', 'Logout berhasil!');
    }
}
