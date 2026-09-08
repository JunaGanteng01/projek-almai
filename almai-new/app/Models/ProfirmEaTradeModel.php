<?php
namespace App\Models;
use CodeIgniter\Model;

class ProfirmEaTradeModel extends Model
{
    protected $table            = 'profirm_ea_trades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'account_login',
        'ticket',
        'symbol',
        'type',
        'lots',
        'open_price',
        'close_price',
        'sl',
        'tp',
        'swap',
        'profit',
        'open_time',
        'close_time',
        'slippage',
        'execution_speed',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at in trades for now
}
