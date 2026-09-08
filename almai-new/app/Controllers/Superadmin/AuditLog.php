<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuditLog extends BaseController
{
    public function index()
    {
        $auditModel = new \App\Models\AuditLogModel();
        
        $module = $this->request->getGet('module');
        $search = $this->request->getGet('search');
        
        $builder = $auditModel->getLogsWithUser();
        
        if ($module && $module !== 'all') {
            $builder->where('module', $module);
        }
        
        if ($search) {
            $builder->groupStart()
                    ->like('action', $search)
                    ->orLike('details', $search)
                    ->orLike('users.name', $search)
                    ->groupEnd();
        }
        
        $logs = $builder->paginate(20, 'default');
        
        return view('superadmin/audit_log/index', [
            'title' => 'Audit Log - Admin Dashboard',
            'logs' => $logs,
            'pager' => $auditModel->pager,
            'currentModule' => $module,
            'currentSearch' => $search,
            'activeMenu' => 'audit_log'
        ]);
    }
}
