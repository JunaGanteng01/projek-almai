<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferensiRegulasiModel extends Model
{
    protected $table = 'referensi_regulasi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nomor_regulasi',
        'tentang',
        'keterangan'
    ];
}
