<?php

namespace App\Controllers\AdminWpa;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        $user = $this->userModel->find($userId);

        if (!$user) return redirect()->to('/admin-wpa')->with('error', 'Profil tidak ditemukan.');

        return view('admin_wpa/profile/index', [
            'title' => 'Profile - Admin WPA Dashboard',
            'activeMenu' => 'profile',
            'user' => $user,
        ]);
    }

    public function update()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/admin-wpa')->with('error', 'Profil tidak ditemukan');

        $rules = [
            'name' => 'required|min_length[3]',
            'phone' => 'permit_empty|min_length[9]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
        ];
        
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($userId, $data);

        session()->set('userName', $data['name']);

        return redirect()->to('/admin-wpa/profile')->with('success', 'Profile berhasil diupdate!');
    }

    public function updatePhoto()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/admin-wpa')->with('error', 'Profil tidak ditemukan');

        $file = $this->request->getFile('photo');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/profiles', $newName);
            $photoPath = 'uploads/profiles/' . $newName;

            $this->userModel->update($userId, ['photo' => $photoPath]);

            session()->set('userPhoto', base_url('file/' . $photoPath));

            return redirect()->to('/admin-wpa/profile')->with('success', 'Foto profile berhasil diupdate!');
        }

        return redirect()->to('/admin-wpa/profile')->with('error', 'Gagal mengupload foto.');
    }
}
