<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wpa_id', 'title', 'slug', 'thumbnail', 'excerpt', 
        'content', 'category', 'read_time', 'status',
        'poin_price', 'is_free', 'verification_status', 
        'rejection_reason', 'verified_at', 'verified_by'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getLatest($limit = 3)
    {
        return $this->select('artikel.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                    ->join('wpa', 'wpa.id = artikel.wpa_id')
                    ->where('artikel.status', 'published')
                    ->where('artikel.verification_status', 'approved')
                    ->orderBy('artikel.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getByWpaId($wpaId)
    {
        return $this->where('wpa_id', $wpaId)
                    ->where('status', 'published')
                    ->where('verification_status', 'approved')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getFiltered($category = null, $search = null)
    {
        $builder = $this->select('artikel.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                        ->join('wpa', 'wpa.id = artikel.wpa_id')
                        ->where('artikel.status', 'published')
                        ->where('artikel.verification_status', 'approved');

        if ($category && $category !== 'all') {
            $builder->where('artikel.category', $category);
        }

        if ($search) {
            $builder->groupStart()
                    ->like('artikel.title', $search)
                    ->orLike('artikel.excerpt', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('artikel.created_at', 'DESC')->findAll();
    }

    public function getPendingVerification()
    {
        return $this->select('artikel.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                    ->join('wpa', 'wpa.id = artikel.wpa_id')
                    ->where('artikel.verification_status', 'pending')
                    ->orderBy('artikel.created_at', 'DESC')
                    ->findAll();
    }

    public function getByWpaIdAll($wpaId)
    {
        return $this->where('wpa_id', $wpaId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
