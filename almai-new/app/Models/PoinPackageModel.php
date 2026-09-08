<?php

namespace App\Models;

use CodeIgniter\Model;

class PoinPackageModel extends Model
{
    protected $table = 'point_packages';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'amount',
        'price',
        'is_enabled'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getActive()
    {
        return $this->where('is_enabled', 1)
            ->orderBy('price', 'ASC')
            ->findAll();
    }
}
