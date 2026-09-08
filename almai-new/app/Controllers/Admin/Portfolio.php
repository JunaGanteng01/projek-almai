<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProfirmEaAccountModel;

class Portfolio extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Ambil SEMUA portofolio, join dengan tabel users untuk dapat Nama & Email
        $builder = $db->table('profirm_ea_accounts');
        $builder->select('profirm_ea_accounts.*, users.name as owner_name, users.email as owner_email');
        $builder->join('users', 'users.id = profirm_ea_accounts.user_id', 'left');
        $builder->orderBy('profirm_ea_accounts.created_at', 'DESC');
        
        $accounts = $builder->get()->getResultArray();

        foreach ($accounts as &$acc) {
            $lastUpdate = strtotime($acc['updated_at']);
            $acc['is_online'] = (time() - $lastUpdate < 300);
        }

        $data = [
            'title' => 'Manajemen Portofolio EA - Super Admin',
            'activeMenu' => 'portfolio',
            'accounts' => $accounts
        ];

        return view('admin/portfolio/index', $data);
    }

    public function delete($id)
    {
        $model = new ProfirmEaAccountModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('/admin/portfolio')->with('success', 'Portofolio berhasil dihapus.');
        }
        return redirect()->to('/admin/portfolio')->with('error', 'Data tidak ditemukan.');
    }
}
