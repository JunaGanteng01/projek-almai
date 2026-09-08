<?php

namespace App\Models;

use CodeIgniter\Model;

class EaApprovalModel extends Model
{
    protected $table            = 'ea_approvals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'approval_code', 'title', 'module', 'source_type', 'source_id',
        'requested_by', 'requested_by_user_id', 'amount', 'description',
        'priority', 'due_date', 'status', 'attachment', 'approver_id',
        'approved_at', 'rejected_at', 'decision_reason', 'notes', 'version'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
