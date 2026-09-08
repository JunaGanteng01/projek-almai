<?php

namespace App\Models;

use CodeIgniter\Model;

class KonsultasiModel extends Model
{
    protected $table = 'konsultasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_klien',
        'jml_nasihat',
        'produk',
        'wpa_id',
        'cwpa_id',
        'media',
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
        $builder = $this->select('konsultasi.*, wpa.name as wpa_name')
                        ->join('wpa', 'wpa.id = konsultasi.wpa_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('konsultasi.nama_klien', $search)
                    ->orLike('konsultasi.produk', $search)
                    ->orLike('konsultasi.media', $search)
                    ->orLike('konsultasi.keterangan', $search)
                    ->groupEnd();
        }

        if (!empty($wpaId) && $wpaId !== 'all') {
            $builder->where('konsultasi.wpa_id', $wpaId);
        }

        return $this->orderBy('konsultasi.tanggal', 'DESC')->paginate($perPage, 'default');
    }
}
