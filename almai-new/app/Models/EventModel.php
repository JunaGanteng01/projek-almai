<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'id';
    protected $dateDateFormat = 'datetime';
    protected $allowedFields = [
        'title',
        'slug',
        'category',
        'image',
        'description',
        'hosted_by',
        'date',
        'time',
        'location',
        'city',
        'address',
        'map_url',
        'price',
        'max_participants',
        'current_participants',
        'views',
        'shares',
        'is_pro',
        'is_featured'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Helper to get formatted short date (e.g., 12 Feb 2026)
    public function getShortDate($date)
    {
        return date('d M Y', strtotime($date));
    }
}
