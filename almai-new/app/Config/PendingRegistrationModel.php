<?php

namespace App\Models;

use CodeIgniter\Model;

class PendingRegistrationModel extends Model
{
    protected $table            = 'pending_registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'registration_token',
        'name',
        'email',
        'password',
        'affiliator_code',
        'phone',
        'status',
        'user_id',
        'ip_address',
        'created_at',
        'expires_at',
        'verified_at'
    ];

    protected $useTimestamps = false;

    /**
     * Generate unique registration token and store pending registration
     */
    public function createPendingRegistration(array $data): string
    {
        $token = 'REG-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        
        // Clean previous pending registrations with same email
        $this->where('email', $data['email'])
             ->where('status', 'PENDING_VERIFICATION')
             ->delete();

        $insertData = [
            'registration_token' => $token,
            'name'               => $data['name'],
            'email'              => $data['email'],
            'password'           => password_hash($data['password'], PASSWORD_DEFAULT),
            'affiliator_code'    => $data['affiliator_code'] ?? 'daftar-direct',
            'status'             => 'PENDING_VERIFICATION',
            'ip_address'         => $data['ip_address'] ?? null,
            'created_at'         => date('Y-m-d H:i:s'),
            'expires_at'         => date('Y-m-d H:i:s', strtotime('+30 minutes')),
        ];

        $this->insert($insertData);

        return $token;
    }

    /**
     * Verify token via WA Webhook
     */
    public function verifyTokenViaWa(string $token, string $phone): ?array
    {
        $pending = $this->where('registration_token', $token)
                        ->where('status', 'PENDING_VERIFICATION')
                        ->where('expires_at >', date('Y-m-d H:i:s'))
                        ->first();

        if (!$pending) {
            return null;
        }

        // Clean phone number format
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        // Update pending record to VERIFIED
        $this->update($pending['id'], [
            'phone'       => $cleanPhone,
            'status'      => 'VERIFIED',
            'verified_at' => date('Y-m-d H:i:s')
        ]);

        $pending['phone'] = $cleanPhone;
        return $pending;
    }

    /**
     * Check verification status (used by Polling)
     */
    public function checkStatus(string $token): array
    {
        $pending = $this->where('registration_token', $token)->first();

        if (!$pending) {
            return ['status' => 'NOT_FOUND'];
        }

        if ($pending['status'] === 'PENDING_VERIFICATION' && strtotime($pending['expires_at']) < time()) {
            $this->update($pending['id'], ['status' => 'EXPIRED']);
            return ['status' => 'EXPIRED'];
        }

        return [
            'status'  => $pending['status'],
            'user_id' => $pending['user_id'] ?? null
        ];
    }
}
