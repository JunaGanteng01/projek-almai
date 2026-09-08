<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WpaModel;

class WpaAuth extends BaseController
{
    public function login()
    {
        // Check if logged in and has WPA level
        $levelWpa = \App\Models\LevelModel::LEVEL_WPA;
        if ($this->session->get('isLoggedIn') && $this->session->get('level_id') == $levelWpa) {
            return redirect()->to('/wpa/dashboard');
        }

        // Get WPA list with user accounts for demo quick login
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

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        // Check by level_id for WPA
        $user = $userModel->where('email', $this->request->getPost('email'))
            ->where('level_id', \App\Models\LevelModel::LEVEL_WPA)
            ->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // Get WPA data
        $wpaModel = new WpaModel();
        $wpa = $wpaModel->where('user_id', $user['id'])->first();

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => 'wpa', // Keep string for legacy check compatibility
            'level_id' => $user['level_id'], // Add level_id to session
            'wpaId' => $wpa['id'] ?? null,
            'wpaName' => $wpa['name'] ?? $user['name'],
            'wpaPhoto' => $wpa['photo'] ?? null,
            'wpaSpecialty' => $wpa['specialty'] ?? null,
            'referralCode' => $user['code_referral'] ?? null,
        ]);

        return redirect()->to('/wpa/dashboard')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Logout berhasil!');
    }
}
