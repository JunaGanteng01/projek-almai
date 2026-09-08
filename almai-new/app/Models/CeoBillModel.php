<?php

namespace App\Models;

use CodeIgniter\Model;

class CeoBillModel extends Model
{
    protected $table = 'ceo_bills';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'vendor', 'reference', 'description', 'amount', 'currency', 'due_date',
        'status', 'approval_id', 'created_by'
    ];
}
