<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\UserModel;

class WpaManagement extends BaseController
{
    protected $wpaModel;
    protected $userModel;

    public function __construct()
    {
        $this->wpaModel = new WpaModel();
        $this->userModel = new UserModel();
        helper('watermark'); // Load watermark helper
        log_message('info', 'WpaManagement: Watermark helper loaded. Function exists: ' . (function_exists('add_watermark') ? 'YES' : 'NO'));
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->wpaModel->select('wpa.*, users.email as user_email, users.name as user_name, users.code_referral as user_referral')
            ->join('users', 'users.id = wpa.user_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('wpa.name', $search)
                ->orLike('wpa.specialty', $search)
                ->orLike('users.email', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $builder->where('wpa.status', $status);
        }

        $wpaList = $builder->orderBy('wpa.id', 'DESC')->paginate(20);
        $pager = $this->wpaModel->pager;

        $stats = [
            'total' => $this->wpaModel->countAll(),
            'active' => $this->wpaModel->where('status', 'active')->countAllResults(),
            'inactive' => $this->wpaModel->where('status', 'inactive')->countAllResults(),
            'avg_rating' => $this->wpaModel->selectAvg('rating')->first()['rating'] ?? 0
        ];

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        return view('superadmin/wpa/index', [
            'title' => 'Kelola WPA - Admin Dashboard',
            'wpaList' => $wpaList,
            'pager' => $pager,
            'search' => $search,
            'currentStatus' => $status,
            'activeMenu' => 'wpa',
            'stats' => $stats,
            'currentPage' => $page,
            'perPage' => $perPage,
            'canWrite' => $this->canWriteAdmin()
        ]);
    }

    public function create()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/wpa')->with('error', 'Akses ditolak.');
        }

        // Get users with level WPA
        $wpaUsers = $this->userModel->select('id, name, email, phone')
            ->where('level_id', \App\Models\LevelModel::LEVEL_WPA)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('superadmin/wpa/create', [
            'title' => 'Tambah WPA - Admin Dashboard',
            'wpaUsers' => $wpaUsers,
            'activeMenu' => 'wpa'
        ]);
    }

    public function store()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/wpa')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email' . ($this->request->getPost('user_id') ? '' : '|is_unique[users.email]'),
            'specialty' => 'required',
            'experience' => 'required',
            'bio' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photoPath = 'https://via.placeholder.com/200';
        $photoFile = $this->request->getFile('photo_file');

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $newName = 'wpa_' . time() . '_' . $photoFile->getRandomName();
            $photoFile->move(WRITEPATH . 'uploads/wpa', $newName);
            $photoPath = 'uploads/wpa/' . $newName;
        } elseif ($this->request->getPost('photo')) {
            $photoPath = $this->request->getPost('photo');
        }

        // Check if using existing user or creating new one
        $existingUserId = $this->request->getPost('user_id');

        if (!empty($existingUserId)) {
            $userId = $existingUserId;
            // Update user name in case it's different from the form
            $this->userModel->update($userId, [
                'name' => $this->request->getPost('name'),
                'phone' => $this->request->getPost('phone') ?? '',
                'level_id' => \App\Models\LevelModel::LEVEL_WPA, // Ensure level is updated to WPA
            ]);
        } else {
            // Create user account for WPA
            $userId = $this->userModel->insert([
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone') ?? '',
                'password' => password_hash('wpa123', PASSWORD_DEFAULT),
                'level_id' => \App\Models\LevelModel::LEVEL_WPA,
                'status' => 'active',
            ]);
        }

        // Create WPA profile
        $certifications = $this->request->getPost('certifications');
        $certsArray = $certifications ? array_map('trim', explode(',', $certifications)) : [];

        $this->wpaModel->insert([
            'user_id' => $userId,
            'name' => $this->request->getPost('name'),
            'photo' => $photoPath,
            'specialty' => $this->request->getPost('specialty'),
            'experience' => $this->request->getPost('experience'),
            'bio' => $this->request->getPost('bio'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'tiktok' => $this->request->getPost('tiktok'),
            'tiktok_secuid' => $this->request->getPost('tiktok_secuid'),
            'mql5_widget_url' => $this->request->getPost('mql5_widget_url'),
            'certifications' => json_encode($certsArray),
            'rating' => 0,
            'total_classes' => 0,
            'status' => 'active',
        ]);

        return redirect()->to('/superadmin/wpa')->with('success', 'WPA berhasil ditambahkan! Password default: wpa123');
    }

    public function edit($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/wpa')->with('error', 'Akses ditolak.');
        }

        $wpa = $this->wpaModel->select('wpa.*, users.email as user_email, users.phone as user_phone')
            ->join('users', 'users.id = wpa.user_id', 'left')
            ->find($id);

        if (!$wpa) {
            return redirect()->to('/superadmin/wpa')->with('error', 'WPA tidak ditemukan');
        }

        // Get users with level WPA
        $wpaUsers = $this->userModel->select('id, name, email, phone')
            ->where('level_id', \App\Models\LevelModel::LEVEL_WPA)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('superadmin/wpa/edit', [
            'title' => 'Edit WPA - Admin Dashboard',
            'wpa' => $wpa,
            'wpaUsers' => $wpaUsers,
            'activeMenu' => 'wpa'
        ]);
    }

    public function update($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/wpa')->with('error', 'Akses ditolak.');
        }

        $wpa = $this->wpaModel->find($id);
        if (!$wpa) {
            return redirect()->to('/superadmin/wpa')->with('error', 'WPA tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'specialty' => 'required',
            'experience' => 'required',
            'bio' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photoPath = $wpa['photo'];
        $photoFile = $this->request->getFile('photo_file');

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            // Delete old photo if it's a local file
            if ($wpa['photo'] && strpos($wpa['photo'], 'uploads/') === 0) {
                $oldPath = WRITEPATH . $wpa['photo'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $newName = 'wpa_' . $id . '_' . time() . '.' . $photoFile->getExtension();
            $photoFile->move(WRITEPATH . 'uploads/wpa', $newName);
            $photoPath = 'uploads/wpa/' . $newName;
        } elseif ($this->request->getPost('photo') && $this->request->getPost('photo') !== $wpa['photo']) {
            $photoPath = $this->request->getPost('photo');
        }

        // Parse certifications
        $certifications = $this->request->getPost('certifications');
        $certsArray = $certifications ? array_map('trim', explode(',', $certifications)) : [];

        // Handle slug
        $slug = $this->request->getPost('slug');
        if (empty($slug)) {
            $slug = url_title($this->request->getPost('name'), '-', true);
        } else {
            $slug = url_title($slug, '-', true);
        }

        // Handle phase certificates upload
        $phaseCertificates = [];
        if (!empty($wpa['phase_certificates'])) {
            $phaseCertificates = is_string($wpa['phase_certificates'])
                ? json_decode($wpa['phase_certificates'], true)
                : $wpa['phase_certificates'];
        }

        for ($i = 1; $i <= 8; $i++) {
            // Check if marked for deletion
            if ($this->request->getPost('delete_cert_' . $i)) {
                // Delete old file if exists
                if (isset($phaseCertificates[$i]) && strpos($phaseCertificates[$i], 'uploads/') === 0) {
                    $oldPath = WRITEPATH . $phaseCertificates[$i];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                unset($phaseCertificates[$i]);
                continue;
            }

            // Handle new upload
            $certFile = $this->request->getFile('phase_cert_' . $i);
            if ($certFile && $certFile->isValid() && !$certFile->hasMoved()) {
                // Delete old file if exists
                if (isset($phaseCertificates[$i]) && strpos($phaseCertificates[$i], 'uploads/') === 0) {
                    $oldPath = WRITEPATH . $phaseCertificates[$i];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Upload new file
                $newName = 'wpa_' . $id . '_phase' . $i . '_' . time() . '.' . $certFile->getExtension();
                $certFile->move(WRITEPATH . 'uploads/certificates', $newName);
                $certPath = 'uploads/certificates/' . $newName;

                // Add watermark to the uploaded certificate
                $fullPath = WRITEPATH . $certPath;
                log_message('info', '>>> ATTEMPTING WATERMARK: ' . $fullPath);
                log_message('info', '>>> File exists: ' . (file_exists($fullPath) ? 'YES' : 'NO'));
                log_message('info', '>>> Function exists: ' . (function_exists('add_watermark') ? 'YES' : 'NO'));

                if (function_exists('add_watermark')) {
                    $watermarkResult = add_watermark($fullPath, 'COPYRIGHT ALMAI.ID');
                    log_message('info', '>>> Watermark result: ' . ($watermarkResult ? 'SUCCESS' : 'FAILED'));
                    log_message('info', '>>> Watermark added to certificate: ' . $certPath);
                } else {
                    log_message('error', '>>> add_watermark function NOT FOUND!');
                }

                $phaseCertificates[$i] = $certPath;
            } elseif ($this->request->getPost('existing_cert_' . $i)) {
                // Keep existing certificate
                $phaseCertificates[$i] = $this->request->getPost('existing_cert_' . $i);
            }
        }

        // Handle user_id change
        $newUserId = $this->request->getPost('user_id');
        if (!empty($newUserId)) {
            // Update user data
            $this->userModel->update($newUserId, [
                'name' => $this->request->getPost('name'),
                'phone' => $this->request->getPost('phone') ?? '',
                'level_id' => \App\Models\LevelModel::LEVEL_WPA, // Ensure level is updated to WPA
            ]);
        }

        // Update WPA profile
        $this->wpaModel->update($id, [
            'user_id' => !empty($newUserId) ? $newUserId : $wpa['user_id'],
            'name' => $this->request->getPost('name'),
            'slug' => $slug,
            'photo' => $photoPath,
            'specialty' => $this->request->getPost('specialty'),
            'experience' => $this->request->getPost('experience'),
            'bio' => $this->request->getPost('bio'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'tiktok' => $this->request->getPost('tiktok'),
            'tiktok_secuid' => $this->request->getPost('tiktok_secuid'),
            'mql5_widget_url' => $this->request->getPost('mql5_widget_url'),
            'certifications' => json_encode($certsArray),
            'rating' => $this->request->getPost('rating') ?? $wpa['rating'],
            'status' => $this->request->getPost('status') ?? 'active',
            'current_phase' => $this->request->getPost('current_phase') ?? 1,
            'phase_certificates' => !empty($phaseCertificates) ? json_encode($phaseCertificates) : null,
        ]);

        return redirect()->to('/superadmin/wpa')->with('success', 'WPA berhasil diupdate!');
    }

    public function delete($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/wpa')->with('error', 'Akses ditolak.');
        }

        $wpa = $this->wpaModel->find($id);
        if (!$wpa) {
            return redirect()->to('/superadmin/wpa')->with('error', 'WPA tidak ditemukan');
        }

        // Delete photo if it's a local file
        if ($wpa['photo'] && strpos($wpa['photo'], 'uploads/') === 0) {
            $photoPath = WRITEPATH . $wpa['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        // Delete phase certificates if exist
        if (!empty($wpa['phase_certificates'])) {
            $phaseCertificates = is_string($wpa['phase_certificates'])
                ? json_decode($wpa['phase_certificates'], true)
                : $wpa['phase_certificates'];

            foreach ($phaseCertificates as $certPath) {
                if (strpos($certPath, 'uploads/') === 0) {
                    $fullPath = WRITEPATH . $certPath;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
        }

        // Update user level back to regular user (don't delete the account)
        if ($wpa['user_id']) {
            $this->userModel->update($wpa['user_id'], [
                'level_id' => \App\Models\LevelModel::LEVEL_USER
            ]);
        }

        // Delete WPA profile only
        $this->wpaModel->delete($id);

        return redirect()->to('/superadmin/wpa')->with('success', 'WPA berhasil dihapus! User account dikembalikan ke level User biasa.');
    }
}
