<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetModel extends Model
{
    protected $table            = 'aset';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_aset',
        'nomor_aset',
        'tanggal_pembelian',
        'harga_beli',
        'akun_aset_kode',
        'akun_kredit_kode',
        'deskripsi',
        'referensi',
        'is_penyusutan',
        'akun_akumulasi_kode',
        'akun_penyusutan_kode',
        'persen_penyusutan',
        'masa_manfaat',
        'metode_penyusutan',
        'tanggal_mulai_penyusutan',
        'akumulasi_penyusutan',
        'batas_biaya',
        'nilai_residu',
        'tag',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getSummary()
    {
        return $this->select('SUM(harga_beli) as total_nilai, SUM(akumulasi_penyusutan) as total_depresiasi')
            ->where('status', 'Terdaftar')
            ->first();
    }
}
