<?php

namespace App\Models;

use CodeIgniter\Model;

class SignalModel extends Model
{
    protected $table = 'signals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jml_peserta',
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
        $builder = $this->select('signals.*, wpa.name as wpa_name')
                        ->join('wpa', 'wpa.id = signals.wpa_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('signals.produk', $search)
                    ->orLike('signals.media', $search)
                    ->orLike('signals.keterangan', $search)
                    ->groupEnd();
        }

        if (!empty($wpaId) && $wpaId !== 'all') {
            $builder->where('signals.wpa_id', $wpaId);
        }

        return $this->orderBy('signals.tanggal', 'DESC')->paginate($perPage, 'default');
    }
}
