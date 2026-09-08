<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wpa_id', 'title', 'thumbnail', 'price', 'original_price',
        'duration', 'modules', 'level', 'rating', 'students',
        'category', 'mode', 'location', 'type', 'zoom_link',
        'zoom_meeting_id', 'zoom_password',
        'schedule', 'next_session', 'description', 'highlights', 'status'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getPopular($limit = 3)
    {
        return $this->select('kelas.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                    ->join('wpa', 'wpa.id = kelas.wpa_id')
                    ->where('kelas.status', 'active')
                    ->orderBy('kelas.students', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getFiltered($category = null, $mode = null, $search = null)
    {
        $builder = $this->select('kelas.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                        ->join('wpa', 'wpa.id = kelas.wpa_id')
                        ->where('kelas.status', 'active');

        if ($category && $category !== 'all') {
            $builder->where('kelas.category', $category);
        }

        if ($mode && $mode !== 'all') {
            $builder->where('kelas.mode', $mode);
        }

        if ($search) {
            $builder->groupStart()
                    ->like('kelas.title', $search)
                    ->orLike('kelas.category', $search)
                    ->orLike('kelas.description', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('kelas.students', 'DESC')->findAll();
    }

    public function getByWpaId($wpaId)
    {
        return $this->where('wpa_id', $wpaId)
                    ->where('status', 'active')
                    ->findAll();
    }

    public function getRelated($excludeId, $category, $limit = 3)
    {
        return $this->select('kelas.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
                    ->join('wpa', 'wpa.id = kelas.wpa_id')
                    ->where('kelas.id !=', $excludeId)
                    ->where('kelas.category', $category)
                    ->where('kelas.status', 'active')
                    ->limit($limit)
                    ->findAll();
    }

    public function getCategories()
    {
        return [
            'Gold Trading',
            'Forex Trading',
            'Crypto Trading',
            'Commodity Trading',
            'Index Trading',
            'Algorithmic Trading',
            'Risk Management',
            'Technical Analysis'
        ];
    }

    public function getHighlightsArray($highlights)
    {
        if (is_string($highlights)) {
            return json_decode($highlights, true) ?? [];
        }
        return $highlights ?? [];
    }
}
