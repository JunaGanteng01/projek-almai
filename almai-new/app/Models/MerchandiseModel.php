<?php

namespace App\Models;

use CodeIgniter\Model;

class MerchandiseModel extends Model
{
    protected $table = 'merchandise';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'description', 'icon', 'image', 'points_required',
        'stock', 'unlimited_stock', 'status'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getActive()
    {
        return $this->where('status', 'active')
                    ->where('(stock > 0 OR unlimited_stock = 1)')
                    ->orderBy('points_required', 'ASC')
                    ->findAll();
    }
}
