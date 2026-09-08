<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananPengaduanModel extends Model
{
    protected $table            = 'layanan_pengaduan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'name',
        'email',
        'whatsapp',
        'ktp_number',
        'address',
        'broker_name',
        'trading_type',
        'category_problem',
        'sub_category',
        'loss_amount',
        'incident_date',
        'chronology',
        'trading_account',
        'trading_password',
        'broker_server',
        'file_attachment',
        'referrer_id',
        'referral_code',
        'status',
        'admin_note',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $afterInsert = ['notifyTelegram'];

    protected function notifyTelegram(array $data)
    {
        if (isset($data['id'])) {
            $row = $this->find($data['id']);
            if ($row) {
                (new \App\Libraries\TelegramService())->notifyAdmin('advocacy', $row);
            }
        }
        return $data;
    }

    /**
     * Get complaints for a specific user
     */
    public function getByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
