<?php

namespace App\Models;

use CodeIgniter\Model;

class RefreshTokenModel extends Model
{
    protected $table      = 'refresh_tokens';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'token',
        'expires_at',
        'revoked',
    ];

    protected $useTimestamps = true;
    protected $returnType    = 'array';

    /**
     * Save a new refresh token for a user
     */
    public function saveToken(int $userId, string $token, int $expirySeconds = 2592000): bool
    {
        return $this->insert([
            'user_id'    => $userId,
            'token'      => $token,
            'expires_at' => date('Y-m-d H:i:s', time() + $expirySeconds),
            'revoked'    => 0,
        ]) !== false;
    }

    /**
     * Find a valid (not revoked, not expired) refresh token
     */
    public function findValidToken(string $token): ?array
    {
        return $this->where('token', $token)
                    ->where('revoked', 0)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Revoke a specific token
     */
    public function revokeToken(string $token): bool
    {
        return $this->where('token', $token)->set(['revoked' => 1])->update() !== false;
    }

    /**
     * Revoke all tokens for a user (logout all devices)
     */
    public function revokeAllForUser(int $userId): bool
    {
        return $this->where('user_id', $userId)->set(['revoked' => 1])->update() !== false;
    }

    /**
     * Clean up expired tokens (run periodically)
     */
    public function cleanExpired(): int
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
