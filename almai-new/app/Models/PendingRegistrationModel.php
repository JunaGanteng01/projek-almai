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
        'referral_source',
        'device_info',
        'phone',
        'status',
        'user_id',
        'ip_address',
        'created_at',
        'updated_at',
        'expires_at',
        'verified_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Auto-ensure table pending_registrations exists in MySQL on the fly
     */
    public function ensureTableExists()
    {
        try {
            $db = \Config\Database::connect();
            if (!$db->tableExists('pending_registrations')) {
                $forge = \Config\Database::forge();
                $forge->addField([
                    'id' => [
                        'type'           => 'INT',
                        'constraint'     => 11,
                        'unsigned'       => true,
                        'auto_increment' => true,
                    ],
                    'registration_token' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 64,
                        'null'       => false,
                    ],
                    'name' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'null'       => false,
                    ],
                    'email' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'null'       => false,
                    ],
                    'password' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'null'       => false,
                    ],
                    'affiliator_code' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 50,
                        'null'       => false,
                    ],
                    'referral_source' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 50,
                        'null'       => true,
                        'default'    => null,
                    ],
                    'device_info' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'null'       => true,
                        'default'    => null,
                    ],
                    'phone' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 32,
                        'null'       => true,
                        'default'    => null,
                    ],
                    'status' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 32,
                        'null'       => false,
                        'default'    => 'PENDING_VERIFICATION',
                    ],
                    'user_id' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'null'       => true,
                        'default'    => null,
                    ],
                    'ip_address' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 45,
                        'null'       => true,
                        'default'    => null,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                        'default' => null,
                    ],
                    'expires_at' => [
                        'type' => 'DATETIME',
                        'null' => false,
                    ],
                    'verified_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                        'default' => null,
                    ],
                ]);

                $forge->addKey('id', true);
                $forge->addUniqueKey('registration_token');
                $forge->addKey('email');
                $forge->addKey('status');

                $forge->createTable('pending_registrations', true);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Ensure Table Exists Failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique registration token and store pending registration
     */
    public function createPendingRegistration(array $data): string
    {
        $this->ensureTableExists();

        $token = 'REG-' . strtoupper(bin2hex(random_bytes(6)));
        
        try {
            $this->where('email', strtolower(trim($data['email'])))
                 ->where('status', 'PENDING_VERIFICATION')
                 ->delete();
        } catch (\Throwable $e) {
            // Ignore if clean up fails
        }

        $insertData = [
            'registration_token' => $token,
            'name'               => trim($data['name']),
            'email'              => strtolower(trim($data['email'])),
            'password'           => password_hash($data['password'] ?? 'Almai123', PASSWORD_BCRYPT),
            'affiliator_code'    => strtoupper(trim($data['affiliator_code'])),
            'referral_source'    => $data['referral_source'] ?? 'MANUAL_INPUT',
            'device_info'        => $data['device_info'] ?? null,
            'status'             => 'PENDING_VERIFICATION',
            'ip_address'         => $data['ip_address'] ?? null,
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
            'expires_at'         => date('Y-m-d H:i:s', strtotime('+24 hours')),
        ];

        $this->insert($insertData);

        return $token;
    }

    /**
     * Verify token via WA Webhook
     */
    public function verifyTokenViaWa(string $token, string $phone): ?array
    {
        $this->ensureTableExists();

        $pending = $this->where('registration_token', $token)
                        ->where('status', 'PENDING_VERIFICATION')
                        ->where('expires_at >', date('Y-m-d H:i:s'))
                        ->first();

        if (!$pending) {
            return null;
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $this->update($pending['id'], [
            'phone'       => $cleanPhone,
            'status'      => 'VERIFIED',
            'updated_at'  => date('Y-m-d H:i:s'),
            'verified_at' => date('Y-m-d H:i:s')
        ]);

        $pending['phone'] = $cleanPhone;
        return $pending;
    }

    /**
     * Check status for Polling
     */
    public function checkStatus(string $token): array
    {
        $this->ensureTableExists();

        $pending = $this->where('registration_token', $token)->first();

        if (!$pending) {
            return ['status' => 'NOT_FOUND'];
        }

        if ($pending['status'] === 'PENDING_VERIFICATION' && strtotime($pending['expires_at']) < time()) {
            $this->update($pending['id'], [
                'status'     => 'EXPIRED',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return ['status' => 'EXPIRED'];
        }

        return [
            'status'  => $pending['status'],
            'user_id' => $pending['user_id'] ?? null
        ];
    }
}
