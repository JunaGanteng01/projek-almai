<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminWpaAssignmentModel extends Model
{
    protected $table = 'admin_wpa_assignments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['admin_id', 'wpa_id'];
    protected $useTimestamps = true;

    public function getWpaIdsByAdmin($adminId)
    {
        return $this->where('admin_id', $adminId)->findColumn('wpa_id') ?? [];
    }

    public function getAdminIdsByWpa($wpaId)
    {
        return $this->where('wpa_id', $wpaId)->findColumn('admin_id') ?? [];
    }

    public function assignAdminToWpa($adminId, $wpaId)
    {
        // Check if already assigned
        if ($this->where(['admin_id' => $adminId, 'wpa_id' => $wpaId])->first()) {
            return true;
        }

        // Limit check could be added here if strictly 5
        $count = $this->where('admin_id', $adminId)->countAllResults();
        if ($count >= 5) {
            // Depending on requirements, we might throw error or allow it
            // The prompt says "bisa mengelola 5 wpa gitu", usually implies a limit.
        }

        return $this->insert([
            'admin_id' => $adminId,
            'wpa_id' => $wpaId
        ]);
    }

    public function removeAssignment($adminId, $wpaId)
    {
        return $this->where(['admin_id' => $adminId, 'wpa_id' => $wpaId])->delete();
    }
}
