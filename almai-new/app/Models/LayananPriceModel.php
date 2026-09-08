<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananPriceModel extends Model
{
    protected $table = 'layanan_prices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'layanan_type',
        'layanan_id',
        'name',
        'price',
        'poin_price',
        'original_price',
        'description',
        'features',
        'sort_order',
        'referral_user_cash',
        'referral_user_poin',
        'referral_wpa_cash',
        'referral_wpa_poin',
        'duration_days',
    ];
    protected $useTimestamps = true;

    public function getPackages($type, $id)
    {
        return $this->where('layanan_type', $type)
            ->where('layanan_id', $id)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
