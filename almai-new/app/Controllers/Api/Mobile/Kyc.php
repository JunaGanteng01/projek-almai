<?php

namespace App\Controllers\Api\Mobile;

use App\Models\UserModel;
use App\Models\KycSubmissionModel;
use CodeIgniter\RESTful\ResourceController;

class Kyc extends ResourceController
{
    protected $format = 'json';

    /**
     * GET /api/mobile/kyc
     * Status KYC user
     */
    public function index()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        $kycStatus = $user['kyc_status'] ?? 'not_submitted';

        $statusInfo = match($kycStatus) {
            'approved' => [
                'status'      => 'approved',
                'label'       => 'Terverifikasi',
                'description' => 'KYC Anda telah disetujui. Akun Anda sudah berstatus PRO.',
                'color'       => 'green',
            ],
            'pending' => [
                'status'      => 'pending',
                'label'       => 'Menunggu Review',
                'description' => 'Dokumen KYC Anda sedang dalam proses review oleh tim kami.',
                'color'       => 'yellow',
            ],
            'rejected' => [
                'status'      => 'rejected',
                'label'       => 'Ditolak',
                'description' => 'KYC Anda ditolak. Silakan ajukan ulang dengan dokumen yang valid.',
                'color'       => 'red',
            ],
            default => [
                'status'      => 'not_submitted',
                'label'       => 'Belum Diajukan',
                'description' => 'Lengkapi KYC untuk mengakses fitur PRO dan semua layanan.',
                'color'       => 'gray',
            ],
        };

        return $this->respond([
            'success' => true,
            'data'    => [
                'kyc'      => $statusInfo,
                'is_pro'   => (bool)($user['is_pro'] ?? false),
                'benefits' => [
                    'Akses fitur PRO lengkap',
                    'Download sertifikat advokasi',
                    'Prioritas layanan',
                    'Badge Member Terverifikasi',
                ],
                'requirements' => [
                    'KTP / Kartu Identitas yang masih berlaku',
                    'Foto selfie dengan KTP',
                    'Nomor HP aktif',
                ],
            ],
        ]);
    }

    /**
     * POST /api/mobile/kyc/submit
     * Multipart form: ktp=<file>, selfie=<file>, name, id_number, address, phone
     */
    public function submit()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (($user['kyc_status'] ?? '') === 'approved') {
            return $this->fail(['message' => 'KYC Anda sudah disetujui.'], 400);
        }

        if (($user['kyc_status'] ?? '') === 'pending') {
            return $this->fail(['message' => 'KYC Anda sedang dalam proses review.'], 400);
        }

        $ktpFile    = $this->request->getFile('ktp');
        $selfieFile = $this->request->getFile('selfie');

        if (!$ktpFile || !$ktpFile->isValid()) {
            return $this->fail(['message' => 'File KTP wajib diupload.'], 422);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($ktpFile->getMimeType(), $allowedTypes)) {
            return $this->fail(['message' => 'Format KTP harus JPG atau PNG.'], 422);
        }

        $savePath = WRITEPATH . 'uploads/kyc/';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
        }

        // Simpan KTP
        $ktpName = 'ktp_' . $userId . '_' . time() . '.' . $ktpFile->getExtension();
        $ktpFile->move($savePath, $ktpName);
        $ktpPath = 'uploads/kyc/' . $ktpName;

        // Simpan selfie jika ada
        $selfiePath = null;
        if ($selfieFile && $selfieFile->isValid() && in_array($selfieFile->getMimeType(), $allowedTypes)) {
            $selfieName = 'selfie_' . $userId . '_' . time() . '.' . $selfieFile->getExtension();
            $selfieFile->move($savePath, $selfieName);
            $selfiePath = 'uploads/kyc/' . $selfieName;
        }

        // Update user kyc_status ke pending
        $userModel->update($userId, [
            'kyc_status'      => 'pending',
            'kyc_submitted_at'=> date('Y-m-d H:i:s'),
        ]);

        // Simpan ke kyc_submissions jika model ada
        try {
            $kycModel = new KycSubmissionModel();
            $kycModel->insert([
                'user_id'    => $userId,
                'ktp_path'   => $ktpPath,
                'selfie_path'=> $selfiePath,
                'status'     => 'pending',
                'name'       => $this->request->getPost('name') ?? '',
                'id_number'  => $this->request->getPost('id_number') ?? '',
            ]);
        } catch (\Exception $e) {
            // Lanjut meski model tidak lengkap
        }

        return $this->respond([
            'success' => true,
            'message' => 'Dokumen KYC berhasil diajukan. Tim kami akan mereview dalam 1x24 jam.',
        ]);
    }
}
