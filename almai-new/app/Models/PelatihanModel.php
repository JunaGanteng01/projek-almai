<?php

namespace App\Models;

use CodeIgniter\Model;

class PelatihanModel extends Model
{
    protected $table = 'pelatihan_simulasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul',
        'jml_peserta',
        'produk',
        'wpa_id',
        'cwpa_id',
        'lokasi',
        'tanggal',
        'topik',
        'kode_qr',
        'link_absensi',
        'expired_link_kode_qr',
        'keterangan',
        'format_notif_wa',
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
        $builder = $this->select('pelatihan_simulasi.*, wpa.name as wpa_name')
                        ->join('wpa', 'wpa.id = pelatihan_simulasi.wpa_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('pelatihan_simulasi.judul', $search)
                    ->orLike('pelatihan_simulasi.topik', $search)
                    ->orLike('pelatihan_simulasi.lokasi', $search)
                    ->orLike('pelatihan_simulasi.produk', $search)
                    ->groupEnd();
        }

        if (!empty($wpaId) && $wpaId !== 'all') {
            $builder->where('pelatihan_simulasi.wpa_id', $wpaId);
        }

        return $this->orderBy('pelatihan_simulasi.tanggal', 'DESC')->paginate($perPage, 'default');
    }
}
