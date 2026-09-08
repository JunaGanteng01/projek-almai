<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PoinModel;
use App\Models\UserModel;
use App\Models\PoinPackageModel;
use App\Models\TransaksiModel;
use App\Libraries\XenditService;

class Poin extends BaseController
{
    protected $poinModel;
    protected $userModel;

    public function __construct()
    {
        $this->poinModel = new PoinModel();
        $this->userModel = new UserModel();
    }

    /**
     * Show point purchase page for admin (beli poin).
     */
    public function buy()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/admin/login')->with('error', 'Sesi kadaluarsa.');

        $poinBalance = $this->poinModel->getUserBalance($userId);
        $packageModel = new PoinPackageModel();
        $packages = $packageModel->getActive();

        return view('admin/poin/buy', [
            'title' => 'Beli Poin - Admin',
            'poinBalance' => $poinBalance,
            'packages' => $packages,
            'activeMenu' => 'poin'
        ]);
    }

    /**
     * Purchase poin package via Xendit (admin).
     */
    public function purchase()
    {
        $userId = session()->get('userId');
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'Sesi kadaluarsa.']);

        $user = $this->userModel->find($userId);

        $json = $this->request->getJSON();
        if (!$json) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
        }

        $packageId = $json->package_id ?? 0;
        $packageModel = new PoinPackageModel();
        $package = $packageModel->find($packageId);

        if (!$package || $package['is_enabled'] != 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Paket tidak tersedia']);
        }

        $transaksiModel = new TransaksiModel();
        $invoiceNumber = $transaksiModel->generateInvoiceNumber();

        $transaksiData = [
            'invoice_number' => $invoiceNumber,
            'user_id' => $userId,
            'kelas_id' => null,
            'product_type' => 'poin',
            'product_name' => 'Service Fee: ' . number_format($package['amount']) . ' Poin',
            'amount' => $package['price'],
            'discount' => 0,
            'total' => $package['price'],
            'payment_method' => 'xendit',
            'status' => 'pending',
            'notes' => 'Poin: ' . $package['amount'],
        ];

        $transaksiId = $transaksiModel->insert($transaksiData);

        $xendit = new XenditService();
        $invoiceData = [
            'external_id' => $invoiceNumber,
            'amount' => $package['price'],
            'email' => $user['email'],
            'customer_name' => $user['name'] ?? 'Guest',
            'phone' => $user['phone'] ?? null,
            'description' => 'Pembelian Service Fee: ' . ($package['name'] ?? 'Paket Poin') . ' - ' . ($user['name'] ?? 'Guest') . ' (' . $invoiceNumber . ')',
            'item_name' => 'Service Fee: ' . ($package['name'] ?? 'Paket Poin'),
            'success_url' => base_url('admin/poin/purchase-success?invoice=' . $invoiceNumber),
            'failure_url' => base_url('admin/poin/purchase-failed?invoice=' . $invoiceNumber),
        ];

        $result = $xendit->createInvoice($invoiceData);

        if ($result['success'] && isset($result['data']['invoice_url'])) {
            $transaksiModel->update($transaksiId, [
                'notes' => $transaksiData['notes'] . ' | Xendit ID: ' . $result['data']['id'],
            ]);
            return $this->response->setJSON([
                'success' => true,
                'redirect_url' => $result['data']['invoice_url']
            ]);
        }

        $transaksiModel->update($transaksiId, [
            'status' => 'cancelled',
            'notes' => $transaksiData['notes'] . ' | Xendit error: ' . ($result['error'] ?? 'Unknown'),
        ]);
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Payment gateway tidak tersedia. Silakan coba lagi.'
        ]);
    }

    public function purchaseSuccess()
    {
        return redirect()->to('/admin/poin/buy')->with('success', 'Pembayaran sedang diproses. Poin akan ditambahkan setelah pembayaran dikonfirmasi.');
    }

    public function purchaseFailed()
    {
        return redirect()->to('/admin/poin/buy')->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
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

        return view('admin/poin/index', [
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

        return view('admin/poin/history', [
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
            return redirect()->to('/admin/poin')->with('error', 'Data poin tidak ditemukan');
        }

        $this->poinModel->delete($id);
        \App\Models\AuditLogModel::record('Hapus Riwayat Poin', 'poin', $id, $poin);
        return redirect()->to('/admin/poin')->with('success', 'Data poin berhasil dihapus!');
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

        return view('admin/poin/user_balance', [
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
