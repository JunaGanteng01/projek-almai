<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\CwpaModel;
use App\Models\LayananModel;

class Profile extends BaseController
{
    protected $cwpaModel;
    protected $layananModel;

    public function __construct()
    {
        $this->cwpaModel = new CwpaModel();
        $this->layananModel = new LayananModel();
    }

    public function index()
    {
        $cwpaId = session()->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

        $cwpa = $this->cwpaModel->select('cwpa.*, users.email, users.phone, users.code_referral')
            ->join('users', 'users.id = cwpa.user_id')
            ->find($cwpaId);

        if (!$cwpa) return redirect()->to('/cwpa/login')->with('error', 'Profil tidak ditemukan');

        // Get stats
        $totalLayanan = $this->layananModel->countByCwpaId($cwpaId);

        // Get specialties (Updated list from user)
        $specialties = ['Gold Specialist', 'Forex Specialist', 'Crypto Specialist', 'Stock Specialist', 'Index Specialist', 'EA Specialist', 'AI Specialist', 'Propfirm Specialist', 'Risk Management', 'AMANDANA', 'METAVULUS', 'REPUBLIC'];
        $certs = !empty($cwpa['certifications']) ? explode("\n", $cwpa['certifications']) : [];

        return view('cwpa/profile/index', [
            'title' => 'Profile - CWPA Dashboard',
            'activeMenu' => 'profile',
            'cwpa' => $cwpa,
            'totalLayanan' => $totalLayanan,
            'specialties' => $specialties,
            'certifications' => $certs
        ]);
    }

    public function edit()
    {
        $cwpaId = session()->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

        $cwpa = $this->cwpaModel->select('cwpa.*, users.email, users.phone, users.code_referral')
            ->join('users', 'users.id = cwpa.user_id')
            ->find($cwpaId);

        if (!$cwpa) return redirect()->to('/cwpa/login')->with('error', 'Profil tidak ditemukan');

        $totalLayanan = $this->layananModel->countByCwpaId($cwpaId);
        $specialties = ['Gold Specialist', 'Forex Specialist', 'Crypto Specialist', 'Stock Specialist', 'Index Specialist', 'EA Specialist', 'AI Specialist', 'Propfirm Specialist', 'Risk Management', 'AMANDANA', 'METAVULUS', 'REPUBLIC'];
        $certs = !empty($cwpa['certifications']) ? explode("\n", $cwpa['certifications']) : [];

        return view('cwpa/profile/edit', [
            'title' => 'Edit Profile - CWPA Dashboard',
            'activeMenu' => 'profile',
            'cwpa' => $cwpa,
            'totalLayanan' => $totalLayanan,
            'specialties' => $specialties,
            'certifications' => $certs
        ]);
    }

    public function update()
    {
        $cwpaId = session()->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|min_length[10]',
            'batch' => 'permit_empty|max_length[50]',
            'specialty' => 'permit_empty|max_length[255]',
            'experience' => 'permit_empty|max_length[100]',
            'bio' => 'permit_empty|max_length[2000]',
            'certifications' => 'permit_empty',
            'instagram' => 'permit_empty|max_length[100]',
            'youtube' => 'permit_empty|max_length[200]',
            'mql5_widget_url' => 'permit_empty|max_length[500]',
            'password' => 'permit_empty|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $cwpa = $this->cwpaModel->find($cwpaId);
        $userId = $cwpa['user_id'];

        $cwpaData = [
            'name' => $this->request->getPost('name'),
            'batch' => $this->request->getPost('batch'),
            'specialty' => $this->request->getPost('specialty'),
            'experience' => $this->request->getPost('experience'),
            'bio' => $this->request->getPost('bio'),
            'certifications' => $this->request->getPost('certifications'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'mql5_widget_url' => $this->request->getPost('mql5_widget_url'),
        ];

        $this->cwpaModel->update($cwpaId, $cwpaData);

        // Update User Account Info
        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password'] = $password; // Model will hash it
        }

        (new \App\Models\UserModel())->update($userId, $userData);

        // Update session
        session()->set('cwpaName', $cwpaData['name']);

        return redirect()->to('/cwpa/dashboard/profile')->with('success', 'Profile & Account berhasil diupdate!');
    }

    public function updatePhoto()
    {
        $cwpaId = session()->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

        $file = $this->request->getFile('photo');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
            return redirect()->back()->with('error', 'Format file harus JPG, PNG, atau WebP');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB');
        }

        $newName = 'cwpa_' . $cwpaId . '_' . time() . '.' . $file->getExtension();
        
        // Ensure directory exists
        if (!is_dir(WRITEPATH . 'uploads/cwpa')) {
            mkdir(WRITEPATH . 'uploads/cwpa', 0777, true);
        }

        $file->move(WRITEPATH . 'uploads/cwpa', $newName);

        $photoPath = 'uploads/cwpa/' . $newName;
        $this->cwpaModel->update($cwpaId, ['photo' => $photoPath]);

        // Update session
        session()->set('cwpaPhoto', base_url('file/' . $photoPath));

        return redirect()->to('/cwpa/dashboard/profile')->with('success', 'Foto profile berhasil diupdate!');
    }
}
