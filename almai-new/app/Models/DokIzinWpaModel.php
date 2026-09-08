<?php

namespace App\Models;

use CodeIgniter\Model;

class DokIzinWpaModel extends Model
{
    protected $table = 'dok_izin_wpa';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'wpa_id',
        'nomor_izin',
        'tanggal_izin',
        'tanggal_expired',
        'gelar_sertifikat',
        'status',
        'catatan'
    ];
}
