<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $allowedFields = [
        'name',
        'company',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'status',
        'notes'
    ];

    /**
     * Get active suppliers
     */
    public function getActiveSuppliers()
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}
