<?php

namespace App\Models;

use CodeIgniter\Model;

class UlasanModel extends Model
{
    protected $table = 'ulasan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kelas_id', 'user_id', 'rating', 'ulasan', 'status'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getByKelas($kelasId, $status = 'approved')
    {
        return $this->select('ulasan.*, users.name as user_name, users.avatar as user_photo')
                    ->join('users', 'users.id = ulasan.user_id')
                    ->where('ulasan.kelas_id', $kelasId)
                    ->where('ulasan.status', $status)
                    ->orderBy('ulasan.created_at', 'DESC')
                    ->findAll();
    }

    public function hasUserReviewed($kelasId, $userId)
    {
        return $this->where('kelas_id', $kelasId)
                    ->where('user_id', $userId)
                    ->first() !== null;
    }

    public function getAverageRating($kelasId)
    {
        $result = $this->selectAvg('rating')
                       ->where('kelas_id', $kelasId)
                       ->where('status', 'approved')
                       ->first();
        return $result['rating'] ? round($result['rating'], 1) : 0;
    }

    public function getReviewCount($kelasId)
    {
        return $this->where('kelas_id', $kelasId)
                    ->where('status', 'approved')
                    ->countAllResults();
    }
}
