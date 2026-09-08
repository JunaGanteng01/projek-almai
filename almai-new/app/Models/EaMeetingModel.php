<?php

namespace App\Models;

use CodeIgniter\Model;

class EaMeetingModel extends Model
{
    protected $table            = 'ea_meetings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'location', 'meet_url', 'start_time', 'end_time', 'attendees',
        'category', 'priority', 'description', 'status', 'created_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
