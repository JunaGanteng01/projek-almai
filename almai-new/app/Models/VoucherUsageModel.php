<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherUsageModel extends Model
{
    protected $table = 'voucher_usages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'voucher_id', 'user_id', 'transaksi_id', 'discount_amount'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Get usage with details
     */
    public function getUsageWithDetails($voucherId)
    {
        return $this->select('voucher_usages.*, users.name as user_name, users.email as user_email, transaksi.invoice_number')
                    ->join('users', 'users.id = voucher_usages.user_id', 'left')
                    ->join('transaksi', 'transaksi.id = voucher_usages.transaksi_id', 'left')
                    ->where('voucher_usages.voucher_id', $voucherId)
                    ->orderBy('voucher_usages.created_at', 'DESC')
                    ->findAll();
    }
}
