<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\VoucherModel;

class Voucher extends BaseController
{
    protected $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->voucherModel->orderBy('created_at', 'DESC');
        
        if ($status && $status !== 'all') {
            $builder->where('status', $status);
        }

        $vouchers = $builder->paginate(20);
        $pager = $this->voucherModel->pager;

        // Stats
        $totalVouchers = $this->voucherModel->countAllResults(false);
        $activeVouchers = $this->voucherModel->where('status', 'active')->countAllResults(false);
        $totalUsage = $this->voucherModel->selectSum('used_count')->first()['used_count'] ?? 0;

        return view('laporan-kegiatan/voucher/index', [
            'title' => 'Voucher - Partnership Admin',
            'vouchers' => $vouchers,
            'pager' => $pager,
            'totalVouchers' => $totalVouchers,
            'activeVouchers' => $activeVouchers,
            'totalUsage' => $totalUsage,
            'currentStatus' => $status,
            'activeMenu' => 'voucher'
        ]);
    }
}
