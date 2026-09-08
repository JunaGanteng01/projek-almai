<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyProfileModel extends Model
{
    protected $table            = 'company_profile';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'identitas',
        'pejabat',
        'produk_layanan',
        'kualifikasi_pengguna',
        'penjelasan_wpa_cwpa'
    ];

    // Dates
    protected $useTimestamps = false; // We use updated_at via DB trigger/default

    public function getProfile()
    {
        $profile = $this->first();
        if ($profile) {
            $profile['identitas'] = json_decode($profile['identitas'], true) ?? [];
            $profile['pejabat'] = json_decode($profile['pejabat'], true) ?? [];
            $profile['produk_layanan'] = json_decode($profile['produk_layanan'], true) ?? [];
            $profile['kualifikasi_pengguna'] = json_decode($profile['kualifikasi_pengguna'], true) ?? [];
            $profile['penjelasan_wpa_cwpa'] = json_decode($profile['penjelasan_wpa_cwpa'], true) ?? [];
        }
        return $profile;
    }

    public function updateProfile($data)
    {
        // Encode arrays back to json before saving
        foreach (['identitas', 'pejabat', 'produk_layanan', 'kualifikasi_pengguna', 'penjelasan_wpa_cwpa'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }
        return $this->update(1, $data);
    }
}
