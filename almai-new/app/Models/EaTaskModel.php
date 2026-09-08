<?php

namespace App\Models;

use CodeIgniter\Model;

class EaTaskModel extends Model
{
    protected $table            = 'ea_tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'assigned_to', 'priority', 'module', 'deadline', 'status', 'attachment', 'activity_log'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
