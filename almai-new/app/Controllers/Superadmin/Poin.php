<?php

namespace App\Controllers\Superadmin;

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

        // Granular Stats for Dashboard Table Breakdown
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

        return view('superadmin/poin/index', [
            'title' => 'Almai Poin Dashboard - Admin Dashboard',
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

        return view('superadmin/poin/history', [
            'title' => 'Riwayat Poin - Admin Dashboard',
            'poinList' => $poinList,
            'pager' => $this->poinModel->pager,
            'currentType' => $type,
            'currentSearch' => $search,
            'activeMenu' => 'poin'
        ]);
    }

    public function delete($id)
    {
        $poin = $this->poinModel->find($id);
        if (!$poin) {
            return redirect()->to('/superadmin/poin')->with('error', 'Data poin tidak ditemukan');
        }

        $this->poinModel->delete($id);
        \App\Models\AuditLogModel::record('Hapus Riwayat Poin', 'poin', $id, $poin);
        return redirect()->to('/superadmin/poin')->with('success', 'Data poin berhasil dihapus!');
    }

    public function userBalance($userId)
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['error' => 'User tidak ditemukan']);
        }

        $db = \Config\Database::connect();
        $balance = $this->poinModel->getUserBalance($userId);
        $history = $this->poinModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(20);

        // Summary stats
        $totalEarned  = $db->table('points')->selectSum('point')->where('user_id', $userId)->where('point >', 0)->get()->getRowArray()['point'] ?? 0;
        $totalRedeemed = abs($db->table('points')->selectSum('point')->where('user_id', $userId)->where('point <', 0)->get()->getRowArray()['point'] ?? 0);
        $totalReferralPoin = $db->table('points')->selectSum('point')->where('user_id', $userId)->where('point >', 0)->like('description', 'Komisi referral', 'both')->get()->getRowArray()['point'] ?? 0;

        // Cash referral = saldo rupiah aktual user (hasil dari distribusi referral chain)
        $totalReferralCash = (float)($user['balance'] ?? 0);

        return view('superadmin/poin/user_balance', [
            'title'              => 'Saldo Poin User - Admin Dashboard',
            'user'               => $user,
            'balance'            => $balance,
            'history'            => $history,
            'pager'              => $this->poinModel->pager,
            'activeMenu'         => 'poin',
            'totalEarned'        => (int)$totalEarned,
            'totalRedeemed'      => (int)$totalRedeemed,
            'totalReferralPoin'  => (int)$totalReferralPoin,
            'totalReferralCash'  => (int)$totalReferralCash,
        ]);
    }

    public function syncInit()
    {
        $db = \Config\Database::connect();
        
        // 1. Delete all points of specific types
        $typesToDelete = [
            'bonus_registration',
            'bonus_referral_registration',
            'bonus_upgrade_pro',
            'bonus_upgrade_pro_referral'
        ];
        
        $this->poinModel->whereIn('type', $typesToDelete)->delete();

        // 2. Count total users
        $totalUsers = $this->userModel->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'total' => $totalUsers
        ]);
    }

    public function syncProcess()
    {
        $offset = (int) $this->request->getPost('offset');
        $limit = (int) $this->request->getPost('limit');

        $users = $this->userModel->orderBy('created_at', 'ASC')->findAll($limit, $offset);
        $poinService = new \App\Libraries\PoinService();

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($users as $user) {
            $userId = $user['id'];
            
            // Registration points
            $referrerId = null;
            if (!empty($user['affiliator_code'])) {
                $referrer = $this->userModel->findByReferralCode($user['affiliator_code']);
                if ($referrer) {
                    $referrerId = $referrer['id'];
                }
            }
            $poinService->processReferralRegistration($userId, $referrerId);

            // Upgrade PRO points
            if (isset($user['level_id']) && $user['level_id'] == \App\Models\LevelModel::LEVEL_PRO) {
                $poinService->processUpgradeProBonus($userId);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Database transaction failed']);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function syncComplete()
    {
        \App\Models\AuditLogModel::record('Sinkronisasi Poin 8 Level', 'poin', null, 'Mengkalkulasi ulang bonus pendaftaran dan upgrade PRO ke sistem 8 level untuk seluruh user');
        session()->setFlashdata('success', 'Semua poin pendaftaran dan upgrade PRO berhasil disinkronisasi ke sistem 8 level!');
        return $this->response->setJSON(['status' => 'success']);
    }
}
