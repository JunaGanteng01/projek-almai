<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LevelModel;

class Auth extends BaseController
{
    public function index()
    {
        // Check if logged in and has CEO level
        $isLoggedIn = $this->session->get('isLoggedIn');
        $levelId = (int) $this->session->get('level_id');

        if ($isLoggedIn && $levelId === LevelModel::LEVEL_CEO) {
            return redirect()->to('/ceo/dashboard');
        }

        return view('ea/auth/login', [
            'title' => 'CEO Login - ALMAI'
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // SECURITY: Rate Limiting anti-brute-force (3 percobaan per menit)
        $throttler = \Config\Services::throttler();
        $ipAddress = $this->request->getIPAddress();
        if ($throttler->check(md5('ea-login-' . $ipAddress), 3, 60) === false) {
            log_message('warning', 'EA login brute-force attempt from IP: ' . $ipAddress);
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan login. Demi keamanan, tunggu 1 menit sebelum mencoba lagi.');
        }

        $userModel = new UserModel();

        // 1. Fetch user by email
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify((string)$this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // 2. Verify CEO Permissions
        $userLevelId = (int) ($user['level_id'] ?? 0);
        
        if ($userLevelId !== LevelModel::LEVEL_CEO) {
            return redirect()->back()->withInput()->with('error', 'Akses ditolak. Anda tidak memiliki akses sebagai CEO.');
        }

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => 'ceo', // legacy string role
            'level_id' => $userLevelId, // 10
        ]);

        $this->session->regenerate(true);

        return redirect()->to('/ceo/dashboard')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        // Remove only relevant session data if needed or destroy all
        $this->session->destroy();
        return redirect()->to('/ceo')->with('success', 'Logout berhasil!');
    }
}
