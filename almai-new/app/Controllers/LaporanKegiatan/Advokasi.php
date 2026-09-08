<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\LayananPengaduanModel;
use App\Models\UserModel;

class Advokasi extends BaseController
{
    public function index()
    {
        $model = new LayananPengaduanModel();
        $userModel = new UserModel();
        $userId = session()->get('userId');
        $user = $userModel->find($userId);
        
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        
        $builder = $model->select('layanan_pengaduan.*, users.name as user_name, users.email as user_email')
                         ->join('users', 'users.id = layanan_pengaduan.user_id', 'left');
        
        if ($status) {
            $builder->where('layanan_pengaduan.status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                    ->like('layanan_pengaduan.name', $search)
                    ->orLike('layanan_pengaduan.broker_name', $search)
                    ->orLike('users.name', $search)
                    ->groupEnd();
        }
        
        // Use paginate for safety, but the new view needs 'advokasiList'
        $advokasiList = $builder->orderBy('layanan_pengaduan.created_at', 'DESC')->paginate(20, 'default');

        $data = [
            'title' => 'Monitoring Advokasi - Partnership',
            'activeMenu' => 'advokasi',
            'advokasiList' => $advokasiList,
            'pager' => $model->pager,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'user' => $user,
            'isAuthorized' => true // Admin is always authorized
        ];

        return view('laporan-kegiatan/advokasi/index', $data);
    }

    public function belajar()
    {
        $data = [
            'title' => 'Academy Advokasi - Partnership Oversight',
            'activeMenu' => 'advokasi',
            'isPaid' => true // Admin has full access
        ];

        return view('laporan-kegiatan/advokasi/belajar', $data);
    }
}
