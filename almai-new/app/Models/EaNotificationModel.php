<?php

namespace App\Models;

use CodeIgniter\Model;

class EaNotificationModel extends Model
{
    protected $table            = 'ea_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'type', 'title', 'message', 'link', 'is_read', 'is_archived'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
