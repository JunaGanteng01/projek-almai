<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\CwpaSubmissionModel;
use App\Models\CwpaModel;

class CwpaManagement extends BaseController
{
    protected $cwpaModel;
    protected $cwpaSubmissionModel;

    public function __construct()
    {
        $this->cwpaModel = new CwpaModel();
        $this->cwpaSubmissionModel = new CwpaSubmissionModel();
    }
    // ... (skip lines until verification method)

    public function verification()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->cwpaSubmissionModel->select('cwpa_submissions.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = cwpa_submissions.user_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('cwpa_submissions.whatsapp', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $builder->where('cwpa_submissions.status', $status);
        }

        $cwpaList = $builder->orderBy('cwpa_submissions.created_at', 'DESC')->paginate(20);
        $pager = $this->cwpaSubmissionModel->pager;

        // Stats
        $stats = [
            'total' => $this->cwpaSubmissionModel->countAllResults(),
            'pending' => $this->cwpaSubmissionModel->where('status', 'pending')->countAllResults(),
            'approved' => $this->cwpaSubmissionModel->where('status', 'approved')->countAllResults(),
            'rejected' => $this->cwpaSubmissionModel->where('status', 'rejected')->countAllResults(),
        ];

        return view('superadmin/cwpa/verification', [
            'title' => 'Verifikasi CWPA - Admin Dashboard',
            'cwpaList' => $cwpaList,
            'pager' => $pager,
            'search' => $search,
            'currentStatus' => $status,
            'activeMenu' => 'cwpa',
            'stats' => $stats
        ]);
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->cwpaModel->select('cwpa.*, users.name as user_name, users.code_referral as user_referral')
            ->join('users', 'users.id = cwpa.user_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('cwpa.name', $search)
                ->orLike('cwpa.university', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $builder->where('cwpa.status', $status);
        }

        // Stats: pakai model terpisah agar hitung hanya data yang tidak soft-deleted (sesuai tampilan tabel)
        $statsModel = new CwpaModel();
        $stats = [
            'total' => $statsModel->countAllResults(),
            'active' => (new CwpaModel())->where('status', 'active')->countAllResults(),
            'inactive' => (new CwpaModel())->where('status', 'inactive')->countAllResults(),
        ];

        $cwpaList = $builder->orderBy('cwpa.id', 'DESC')->paginate(20);
        $pager = $this->cwpaModel->pager;

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        return view('superadmin/cwpa/index', [
            'title' => 'Kelola CWPA - Admin Dashboard',
            'cwpaList' => $cwpaList,
            'pager' => $pager,
            'search' => $search,
            'currentStatus' => $status,
            'activeMenu' => 'cwpa',
            'stats' => $stats,
            'currentPage' => $page,
            'perPage' => $perPage
        ]);
    }

    public function create()
    {
        return view('superadmin/cwpa/create', [
            'title' => 'Tambah CWPA - Admin Dashboard',
            'activeMenu' => 'cwpa'
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'university' => 'required',
            'photo_file' => 'permit_empty|max_size[photo_file,2048]|is_image[photo_file]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photoPath = '';
        $photoFile = $this->request->getFile('photo_file');

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $newName = 'cwpa_' . time() . '_' . $photoFile->getRandomName();
            // Use FCPATH for public directory instead of WRITEPATH
            if (!is_dir(FCPATH . 'images/cwpa')) {
                mkdir(FCPATH . 'images/cwpa', 0755, true);
            }
            $photoFile->move(FCPATH . 'images/cwpa', $newName);
            $photoPath = 'images/cwpa/' . $newName;
        }

        $this->cwpaModel->insert([
            'name' => $this->request->getPost('name'),
            'specialty' => $this->request->getPost('specialty'),
            'university' => $this->request->getPost('university'),
            'experience' => $this->request->getPost('experience'),
            'batch' => $this->request->getPost('batch'),
            'bio' => $this->request->getPost('bio'),
            'certifications' => $this->request->getPost('certifications'),
            'current_phase' => (int) $this->request->getPost('current_phase'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'tiktok' => $this->request->getPost('tiktok'),
            'tiktok_secuid' => $this->request->getPost('tiktok_secuid'),
            'mql5_widget_url' => $this->request->getPost('mql5_widget_url'),
            'photo' => $photoPath,
            'user_id' => $this->request->getPost('user_id') ?: null,
            'status' => 'active',
        ]);

        return redirect()->to('/superadmin/cwpa')->with('success', 'CWPA berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $cwpa = $this->cwpaModel->find($id);

        if (!$cwpa) {
            return redirect()->to('/superadmin/cwpa')->with('error', 'CWPA tidak ditemukan');
        }

        return view('superadmin/cwpa/edit', [
            'title' => 'Edit CWPA - Admin Dashboard',
            'cwpa' => $cwpa,
            'activeMenu' => 'cwpa'
        ]);
    }

    public function update($id)
    {
        $cwpa = $this->cwpaModel->find($id);
        if (!$cwpa) {
            return redirect()->to('/superadmin/cwpa')->with('error', 'CWPA tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'university' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photoPath = $cwpa['photo'];
        $photoFile = $this->request->getFile('photo_file');

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            // Delete old photo
            if ($cwpa['photo']) {
                $oldPath = '';
                if (strpos($cwpa['photo'], 'uploads/') === 0) {
                    $oldPath = WRITEPATH . $cwpa['photo'];
                } elseif (strpos($cwpa['photo'], 'images/') === 0) {
                    $oldPath = FCPATH . $cwpa['photo'];
                }

                if ($oldPath && file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $newName = 'cwpa_' . $id . '_' . time() . '.' . $photoFile->getExtension();

            if (!is_dir(FCPATH . 'images/cwpa')) {
                mkdir(FCPATH . 'images/cwpa', 0755, true);
            }

            $photoFile->move(FCPATH . 'images/cwpa', $newName);
            $photoPath = 'images/cwpa/' . $newName;
        }

        // Handle phase certificates upload
        $phaseCertificates = [];
        if (!empty($cwpa['phase_certificates'])) {
            $phaseCertificates = is_string($cwpa['phase_certificates'])
                ? json_decode($cwpa['phase_certificates'], true)
                : $cwpa['phase_certificates'];
        }

        $phaseCertificateNumbers = [];
        if (!empty($cwpa['phase_certificate_numbers'])) {
            $phaseCertificateNumbers = is_string($cwpa['phase_certificate_numbers'])
                ? json_decode($cwpa['phase_certificate_numbers'], true)
                : $cwpa['phase_certificate_numbers'];
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
                unset($phaseCertificateNumbers[$i]);
                continue;
            }

            // Update certificate number
            if ($this->request->getPost('phase_cert_number_' . $i) !== null) {
                $phaseCertificateNumbers[$i] = $this->request->getPost('phase_cert_number_' . $i);
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
                $newName = 'cwpa_' . $id . '_phase' . $i . '_' . time() . '.' . $certFile->getExtension();
                $certFile->move(WRITEPATH . 'uploads/certificates', $newName);
                $phaseCertificates[$i] = 'uploads/certificates/' . $newName;
            } elseif ($this->request->getPost('existing_cert_' . $i)) {
                // Keep existing certificate
                $phaseCertificates[$i] = $this->request->getPost('existing_cert_' . $i);
            }
        }

        // Update CWPA profile
        $updated = $this->cwpaModel->update($id, [
            'name' => $this->request->getPost('name'),
            'specialty' => $this->request->getPost('specialty'),
            'university' => $this->request->getPost('university'),
            'experience' => $this->request->getPost('experience'),
            'batch' => $this->request->getPost('batch'),
            'bio' => $this->request->getPost('bio'),
            'certifications' => $this->request->getPost('certifications'),
            'current_phase' => (int) $this->request->getPost('current_phase'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'tiktok' => $this->request->getPost('tiktok'),
            'tiktok_secuid' => $this->request->getPost('tiktok_secuid'),
            'mql5_widget_url' => $this->request->getPost('mql5_widget_url'),
            'photo' => $photoPath,
            'status' => $this->request->getPost('status') ?? 'active',
            'user_id' => $this->request->getPost('user_id') ?: null,
            'phase_certificates' => !empty($phaseCertificates) ? json_encode($phaseCertificates) : null,
            'phase_certificate_numbers' => !empty($phaseCertificateNumbers) ? json_encode($phaseCertificateNumbers) : null,
            
            // Sync to custom columns
            'almai_pendampingan' => $phaseCertificateNumbers[1] ?? null,
            'almai_pendampingan_file' => $phaseCertificates[1] ?? null,
            'bursa_icdx_sertifikasi_multilateral' => $phaseCertificateNumbers[2] ?? null,
            'bursa_icdx_sertifikasi_multilateral_file' => $phaseCertificates[2] ?? null,
            'lpk_sertifikasi_pelatihan_pbk' => $phaseCertificateNumbers[3] ?? null,
            'lpk_sertifikasi_pelatihan_pbk_file' => $phaseCertificates[3] ?? null,
            'bnsp_sertifikasi_kompetensi' => $phaseCertificateNumbers[4] ?? null,
            'bnsp_sertifikasi_kompetensi_file' => $phaseCertificates[4] ?? null,
        ]);

        if (!$updated) {
            $errors = $this->cwpaModel->errors();
            log_message('error', 'Gagal memperbarui CWPA ID ' . $id . ': ' . json_encode($errors));

            return redirect()->back()->withInput()->with(
                'errors',
                $errors ?: ['Data CWPA gagal disimpan. Silakan coba kembali.']
            );
        }

        return redirect()->to('/superadmin/cwpa')->with('success', 'CWPA berhasil diupdate!');
    }

    public function delete($id)
    {
        $cwpa = $this->cwpaModel->find($id);
        if (!$cwpa) {
            return redirect()->to('/superadmin/cwpa')->with('error', 'CWPA tidak ditemukan');
        }

        // Delete photo if it's a local file
        if ($cwpa['photo']) {
            $photoPath = '';
            if (strpos($cwpa['photo'], 'uploads/') === 0) {
                $photoPath = WRITEPATH . $cwpa['photo'];
            } elseif (strpos($cwpa['photo'], 'images/') === 0) {
                $photoPath = FCPATH . $cwpa['photo'];
            }

            if ($photoPath && file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        // Delete phase certificates if exist
        if (!empty($cwpa['phase_certificates'])) {
            $phaseCertificates = is_string($cwpa['phase_certificates'])
                ? json_decode($cwpa['phase_certificates'], true)
                : $cwpa['phase_certificates'];

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
        if (!empty($cwpa['user_id'])) {
            $userModel = new \App\Models\UserModel();
            $userModel->update($cwpa['user_id'], [
                'level_id' => \App\Models\LevelModel::LEVEL_USER
            ]);
        }

        // Delete CWPA profile only
        $this->cwpaModel->delete($id);

        return redirect()->to('/superadmin/cwpa')->with('success', 'CWPA berhasil dihapus! User account dikembalikan ke level User biasa.');
    }



    public function getDetail($id)
    {
        $submission = $this->cwpaSubmissionModel->select('cwpa_submissions.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = cwpa_submissions.user_id', 'left')
            ->where('cwpa_submissions.id', $id)
            ->first();

        if (!$submission) {
            return redirect()->to('superadmin/cwpa/verification')->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Fetch Payment Info
        $transaksiModel = new \App\Models\TransaksiModel();
        $payment = $transaksiModel->where('user_id', $submission['user_id'])
            ->where('product_type', 'cwpa')
            ->orderBy('created_at', 'DESC')
            ->first();

        // Prepare documents list for display
        $documents = [
            'CV' => $submission['cv'],
            'Ijazah' => $submission['ijazah'],
            'KTP' => $submission['ktp'],
            'NPWP' => $submission['npwp'],
            'SKCK' => $submission['skck'],
            'Bukti Transfer' => $submission['transfer_proof'] ?? ($payment['transfer_proof'] ?? null),
        ];

        return view('superadmin/cwpa/detail_verification', [
            'submission' => $submission,
            'documents' => $documents,
            'payment' => $payment
        ]);
    }

    public function verify($id)
    {
        $submission = $this->cwpaSubmissionModel->find($id);
        if (!$submission) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data pendaftaran tidak ditemukan'])->setStatusCode(404);
        }

        $notes = $this->request->getPost('verification_notes');

        $this->cwpaSubmissionModel->update($id, [
            'status' => 'approved',
            'admin_note' => $notes,
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => session()->get('userId')
        ]);

        // Send Notification to User
        try {
            $notifModel = new \App\Models\NotificationModel();
            $notifTitle = "Pendaftaran CWPA Disetujui!";
            $notifMsg = "Selamat! Pendaftaran CWPA Anda telah diverifikasi dan disetujui oleh Admin. Silakan cek dashboard Anda untuk langkah selanjutnya.";
            if (!empty($notes)) {
                $notifMsg .= " Catatan: $notes";
            }
            // Link to CWPA Dashboard or Training Page
            $notifUrl = base_url('cwpa/dashboard');

            $notifModel->createNotification(
                $submission['user_id'],
                $notifTitle,
                $notifMsg,
                'success',
                $notifUrl
            );
        } catch (\Throwable $e) {
            log_message('error', 'CWPA Verify Notification Failed: ' . $e->getMessage());
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Pendaftaran CWPA berhasil diverifikasi (Approved)']);
    }

    public function unverify($id)
    {
        $submission = $this->cwpaSubmissionModel->find($id);
        if (!$submission) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data pendaftaran tidak ditemukan'])->setStatusCode(404);
        }

        $notes = $this->request->getPost('verification_notes');

        $this->cwpaSubmissionModel->update($id, [
            'status' => 'rejected',
            'admin_note' => $notes
        ]);

        // Send Notification to User
        try {
            $notifModel = new \App\Models\NotificationModel();
            $notifTitle = "Pendaftaran CWPA Ditolak";
            $notifMsg = "Mohon maaf, pendaftaran CWPA Anda belum dapat kami setujui saat ini.";
            if (!empty($notes)) {
                $notifMsg .= " Alasan: $notes";
            } else {
                $notifMsg .= " Silakan hubungi admin untuk informasi lebih lanjut.";
            }
            $notifUrl = base_url('daftar-cwpa'); // Redirect to re-apply or check status

            $notifModel->createNotification(
                $submission['user_id'],
                $notifTitle,
                $notifMsg,
                'danger', // or 'error' / 'warning' depending on your design system
                $notifUrl
            );
        } catch (\Throwable $e) {
            log_message('error', 'CWPA Reject Notification Failed: ' . $e->getMessage());
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Pendaftaran CWPA ditolak/dibatalkan']);
    }

    public function promote($id)
    {
        $cwpa = $this->cwpaModel->find($id);
        if (!$cwpa) {
            return redirect()->to('/superadmin/cwpa')->with('error', 'CWPA tidak ditemukan');
        }

        if (empty($cwpa['user_id'])) {
            return redirect()->back()->with('error', 'CWPA ini belum ditautkan ke akun user. Harap kaitkan akun user terlebih dahulu sebelum memindahkan ke data WPA.');
        }

        $wpaModel = new \App\Models\WpaModel();
        $userModel = new \App\Models\UserModel();

        // Check if WPA already exists for this user
        $existingWpa = $wpaModel->where('user_id', $cwpa['user_id'])->first();
        if ($existingWpa) {
            return redirect()->back()->with('error', 'User ini sudah terdaftar sebagai WPA.');
        }

        // Prepare WPA data
        $wpaData = [
            'user_id' => $cwpa['user_id'],
            'name' => $cwpa['name'],
            'photo' => $cwpa['photo'],
            'instagram' => $cwpa['instagram'],
            'youtube' => $cwpa['youtube'],
            'tiktok' => $cwpa['tiktok'],
            'tiktok_secuid' => $cwpa['tiktok_secuid'],
            'mql5_widget_url' => $cwpa['mql5_widget_url'],
            'current_phase' => $cwpa['current_phase'],
            'phase_certificates' => $cwpa['phase_certificates'],
            'status' => 'active',
            'rating' => 5.0,
            'experience' => 'Alumni Program CWPA',
            'specialty' => 'Trading Expert', // Default specialty
            'bio' => 'Lulusan program bimbingan CWPA (Candidate Wakil Penasihat Berjangka).',
            'certifications' => json_encode(['CWPA']),
        ];

        // Start Transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert into WPA table
        $wpaModel->insert($wpaData);

        // 2. Update User level to WPA
        $userModel->update($cwpa['user_id'], [
            'level_id' => \App\Models\LevelModel::LEVEL_WPA
        ]);

        // 3. Mark CWPA as inactive
        $this->cwpaModel->update($id, [
            'status' => 'inactive'
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses pemindahan data ke WPA.');
        }

        return redirect()->to('/superadmin/wpa')->with('success', 'Berhasil! Data CWPA telah dipindahkan ke data WPA dan level user telah diperbarui.');
    }
}
