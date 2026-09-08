<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfirmEaAccountModel extends Model
{
    protected $table            = 'profirm_ea_accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'account_login',
        'account_name',
        'community_name',
        'logo',
        'broker',
        'balance',
        'equity',
        'total_profit',
        'total_deposits',
        'total_withdrawals',
        'margin',
        'free_margin',
        'margin_level',
        'open_trades',
        'server',
        'currency',
        'leverage',
        'profirm',
        'updated_at',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
