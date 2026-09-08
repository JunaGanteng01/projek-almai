<?php

namespace App\Models;

use CodeIgniter\Model;

class MerchandiseRedemptionModel extends Model
{
    protected $table = 'merchandise_redemptions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'merchandise_id', 'points_used', 'status',
        'shipping_address', 'tracking_number', 'notes'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getWithDetails()
    {
        return $this->select('merchandise_redemptions.*, users.name as user_name, users.email as user_email, users.phone as user_phone, merchandise.name as merchandise_name, merchandise.image as merchandise_image')
                    ->join('users', 'users.id = merchandise_redemptions.user_id')
                    ->join('merchandise', 'merchandise.id = merchandise_redemptions.merchandise_id')
                    ->orderBy('merchandise_redemptions.created_at', 'DESC')
                    ->findAll();
    }

    public function getByStatus($status)
    {
        return $this->select('merchandise_redemptions.*, users.name as user_name, users.email as user_email, merchandise.name as merchandise_name, merchandise.image as merchandise_image')
                    ->join('users', 'users.id = merchandise_redemptions.user_id')
                    ->join('merchandise', 'merchandise.id = merchandise_redemptions.merchandise_id')
                    ->where('merchandise_redemptions.status', $status)
                    ->orderBy('merchandise_redemptions.created_at', 'DESC')
                    ->findAll();
    }
}
