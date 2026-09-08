<?php

namespace App\Models;

use CodeIgniter\Model;

class DokBahanKegiatanModel extends Model
{
    protected $table = 'dok_bahan_kegiatan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'judul_bahan',
        'jenis_kegiatan',
        'tgl_pengajuan',
        'tgl_persetujuan',
        'no_surat_persetujuan',
        'status',
        'file_path'
    ];
}
