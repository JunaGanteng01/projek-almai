<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id', 
        'action', 
        'module', 
        'target_id', 
        'details', 
        'ip_address', 
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    public function getLogsWithUser()
    {
        return $this->select('audit_logs.*, users.name as user_name, users.email as user_email')
                    ->join('users', 'users.id = audit_logs.user_id', 'left')
                    ->orderBy('audit_logs.created_at', 'DESC');
    }

    public static function record($action, $module, $targetId = null, $details = null)
    {
        $model = new self();
        $request = \Config\Services::request();
        
        $data = [
            'user_id'    => session()->get('userId') ?? null,
            'action'     => $action,
            'module'     => $module,
            'target_id'  => $targetId,
            'details'    => is_array($details) ? json_encode($details) : $details,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
        ];
        
        return $model->insert($data);
    }
}
