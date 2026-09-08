<?php

namespace App\Controllers\LaporanKegiatan;

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
            'title' => 'Portofolio EA - Partnership Admin',
            'activeMenu' => 'portfolio',
            'accounts' => $accounts
        ];

        return view('laporan-kegiatan/portfolio/index', $data);
    }
}
