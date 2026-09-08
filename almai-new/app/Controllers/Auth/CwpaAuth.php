<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CwpaModel;

class CwpaAuth extends BaseController
{
    public function login()
    {
        // Check if logged in and has CWPA record
        if ($this->session->get('isLoggedIn') && $this->session->get('cwpaId')) {
            return redirect()->to('/cwpa/dashboard');
        }

        return view('cwpa/login', [
            'title' => 'CWPA Login - Almai'
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
        // Allow any user to try, but we will check if they are CWPA
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        // Check if user is a CWPA
        $cwpaModel = new CwpaModel();
        $cwpa = $cwpaModel->where('user_id', $user['id'])->where('status', 'active')->first();

        if (!$cwpa) {
            // If they are admin, maybe allow? proper not for now.
             return redirect()->back()->withInput()->with('error', 'Akun ini tidak terdaftar sebagai CWPA aktif.');
        }

        $this->session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'userRole' => 'cwpa', 
            'level_id' => $user['level_id'], 
            'cwpaId' => $cwpa['id'],
            'cwpaName' => $cwpa['name'],
            'cwpaPhoto' => $cwpa['photo'],
            'cwpaPhase' => $cwpa['current_phase'],
        ]);

        return redirect()->to('/cwpa/dashboard')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/cwpa/login')->with('success', 'Logout berhasil!');
    }
}
