<?php

namespace App\Models;

use CodeIgniter\Model;

class BotOrderModel extends Model
{
    protected $table            = 'bot_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'bot_id',
        'exchange_order_id',
        'symbol',
        'side',
        'type',
        'price',
        'amount',
        'filled',
        'status',
        'pnl',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
