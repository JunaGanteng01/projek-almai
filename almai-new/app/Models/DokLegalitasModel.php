<?php

namespace App\Models;

use CodeIgniter\Model;

class DokLegalitasModel extends Model
{
    protected $table = 'dok_legalitas_perusahaan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nama_dokumen',
        'nomor_dokumen',
        'tanggal_terbit',
        'tanggal_expired',
        'diterbitkan_oleh',
        'status',
        'file_path',
        'keterangan'
    ];
}
