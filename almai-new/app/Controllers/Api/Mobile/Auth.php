<?php

namespace App\Controllers\Api\Mobile;

use App\Libraries\JwtLibrary;
use App\Models\UserModel;
use App\Models\RefreshTokenModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Auth extends ResourceController
{
    protected $format = 'json';

    private JwtLibrary $jwt;
    private UserModel $userModel;
    private RefreshTokenModel $refreshTokenModel;

    public function __construct()
    {
        $this->jwt               = new JwtLibrary();
        $this->userModel         = new UserModel();
        $this->refreshTokenModel = new RefreshTokenModel();
    }

    /**
     * POST /api/mobile/auth/login
     * Body: { email, password }
     */
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 422);
        }

        $email    = $this->request->getJSON()->email ?? $this->request->getPost('email');
        $password = $this->request->getJSON()->password ?? $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->fail(['message' => 'Email atau password salah.'], 401);
        }

        if (($user['status'] ?? 'active') === 'banned') {
            return $this->fail(['message' => 'Akun Anda telah dinonaktifkan.'], 403);
        }

        $accessToken  = $this->jwt->generateAccessToken($user);
        $refreshToken = $this->jwt->generateRefreshToken($user['id']);

        $this->refreshTokenModel->saveToken($user['id'], $refreshToken);

        return $this->respond([
            'success' => true,
            'message' => 'Login berhasil!',
            'data'    => [
                'user'          => $this->formatUser($user),
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in'    => 900,
            ],
        ]);
    }

    /**
     * POST /api/mobile/auth/register
     * Body: { name, email, password, phone, referral_code? }
     */
    public function register()
    {
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'phone'    => 'required|min_length[9]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 422);
        }

        $json = $this->request->getJSON();

        $data = [
            'name'              => $json->name ?? $this->request->getPost('name'),
            'email'             => $json->email ?? $this->request->getPost('email'),
            'password'          => $json->password ?? $this->request->getPost('password'),
            'phone'             => $json->phone ?? $this->request->getPost('phone'),
            'level_id'          => 1,
            'status'            => 'active',
            'registration_types'=> 'mobile',
        ];

        // Handle referral code
        $referralCode = $json->referral_code ?? $this->request->getPost('referral_code');
        if ($referralCode) {
            $referrer = $this->userModel->findByReferralCode($referralCode);
            if ($referrer) {
                $data['affiliator_code'] = $referralCode;
            }
        }

        $userId = $this->userModel->insert($data);

        if (!$userId) {
            return $this->fail(['message' => 'Gagal membuat akun. Coba lagi.'], 500);
        }

        $user         = $this->userModel->find($userId);
        $accessToken  = $this->jwt->generateAccessToken($user);
        $refreshToken = $this->jwt->generateRefreshToken($user['id']);

        $this->refreshTokenModel->saveToken($user['id'], $refreshToken);

        return $this->respondCreated([
            'success' => true,
            'message' => 'Akun berhasil dibuat!',
            'data'    => [
                'user'          => $this->formatUser($user),
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in'    => 900,
            ],
        ]);
    }

    /**
     * POST /api/mobile/auth/refresh
     * Body: { refresh_token }
     */
    public function refresh()
    {
        $json         = $this->request->getJSON();
        $refreshToken = $json->refresh_token ?? $this->request->getPost('refresh_token');

        if (!$refreshToken) {
            return $this->fail(['message' => 'Refresh token diperlukan.'], 422);
        }

        // Cek di DB
        $tokenRecord = $this->refreshTokenModel->findValidToken($refreshToken);
        if (!$tokenRecord) {
            return $this->fail(['message' => 'Refresh token tidak valid atau sudah expired.'], 401);
        }

        // Validasi JWT-nya
        $payload = $this->jwt->getPayload($refreshToken);
        if (!$payload || ($payload['type'] ?? '') !== 'refresh') {
            return $this->fail(['message' => 'Token tidak valid.'], 401);
        }

        $user = $this->userModel->find($tokenRecord['user_id']);
        if (!$user) {
            return $this->fail(['message' => 'User tidak ditemukan.'], 404);
        }

        // Rotate: revoke lama, buat baru
        $this->refreshTokenModel->revokeToken($refreshToken);
        $newAccessToken  = $this->jwt->generateAccessToken($user);
        $newRefreshToken = $this->jwt->generateRefreshToken($user['id']);
        $this->refreshTokenModel->saveToken($user['id'], $newRefreshToken);

        return $this->respond([
            'success'       => true,
            'access_token'  => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'expires_in'    => 900,
        ]);
    }

    /**
     * POST /api/mobile/auth/logout
     * Header: Authorization: Bearer <access_token>
     * Body: { refresh_token }
     */
    public function logout()
    {
        $json         = $this->request->getJSON();
        $refreshToken = $json->refresh_token ?? $this->request->getPost('refresh_token');

        if ($refreshToken) {
            $this->refreshTokenModel->revokeToken($refreshToken);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Format user data yang aman untuk dikembalikan ke Flutter
     */
    private function formatUser(array $user): array
    {
        return [
            'id'         => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'phone'      => $user['phone'] ?? '',
            'avatar'     => !empty($user['avatar']) ? base_url('file/' . $user['avatar']) : base_url('images/default-avatar.png'),
            'level_id'   => $user['level_id'] ?? 1,
            'is_pro'     => (bool)($user['is_pro'] ?? false),
            'kyc_status' => $user['kyc_status'] ?? 'pending',
            'code_referral' => $user['code_referral'] ?? '',
        ];
    }
}
