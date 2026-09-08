<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table = 'kontak';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tipe',
        'nama',
        'perusahaan',
        'email',
        'telepon',
        'alamat',
        'npwp',
        'kode_akun_piutang',
        'kode_akun_hutang',
        'is_active',
    ];
    protected $useTimestamps = true;

    public function getWithFilters(array $filters = [])
    {
        $builder = $this->select('*');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $builder->groupStart()
                ->like('nama', $s)
                ->orLike('perusahaan', $s)
                ->orLike('email', $s)
                ->groupEnd();
        }

        if (!empty($filters['tipe']) && $filters['tipe'] !== 'all') {
            $builder->where('tipe', $filters['tipe']);
        }

        return $builder->where('is_active', 1)->orderBy('nama', 'ASC');
    }

    public function getActiveForSelect(string $tipe = ''): array
    {
        $builder = $this->where('is_active', 1);
        if ($tipe === 'pelanggan') {
            $builder->whereIn('tipe', ['pelanggan', 'keduanya']);
        } elseif ($tipe === 'pemasok') {
            $builder->whereIn('tipe', ['pemasok', 'keduanya']);
        }

        return $builder->orderBy('nama', 'ASC')->findAll();
    }
}
