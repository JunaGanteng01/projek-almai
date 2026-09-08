<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanLainnyaModel extends Model
{
    protected $table = 'kegiatan_lainnya';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kegiatan',
        'jml_klien',
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
        $builder = $this->select('kegiatan_lainnya.*, wpa.name as wpa_name')
                        ->join('wpa', 'wpa.id = kegiatan_lainnya.wpa_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('kegiatan_lainnya.nama_kegiatan', $search)
                    ->orLike('kegiatan_lainnya.produk', $search)
                    ->orLike('kegiatan_lainnya.media', $search)
                    ->orLike('kegiatan_lainnya.keterangan', $search)
                    ->groupEnd();
        }

        if (!empty($wpaId) && $wpaId !== 'all') {
            $builder->where('kegiatan_lainnya.wpa_id', $wpaId);
        }

        return $this->orderBy('kegiatan_lainnya.tanggal', 'DESC')->paginate($perPage, 'default');
    }
}
