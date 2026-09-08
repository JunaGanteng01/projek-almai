<?php

namespace App\Models;

use CodeIgniter\Model;

class CsMessageModel extends Model
{
    protected $table            = 'cs_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'conversation_id',
        'platform_message_id',
        'sender_type',
        'message_type',
        'message_body',
        'media_url',
        'status',
        'is_auto_reply',
        'is_internal_note',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false; // We only use created_at, no updated_at
    
    // Auto populate created_at
    protected $beforeInsert = ['setCreatedAt'];

    protected function setCreatedAt(array $data)
    {
        if (!isset($data['data']['created_at'])) {
            $data['data']['created_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Dapatkan pesan berdasarkan ID percakapan
     */
    public function getMessagesByConversation(int $conversationId)
    {
        return $this->where('conversation_id', $conversationId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

}
