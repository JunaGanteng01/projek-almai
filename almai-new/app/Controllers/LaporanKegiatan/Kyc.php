<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserDataModel;
use App\Models\LevelModel;

class Kyc extends BaseController
{
    protected $userModel;
    protected $userDataModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userDataModel = new UserDataModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status') ?: 'pending';
        $search = $this->request->getGet('search');

        $builder = $this->userModel->select('users.*')->orderBy('id', 'DESC');

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $builder->where('users.kyc_status', $status);
        }

        if ($search) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('users.phone', $search)
                ->groupEnd();
        }

        $kycList = $builder->paginate(20);
        $pager = $this->userModel->pager;

        $totalPending = $this->userModel->where('kyc_status', 'pending')->countAllResults();
        $totalApproved = $this->userModel->where('kyc_status', 'approved')->countAllResults();
        $totalRejected = $this->userModel->where('kyc_status', 'rejected')->countAllResults();

        return view('laporan-kegiatan/kyc/index', [
            'title' => 'Verifikasi PRO - Partnership Admin',
            'kycList' => $kycList,
            'pager' => $pager,
            'totalPending' => $totalPending,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
            'currentStatus' => $status,
            'currentSearch' => $search,
            'activeMenu' => 'kyc'
        ]);
    }

    public function detail($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/laporan-kegiatan/kyc')->with('error', 'User tidak ditemukan');
        }

        $kycData = $this->userDataModel->where('user_id', $id)->first();

        return view('laporan-kegiatan/kyc/detail', [
            'title' => 'Detail KYC - Partnership Admin',
            'user' => $user,
            'kyc' => $kycData,
            'activeMenu' => 'kyc'
        ]);
    }
}
