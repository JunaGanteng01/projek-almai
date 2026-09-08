<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\MerchandiseModel;
use App\Models\MerchandiseRedemptionModel;

class Merchandise extends BaseController
{
    public function index()
    {
        $model = new MerchandiseModel();
        $redemptionModel = new MerchandiseRedemptionModel();
        
        $merchandise = $model->orderBy('created_at', 'DESC')->findAll();
        
        // Get stats
        $totalMerchandise = count($merchandise);
        $activeMerchandise = count(array_filter($merchandise, fn($m) => $m['status'] === 'active'));
        $totalRedemptions = $redemptionModel->countAllResults();
        $pendingRedemptions = $redemptionModel->where('status', 'pending')->countAllResults();
        
        return view('laporan-kegiatan/merchandise/index', [
            'title' => 'Merchandise - Partnership Admin',
            'pageTitle' => 'Merchandise',
            'activeMenu' => 'merchandise',
            'merchandise' => $merchandise,
            'stats' => [
                'total' => $totalMerchandise,
                'active' => $activeMerchandise,
                'redemptions' => $totalRedemptions,
                'pending' => $pendingRedemptions,
            ]
        ]);
    }

    public function redemptions()
    {
        $model = new MerchandiseRedemptionModel();
        $filter = $this->request->getGet('status');
        
        if ($filter && $filter !== 'all') {
            $redemptions = $model->getByStatus($filter);
        } else {
            $redemptions = $model->getWithDetails();
        }
        
        // Stats
        $stats = [
            'total' => $model->countAllResults(false),
            'pending' => $model->where('status', 'pending')->countAllResults(false),
            'processing' => $model->where('status', 'processing')->countAllResults(false),
            'shipped' => $model->where('status', 'shipped')->countAllResults(false),
            'completed' => $model->where('status', 'completed')->countAllResults(false),
        ];

        return view('laporan-kegiatan/merchandise/redemptions', [
            'title' => 'Penukaran Merchandise - Partnership Admin',
            'pageTitle' => 'Penukaran Merchandise',
            'activeMenu' => 'merchandise',
            'redemptions' => $redemptions,
            'stats' => $stats,
            'filter' => $filter,
        ]);
    }
}
