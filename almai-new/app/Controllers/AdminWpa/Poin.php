<?php

namespace App\Controllers\AdminWpa;

use App\Models\MerchandiseModel;
use App\Models\MerchandiseRedemptionModel;
use App\Models\NotificationModel;
use App\Models\PoinPackageModel;
use App\Models\TransaksiModel;
use App\Libraries\XenditService;
use App\Controllers\BaseController;
use App\Models\PoinModel;
use App\Models\UserModel;
use App\Models\WpaModel;
use App\Models\LayananModel;
use App\Models\SettingModel;

class Poin extends BaseController
{
    public function index()
    {
        $userId = $this->session->get('userId');
        
        $poinModel = new PoinModel();
        
        $poinBalance = $poinModel->getUserBalance($userId);
        $poinHistory = $poinModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        
        // Fetch user data for referral code
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Update session if needed
        if ($user && !session()->has('referralCode')) {
            session()->set('referralCode', $user['code_referral'] ?? null);
        }

        // Determine if user can check-in today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $poinModel->where('user_id', $userId)
                                      ->where('pointable_type', 'chekin')
                                      ->where('created_at >=', $todayStart)
                                      ->where('created_at <=', $todayEnd)
                                      ->first();
        $canCheckin = !$alreadyCheckedIn;

        // Fetch WPA ID properly (logic from Dashboard)
        $wpaId = $this->session->get('wpaId');
        if (!$wpaId) {
            $assignedWpaIds = (new \App\Models\AdminWpaAssignmentModel())->getWpaIdsByAdmin($userId);
            if (!empty($assignedWpaIds)) {
                $wpaId = $assignedWpaIds[0];
            } else {
                $wpaModel = new \App\Models\WpaModel();
                $wpa = $wpaModel->where('user_id', $userId)->first();
                if ($wpa) {
                    $wpaId = $wpa['id'];
                }
            }
            if ($wpaId) {
                $this->session->set('wpaId', $wpaId);
            }
        }
        
        // Calculate Total Earnings (Rupiah)
        $totalEarnings = 0;
        if ($wpaId) {
            $layananModel = new LayananModel();
            $layananList = $layananModel->getByWpaId($wpaId);
            
            if (!empty($layananList)) {
                $db = \Config\Database::connect();
                $builder = $db->table('transaksi');
                
                $layananIds = array_filter(array_column($layananList, 'id'));
                $layananNames = array_unique(array_filter(array_map(function($item) {
                    return $item['name'] ?? $item['title'] ?? null;
                }, $layananList)));

                $builder->groupStart();
                if (!empty($layananIds)) {
                    $builder->whereIn('layanan_id', $layananIds);
                }
                if (!empty($layananNames)) {
                    $builder->orWhereIn('product_name', $layananNames);
                    if (count($layananNames) < 50) {
                        foreach ($layananNames as $name) {
                            $builder->orLike('product_name', $name . ' - ', 'after');
                        }
                    }
                }
                $builder->groupEnd();
                
                $totalEarnings = (clone $builder)->where('status', 'confirmed')
                    ->selectSum('total')
                    ->get()->getRowArray()['total'] ?? 0;
            }
        }

        // Fetch settings for "Cara Dapat Poin"
        $settingModel = new SettingModel();
        $settings = $settingModel->getAllAsArray();
        
        $referralBonus = $settings['poin_referral_registration'] ?? 1000;
        $referralPurchasePercent = $settings['poin_mlm_percentage'] ?? 5; // Using mlm_percentage as referral purchase %
        $wpaCommissionPercent = $settings['poin_wpa_commission_percent'] ?? 5; // WPA commission from class sales
        
        // Additional Point Actions
        $poinLayananUlasan = $settings['poin_layanan_ulasan'] ?? 50;
        $poinFollowAccount = $settings['poin_follow_account'] ?? 20;
        $poinDailyCheckin = $settings['poin_daily_checkin'] ?? 10;
        
        // Statistics for User
        $stats = [
            // LEFT SIDE: PENGELUARAN / USAGE
            'redeem' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->selectSum('point')->first()['point'] ?? 0),
            'artikel' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'artikel')->selectSum('point')->first()['point'] ?? 0),
            'layanan_usage' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'layanan')->selectSum('point')->first()['point'] ?? 0),
            'merchandise' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'merchandise')->selectSum('point')->first()['point'] ?? 0),
            'share_out' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->whereIn('pointable_type', ['share', 'poin_sharing', 'share_out'])->selectSum('point')->first()['point'] ?? 0),
            
            // RIGHT SIDE: PEMASUKAN / EARNING
            'bonus_total' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->selectSum('point')->first()['point'] ?? 0,
            'register' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->where('pointable_type', 'registration')->selectSum('point')->first()['point'] ?? 0,
            'referral' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->whereIn('pointable_type', ['referral', 'transaksi'])->selectSum('point')->first()['point'] ?? 0,
            'layanan_earn' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->where('pointable_type', 'layanan')->selectSum('point')->first()['point'] ?? 0,
            'share_in' => $poinModel->where('user_id', $userId)->where('point >', 0)->whereIn('pointable_type', ['share', 'poin_sharing', 'share_in'])->selectSum('point')->first()['point'] ?? 0,
        ];

        // Global Pool Stats Context
        $maxSupply = 3000000000; // 3 Billion
        $totalDistributed = $poinModel->getTotalPoinDistributed();
        $totalRedeemed = $poinModel->getTotalPoinRedeemed();
        $totalActive = $totalDistributed - $totalRedeemed;

        return view('admin_wpa/poin/index', [
            'title' => 'Almai Poin - WPA Dashboard',
            'activeMenu' => 'poin',
            'poinBalance' => $poinBalance,
            'poinHistory' => $poinHistory,
            'pager' => $poinModel->pager,
            'referralCode' => $user['code_referral'] ?? null,
            'totalEarnings' => $totalEarnings,
            'referralBonus' => $referralBonus,
            'referralPurchasePercent' => $referralPurchasePercent,
            'wpaCommissionPercent' => $wpaCommissionPercent,
            'poinLayananUlasan' => $poinLayananUlasan,
            'poinFollowAccount' => $poinFollowAccount,
            'poinDailyCheckin' => $poinDailyCheckin,
            'stats' => $stats,
            'maxSupply' => $maxSupply,
            'totalActive' => $totalActive,
            'canCheckin' => $canCheckin,
        ]);
    }

    /**
     * Handle daily check-in action
     */
    public function checkin()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesi kadaluarsa.']);
        }

        $poinService = new \App\Libraries\PoinService();
        $success = $poinService->processDailyCheckin($userId);

        if ($success) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Check-in berhasil! Anda telah menerima bonus poin harian.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Anda sudah melakukan check-in hari ini. Coba lagi besok.'
            ]);
        }
    }


    public function merchandise()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/login')->with('error', 'Sesi kadaluarsa.');
        $userModel = new UserModel();
        $merchandiseModel = new MerchandiseModel();
        $poinModel = new PoinModel();

        $user = $userModel->find($userId);



        $merchandise = $merchandiseModel->getActive();
        $poinBalance = $poinModel->getUserBalance($userId);

        return view('wpa/poin/merchandise', [
            'title' => 'Tukar Merchandise',
            'user' => $user,
            'merchandise' => $merchandise,
            'poinBalance' => $poinBalance,
        ]);
    }

    /**
     * Show point purchase page
     */
    public function buy()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/login')->with('error', 'Sesi kadaluarsa.');
        $userModel = new UserModel();
        $poinModel = new PoinModel();
        $packageModel = new PoinPackageModel(); // Ensure this model is used

        $user = $userModel->find($userId);
        $poinBalance = $poinModel->getUserBalance($userId);
        $packages = $packageModel->getActive();

        return view('wpa/poin/buy', [
            'title' => 'Beli Poin',
            'user' => $user,
            'poinBalance' => $poinBalance,
            'packages' => $packages,
        ]);
    }

    public function redeemMerchandise()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/login')->with('error', 'Sesi kadaluarsa.');
        $userName = session()->get('userName');
        $poinModel = new PoinModel();
        $merchandiseModel = new MerchandiseModel();
        $redemptionModel = new MerchandiseRedemptionModel();
        $userModel = new UserModel();
        $notifModel = new NotificationModel();



        // Get JSON input
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $merchandiseId = $json->merchandise_id ?? 0;

        // Get merchandise from database
        $merchandise = $merchandiseModel->find($merchandiseId);

        if (!$merchandise || $merchandise['status'] !== 'active') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Merchandise tidak tersedia'
            ]);
        }

        // Check stock
        if (!$merchandise['unlimited_stock'] && $merchandise['stock'] <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stok merchandise habis'
            ]);
        }

        $pointsRequired = $merchandise['points_required'];

        // Check user balance
        $userBalance = $poinModel->getUserBalance($userId);

        if ($userBalance < $pointsRequired) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Poin tidak mencukupi'
            ]);
        }

        // Get user address
        $user = $userModel->find($userId);
        $address = $user['address'] ?? 'Alamat belum diisi';

        // Deduct points
        // Deduct points
        $poinModel->insert([
            'user_id' => $userId,
            'point' => $pointsRequired,
            'type' => 'redeem',
            'description' => 'Tukar merchandise: ' . $merchandise['name'],
            'pointable_type' => 'merchandise',
            'pointable_id' => $merchandiseId,
        ]);

        // Create redemption record
        $redemptionModel->insert([
            'user_id' => $userId,
            'merchandise_id' => $merchandiseId,
            'points_used' => $pointsRequired,
            'status' => 'pending',
            'shipping_address' => $address,
        ]);

        // Reduce stock if not unlimited
        if (!$merchandise['unlimited_stock']) {
            $merchandiseModel->update($merchandiseId, [
                'stock' => $merchandise['stock'] - 1
            ]);
        }

        // Calculate new balance
        $newBalance = $userBalance - $pointsRequired;

        // Notification to USER: Poin berkurang
        $notifModel->createNotification(
            $userId,
            'Poin Berkurang',
            'Poin Anda berkurang ' . number_format($pointsRequired) . ' untuk penukaran ' . $merchandise['name'] . '. Sisa poin: ' . number_format($newBalance),
            'info',
            '/user/poin'
        );

        // Notification to USER: Redemption created
        $notifModel->createNotification(
            $userId,
            'Penukaran Merchandise Berhasil',
            'Penukaran ' . $merchandise['name'] . ' sedang diproses. Kami akan mengirimkan ke alamat Anda dalam 7-14 hari kerja.',
            'success',
            '/user/poin'
        );

        // Notification to ALL ADMINS: New redemption
        $admins = $userModel->where('role', 'admin')->findAll();
        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Penukaran Merchandise Baru',
                $userName . ' menukarkan ' . number_format($pointsRequired) . ' poin untuk ' . $merchandise['name'],
                'info',
                '/admin/merchandise/redemptions'
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Penukaran berhasil! Merchandise akan dikirim ke alamat Anda.'
        ]);
    }

    /**
     * Purchase poin package
     */
    public function purchase()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/login')->with('error', 'Sesi kadaluarsa.');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Get JSON input
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $packageId = $json->package_id ?? 0;

        // Get package
        $packageModel = new PoinPackageModel();
        $package = $packageModel->find($packageId);

        if (!$package || $package['is_enabled'] != 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paket tidak tersedia'
            ]);
        }

        // Create transaction
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

        // Create Xendit invoice
        $xendit = new XenditService();

        $invoiceData = [
            'external_id' => $invoiceNumber,
            'amount' => $package['price'],
            'email' => $user['email'],
            'customer_name' => $user['name'] ?? 'Guest', // Add null check for name
            'phone' => $user['phone'] ?? null,
            'description' => 'Pembelian Service Fee: ' . ($package['name'] ?? 'Paket Poin') . ' - ' . ($user['name'] ?? 'Guest') . ' (' . $invoiceNumber . ')',
            'item_name' => 'Service Fee: ' . ($package['name'] ?? 'Paket Poin'),
            'success_url' => base_url('wpa/dashboard/poin/purchase-success?invoice=' . $invoiceNumber),
            'failure_url' => base_url('wpa/dashboard/poin/purchase-failed?invoice=' . $invoiceNumber),
        ];

        $result = $xendit->createInvoice($invoiceData);

        if ($result['success'] && isset($result['data']['invoice_url'])) {
            // Update transaksi with xendit invoice id
            $transaksiModel->update($transaksiId, [
                'notes' => $transaksiData['notes'] . ' | Xendit ID: ' . $result['data']['id'],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'redirect_url' => $result['data']['invoice_url']
            ]);
        } else {
            // Xendit failed
            $transaksiModel->update($transaksiId, [
                'status' => 'cancelled',
                'notes' => $transaksiData['notes'] . ' | Xendit error: ' . ($result['error'] ?? 'Unknown'),
            ]);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment gateway tidak tersedia. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Purchase success callback
     */
    public function purchaseSuccess()
    {
        $invoiceNumber = $this->request->getGet('invoice');
        return redirect()->to('/admin-wpa/poin')->with('success', 'Pembayaran sedang diproses. Poin akan ditambahkan setelah pembayaran dikonfirmasi.');
    }

    /**
     * Purchase failed callback
     */
    public function purchaseFailed()
    {
        $invoiceNumber = $this->request->getGet('invoice');
        return redirect()->to('/admin-wpa/poin')->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
    }
}
