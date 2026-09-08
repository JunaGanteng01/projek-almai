<?php

namespace App\Models;

use CodeIgniter\Model;

class EntitasAkuntansiModel extends Model
{
    protected $table = 'entitas_akuntansi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_entitas',
        'nama_pendek',
        'alamat',
        'kota',
        'npwp',
        'telepon',
        'email',
        'mata_uang',
        'bulan_awal_fiskal',
        'catatan_laporan',
    ];
    protected $useTimestamps = true;

    public function getProfile(): ?array
    {
        return $this->orderBy('id', 'ASC')->first();
    }
}
