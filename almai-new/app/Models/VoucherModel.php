<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'code', 'name', 'description', 'discount_type', 'discount_value',
        'min_purchase', 'max_discount', 'usage_limit', 'used_count',
        'per_user_limit', 'product_type', 'product_ids', 'start_date',
        'end_date', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Validate voucher code
     */
    public function validateVoucher($code, $userId, $productType, $productId, $amount)
    {
        $voucher = $this->where('code', strtoupper($code))
                        ->where('status', 'active')
                        ->first();

        if (!$voucher) {
            return ['valid' => false, 'message' => 'Kode voucher tidak ditemukan'];
        }

        // Check date validity
        $now = time();
        if (!empty($voucher['start_date'])) {
            $startTime = strtotime($voucher['start_date']);
            if ($startTime && $startTime > $now) {
                return ['valid' => false, 'message' => 'Voucher belum aktif (mulai ' . date('d M Y', $startTime) . ')'];
            }
        }
        if (!empty($voucher['end_date'])) {
            $endTime = strtotime($voucher['end_date']);
            if ($endTime && $endTime < $now) {
                return ['valid' => false, 'message' => 'Voucher sudah kadaluarsa'];
            }
        }

        // Check usage limit
        if ($voucher['usage_limit'] && $voucher['used_count'] >= $voucher['usage_limit']) {
            return ['valid' => false, 'message' => 'Voucher sudah habis digunakan'];
        }

        // Check per user limit (only if user is logged in)
        if ($userId) {
            $usageModel = new VoucherUsageModel();
            $userUsage = $usageModel->where('voucher_id', $voucher['id'])
                                    ->where('user_id', $userId)
                                    ->countAllResults();
            if ($userUsage >= $voucher['per_user_limit']) {
                return ['valid' => false, 'message' => 'Anda sudah menggunakan voucher ini'];
            }
        }

        // Check product type
        if ($voucher['product_type'] !== 'all' && $voucher['product_type'] !== $productType) {
            log_message('debug', "Voucher type mismatch: voucher={$voucher['product_type']}, product={$productType}");
            return ['valid' => false, 'message' => 'Voucher tidak berlaku untuk produk ini'];
        }

        // Check specific products
        if ($voucher['product_ids']) {
            $allowedProducts = json_decode($voucher['product_ids'], true);
            if (!empty($allowedProducts)) {
                // Convert both to integers for comparison
                $allowedProductsInt = array_map('intval', $allowedProducts);
                $productIdInt = intval($productId);
                
                log_message('debug', "Voucher product check: allowed=" . json_encode($allowedProductsInt) . ", checking={$productIdInt}");
                
                if (!in_array($productIdInt, $allowedProductsInt)) {
                    log_message('debug', "Product ID {$productIdInt} not in allowed list");
                    return ['valid' => false, 'message' => 'Voucher tidak berlaku untuk produk ini'];
                }
            }
        }

        // Check minimum purchase
        if ($amount < $voucher['min_purchase']) {
            return [
                'valid' => false, 
                'message' => 'Minimum pembelian Rp ' . number_format($voucher['min_purchase'], 0, ',', '.')
            ];
        }

        // Calculate discount
        $discount = $this->calculateDiscount($voucher, $amount);

        return [
            'valid' => true,
            'voucher' => $voucher,
            'discount' => $discount,
            'message' => 'Voucher berhasil diterapkan'
        ];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($voucher, $amount)
    {
        if ($voucher['discount_type'] === 'percentage') {
            $discount = ($amount * $voucher['discount_value']) / 100;
            // Apply max discount cap
            if ($voucher['max_discount'] && $discount > $voucher['max_discount']) {
                $discount = $voucher['max_discount'];
            }
        } else {
            $discount = $voucher['discount_value'];
        }

        // Discount cannot exceed amount
        if ($discount > $amount) {
            $discount = $amount;
        }

        return $discount;
    }

    /**
     * Use voucher (increment counter)
     */
    public function useVoucher($voucherId, $userId, $transaksiId, $discountAmount)
    {
        // Ensure all values are proper types
        $voucherId = (int) $voucherId;
        $userId = (int) $userId;
        $transaksiId = (int) $transaksiId;
        $discountAmount = (float) $discountAmount;
        
        // Increment used count using raw query
        $db = \Config\Database::connect();
        $db->query("UPDATE vouchers SET used_count = used_count + 1 WHERE id = ?", [$voucherId]);

        // Record usage
        $usageModel = new VoucherUsageModel();
        $usageModel->insert([
            'voucher_id' => (int) $voucherId,
            'user_id' => (int) $userId,
            'transaksi_id' => (int) $transaksiId,
            'discount_amount' => (float) $discountAmount,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    /**
     * Generate unique voucher code
     */
    public function generateCode($prefix = 'ALMAI')
    {
        do {
            $code = $prefix . strtoupper(substr(md5(uniqid()), 0, 6));
        } while ($this->where('code', $code)->first());

        return $code;
    }
}
