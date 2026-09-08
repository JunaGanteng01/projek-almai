<?php

namespace App\Controllers\Api\Mobile;

use App\Models\UserModel;
use App\Models\PoinModel;
use App\Models\LayananSubscriptionModel;
use CodeIgniter\RESTful\ResourceController;

class Dashboard extends ResourceController
{
    protected $format = 'json';

    /**
     * GET /api/mobile/dashboard
     * Header: Authorization: Bearer <access_token>
     * 
     * Returns: user summary + poin balance + status advokasi
     */
    public function index()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $userModel  = new UserModel();
        $poinModel  = new PoinModel();

        $user = $userModel->find($userId);
        if (!$user) {
            return $this->fail(['message' => 'User tidak ditemukan.'], 404);
        }

        // Poin balance
        $poinBalance = $poinModel->getUserBalance($userId);

        // Riwayat poin 5 terakhir
        $poinHistory = $poinModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $poinHistoryFormatted = array_map(function ($p) {
            return [
                'point'       => (int)$p['point'],
                'type'        => $p['type'],
                'description' => $p['description'],
                'date'        => $p['created_at'],
            ];
        }, $poinHistory);

        // Status advokasi: cek apakah user punya pembelian layanan advokasi
        $hasAdvokasi = false;
        $advokasiStep = 0;
        try {
            $db = \Config\Database::connect();
            // Cek via layanan_subscriptions atau transaksi advokasi
            $adv = $db->query("
                SELECT COUNT(*) as total 
                FROM transaksi 
                WHERE user_id = ? 
                  AND status = 'paid' 
                  AND product_type LIKE '%advokasi%'
                LIMIT 1
            ", [$userId])->getRow();
            $hasAdvokasi = ($adv && $adv->total > 0);
        } catch (\Exception $e) {
            // Tidak error jika tabel tidak ada
        }

        // Status KYC
        $kycStatus = $user['kyc_status'] ?? 'not_submitted';
        $kycLabel  = match($kycStatus) {
            'approved' => 'Terverifikasi',
            'pending'  => 'Menunggu Review',
            'rejected' => 'Ditolak',
            default    => 'Belum Diajukan',
        };

        // Level label
        $levelLabels = [
            1 => 'Member',
            2 => 'PRO',
            3 => 'CWPA',
            4 => 'WPA',
            5 => 'Admin',
        ];
        $levelLabel = $levelLabels[$user['level_id'] ?? 1] ?? 'Member';

        // Check if user can checkin today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $poinModel->where('user_id', $userId)
                                      ->where('pointable_type', 'chekin')
                                      ->where('created_at >=', $todayStart)
                                      ->where('created_at <=', $todayEnd)
                                      ->first();
        $canCheckin = !$alreadyCheckedIn;

        return $this->respond([
            'success' => true,
            'data'    => [
                'user' => [
                    'id'            => $user['id'],
                    'name'          => $user['name'],
                    'email'         => $user['email'],
                    'phone'         => $user['phone'] ?? '',
                    'avatar'        => !empty($user['avatar']) ? base_url('file/' . $user['avatar']) : base_url('images/default-avatar.png'),
                    'level_id'      => $user['level_id'] ?? 1,
                    'level_label'   => $levelLabel,
                    'is_pro'        => (bool)($user['is_pro'] ?? false),
                    'kyc_status'    => $kycStatus,
                    'kyc_label'     => $kycLabel,
                    'code_referral' => $user['code_referral'] ?? '',
                ],
                'poin' => [
                    'balance'       => $poinBalance,
                    'balance_label' => number_format($poinBalance) . ' Poin',
                    'history'       => $poinHistoryFormatted,
                    'can_checkin'   => (bool)$canCheckin,
                ],
                'advokasi' => [
                    'has_access' => $hasAdvokasi,
                    'step'       => $advokasiStep,
                ],
                'signal' => [
                    'status' => 'coming_soon',
                    'label'  => 'Coming Soon',
                ],
                'quick_links' => [
                    ['title' => 'Cek Poin',   'icon' => 'star',       'route' => 'poin'],
                    ['title' => 'Advokasi',   'icon' => 'shield',     'route' => 'advokasi'],
                    ['title' => 'Signal',     'icon' => 'trending_up','route' => 'signal'],
                    ['title' => 'KYC',        'icon' => 'verified',   'route' => 'kyc'],
                ],
            ],
        ]);
    }

    /**
     * GET /api/mobile/dashboard/poin
     * Poin detail & riwayat lengkap
     */
    public function poin()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];
        $page    = (int)($this->request->getGet('page') ?? 1);
        $limit   = 20;
        $offset  = ($page - 1) * $limit;

        $poinModel = new PoinModel();
        $balance   = $poinModel->getUserBalance($userId);

        $history = $poinModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();

        $total = $poinModel->where('user_id', $userId)->countAllResults();

        $historyFormatted = array_map(function ($p) {
            return [
                'point'       => (int)$p['point'],
                'type'        => $p['type'],
                'description' => $p['description'],
                'is_credit'   => $p['point'] > 0,
                'date'        => $p['created_at'],
            ];
        }, $history);

        return $this->respond([
            'success' => true,
            'data'    => [
                'balance'      => $balance,
                'balance_label'=> number_format($balance) . ' Poin',
                'history'      => $historyFormatted,
                'pagination'   => [
                    'current_page' => $page,
                    'total'        => $total,
                    'per_page'     => $limit,
                    'total_pages'  => (int)ceil($total / $limit),
                ],
            ],
        ]);
    }

    /**
     * POST /api/mobile/dashboard/checkin
     * Daily checkin endpoint for mobile
     */
    public function checkin()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $poinService = new \App\Libraries\PoinService();
        $poinModel   = new \App\Models\PoinModel();

        // Ensure user can only check-in once per day
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $poinModel->where('user_id', $userId)
                                      ->where('pointable_type', 'chekin')
                                      ->where('created_at >=', $todayStart)
                                      ->where('created_at <=', $todayEnd)
                                      ->first();
        if ($alreadyCheckedIn) {
            return $this->fail(['message' => 'Anda sudah melakukan check-in hari ini.'], 400);
        }

        $success = $poinService->processDailyCheckin($userId);
        if ($success) {
            $bonus = $poinService->getSetting('poin_daily_checkin', 10);
            $newBalance = $poinModel->getUserBalance($userId);
            return $this->respond([
                'success' => true,
                'message' => 'Check-in harian berhasil! +' . $bonus . ' poin diperoleh.',
                'bonus'   => $bonus,
                'balance' => $newBalance,
            ]);
        }

        return $this->fail(['message' => 'Gagal memproses check-in harian.'], 500);
    }
}
