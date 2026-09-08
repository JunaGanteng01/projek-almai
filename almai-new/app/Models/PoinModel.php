<?php

namespace App\Models;

use CodeIgniter\Model;

class PoinModel extends Model
{
    protected $table = 'points'; // Changed from 'poin' to 'points'
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    // Updated fields based on points.sql
    protected $allowedFields = [
        'user_id', 
        'point', // Was 'amount'
        'type', 
        'description', 
        'pointable_type', // Was 'reference_type'
        'pointable_id',   // Was 'reference_id'
        'purchaser_id'    // New field
    ];
    
    protected $useTimestamps = true;

    public function getWithUser()
    {
        return $this->select('points.*, users.name as user_name, users.email as user_email')
                    ->join('users', 'users.id = points.user_id', 'left')
                    ->orderBy('points.created_at', 'DESC');
    }

    public function getUserBalance($userId)
    {
        // Calculate balance directly from sum of 'point' column
        // In new schema, point can be negative for deduction/usage
        
        $balance = $this->where('user_id', $userId)
                        ->selectSum('point')
                        ->first()['point'] ?? 0;
                        
        return (int) $balance;
    }

    public function getTotalPoinDistributed()
    {
        return $this->where('point >', 0)
                    ->selectSum('point')
                    ->first()['point'] ?? 0;
    }

    public function getTotalPoinRedeemed()
    {
        // Redemptions are usually negative points
        $redeemed = $this->where('point <', 0)
                         ->selectSum('point')
                         ->first()['point'] ?? 0;
                         
        return abs($redeemed);
    }

    public function getUserBalances()
    {
        return $this->select('users.id, users.name, users.email, users.balance as rupiah_balance, COALESCE(SUM(points.point), 0) as balance')
                    ->join('users', 'users.id = points.user_id', 'right')
                    ->where('users.level_id <', 5) // Exclude admins
                    ->groupBy('users.id')
                    ->orderBy('balance', 'DESC');
    }
}
