<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\LayananModel;

class Profile extends BaseController
{
    protected $wpaModel;
    protected $layananModel;

    public function __construct()
    {
        $this->wpaModel = new WpaModel();
        $this->layananModel = new LayananModel();
    }

    public function index()
    {
        $wpaId = session()->get('wpaId');
        $wpa = $this->wpaModel->select('wpa.*, users.code_referral')
            ->join('users', 'users.id = wpa.user_id')
            ->find($wpaId);

        // Get stats
        $totalLayanan = $this->layananModel->countByWpaId($wpaId);

        // Get specialties for dropdown
        $specialties = $this->wpaModel->getSpecialties();

        // Parse certifications
        $certifications = $this->wpaModel->getCertificationsArray($wpa['certifications'] ?? '[]');

        return view('wpa/profile/index', [
            'title' => 'Profile - WPA Dashboard',
            'activeMenu' => 'profile',
            'wpa' => $wpa,
            'totalLayanan' => $totalLayanan,
            'specialties' => $specialties,
            'certifications' => $certifications,
        ]);
    }

    public function update()
    {
        $wpaId = session()->get('wpaId');

        $rules = [
            'name' => 'required|min_length[3]',
            'specialty' => 'required',
            'experience' => 'required',
            'bio' => 'permit_empty|max_length[1000]',
            'instagram' => 'permit_empty|max_length[100]',
            'youtube' => 'permit_empty|max_length[200]',
            'mql5_widget_url' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'specialty' => $this->request->getPost('specialty'),
            'experience' => $this->request->getPost('experience'),
            'bio' => $this->request->getPost('bio'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'mql5_widget_url' => $this->request->getPost('mql5_widget_url'),
        ];

        // Handle certifications
        $certifications = $this->request->getPost('certifications');
        if ($certifications) {
            $certArray = array_filter(array_map('trim', explode("\n", $certifications)));
            $data['certifications'] = json_encode($certArray);
        }

        $this->wpaModel->update($wpaId, $data);

        // Update session
        session()->set('wpaName', $data['name']);

        return redirect()->to('/wpa/dashboard/profile')->with('success', 'Profile berhasil diupdate!');
    }

    public function updatePhoto()
    {
        $wpaId = session()->get('wpaId');

        $file = $this->request->getFile('photo');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        // Validate file
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
            return redirect()->back()->with('error', 'Format file harus JPG, PNG, atau WebP');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB');
        }

        // Generate filename
        $newName = 'wpa_' . $wpaId . '_' . time() . '.' . $file->getExtension();

        // Move file
        $file->move(WRITEPATH . 'uploads/wpa', $newName);

        // Update database
        // Store path relative to writable/ or public/ so it can be served via file controller
        $photoPath = 'uploads/wpa/' . $newName;
        $this->wpaModel->update($wpaId, ['photo' => $photoPath]);

        // Update session
        session()->set('wpaPhoto', base_url('file/' . $photoPath));

        return redirect()->to('/wpa/dashboard/profile')->with('success', 'Foto profile berhasil diupdate!');
    }
}
