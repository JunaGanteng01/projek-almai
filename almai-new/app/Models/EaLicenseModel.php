<?php

namespace App\Models;

use CodeIgniter\Model;

class EaLicenseModel extends Model
{
    protected $table            = 'ea_licenses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'user_id',
        'broker_name',
        'account_trading_number',
        'license_key',
        'status',
        'license_activated_at',
        'expires_at',
        'license_email_sent',
        'license_email_sent_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function validateLicense($licenseKey, $accountNumber)
    {
        $license = $this->where('license_key', $licenseKey)->first();

        if (!$license) {
            return ['valid' => false, 'message' => 'License Key tidak ditemukan'];
        }
        
        // Cek Status (status harus aktif)
        if (isset($license['status']) && $license['status'] !== 'active') {
             return ['valid' => false, 'message' => 'Lisensi tidak aktif (' . $license['status'] . ')'];
        }

        // Cek Expiration (jika tidak null)
        if (!empty($license['expires_at'])) {
            $now = time();
            $expiry = strtotime($license['expires_at']);
            if ($now > $expiry) {
                // Update status to expired if needed, or just return false
                return ['valid' => false, 'message' => 'Masa berlaku lisensi telah habis'];
            }
        }

        // Check if account number matches
        // Explicitly cast to string for comparison to avoid type issues
        if ((string)$license['account_trading_number'] !== (string)$accountNumber) {
            return ['valid' => false, 'message' => 'License Key tidak valid untuk akun ini'];
        }
        
        // Update Activation Date if first time
        if (empty($license['license_activated_at'])) {
            $this->update($license['id'], ['license_activated_at' => date('Y-m-d H:i:s')]);
        }
        
        $typeName = 'Lifetime';
        if (!empty($license['expires_at'])) {
             $daysLeft = ceil((strtotime($license['expires_at']) - time()) / 86400);
             $typeName = $daysLeft . ' Hari Tersisa';
        }

        return [
            'valid' => true,
            'message' => 'Lisensi Valid',
            'data' => [
                'status' => 'Active',
                'type' => $typeName,
                'expires_at' => $license['expires_at'],
                'owner' => $license['user_id'] // Or other info
            ]
        ];
    }
}
