<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\WithdrawalModel;

class Withdrawal extends BaseController
{
    protected $wdModel;

    public function __construct()
    {
        $this->wdModel = new WithdrawalModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->wdModel->select('withdrawals.*, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = withdrawals.wpa_id');

        if ($status) {
            $builder->where('withdrawals.status', $status);
        }

        $withdrawals = $builder->orderBy('withdrawals.created_at', 'DESC')->paginate(20);

        // Calculate stats
        $stats = [
            'total_pending' => $this->wdModel->where('status', 'pending')->selectSum('amount')->first()['amount'] ?? 0,
            'count_pending' => $this->wdModel->where('status', 'pending')->countAllResults(),
            'total_approved' => $this->wdModel->where('status', 'approved')->selectSum('amount')->first()['amount'] ?? 0,
            'count_approved' => $this->wdModel->where('status', 'approved')->countAllResults(),
            'total_completed' => $this->wdModel->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0,
            'count_completed' => $this->wdModel->where('status', 'completed')->countAllResults(),
        ];

        return view('laporan-kegiatan/withdrawals/index', [
            'title' => 'Penarikan WPA - Partnership Admin',
            'activeMenu' => 'withdrawals',
            'withdrawals' => $withdrawals,
            'pager' => $this->wdModel->pager,
            'currentStatus' => $status,
            'stats' => $stats
        ]);
    }
}
