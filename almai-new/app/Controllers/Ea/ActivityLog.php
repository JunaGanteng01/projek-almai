<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;

class ActivityLog extends BaseController
{
    public function index()
    {
        $model = new \App\Models\AuditLogModel();
        $activities = $model->getLogsWithUser()->paginate(50);
        $data = ['title' => 'Audit Trail & Jejak Aktivitas', 'activities' => $activities, 'pager' => $model->pager, 'activeMenu' => 'activity'];
        
        return view('ea/activity', $data);
    }
}
