<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\KelasModel;
use App\Models\TransaksiModel;

class Earnings extends BaseController
{
    protected $wpaModel;
    protected $kelasModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->wpaModel = new WpaModel();
        $this->kelasModel = new KelasModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $wpaId = session()->get('wpaId');
        $wpa = $this->wpaModel->find($wpaId);
        
        // Get filter period
        $period = $this->request->getGet('period') ?? 'month';
        
        // Get all kelas for this WPA
        $kelasList = $this->kelasModel->where('wpa_id', $wpaId)->findAll();
        $kelasIds = array_column($kelasList, 'id');
        
        // Initialize stats
        $totalEarnings = 0;
        $pendingEarnings = 0;
        $totalTransactions = 0;
        $confirmedTransactions = 0;
        $transactionHistory = [];
        $earningsByKelas = [];
        
        if (!empty($kelasIds)) {
            // Date filter based on period
            $dateFilter = $this->getDateFilter($period);
            
            // Total earnings (confirmed)
            $totalEarnings = $this->transaksiModel
                ->whereIn('transaksi.kelas_id', $kelasIds)
                ->where('transaksi.status', 'confirmed')
                ->where('transaksi.created_at >=', $dateFilter)
                ->selectSum('transaksi.total')
                ->first()['total'] ?? 0;
            
            // Pending earnings (paid but not confirmed)
            $pendingEarnings = $this->transaksiModel
                ->whereIn('transaksi.kelas_id', $kelasIds)
                ->where('transaksi.status', 'paid')
                ->where('transaksi.created_at >=', $dateFilter)
                ->selectSum('transaksi.total')
                ->first()['total'] ?? 0;
            
            // Total transactions count
            $totalTransactions = $this->transaksiModel
                ->whereIn('transaksi.kelas_id', $kelasIds)
                ->where('transaksi.created_at >=', $dateFilter)
                ->countAllResults(false);
            
            // Confirmed transactions count
            $confirmedTransactions = $this->transaksiModel
                ->whereIn('transaksi.kelas_id', $kelasIds)
                ->where('transaksi.status', 'confirmed')
                ->where('transaksi.created_at >=', $dateFilter)
                ->countAllResults(false);
            
            // Transaction history
            $transactionHistory = $this->transaksiModel
                ->select('transaksi.*, users.name as user_name, users.email as user_email, kelas.title as kelas_title')
                ->join('users', 'users.id = transaksi.user_id', 'left')
                ->join('kelas', 'kelas.id = transaksi.kelas_id', 'left')
                ->whereIn('transaksi.kelas_id', $kelasIds)
                ->where('transaksi.created_at >=', $dateFilter)
                ->orderBy('transaksi.created_at', 'DESC')
                ->findAll();
            
            // Earnings by kelas
            foreach ($kelasList as $kelas) {
                $kelasEarning = $this->transaksiModel
                    ->where('transaksi.kelas_id', $kelas['id'])
                    ->where('transaksi.status', 'confirmed')
                    ->where('transaksi.created_at >=', $dateFilter)
                    ->selectSum('transaksi.total')
                    ->first()['total'] ?? 0;
                
                $kelasTransactions = $this->transaksiModel
                    ->where('transaksi.kelas_id', $kelas['id'])
                    ->where('transaksi.status', 'confirmed')
                    ->where('transaksi.created_at >=', $dateFilter)
                    ->countAllResults(false);
                
                $earningsByKelas[] = [
                    'kelas' => $kelas,
                    'earnings' => $kelasEarning,
                    'transactions' => $kelasTransactions,
                ];
            }
            
            // Sort by earnings descending
            usort($earningsByKelas, fn($a, $b) => $b['earnings'] <=> $a['earnings']);
        }
        
        // Calculate growth (compare with previous period)
        $previousDateFilter = $this->getPreviousDateFilter($period);
        $previousEarnings = 0;
        if (!empty($kelasIds)) {
            $previousEarnings = $this->transaksiModel
                ->whereIn('transaksi.kelas_id', $kelasIds)
                ->where('transaksi.status', 'confirmed')
                ->where('transaksi.created_at >=', $previousDateFilter)
                ->where('transaksi.created_at <', $dateFilter)
                ->selectSum('transaksi.total')
                ->first()['total'] ?? 0;
        }
        
        $growth = 0;
        if ($previousEarnings > 0) {
            $growth = round((($totalEarnings - $previousEarnings) / $previousEarnings) * 100);
        }

        return view('wpa/earnings/index', [
            'title' => 'Earnings - WPA Dashboard',
            'activeMenu' => 'earnings',
            'wpa' => $wpa,
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings,
            'totalTransactions' => $totalTransactions,
            'confirmedTransactions' => $confirmedTransactions,
            'transactionHistory' => $transactionHistory,
            'earningsByKelas' => $earningsByKelas,
            'growth' => $growth,
            'currentPeriod' => $period,
        ]);
    }

    private function getDateFilter($period)
    {
        switch ($period) {
            case '3month':
                return date('Y-m-d', strtotime('-3 months'));
            case 'year':
                return date('Y-01-01');
            case 'all':
                return '2000-01-01';
            default: // month
                return date('Y-m-01');
        }
    }

    private function getPreviousDateFilter($period)
    {
        switch ($period) {
            case '3month':
                return date('Y-m-d', strtotime('-6 months'));
            case 'year':
                return date('Y-01-01', strtotime('-1 year'));
            case 'all':
                return '2000-01-01';
            default: // month
                return date('Y-m-01', strtotime('-1 month'));
        }
    }
}
