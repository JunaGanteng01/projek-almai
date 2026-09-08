<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpertAdvisorModel extends Model
{
    protected $table = 'expert_advisor';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_layanan',
        'penjelasan_layanan',
        'jml_klien',
        'produk',
        'wpa_id',
        'cwpa_id',
        'winning_rate',
        'tanggal',
        'keterangan',
        'format_notif_wa',
        'kode_qr',
        'link_absensi',
        'expired_link_kode_qr',
        'created_by_admin_id',
        'banner_image',
        'created_by_wpa_id',
        'created_by_cwpa_id',
        'registration_roles'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getPaginatedWithWpa($perPage = 20, $search = null, $wpaId = null)
    {
        $builder = $this->select('expert_advisor.*, wpa.name as wpa_name')
                        ->join('wpa', 'wpa.id = expert_advisor.wpa_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('expert_advisor.nama_layanan', $search)
                    ->orLike('expert_advisor.penjelasan_layanan', $search)
                    ->orLike('expert_advisor.produk', $search)
                    ->orLike('expert_advisor.keterangan', $search)
                    ->groupEnd();
        }

        if (!empty($wpaId) && $wpaId !== 'all') {
            $builder->where('expert_advisor.wpa_id', $wpaId);
        }

        return $this->orderBy('expert_advisor.tanggal', 'DESC')->paginate($perPage, 'default');
    }
}
