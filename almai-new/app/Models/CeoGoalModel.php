<?php

namespace App\Models;

use CodeIgniter\Model;

class CeoGoalModel extends Model
{
    protected $table = 'ceo_goals';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'objective', 'owner', 'period_start', 'period_end', 'target_value',
        'actual_value', 'unit', 'confidence', 'status', 'update_note', 'created_by'
    ];
}
