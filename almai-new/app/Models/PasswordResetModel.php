<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'token', 'expires_at', 'used', 'created_at'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Create a password reset token
     */
    public function createToken(string $email): string
    {
        // Invalidate any existing tokens for this email
        $this->where('email', $email)->where('used', 0)->set(['used' => 1])->update();

        // Generate new token
        $token = bin2hex(random_bytes(32));
        
        $this->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'used' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    /**
     * Validate a password reset token
     */
    public function validateToken(string $token): ?array
    {
        $reset = $this->where('token', $token)
                      ->where('used', 0)
                      ->where('expires_at >', date('Y-m-d H:i:s'))
                      ->first();

        return $reset;
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(string $token): bool
    {
        return $this->where('token', $token)->set(['used' => 1])->update();
    }

    /**
     * Clean up expired tokens
     */
    public function cleanExpired(): int
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
