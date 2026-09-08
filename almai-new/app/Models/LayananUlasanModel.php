<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananUlasanModel extends Model
{
    protected $table = 'layanan_ulasan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'layanan_id', 'layanan_type', 'rating', 'ulasan', 'status'];
    protected $useTimestamps = true;

    public function getReviews($id, $type)
    {
        return $this->select('layanan_ulasan.*, users.name as user_name, users.avatar as user_photo')
                    ->join('users', 'users.id = layanan_ulasan.user_id')
                    ->where('layanan_id', $id)
                    ->where('layanan_type', $type)
                    ->where('layanan_ulasan.status', 'approved')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    public function hasUserReviewed($userId, $id, $type)
    {
        return $this->where('user_id', $userId)
                    ->where('layanan_id', $id)
                    ->where('layanan_type', $type)
                    ->first() !== null;
    }
}
