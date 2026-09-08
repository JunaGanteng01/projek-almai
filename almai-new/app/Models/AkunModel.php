<?php

namespace App\Models;

use CodeIgniter\Model;

class AkunModel extends Model
{
    protected $table            = 'akun';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_akun',
        'kode_akun',
        'kategori',
        'kode_sub_akun',
        'nama_sub_akun'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_akun' => 'required|max_length[255]',
        'kode_akun' => 'required|max_length[50]',
        'kategori'  => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function findByKode(string $kode): ?array
    {
        return $this->where('kode_akun', $kode)->first();
    }

    public function getFirstByKategori(string $kategori): ?array
    {
        return $this->where('kategori', $kategori)->orderBy('kode_akun', 'ASC')->first();
    }

    public const KATEGORI_AKUN = [
        'akun piutang',
        'kas & bank',
        'persediaan',
        'aktiva lancar lainya',
        'aktiva tetap',
        'aktiva lainya',
        'depresiasi & amortisasi',
        'akun hutang',
        'kewajiban lancar lainya',
        'ekuitas',
        'pendapatan',
        'harga pokok penjualan',
        'beban',
        'pendapatan lainya',
        'beban lainya'
    ];
}
