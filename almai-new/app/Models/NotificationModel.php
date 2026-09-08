<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'message', 'type', 'link', 'is_read'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getByUserId($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)->set(['is_read' => 1])->update();
    }

    public function createNotification($userId, $title, $message, $type = 'info', $link = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link
        ]);
    }
}
