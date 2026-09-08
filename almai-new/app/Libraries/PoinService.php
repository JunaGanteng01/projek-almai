<?php

namespace App\Libraries;

use App\Models\PoinModel;
use App\Models\SettingModel;
use App\Models\NotificationModel;
use App\Models\UserModel;

class PoinService
{
    protected $poinModel;
    protected $settingModel;
    protected $notifModel;
    protected $userModel;

    public function __construct()
    {
        $this->poinModel = new PoinModel();
        $this->settingModel = new SettingModel();
        $this->notifModel = new NotificationModel();
        $this->userModel = new UserModel();
    }

    /**
     * Get setting value
     */
    public function getSetting($key, $default = 0)
    {
        return (int) $this->settingModel->get($key, $default);
    }

    /**
     * Add poin to user
     */
    public function addPoin($userId, $amount, $type, $description, $refType = null, $refId = null)
    {
        if ($amount <= 0) return false;

        $this->poinModel->insert([
            'user_id' => $userId,
            'type' => $type,
            'point' => $amount,
            'description' => $description,
            'pointable_type' => $refType,
            'pointable_id' => $refId
        ]);

        // Send notification
        $this->notifModel->createNotification(
            $userId,
            'Poin Diterima! 🎉',
            "Anda mendapatkan {$amount} poin. {$description}",
            'success',
            '/user/poin'
        );

        return true;
    }

    /**
     * Deduct poin from user
     */
    public function deductPoin($userId, $amount, $description, $refType = null, $refId = null)
    {
        $balance = $this->poinModel->getUserBalance($userId);
        if ($balance < $amount) return false;

        $this->poinModel->insert([
            'user_id' => $userId,
            'type' => 'redeem',
            'point' => -$amount,
            'description' => $description,
            'pointable_type' => $refType,
            'pointable_id' => $refId
        ]);

        return true;
    }

    /**
     * Process referral registration bonus
     * Called when new user registers with referral code
     */
    public function processReferralRegistration($newUserId, $referrerId)
    {
        $newUser = $this->userModel->find($newUserId);
        
        // Bonus for new user (100 points)
        $this->addPoin(
            $newUserId,
            100,
            'bonus_registration',
            "bonus pendaftaran",
            'registration',
            null
        );

        // 8 Levels Configuration
        $levelBonuses = [
            1 => 100,
            2 => 50,
            3 => 25,
            4 => 12,
            5 => 6,
            6 => 3,
            7 => 2,
            8 => 1
        ];

        $currentReferrerId = $referrerId;
        
        // If there's no referrer (organic user), stop here. Admin does not get the 199 points.
        if (!$currentReferrerId) {
            return true;
        }

        $totalBasis = array_sum($levelBonuses); // 199
        $distributedPoints = 0;
        
        for ($level = 1; $level <= 8; $level++) {
            if (!$currentReferrerId) {
                break; // No more upline
            }
            
            $referrer = $this->userModel->find($currentReferrerId);
            if (!$referrer) {
                break; // Referrer not found
            }
            
            $bonus = $levelBonuses[$level];
            
            // Give points to this level
            $this->addPoin(
                $referrer['id'],
                $bonus,
                'bonus_referral_registration',
                "bonus pendaftaran downline level {$level} ({$newUser['name']})",
                'registration',
                $newUserId
            );
            
            $distributedPoints += $bonus;
            
            // Find next upline using affiliator_code
            $nextReferrer = null;
            if (!empty($referrer['affiliator_code'])) {
                $nextReferrer = $this->userModel->findByReferralCode($referrer['affiliator_code']);
            }
            
            $currentReferrerId = $nextReferrer ? $nextReferrer['id'] : null;
        }
        
        // Admin gets the remaining points
        $remainingPoints = $totalBasis - $distributedPoints;
        if ($remainingPoints > 0) {
            $adminId = 1; // Admin ID 1 (alma.indonesia.raya@gmail.com)
            $this->addPoin(
                $adminId,
                $remainingPoints,
                'bonus_referral_registration',
                "sisa bonus pendaftaran dari struktur upline tidak lengkap ({$newUser['name']})",
                'registration',
                $newUserId
            );
        }

        return true;
    }

    /**
     * Process bonus for upgrading to PRO
     * Uses 8-level upline system like registration
     */
    public function processUpgradeProBonus($upgradingUserId)
    {
        $user = $this->userModel->find($upgradingUserId);
        if (!$user) return false;
        
        // Bonus for user upgrading to PRO (100 points)
        $this->addPoin(
            $upgradingUserId,
            100,
            'bonus_upgrade_pro',
            "bonus upgrade akun PRO",
            'upgrade_pro',
            null
        );

        // 8 Levels Configuration
        $levelBonuses = [
            1 => 100,
            2 => 50,
            3 => 25,
            4 => 12,
            5 => 6,
            6 => 3,
            7 => 2,
            8 => 1
        ];

        // Find referrer using affiliator_code of the upgrading user
        $currentReferrerId = null;
        if (!empty($user['affiliator_code'])) {
            $referrer = $this->userModel->findByReferralCode($user['affiliator_code']);
            if ($referrer) {
                $currentReferrerId = $referrer['id'];
            }
        }
        
        // If there's no referrer (organic user), stop here. Admin does not get the 199 points.
        if (!$currentReferrerId) {
            return true;
        }

        $totalBasis = array_sum($levelBonuses); // 199
        $distributedPoints = 0;
        
        for ($level = 1; $level <= 8; $level++) {
            if (!$currentReferrerId) {
                break; // No more upline
            }
            
            $referrer = $this->userModel->find($currentReferrerId);
            if (!$referrer) {
                break; // Referrer not found
            }
            
            $bonus = $levelBonuses[$level];
            
            // Give points to this level
            $this->addPoin(
                $referrer['id'],
                $bonus,
                'bonus_upgrade_pro_referral',
                "bonus upgrade PRO downline level {$level} ({$user['name']})",
                'upgrade_pro',
                $upgradingUserId
            );
            
            $distributedPoints += $bonus;
            
            // Find next upline using affiliator_code
            $nextReferrer = null;
            if (!empty($referrer['affiliator_code'])) {
                $nextReferrer = $this->userModel->findByReferralCode($referrer['affiliator_code']);
            }
            
            $currentReferrerId = $nextReferrer ? $nextReferrer['id'] : null;
        }
        
        // Admin gets the remaining points
        $remainingPoints = $totalBasis - $distributedPoints;
        if ($remainingPoints > 0) {
            $adminId = 1; // Admin ID 1 (alma.indonesia.raya@gmail.com)
            $this->addPoin(
                $adminId,
                $remainingPoints,
                'bonus_upgrade_pro_referral',
                "sisa bonus upgrade PRO dari struktur upline tidak lengkap ({$user['name']})",
                'upgrade_pro',
                $upgradingUserId
            );
        }

        return true;
    }

    /**
     * Process welcome bonus for new registration
     */
    public function processRegistrationBonus($newUserId)
    {
        $bonus = $this->getSetting('poin_register_bonus', 100);
        if ($bonus > 0) {
            $this->addPoin(
                $newUserId,
                $bonus,
                'bonus_registration',
                "bonus pendaftaran member baru",
                'registration',
                $newUserId
            );
        }
        return true;
    }

    /**
     * Process referral purchase commission
     * Called when referred user makes a purchase
     */
    public function processReferralPurchase($buyerId, $transactionId, $totalAmount)
    {
        $buyer = $this->userModel->find($buyerId);
        
        // Check if buyer has referrer
        if (empty($buyer['referred_by'])) return false;

        $referrerId = $buyer['referred_by'];
        $percent = $this->getSetting('poin_referral_purchase_percent', 5);
        
        if ($percent <= 0) return false;

        $poinAmount = (int) ($totalAmount * $percent / 100);
        
        if ($poinAmount <= 0) return false;

        // Give poin to referrer
        $this->addPoin(
            $referrerId,
            $poinAmount,
            'bonus',
            "bonus referal layanan dari {$buyer['name']}",
            'transaksi',
            $transactionId
        );

        return true;
    }

    /**
     * Process WPA commission from purchase
     * Called when user buys kelas/tools owned by WPA
     */
    public function processWpaCommission($wpaUserId, $transactionId, $totalAmount, $itemName, $buyerId)
    {
        $percent = $this->getSetting('poin_wpa_commission_percent', 10);
        
        if ($percent <= 0) return false;

        $poinAmount = (int) ($totalAmount * $percent / 100);
        
        if ($poinAmount <= 0) return false;

        $buyer = $this->userModel->find($buyerId);
        $buyerName = $buyer['name'] ?? 'User';

        // Give poin to WPA
        $this->addPoin(
            $wpaUserId,
            $poinAmount,
            'earn',
            "pembelian layanan dari {$buyerName}",
            'layanan',
            $transactionId
        );

        return true;
    }

    /**
     * Get user poin balance
     */
    public function getBalance($userId)
    {
        return $this->poinModel->getUserBalance($userId);
    }

    /**
     * Get user poin history
     */
    public function getHistory($userId, $limit = 20)
    {
        return $this->poinModel->where('user_id', $userId)
                               ->orderBy('created_at', 'DESC')
                               ->limit($limit)
                               ->findAll();
    }

    /**
     * Convert poin to rupiah value
     */
    public function poinToRupiah($poin)
    {
        $rate = $this->getSetting('poin_to_rupiah', 100);
        return $poin * $rate;
    }

    /**
     * Check if user can redeem poin
     */
    public function canRedeem($userId)
    {
        $balance = $this->getBalance($userId);
        $minimum = $this->getSetting('poin_minimum_redeem', 10000);
        return $balance >= $minimum;
    }

    /**
     * Process daily check-in bonus
     */
    public function processDailyCheckin($userId)
    {
        $bonus = $this->getSetting('poin_daily_checkin', 10);
        if ($bonus <= 0) return false;

        // Ensure user can only check-in once per day
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $this->poinModel->where('user_id', $userId)
                                            ->where('pointable_type', 'chekin')
                                            ->where('created_at >=', $todayStart)
                                            ->where('created_at <=', $todayEnd)
                                            ->first();
        if ($alreadyCheckedIn) {
            return false;
        }

        $this->addPoin(
            $userId,
            $bonus,
            'earn', // updated type to match 'pendapatan' DB enum semantics
            "bonus chekin harian",
            'chekin',
            null
        );

        return true;
    }

    /**
     * Process follow account bonus
     */
    public function processFollowAccount($userId, $accountId)
    {
        $bonus = $this->getSetting('poin_follow_account', 20);
        if ($bonus <= 0) return false;

        $accountUser = $this->userModel->find($accountId);
        $accountName = $accountUser ? $accountUser['name'] : 'Akun';

        $this->addPoin(
            $userId,
            $bonus,
            'bonus',
            "bonus follow akun {$accountName}",
            'follow',
            $accountId
        );

        return true;
    }

    /**
     * Process bonus for checking in to an event
     * Distributes points to user, their direct referrer, CWPA, and WPA
     */
    public function processAbsensiBonus($userId, $absensiId)
    {
        $user = $this->userModel->find($userId);
        if (!$user) return false;

        // 1. Give the check-in user their standard 100 points
        $this->addPoin(
            $userId,
            100,
            'absensi_reward',
            'Reward absensi acara',
            'absensi_peserta',
            $absensiId
        );

        // 2. Process upline & event organizer distribution (Referrer, CWPA, WPA)
        $referrerUser = null;
        if (!empty($user['affiliator_code'])) {
            $referrerUser = $this->userModel->findByReferralCode($user['affiliator_code']);
        }

        $totalBasis = 175; // 100 (Referral) + 50 (CWPA) + 25 (WPA)
        $distributedPoints = 0;

        $targetDirect = $referrerUser ? $referrerUser['id'] : null;
        $targetCWPA = null;
        $targetWPA = null;

        // Fetch Event Data to get WPA and CWPA
        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        $absensi = $absensiPesertaModel->find($absensiId);
        
        if ($absensi) {
            $db = \Config\Database::connect();
            $tableMap = [
                'seminar' => 'seminar_fgd',
                'seminar_fgd' => 'seminar_fgd',
                'pelatihan' => 'pelatihan_simulasi',
                'pelatihan_simulasi' => 'pelatihan_simulasi',
                'signals' => 'signals',
                'konsultasi' => 'konsultasi',
                'expert_advisor' => 'expert_advisor',
                'kegiatan_lainnya' => 'kegiatan_lainnya'
            ];
            
            $table = $tableMap[$absensi['kegiatan_type']] ?? null;
            if ($table) {
                $event = $db->table($table)->where('id', $absensi['kegiatan_id'])->get()->getRowArray();
                if ($event) {
                    if (!empty($event['cwpa_id'])) {
                        $cwpaModel = new \App\Models\CwpaModel();
                        $cwpa = $cwpaModel->find($event['cwpa_id']);
                        if ($cwpa && !empty($cwpa['user_id'])) {
                            $targetCWPA = $cwpa['user_id'];
                        }
                    }
                    if (!empty($event['wpa_id'])) {
                        $wpaModel = new \App\Models\WpaModel();
                        $wpa = $wpaModel->find($event['wpa_id']);
                        if ($wpa && !empty($wpa['user_id'])) {
                            $targetWPA = $wpa['user_id'];
                        }
                    }
                }
            }
        }

        // Distribute to Direct Referrer (Level 1)
        if ($targetDirect) {
            $this->addPoin(
                $targetDirect, 
                100, 
                'bonus_absensi_referral', 
                "bonus absensi referral langsung dari {$user['name']}", 
                'absensi_peserta', 
                $absensiId
            );
            $distributedPoints += 100;
        }

        // Distribute to CWPA (Level 2)
        if ($targetCWPA) {
            $this->addPoin(
                $targetCWPA, 
                50, 
                'bonus_absensi_cwpa', 
                "bonus absensi acara (sebagai CWPA) dari check-in {$user['name']}", 
                'absensi_peserta', 
                $absensiId
            );
            $distributedPoints += 50;
        }

        // Distribute to WPA (Level 3)
        if ($targetWPA) {
            $this->addPoin(
                $targetWPA, 
                25, 
                'bonus_absensi_wpa', 
                "bonus absensi acara (sebagai WPA) dari check-in {$user['name']}", 
                'absensi_peserta', 
                $absensiId
            );
            $distributedPoints += 25;
        }

        // Sisa Poin ke Admin (jika struktur peran kosong)
        $remainingPoints = $totalBasis - $distributedPoints;
        if ($remainingPoints > 0) {
            $this->addPoin(
                1, // Admin ID 1
                $remainingPoints, 
                'bonus_absensi_sisa', 
                "sisa bonus absensi dari struktur acara/referral tidak lengkap ({$user['name']})", 
                'absensi_peserta', 
                $absensiId
            );
        }

        return true;
    }
}
