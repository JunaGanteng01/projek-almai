<?php

namespace App\Models;

use CodeIgniter\Model;

class SeminarModel extends Model
{
    protected $table = 'seminar_fgd';
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
        $builder = $this->select('seminar_fgd.*, wpa.name as wpa_name')
                        ->join('wpa', 'wpa.id = seminar_fgd.wpa_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('seminar_fgd.judul', $search)
                    ->orLike('seminar_fgd.topik', $search)
                    ->orLike('seminar_fgd.lokasi', $search)
                    ->orLike('seminar_fgd.produk', $search)
                    ->groupEnd();
        }

        if (!empty($wpaId) && $wpaId !== 'all') {
            $builder->where('seminar_fgd.wpa_id', $wpaId);
        }

        return $this->orderBy('seminar_fgd.tanggal', 'DESC')->paginate($perPage, 'default');
    }
}
