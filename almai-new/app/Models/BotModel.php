<?php

namespace App\Models;

use CodeIgniter\Model;

class BotModel extends Model
{
    protected $table            = 'bots';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'user_exchange_key_id',
        'name',
        'symbol',
        'strategy',
        'status',
        'config',
        'total_profit',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
