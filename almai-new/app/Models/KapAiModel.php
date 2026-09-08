<?php

namespace App\Models;

use CodeIgniter\Model;

class KapAiModel extends Model
{
    protected $table            = 'kap_ai';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'nama_item',
        'akun_id',
        'posisi',
        'nominal',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all kap_ai entries with account name from akun table
     */
    public function getWithAkun()
    {
        return $this->select('kap_ai.*, akun.kode_akun, akun.nama_akun')
            ->join('akun', 'akun.id = kap_ai.akun_id', 'left')
            ->orderBy('kap_ai.tanggal', 'DESC')
            ->orderBy('kap_ai.id', 'DESC')
            ->findAll();
    }
}
