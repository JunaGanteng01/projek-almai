<?php

namespace App\Models;

use CodeIgniter\Model;

class JurnalModel extends Model
{
    protected $table            = 'jurnal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'no_reff',
        'deskripsi',
        'akun_id',
        'debit',
        'kredit',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'tanggal' => 'required|valid_date',
        'no_reff' => 'required|max_length[50]',
        'akun_id' => 'required|integer',
        'debit'   => 'numeric',
        'kredit'  => 'numeric',
    ];

    /**
     * Get Journal entries with Account details
     */
    public function getJurnalWithAkun($startDate = null, $endDate = null, $search = null, $sortOrder = 'DESC')
    {
        $builder = $this->select('jurnal.*, akun.kode_akun, akun.nama_akun')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->orderBy('jurnal.tanggal', $sortOrder)
            ->orderBy('jurnal.created_at', $sortOrder);

        if ($startDate) {
            $builder->where('jurnal.tanggal >=', $startDate);
        }
        if ($endDate) {
            $builder->where('jurnal.tanggal <=', $endDate);
        }
        if ($search) {
            $builder->groupStart()
                ->like('jurnal.no_reff', $search)
                ->orLike('jurnal.deskripsi', $search)
                ->orLike('akun.nama_akun', $search)
                ->groupEnd();
        }

        return $builder;
    }
}
