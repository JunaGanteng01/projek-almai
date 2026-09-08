<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CwpaSubmissionModel;
use App\Models\NotificationModel;

class UpgradeCwpa extends BaseController
{
    public function index()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $submissionModel = new CwpaSubmissionModel();

        $user = $userModel->find($userId);
        $submission = $submissionModel->getByUserId($userId);

        return view('user/upgrade_cwpa', [
            'title' => 'Formulir CWPA - Almai',
            'pageTitle' => 'Formulir CWPA',
            'activeMenu' => 'profile',
            'user' => $user,
            'submission' => $submission
        ]);
    }

    public function submit()
    {
        $userId = session()->get('userId');
        $submissionModel = new CwpaSubmissionModel();
        $existing = $submissionModel->getByUserId($userId);

        $fileFields = [
            'cv' => 'Daftar Riwayat Hidup',
            'ijazah' => 'Ijazah Pendidikan Formal',
            'sertifikat_multilateral' => 'Sertifikat Multilateral (ICDX)',
            'sertifikat_lsp_pbk' => 'Sertifikat LSP PBK',
            'tanda_lulus_bappebti' => 'Tanda Lulus Ujian Profesi Bappebti',
            'surat_rekomendasi' => 'Surat Rekomendasi',
            'ktp' => 'KTP',
            'npwp' => 'NPWP',
            'bukti_pelaporan_spt' => 'Bukti Pelaporan SPT Pribadi',
            'skck' => 'SKCK',
            'photo' => 'Pas Foto',
            'surat_pernyataan_cwpa' => 'Surat Pernyataan CWPA',
            'permohonan_izin_wapebti' => 'Formulir Permohonan Izin Wapebti',
            'sk_tidak_pidana' => 'SK Tidak Pernah Dipidana',
            'surat_pernyataan_cwpa_kerja' => 'Surat Pernyataan CWPA (Kerja)'
        ];

        $dataToUpdate = [
            'user_id' => $userId,
            'status' => 'pending', // Reset to pending on update
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $uploadedCount = 0;
        $errors = [];

        foreach ($fileFields as $field => $label) {
            $file = $this->request->getFile($field);

            // Determine requirements
            $isRequired = (!$existing || empty($existing[$field]));

            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validate file
                $mimeType = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];

                if (!in_array($mimeType, $allowedMimes)) {
                    $errors[] = "$label: Format tidak didukung (harus JPG, PNG, atau PDF).";
                    continue;
                }

                if ($field === 'photo' && strpos($mimeType, 'image') === false) {
                    $errors[] = "$label: Harus berupa gambar.";
                    continue;
                }

                if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
                    $errors[] = "$label: Ukuran file terlalu besar (maks 5MB).";
                    continue;
                }

                // Move file
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/cwpa', $newName);
                $dataToUpdate[$field] = 'uploads/cwpa/' . $newName;
                $uploadedCount++;
            } elseif ($isRequired) {
                $errors[] = "$label wajib diunggah.";
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $errors));
        }

        // If simple update without files (unlikely given logic above), or successful upload
        if ($existing) {
            $submissionModel->update($existing['id'], $dataToUpdate);
        } else {
            $dataToUpdate['created_at'] = date('Y-m-d H:i:s');
            $submissionModel->insert($dataToUpdate);
        }

        // Notify Admins
        $this->notifyAdmins($userId);

        return redirect()->to('/user/profile')->with('success', 'Formulir CWPA berhasil dikirim! Tim kami akan memverifikasi berkas Anda.');
    }

    private function notifyAdmins($userId)
    {
        $userModel = new UserModel();
        $notifModel = new NotificationModel();

        $user = $userModel->find($userId);
        $userName = $user['name'] ?? 'User';

        $admins = $userModel->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ])->findAll();

        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Pengajuan Upgrade CWPA 🎓',
                $userName . ' telah mengunggah berkas persyaratan CWPA. Silakan verifikasi.',
                'info',
                '/admin/cwpa/submissions' // Assuming generic admin link or I need to make one
            );
        }
    }
}
