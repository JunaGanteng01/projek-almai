<?php

namespace App\Models;

use CodeIgniter\Model;

class DataPerusahaanModel extends Model
{
    protected $table            = 'data_perusahaan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_perusahaan',
        'nomor_izin',
        'alamat',
        'website',
        'direktur_utama',
        'status',
        'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
