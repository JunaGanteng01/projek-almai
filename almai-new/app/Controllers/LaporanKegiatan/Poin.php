<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\PoinModel;
use App\Models\UserModel;

class Poin extends BaseController
{
    protected $poinModel;
    protected $userModel;

    public function __construct()
    {
        $this->poinModel = new PoinModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $builder = $this->poinModel->getUserBalances();

        if ($search) {
            $builder->groupStart()
                    ->like('users.name', $search)
                    ->orLike('users.email', $search)
                    ->groupEnd();
        }

        $userBalances = $this->poinModel->paginate(20);

        // Current Statistics (Real Data)
        $maxSupply = 3000000000; // 3 Billion
        $totalDistributed = $this->poinModel->getTotalPoinDistributed();
        $totalRedeemed = $this->poinModel->getTotalPoinRedeemed();
        $totalActive = $totalDistributed - $totalRedeemed;
        $totalRupiahBalance = $this->userModel->where('level_id <', 5)->selectSum('balance')->first()['balance'] ?? 0;

        // Stats for Dashboard Table Breakdown
        $stats = [
            // LEFT SIDE: PENGELUARAN / USAGE
            'redeem' => abs($this->poinModel->where('point <', 0)->where('type', 'redeem')->selectSum('point')->first()['point'] ?? 0),
            'artikel' => abs($this->poinModel->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'artikel')->selectSum('point')->first()['point'] ?? 0),
            'layanan_usage' => abs($this->poinModel->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'layanan')->selectSum('point')->first()['point'] ?? 0),
            'merchandise' => abs($this->poinModel->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'merchandise')->selectSum('point')->first()['point'] ?? 0),
            'share_out' => abs($this->poinModel->where('point <', 0)->whereIn('pointable_type', ['share', 'poin_sharing', 'share_out'])->selectSum('point')->first()['point'] ?? 0),
            
            // RIGHT SIDE: PEMASUKAN / EARNING
            'bonus_total' => $this->poinModel->where('point >', 0)->where('type', 'earn')->selectSum('point')->first()['point'] ?? 0,
            'register' => $this->poinModel->where('point >', 0)->where('type', 'earn')->where('pointable_type', 'registration')->selectSum('point')->first()['point'] ?? 0,
            'referral' => $this->poinModel->where('point >', 0)->where('type', 'earn')->whereIn('pointable_type', ['referral', 'transaksi'])->selectSum('point')->first()['point'] ?? 0,
            'layanan_earn' => $this->poinModel->where('point >', 0)->where('type', 'earn')->where('pointable_type', 'layanan')->selectSum('point')->first()['point'] ?? 0,
            'share_in' => $this->poinModel->where('point >', 0)->whereIn('pointable_type', ['share', 'poin_sharing', 'share_in'])->selectSum('point')->first()['point'] ?? 0,
        ];

        return view('laporan-kegiatan/poin/index', [
            'title' => 'Poin Dashboard - Partnership Admin',
            'userBalances' => $userBalances,
            'pager' => $this->poinModel->pager,
            'totalDistributed' => $totalDistributed,
            'totalRedeemed' => $totalRedeemed,
            'totalActive' => $totalActive,
            'totalRupiahBalance' => $totalRupiahBalance,
            'maxSupply' => $maxSupply,
            'stats' => $stats,
            'currentSearch' => $search,
            'activeMenu' => 'poin'
        ]);
    }

    public function history()
    {
        $type = $this->request->getGet('type');
        $search = $this->request->getGet('search');

        $builder = $this->poinModel->getWithUser();

        if ($type && $type !== 'all') {
            $builder->where('points.type', $type);
        }

        if ($search) {
            $builder->groupStart()
                    ->like('users.name', $search)
                    ->orLike('users.email', $search)
                    ->orLike('points.description', $search)
                    ->groupEnd();
        }

        $poinList = $this->poinModel->paginate(20);

        return view('laporan-kegiatan/poin/history', [
            'title' => 'Riwayat Poin - Partnership Admin',
            'poinList' => $poinList,
            'pager' => $this->poinModel->pager,
            'currentType' => $type,
            'currentSearch' => $search,
            'activeMenu' => 'poin'
        ]);
    }

    public function userBalance($userId)
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/laporan-kegiatan/poin')->with('error', 'User tidak ditemukan');
        }

        $balance = $this->poinModel->getUserBalance($userId);
        $history = $this->poinModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(20);

        return view('laporan-kegiatan/poin/user_balance', [
            'title' => 'Saldo Poin User - Partnership Admin',
            'user' => $user,
            'balance' => $balance,
            'history' => $history,
            'pager' => $this->poinModel->pager,
            'activeMenu' => 'poin'
        ]);
    }
}
