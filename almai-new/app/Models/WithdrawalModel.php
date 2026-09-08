<?php

namespace App\Models;

use CodeIgniter\Model;

class WithdrawalModel extends Model
{
    protected $table = 'withdrawals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wpa_id', 'cwpa_id', 'user_id', 'amount', 'bank_name', 'account_number', 'account_holder',
        'status', 'admin_notes'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getPendingTotal($wpaId)
    {
        return $this->where('wpa_id', $wpaId)
                    ->whereIn('status', ['pending', 'approved'])
                    ->selectSum('amount')
                    ->first()['amount'] ?? 0;
    }

    public function getCompletedTotal($wpaId)
    {
        return $this->where('wpa_id', $wpaId)
                    ->where('status', 'completed')
                    ->selectSum('amount')
                    ->first()['amount'] ?? 0;
    }


    public function getPendingTotalCwpa($cwpaId)
    {
        if (!$this->db->fieldExists('cwpa_id', $this->table)) {
            return 0;
        }
        return $this->where('cwpa_id', $cwpaId)
                    ->whereIn('status', ['pending', 'approved'])
                    ->selectSum('amount')
                    ->first()['amount'] ?? 0;
    }

    public function getCompletedTotalCwpa($cwpaId)
    {
        if (!$this->db->fieldExists('cwpa_id', $this->table)) {
            return 0;
        }
        return $this->where('cwpa_id', $cwpaId)
                    ->where('status', 'completed')
                    ->selectSum('amount')
                    ->first()['amount'] ?? 0;
    }

    public function getPendingTotalUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->whereIn('status', ['pending', 'approved'])
                    ->selectSum('amount')
                    ->first()['amount'] ?? 0;
    }

    public function getCompletedTotalUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('status', 'completed')
                    ->selectSum('amount')
                    ->first()['amount'] ?? 0;
    }
}
