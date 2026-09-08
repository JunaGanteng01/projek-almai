<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table = 'certificates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'certificate_number',
        'user_id',
        'layanan_id',
        'layanan_type',
        'layanan_name',
        'user_name',
        'wpa_name',
        'completion_id',
        'issued_at',
        'pdf_path',
    ];
    
    protected $useTimestamps = true;

    /**
     * Generate unique certificate number
     */
    public function generateCertificateNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Get count of certificates this month
        $count = $this->where('certificate_number LIKE', "ALMAI-CERT-{$year}{$month}%")
                      ->countAllResults();
        
        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        
        return "ALMAI-CERT-{$year}{$month}{$sequence}";
    }

    /**
     * Create certificate for completion
     */
    public function createCertificate($userId, $layananId, $layananType, $layananName, $userName, $wpaName = null, $completionId = null)
    {
        // Check if certificate already exists
        $existing = $this->where('user_id', $userId)
                         ->where('layanan_id', $layananId)
                         ->where('layanan_type', $layananType)
                         ->first();
        
        if ($existing) {
            return $existing;
        }

        $data = [
            'certificate_number' => $this->generateCertificateNumber(),
            'user_id' => $userId,
            'layanan_id' => $layananId,
            'layanan_type' => $layananType,
            'layanan_name' => $layananName,
            'user_name' => $userName,
            'wpa_name' => $wpaName,
            'completion_id' => $completionId,
            'issued_at' => date('Y-m-d H:i:s'),
        ];

        $this->insert($data);
        return $this->find($this->getInsertID());
    }

    /**
     * Get user's certificates
     */
    public function getUserCertificates($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('issued_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get certificate by number
     */
    public function findByNumber($certificateNumber)
    {
        return $this->where('certificate_number', $certificateNumber)->first();
    }

    /**
     * Get certificate for specific layanan
     */
    public function getCertificateForLayanan($userId, $layananId, $layananType = 'event')
    {
        return $this->where('user_id', $userId)
                    ->where('layanan_id', $layananId)
                    ->where('layanan_type', $layananType)
                    ->first();
    }
}
