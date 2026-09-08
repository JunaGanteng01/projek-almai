<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtLibrary
{
    private string $secretKey;
    private string $algorithm = 'HS256';
    private int $accessTokenExpiry = 900;      // 15 menit
    private int $refreshTokenExpiry = 2592000; // 30 hari

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET_KEY', 'wpa-platform-jwt-secret-2024');
    }

    /**
     * Generate access token (short-lived, 15 menit)
     */
    public function generateAccessToken(array $user): string
    {
        $payload = [
            'iss'        => base_url(),
            'aud'        => 'wpa-mobile',
            'iat'        => time(),
            'exp'        => time() + $this->accessTokenExpiry,
            'type'       => 'access',
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'level_id'   => $user['level_id'] ?? 1,
            'is_pro'     => $user['is_pro'] ?? 0,
            'kyc_status' => $user['kyc_status'] ?? 'pending',
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Generate refresh token (long-lived, 30 hari)
     */
    public function generateRefreshToken(int $userId): string
    {
        $payload = [
            'iss'     => base_url(),
            'iat'     => time(),
            'exp'     => time() + $this->refreshTokenExpiry,
            'type'    => 'refresh',
            'user_id' => $userId,
            'jti'     => bin2hex(random_bytes(16)), // unique token ID
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validate and decode a token
     * Returns decoded payload or throws Exception
     */
    public function validateToken(string $token): object
    {
        return JWT::decode($token, new Key($this->secretKey, $this->algorithm));
    }

    /**
     * Validate token and return as array, or return null if invalid
     */
    public function getPayload(string $token): ?array
    {
        try {
            $decoded = $this->validateToken($token);
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Extract bearer token from Authorization header
     */
    public function extractBearerToken(string $authHeader): ?string
    {
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get remaining seconds until token expiry
     */
    public function getTokenExpiry(string $token): int
    {
        $payload = $this->getPayload($token);
        if (!$payload || !isset($payload['exp'])) return 0;
        return max(0, $payload['exp'] - time());
    }
}
