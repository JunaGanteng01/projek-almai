<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CwpaModel;

class Referral extends BaseController
{
    public function index()
    {
        $userId = $this->session->get('userId');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Sesi kadaluarsa.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan.');
        }

        // Get CWPA info
        $cwpaModel = new CwpaModel();
        $cwpa = $cwpaModel->where('user_id', $userId)->first();

        return view('cwpa/referral', [
            'title' => 'Sistem Referral - CWPA Dashboard',
            'activeMenu' => 'referral',
            'user' => $user,
            'cwpa' => $cwpa,
        ]);
    }

    public function update()
    {
        $userId = $this->session->get('userId');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Sesi kadaluarsa.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan.');
        }

        // Check if already customized
        if (!empty($user['is_referral_customized'])) {
            return redirect()->back()->with('error', 'Kode referral sudah pernah diubah dan tidak bisa diubah lagi.');
        }

        // Check if user is PRO, CWPA, or WPA
        $userLevel = (int)($user['level_id'] ?? 1);
        $isPro = $userLevel >= \App\Models\LevelModel::LEVEL_PRO;

        if (!$isPro) {
            return redirect()->back()->with('error', 'Fitur ini hanya tersedia untuk User PRO, CWPA, dan WPA.');
        }

        // Validate input
        $rules = [
            'code_referral' => 'required|min_length[4]|max_length[20]|alpha_numeric|is_unique[users.code_referral]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Kode referral tidak valid atau sudah digunakan. Gunakan 4-20 karakter (huruf & angka).');
        }

        $newCode = strtoupper($this->request->getPost('code_referral'));

        // Update user
        $userModel->update($userId, [
            'code_referral' => $newCode,
            'is_referral_customized' => 1,
        ]);

        // Update session
        $this->session->set('referralCode', $newCode);

        return redirect()->to('/cwpa/dashboard/referral')->with('success', 'Kode referral berhasil diubah! Kode ini bersifat permanen dan tidak bisa diubah lagi.');
    }
}
