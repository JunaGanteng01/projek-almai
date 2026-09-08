<?php

namespace App\Models;

use CodeIgniter\Model;

class PoinSharingModel extends Model
{
    protected $table = 'poin_sharing';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'share_code', 'sender_id', 'receiver_id', 'package_id',
        'poin_amount', 'price', 'bank_name', 'bank_account_name',
        'bank_account_number', 'transfer_proof', 'status', 'notes',
        'expired_at', 'paid_at', 'verified_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Generate unique share code
     */
    public function generateShareCode()
    {
        do {
            $code = 'PS' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        } while ($this->where('share_code', $code)->first());
        
        return $code;
    }

    /**
     * Get sharing by code
     */
    public function getByCode($code)
    {
        return $this->select('poin_sharing.*, users.name as sender_name, users.phone as sender_phone, users.email as sender_email')
                    ->join('users', 'users.id = poin_sharing.sender_id', 'left')
                    ->where('poin_sharing.share_code', $code)
                    ->first();
    }

    /**
     * Get user's sent sharings
     */
    public function getSentSharings($userId)
    {
        return $this->select('poin_sharing.*, u2.name as receiver_name, u2.email as receiver_email')
                    ->join('users u2', 'u2.id = poin_sharing.receiver_id', 'left')
                    ->where('poin_sharing.sender_id', $userId)
                    ->orderBy('poin_sharing.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get user's received sharings
     */
    public function getReceivedSharings($userId)
    {
        return $this->select('poin_sharing.*, users.name as sender_name, users.phone as sender_phone')
                    ->join('users', 'users.id = poin_sharing.sender_id', 'left')
                    ->where('poin_sharing.receiver_id', $userId)
                    ->orderBy('poin_sharing.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get pending sharings for verification
     */
    public function getPendingVerification($userId)
    {
        return $this->select('poin_sharing.*, users.name as receiver_name, users.email as receiver_email')
                    ->join('users', 'users.id = poin_sharing.receiver_id', 'left')
                    ->where('poin_sharing.sender_id', $userId)
                    ->where('poin_sharing.status', 'paid')
                    ->orderBy('poin_sharing.paid_at', 'DESC')
                    ->findAll();
    }
}
