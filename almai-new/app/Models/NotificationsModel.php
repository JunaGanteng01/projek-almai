<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationsModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'message', 'type', 'link', 'is_read'];
    protected $useTimestamps = true;
}
