<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\AdminWpaAssignmentModel;
use App\Models\UserModel;
use App\Models\WpaModel;
use App\Models\LevelModel;

class AdminWpaAssignment extends BaseController
{
    protected $assignmentModel;
    protected $userModel;
    protected $wpaModel;

    public function __construct()
    {
        $this->assignmentModel = new AdminWpaAssignmentModel();
        $this->userModel = new UserModel();
        $this->wpaModel = new WpaModel();
    }

    public function index()
    {
        // Only Super Admin can manage assignments
        if (session()->get('level_id') != LevelModel::LEVEL_SUPER_ADMIN) {
            return redirect()->to('/superadmin/dashboard')->with('error', 'Akses ditolak.');
        }

        // Get all Level 5 Admins
        $admins = $this->userModel->where('level_id', LevelModel::LEVEL_ADMIN)->findAll();
        
        // Get all WPAs
        $wpas = $this->wpaModel->findAll();

        // Get current assignments
        $assignments = $this->assignmentModel->findAll();
        $adminAssignments = [];
        foreach ($assignments as $a) {
            $adminAssignments[$a['admin_id']][] = $a['wpa_id'];
        }

        $data = [
            'title' => 'Penugasan WPA ke Admin - Multi Management',
            'admins' => $admins,
            'wpas' => $wpas,
            'adminAssignments' => $adminAssignments,
            'activeMenu' => 'wpa_assignment'
        ];

        return view('superadmin/wpa_assignment/index', $data);
    }

    public function store()
    {
        if (session()->get('level_id') != LevelModel::LEVEL_SUPER_ADMIN) {
            return redirect()->to('/superadmin/dashboard')->with('error', 'Akses ditolak.');
        }

        $adminId = $this->request->getPost('admin_id');
        $wpaIds = $this->request->getPost('wpa_ids'); // array

        if (!$adminId) {
            return redirect()->back()->with('error', 'Admin ID diperlukan.');
        }

        // Remove old assignments for this admin
        $this->assignmentModel->where('admin_id', $adminId)->delete();

        // Insert new assignments
        if (!empty($wpaIds) && is_array($wpaIds)) {
            foreach ($wpaIds as $wpaId) {
                $this->assignmentModel->insert([
                    'admin_id' => $adminId,
                    'wpa_id' => $wpaId
                ]);
            }
        }

        return redirect()->to('/superadmin/wpa-assignment')->with('success', 'Penugasan berhasil diperbarui.');
    }
}
