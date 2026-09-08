<?php

namespace App\Controllers\Api\Mobile;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Profile extends ResourceController
{
    protected $format = 'json';

    /**
     * GET /api/mobile/profile
     */
    public function index()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            return $this->fail(['message' => 'User tidak ditemukan.'], 404);
        }

        return $this->respond([
            'success' => true,
            'data'    => [
                'id'            => $user['id'],
                'name'          => $user['name'],
                'email'         => $user['email'],
                'phone'         => $user['phone'] ?? '',
                'address'       => $user['address'] ?? '',
                'avatar'        => !empty($user['avatar']) ? base_url('file/' . $user['avatar']) : base_url('images/default-avatar.png'),
                'level_id'      => $user['level_id'] ?? 1,
                'is_pro'        => (bool)($user['is_pro'] ?? false),
                'kyc_status'    => $user['kyc_status'] ?? 'not_submitted',
                'code_referral' => $user['code_referral'] ?? '',
                'pro_expires_at'=> $user['pro_expires_at'] ?? null,
                'created_at'    => $user['created_at'],
            ],
        ]);
    }

    /**
     * PUT /api/mobile/profile
     * Body: { name?, phone?, address? }
     */
    public function update($id = null)
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $json = $this->request->getJSON(true) ?? $this->request->getRawInput();

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            return $this->fail(['message' => 'User tidak ditemukan.'], 404);
        }

        $allowedFields = ['name', 'phone', 'address'];
        $updateData    = [];

        foreach ($allowedFields as $field) {
            if (isset($json[$field]) && !empty(trim($json[$field]))) {
                $updateData[$field] = trim($json[$field]);
            }
        }

        if (empty($updateData)) {
            return $this->fail(['message' => 'Tidak ada data yang diupdate.'], 422);
        }

        $userModel->update($userId, $updateData);

        $updatedUser = $userModel->find($userId);

        return $this->respond([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'id'      => $updatedUser['id'],
                'name'    => $updatedUser['name'],
                'phone'   => $updatedUser['phone'] ?? '',
                'address' => $updatedUser['address'] ?? '',
            ],
        ]);
    }

    /**
     * POST /api/mobile/profile/avatar
     * Upload avatar (multipart/form-data: avatar=<file>)
     */
    public function avatar()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $file = $this->request->getFile('avatar');

        if (!$file || !$file->isValid()) {
            return $this->fail(['message' => 'File avatar tidak valid.'], 422);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->fail(['message' => 'Format file harus JPG, PNG, atau WebP.'], 422);
        }

        if ($file->getSizeByUnit('mb') > 2) {
            return $this->fail(['message' => 'Ukuran file maksimal 2MB.'], 422);
        }

        $newName  = 'avatar_' . $userId . '_' . time() . '.' . $file->getExtension();
        $savePath = WRITEPATH . 'uploads/avatars/';

        if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
        }

        $file->move($savePath, $newName);

        $avatarPath = 'uploads/avatars/' . $newName;

        $userModel = new UserModel();
        $userModel->update($userId, ['avatar' => $avatarPath]);

        return $this->respond([
            'success'    => true,
            'message'    => 'Avatar berhasil diupload.',
            'avatar_url' => base_url('file/' . $avatarPath),
        ]);
    }

    /**
     * POST /api/mobile/profile/change-password
     * Body: { current_password, new_password }
     */
    public function changePassword()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $json = $this->request->getJSON(true);

        $currentPassword = $json['current_password'] ?? '';
        $newPassword     = $json['new_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            return $this->fail(['message' => 'Password lama dan baru wajib diisi.'], 422);
        }

        if (strlen($newPassword) < 6) {
            return $this->fail(['message' => 'Password baru minimal 6 karakter.'], 422);
        }

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!password_verify($currentPassword, $user['password'])) {
            return $this->fail(['message' => 'Password lama salah.'], 401);
        }

        $userModel->update($userId, ['password' => $newPassword]); // hashPassword callback will handle hashing

        return $this->respond([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }
}
