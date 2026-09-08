<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananCompletionModel extends Model
{
    protected $table = 'layanan_completions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'user_id',
        'layanan_id',
        'layanan_type',
        'transaksi_id',
        'completed_at',
        'certificate_id',
        'notes',
    ];
    
    protected $useTimestamps = true;

    /**
     * Check if user has completed a layanan
     */
    public function isCompleted($userId, $layananId, $layananType = 'event')
    {
        return $this->where('user_id', $userId)
                    ->where('layanan_id', $layananId)
                    ->where('layanan_type', $layananType)
                    ->first() !== null;
    }

    /**
     * Get completion record
     */
    public function getCompletion($userId, $layananId, $layananType = 'event')
    {
        return $this->where('user_id', $userId)
                    ->where('layanan_id', $layananId)
                    ->where('layanan_type', $layananType)
                    ->first();
    }

    /**
     * Mark layanan as completed
     */
    public function markComplete($userId, $layananId, $layananType = 'event', $transaksiId = null, $notes = null)
    {
        // Check if already completed
        if ($this->isCompleted($userId, $layananId, $layananType)) {
            return $this->getCompletion($userId, $layananId, $layananType);
        }

        $data = [
            'user_id' => $userId,
            'layanan_id' => $layananId,
            'layanan_type' => $layananType,
            'transaksi_id' => $transaksiId,
            'completed_at' => date('Y-m-d H:i:s'),
            'notes' => $notes,
        ];

        $this->insert($data);
        return $this->find($this->getInsertID());
    }

    /**
     * Get user's completed layanans
     */
    public function getUserCompletions($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('completed_at', 'DESC')
                    ->findAll();
    }

    /**
     * Update completion with certificate ID
     */
    public function setCertificate($completionId, $certificateId)
    {
        return $this->update($completionId, ['certificate_id' => $certificateId]);
    }
}
