<?php

namespace App\Models;

use CodeIgniter\Model;

class CsConversationModel extends Model
{
    protected $table            = 'cs_conversations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'platform',
        'platform_user_id',
        'customer_name',
        'customer_avatar',
        'last_message',
        'last_message_at',
        'status',
        'unread_count','handled_by','handler_name','handled_at','resolved_at','follow_up_note',
        'assigned_to','sla_started_at','follow_up_due_at','auto_replied_at','status_changed_at','reminder_sent_at',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan semua percakapan yang masih open
     */
    public function getActiveConversations()
    {
        return $this->whereIn('status', ['NEW','AUTO_REPLIED','UNREAD','IN_PROGRESS','FOLLOW_UP'])
            ->orderBy('last_message_at', 'DESC')
            ->findAll();
    }
}
