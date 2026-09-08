<?php

namespace App\Models;

use CodeIgniter\Model;

class WpaSignalModel extends Model
{
    protected $table            = 'wpa_signals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'wpa_id',
        'pair',
        'timeframe',
        'type',
        'entry_price',
        'sl',
        'tp1',
        'tp2',
        'tp3',
        'description',
        'ai_review',
        'chart_capture',
        'price_points',
        'price_idr',
        'status'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getSignalsWithWpa()
    {
        return $this->select('wpa_signals.*, users.name as wpa_name')
                    ->join('users', 'users.id = wpa_signals.wpa_id', 'left');
    }
}
