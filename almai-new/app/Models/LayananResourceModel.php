<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananResourceModel extends Model
{
    protected $table = 'layanan_resources';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'layanan_id',
        'layanan_type',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'sort_order'
    ];

    public function getByLayanan($type, $id)
    {
        return $this->where('layanan_type', $type)
            ->where('layanan_id', $id)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
