<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'invoice_number',
        'user_id',
        'layanan_id',
        'package_id',
        'product_type',
        'product_name',
        'amount',
        'discount',
        'total',
        'payment_method',
        'payment_proof',
        'transfer_proof',
        'status',
        'notes',
        'paid_at',
        'confirmed_at',
        'referral_code',
        'referrer_id',
        'referral_poin',
        'voucher_id',
        'mail_invoice_pending',
        'mail_invoice_success',
        'mail_legal_profil',
        'mail_legal_pemberian_jasa',
        'mail_legal_risiko',
        'mail_legal_wpa',
        'expires_at'
    ];
    protected $useTimestamps = true;

    protected $afterInsert = ['notifyTelegram'];

    protected function notifyTelegram(array $data)
    {
        if (isset($data['id'])) {
            $row = $this->find($data['id']);
            if ($row) {
                (new \App\Libraries\TelegramService())->notifyAdmin('transaction', $row);
            }
        }
        return $data;
    }

    public function getWithUser()
    {
        $db = \Config\Database::connect();
        
        $select = 'transaksi.*, users.name as user_name, users.email as user_email, 
            COALESCE(layanan.name, layanan_event.title, layanan_tools.name, layanan_subscription.name) as layanan_name,
            layanan.category as layanan_category, layanan.subcategory as layanan_subcategory,
            COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id) as creator_wpa_id';

        // Defensively check for cwpa_id as some environments might have missing columns
        if ($db->fieldExists('cwpa_id', 'layanan')) {
            $select .= ', COALESCE(layanan.cwpa_id, layanan_event.cwpa_id, layanan_tools.cwpa_id, layanan_subscription.cwpa_id) as creator_cwpa_id';
        }

        return $this->select($select)
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left')
            ->join('layanan_event', 'layanan_event.id = transaksi.layanan_id', 'left')
            ->join('layanan_tools', 'layanan_tools.id = transaksi.layanan_id', 'left')
            ->join('layanan_subscription', 'layanan_subscription.id = transaksi.layanan_id', 'left')
            ->orderBy('transaksi.created_at', 'DESC');
    }

    public function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $lastInvoice = $this->like('invoice_number', $prefix . $date, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice['invoice_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalRevenue($status = 'confirmed')
    {
        return $this->where('status', $status)->where('payment_method !=', 'poin')->selectSum('total')->first()['total'] ?? 0;
    }

    public function getRecentTransactions($limit = 5)
    {
        return $this->getWithUser()->limit($limit)->findAll();
    }


    public function processReferralPoin($transaksiId)
    {
        $transaksi = $this->find($transaksiId);
        if (!$transaksi) return false;

        $totalPoinGiven = 0;
        $totalCashGiven = 0;
        $userModel = new \App\Models\UserModel();
        $db = \Config\Database::connect();

        // 1. Process REFERRER COMMISSION (MLM SCHEME)
        // Guard: skip jika sudah pernah diproses (referral_poin > 0 ATAU sudah ada record di points)
        $alreadyProcessed = $transaksi['referral_poin'] > 0;
        if (!$alreadyProcessed) {
            // Cek juga di tabel points untuk kasus cash-only (poin = 0 tapi cash sudah dibagi)
            $existingPoints = $db->table('points')
                ->where('pointable_type', 'transaksi')
                ->where('pointable_id', $transaksiId)
                ->where('description LIKE', '%Komisi referral%')
                ->countAllResults();
            if ($existingPoints > 0) $alreadyProcessed = true;
        }

        if (!$alreadyProcessed) {

            // A. Determine Reward Configuration
            $rewardConfig = [
                'total_poin' => 0,
                'total_cash' => 0,
                'percentage' => 50, // Default 50% decay (Excel Rumus)
                'max_depth' => 8    // Target 8 levels as per Excel
            ];

            $type = strtolower($transaksi['product_type'] ?? 'layanan');
            $id = $transaksi['layanan_id'];

            // Fetch config from specific table
            $item = null;
            if ($type === 'layanan' || $type === 'course') {
                $item = $db->table('layanan')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
                $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'tool' || $type === 'tools' || $type === 'ea') {
                $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'artikel') {
                $item = $db->table('layanan_artikel')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'subscription' || in_array($type, ['pendampingan', 'profirm', 'vip_member', 'private_konsultan'])) {
                $item = $db->table('layanan_subscription')->where('id', $id)->get()->getRowArray();
            }

            if ($item) {
                // ── Tentukan pool referral ──────────────────────────────────────────
                // Prioritas:
                // 1. Package → ambil referral_user_cash/poin dari layanan_prices
                // 2. Layanan-level → ambil referral_user_cash/poin dari tabel layanan
                // 3. Auto-calc → harga × dist% / 100  (jika semua 0)
                // 4. Global fallback → poin_per_transaksi setting (1%)
                //
                // Pool = total yang akan didistribusikan ke chain L1-L8
                // L1 dapat 50% dari pool, L2 dapat 50% dari sisa, dst. (decay 50%)
                // Contoh: pool Rp 500.000 → L1=250rb, L2=125rb, L3=62.5rb, ...

                $distPct   = (float) ($item['referral_distribution_percentage'] ?? 0);
                $maxDepth  = (int)   ($item['referral_max_depth'] ?? 8);

                if (!empty($transaksi['package_id'])) {
                    // PRIORITAS 1: Pakai nilai pre-calculated dari package
                    $package = $db->table('layanan_prices')
                        ->select('referral_user_cash, referral_user_poin')
                        ->where('id', $transaksi['package_id'])
                        ->get()->getRowArray();

                    if ($package && (float)$package['referral_user_cash'] > 0) {
                        $rewardConfig['total_cash'] = (float) $package['referral_user_cash'];
                        $rewardConfig['total_poin'] = (float) $package['referral_user_poin'];
                    } elseif ($distPct > 0) {
                        // Package ada tapi belum punya nilai → hitung dari harga package
                        $pkgPrice = $db->table('layanan_prices')
                            ->select('price')
                            ->where('id', $transaksi['package_id'])
                            ->get()->getRowArray();
                        $basePrice = $pkgPrice ? (float)$pkgPrice['price'] : (float)$transaksi['total'];
                        $pool = (int) floor($basePrice * $distPct / 100);
                        $rewardConfig['total_cash'] = $pool;
                        $rewardConfig['total_poin'] = (int) floor($pool / 1000);
                    }
                } else {
                    // PRIORITAS 2: Pakai nilai dari layanan-level
                    $rewardConfig['total_cash'] = (float) ($item['referral_user_cash'] ?? 0);
                    $rewardConfig['total_poin'] = (float) ($item['referral_user_poin'] ?? 0);

                    // PRIORITAS 3: Auto-calc jika masih 0 dan dist% > 0
                    if ($rewardConfig['total_cash'] == 0 && $rewardConfig['total_poin'] == 0 && $distPct > 0) {
                        $pool = (int) floor($transaksi['total'] * $distPct / 100);
                        $rewardConfig['total_cash'] = $pool;
                        $rewardConfig['total_poin'] = (int) floor($pool / 1000);
                    }
                }

                $rewardConfig['percentage'] = 50;
                $rewardConfig['max_depth']  = $maxDepth;

                // PRIORITAS 4: Global fallback jika semua masih 0
                if ($rewardConfig['total_cash'] == 0 && $rewardConfig['total_poin'] == 0) {
                    $settingModel = new SettingModel();
                    $poinPercent = (float) $settingModel->get('poin_per_transaksi', 1);
                    $rewardConfig['total_poin'] = floor(($transaksi['total'] * $poinPercent) / 100);
                    $rewardConfig['max_depth']  = 1;
                    $rewardConfig['percentage'] = 100;
                }
            } elseif ($id == 9999) {
                // Special handle for Advocacy Membership
                $referrerId = $transaksi['referrer_id'];
                if ($referrerId) {
                    $referrer = $userModel->find($referrerId);
                    // If referrer is WPA or CWPA, give 50% commission
                    if ($referrer && in_array($referrer['level_id'], [LevelModel::LEVEL_WPA, LevelModel::LEVEL_CWPA])) {
                        $rewardConfig['total_cash'] = floor($transaksi['total'] * 50 / 100);
                        $rewardConfig['total_poin'] = 0;
                        $rewardConfig['max_depth'] = 1;
                        $rewardConfig['percentage'] = 100;
                    } else {
                        // Regular user fallback (1% points)
                        $settingModel = new SettingModel();
                        $poinPercent = (float) $settingModel->get('poin_per_transaksi', 1);
                        $rewardConfig['total_poin'] = floor(($transaksi['total'] * $poinPercent) / 100);
                        $rewardConfig['max_depth'] = 1;
                        $rewardConfig['percentage'] = 100;
                    }
                }
            }

            // B. Distribute Rewards Recursively (Level 1 = Referrer, Level 2+ = Uplines)
            $currentPoinPool = $rewardConfig['total_poin'];
            $currentCashPool = $rewardConfig['total_cash'];
            $depth = 1;

            // Start from direct referrer (Level 1), then walk up the upline chain
            $nextUserId = $transaksi['referrer_id'] ?? null;
            $currentReferralCode = $transaksi['referral_code'] ?? null;

            // FALLBACK: If transaction record doesn't have referrer_id, try to get it from the user's profile
            // This is crucial for processing old transactions made before the system update.
            $buyer = $userModel->find($transaksi['user_id']);
            if (empty($nextUserId) && $buyer && !empty($buyer['affiliator_code'])) {
                $upline = $userModel->where('code_referral', $buyer['affiliator_code'])->first();
                if ($upline) {
                    $nextUserId = $upline['id'];
                    $currentReferralCode = $buyer['affiliator_code'];
                    // Update transaction record for data consistency
                    $this->update($transaksiId, [
                        'referrer_id' => $nextUserId,
                        'referral_code' => $currentReferralCode
                    ]);
                }
            }

            if (($currentPoinPool > 0 || $currentCashPool > 0) && !empty($nextUserId)) {
                while ($depth <= $rewardConfig['max_depth'] && ($currentPoinPool >= 1 || $currentCashPool >= 1) && $nextUserId) {
                    $user = $userModel->find($nextUserId);
                    if (!$user) break;

                    // Calculate Share (50% of current pool)
                    $sharePoin = floor($currentPoinPool * ($rewardConfig['percentage'] / 100));
                    $shareCash = floor($currentCashPool * ($rewardConfig['percentage'] / 100));

                    $label = $depth === 1 ? "Level 1 (Referrer)" : "Level $depth (Upline)";

                    // 1. Give Points (Always)
                    if ($sharePoin >= 1) {
                        $poinModel = new PoinModel();
                        $poinModel->insert([
                            'user_id' => $nextUserId,
                            'type' => 'earn',
                            'point' => $sharePoin,
                            'description' => "Komisi referral Poin ($label) dari " . $transaksi['product_name'],
                            'pointable_type' => 'transaksi',
                            'pointable_id' => $transaksiId,
                        ]);
                        $totalPoinGiven += $sharePoin;
                    }

                    // 2. Give Cash (All users — no PRO restriction)
                    if ($shareCash >= 1) {
                        $db->table('users')->where('id', $nextUserId)->set('balance', "balance + $shareCash", false)->update();
                        $totalCashGiven += $shareCash;
                    }

                    // 3. Send Notification
                    try {
                        $notifModel = new \App\Models\NotificationModel();
                        $notifTitle = "Komisi Referral Masuk!";
                        $notifMsg = "Selamat! Anda mendapatkan komisi dari pembelian " . $transaksi['product_name'] . ". ";
                        $notifMsg .= "Detail: Poin +" . number_format($sharePoin);
                        if ($shareCash >= 1) {
                            $notifMsg .= ", Cash +Rp " . number_format($shareCash, 0, ',', '.');
                        }

                        $notifModel->createNotification(
                            $nextUserId,
                            $notifTitle,
                            $notifMsg,
                            'success',
                            base_url('wpa/dashboard/transaksi')
                        );
                    } catch (\Throwable $e) {
                        log_message('error', 'Referral Notification Failed: ' . $e->getMessage());
                    }

                    // Deduct from pool
                    $currentPoinPool -= $sharePoin;
                    $currentCashPool -= $shareCash;

                    // Walk up the upline chain
                    $affiliatorCode = $user['affiliator_code'] ?? null;
                    if ($affiliatorCode) {
                        $upline = $userModel->where('code_referral', $affiliatorCode)->first();
                        $nextUserId = $upline['id'] ?? null;
                    } else {
                        $nextUserId = null;
                    }

                    $depth++;
                }
                
                // ═══════════════════════════════════════════════════════════════════
                // TRANSFER SISA KOMISI KE WPA/CWPA
                // ═══════════════════════════════════════════════════════════════════
                // Jika masih ada sisa pool setelah distribusi ke upline chain,
                // transfer sisa tersebut ke WPA/CWPA pemilik layanan
                if (($currentPoinPool >= 1 || $currentCashPool >= 1) && !empty($item)) {
                    $creatorUserId = null;
                    $creatorType = null;
                    
                    // Cari WPA atau CWPA pemilik layanan
                    if (!empty($item['cwpa_id'])) {
                        $cwpa = $db->table('cwpa')->select('user_id')->where('id', $item['cwpa_id'])->get()->getRowArray();
                        if ($cwpa) {
                            $creatorUserId = $cwpa['user_id'];
                            $creatorType = 'CWPA';
                        }
                    } elseif (!empty($item['wpa_id'])) {
                        $wpa = $db->table('wpa')->select('user_id')->where('id', $item['wpa_id'])->get()->getRowArray();
                        if ($wpa) {
                            $creatorUserId = $wpa['user_id'];
                            $creatorType = 'WPA';
                        }
                    }
                    
                    // Transfer sisa ke creator
                    if ($creatorUserId) {
                        $remainingPoin = floor($currentPoinPool);
                        $remainingCash = floor($currentCashPool);
                        
                        if ($remainingPoin >= 1) {
                            $poinModel = new PoinModel();
                            $poinModel->insert([
                                'user_id' => $creatorUserId,
                                'type' => 'earn',
                                'point' => $remainingPoin,
                                'description' => "Sisa komisi referral (Level " . ($depth) . "-8 kosong) dari " . $transaksi['product_name'],
                                'pointable_type' => 'transaksi',
                                'pointable_id' => $transaksiId,
                            ]);
                            $totalPoinGiven += $remainingPoin;
                        }
                        
                        if ($remainingCash >= 1) {
                            $db->table('users')->where('id', $creatorUserId)->set('balance', "balance + $remainingCash", false)->update();
                            $totalCashGiven += $remainingCash;
                        }
                        
                        // Notify creator
                        if ($remainingPoin >= 1 || $remainingCash >= 1) {
                            try {
                                $notifModel = new \App\Models\NotificationModel();
                                $notifTitle = "Sisa Komisi Referral Masuk!";
                                $notifMsg = "Anda menerima sisa komisi referral dari transaksi " . $transaksi['product_name'] . " karena upline chain tidak lengkap. ";
                                $notifMsg .= "Detail: ";
                                if ($remainingPoin >= 1) {
                                    $notifMsg .= "Poin +" . number_format($remainingPoin);
                                }
                                if ($remainingCash >= 1) {
                                    if ($remainingPoin >= 1) $notifMsg .= ", ";
                                    $notifMsg .= "Cash +Rp " . number_format($remainingCash, 0, ',', '.');
                                }
                                
                                $notifModel->createNotification(
                                    $creatorUserId,
                                    $notifTitle,
                                    $notifMsg,
                                    'success',
                                    base_url($creatorType === 'WPA' ? 'wpa/dashboard/transaksi' : 'cwpa/dashboard/transaksi')
                                );
                            } catch (\Throwable $e) {
                                log_message('error', 'Remaining Commission Notification Failed: ' . $e->getMessage());
                            }
                        }
                        
                        // Reset pool to 0
                        $currentPoinPool = 0;
                        $currentCashPool = 0;
                    }
                }
            }

            // 1.5 Process PARTNERSHIP ADMIN COMMISSION (Fixed 8% on Direct Referral)
            // If the user's direct affiliator is a Partnership Admin, they get 8% of the total amount in POIN
            if (!empty($currentReferralCode)) {
                $referrer = $userModel->where('level_id', LevelModel::LEVEL_PARTNERSHIP)
                                      ->where('code_referral', $currentReferralCode)
                                      ->first();
                
                if ($referrer) {
                    $partnershipCommission = floor($transaksi['total'] * 8 / 100);
                    
                    if ($partnershipCommission >= 1) {
                        $poinModel = new PoinModel();
                        $poinModel->insert([
                            'user_id' => $referrer['id'],
                            'type' => 'earn',
                            'point' => $partnershipCommission,
                            'description' => "Komisi Partnership Admin (8%) dari " . $transaksi['product_name'],
                            'pointable_type' => 'transaksi',
                            'pointable_id' => $transaksiId,
                        ]);
                        $totalPoinGiven += $partnershipCommission;

                        // Notify Partnership Admin
                        try {
                            $notifModel = new \App\Models\NotificationModel();
                            $notifModel->createNotification(
                                $referrer['id'],
                                "Komisi Partnership Masuk!",
                                "Selamat! Anda mendapatkan komisi 8% dari transaksi referral Anda (" . $transaksi['product_name'] . "). Detail: Poin +" . number_format($partnershipCommission),
                                'success',
                                base_url('laporan-kegiatan/profile')
                            );
                        } catch (\Throwable $e) {
                            log_message('error', 'Partnership Admin Notification Failed: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Save results to transaction
            $this->update($transaksiId, [
                'referral_poin' => $totalPoinGiven,
                'notes' => $transaksi['notes'] . " (Referral Reward: Poin=$totalPoinGiven, Cash=$totalCashGiven)"
            ]);
        }
        // End if referral_poin == 0

        // 2. Process BUYER BONUS (Welcome Bonus)
        if (!empty($transaksi['user_id']) && !empty($transaksi['referral_code'])) {
            $buyer = $userModel->find($transaksi['user_id']);

            if ($buyer) {
                $poinModel = new PoinModel();
                $existingBonus = $poinModel->where('user_id', $transaksi['user_id'])
                    ->where('type', 'bonus_referral_registration')
                    ->first();

                if (!$existingBonus) {
                    // Get bonus amount from Global Settings
                    $settingModel = new SettingModel();
                    $newUserBonus = (int) $settingModel->get('poin_new_user_referral_bonus', 500);

                    if ($newUserBonus > 0) {
                        $poinModel->insert([
                            'user_id' => $transaksi['user_id'],
                            'type' => 'bonus_referral_registration',
                            'point' => $newUserBonus,
                            'description' => 'Bonus daftar menggunakan kode referral',
                            'pointable_type' => 'transaksi',
                            'pointable_id' => $transaksiId,
                        ]);
                    }
                }
            }
        }

        return $totalPoinGiven;
    }
    /**
     * Process WPA Commission
     * Called when transaction is confirmed
     */
    public function processWpaCommission($transaksiId)
    {
        $transaksi = $this->find($transaksiId);
        if (!$transaksi) return false;

        $db = \Config\Database::connect();
        $type = strtolower($transaksi['product_type'] ?? 'layanan');
        $id = $transaksi['layanan_id'];

        $wpaUserId = null;
        $cwpaUserId = null;
        $poinPrice = 0;

        // Find WPA ID or CWPA ID from item
        $item = null;
        $selectFields = ['wpa_id', 'referral_wpa_poin', 'referral_wpa_cash'];
        
        $tableName = '';
        if ($type === 'layanan' || $type === 'course') {
            $tableName = 'layanan';
        } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
            $tableName = 'layanan_event';
        } elseif ($type === 'tool' || $type === 'tools') {
            $tableName = 'layanan_tools';
        } elseif ($type === 'artikel') {
            $tableName = 'layanan_artikel';
        } elseif ($type === 'subscription' || in_array($type, ['cwpa', 'pendampingan', 'profirm', 'vip_member', 'private_konsultan'])) {
            $tableName = 'layanan_subscription';
        }

        if ($tableName) {
            $select = implode(', ', $selectFields);
            if ($db->fieldExists('poin_price', $tableName)) $select .= ', poin_price';
            if ($db->fieldExists('cwpa_id', $tableName)) $select .= ', cwpa_id';
            
            $item = $db->table($tableName)->select($select)->where('id', $id)->get()->getRowArray();
        }
        
        // Special handling for virtual IDs (like hardcoded CWPA)
        if (!$item && $type === 'cwpa' && $id == 0) {
            $item = [
                'cwpa_id' => 1,
                'poin_price' => 235000
            ];
        }

        if ($item && !empty($item['cwpa_id'])) {
            // It's a CWPA Service
            $cwpa = $db->table('cwpa')->select('user_id')->where('id', $item['cwpa_id'])->get()->getRowArray();
            if ($cwpa) {
                $cwpaUserId = $cwpa['user_id'];
                $poinPrice = $item['poin_price'];
            }
        } elseif ($item && !empty($item['wpa_id'])) {
            // Get WPA User ID
            $wpa = $db->table('wpa')->select('user_id')->where('id', $item['wpa_id'])->get()->getRowArray();
            if ($wpa) {
                $wpaUserId = $wpa['user_id'];
            }
        }

        if ($cwpaUserId) {
            $poinModel = new PoinModel();

            // Check if already processed to prevent duplicates
            $existing = $poinModel->where([
                'user_id' => $cwpaUserId,
                'pointable_type' => 'transaksi',
                'pointable_id' => $transaksiId
            ])->first();

            if ($existing) return true;
            
            // Give Full Poin Price to CWPA
            $poinModel->insert([
                'user_id' => $cwpaUserId,
                'type' => 'earn',
                'point' => $poinPrice,
                'description' => "Penjualan layanan {$transaksi['product_name']}",
                'pointable_type' => 'transaksi',
                'pointable_id' => $transaksiId,
            ]);

            // Notify CWPA
            try {
                $notifModel = new \App\Models\NotificationModel();
                $buyer = $db->table('users')->select('name')->where('id', $transaksi['user_id'])->get()->getRowArray();
                $buyerName = $buyer['name'] ?? 'Pelanggan';

                $notifModel->createNotification(
                    $cwpaUserId,
                    "Penjualan Layanan Berhasil!",
                    "Layanan '{$transaksi['product_name']}' Anda telah dibeli oleh $buyerName. Poin sebesar {$poinPrice} telah ditambahkan ke saldo Anda.",
                    'success',
                    base_url('cwpa/dashboard/transaksi')
                );
            } catch (\Throwable $e) {
                log_message('error', 'CWPA Sale Notification Failed: ' . $e->getMessage());
            }

            return true;
        } elseif ($wpaUserId) {
            $wpaPoin = (float) ($item['referral_wpa_poin'] ?? 0);
            $wpaCash = (float) ($item['referral_wpa_cash'] ?? 0);

            $poinModel = new PoinModel();
            
            // Generate notifications conditionally
            $buyer = $db->table('users')->select('name')->where('id', $transaksi['user_id'])->get()->getRowArray();
            $buyerName = $buyer['name'] ?? 'Pelanggan';
            $notifMsg = "Layanan '{$transaksi['product_name']}' Anda telah dibeli oleh $buyerName.";

            if ($wpaPoin > 0 || $wpaCash > 0) {
                $notifMsg .= " Anda mendapatkan Komisi WPA: ";
                if ($wpaPoin > 0) {
                    $poinModel->insert([
                        'user_id' => $wpaUserId,
                        'type' => 'earn',
                        'point' => $wpaPoin,
                        'description' => "pembelian layanan dari {$buyerName}",
                        'pointable_type' => 'layanan',
                        'pointable_id' => $transaksiId,
                    ]);
                    $notifMsg .= "Poin +".number_format($wpaPoin);
                }
                
                if ($wpaCash > 0) {
                    $db->table('users')->where('id', $wpaUserId)->set('balance', "balance + $wpaCash", false)->update();
                    if ($wpaPoin > 0) $notifMsg .= ", ";
                    $notifMsg .= "Cash +Rp ".number_format($wpaCash, 0, ',', '.');
                }
            } else {
                $notifMsg .= " (Belum ada komisi yang diatur untuk layanan ini).";
            }

            // Send Notification to WPA
            try {
                $notifModel = new \App\Models\NotificationModel();
                $notifModel->createNotification(
                    $wpaUserId,
                    "Penjualan Layanan Berhasil!",
                    $notifMsg,
                    'success',
                    base_url('wpa/dashboard/transaksi')
                );
            } catch (\Throwable $e) {
                log_message('error', 'WPA Sale Notification Failed: ' . $e->getMessage());
            }

            return true;
        }

        return false;
    }
    /**
     * Process EA License Generation
     * Called when transaction is confirmed
     */
    public function processEaLicense($transaksiId)
    {
        $transaksi = $this->find($transaksiId);
        if (!$transaksi) return false;

        $db = \Config\Database::connect();
        $type = strtolower($transaksi['product_type'] ?? 'layanan');
        $id = $transaksi['layanan_id'];

        // 1. Check if product is License Product
        $item = null;
        if ($type === 'tool' || $type === 'tools') {
            $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
            $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
        }

        if (!$item || empty($item['is_license_product'])) {
            return false; // Not a license product
        }

        // 2. Get User's Trading Account
        // Try to get from user_data first (if stored there) or require user to input it
        // Ideally, checkout should specificy the trading account, maybe in notes or a specific field?
        // For now, let's assume we look up the user's KYC/Profile data or a dedicated table.
        // Or if checkout doesn't capture it, we can't generate it yet.
        // Assuming user_data has 'mt5_account' or we check a 'forex_accounts' table if exists.

        $accountNumber = null;
        // Check if notes contains account number (Format: "Account: 123456")
        if (!empty($transaksi['notes']) && preg_match('/Account:\s*(\d+)/i', $transaksi['notes'], $matches)) {
            $accountNumber = $matches[1];
        } else {
            // Fallback: Check user profile
            $userData = $db->table('user_data')->where('user_id', $transaksi['user_id'])->get()->getRowArray();
            if ($userData && !empty($userData['mt5_account'])) {
                $accountNumber = $userData['mt5_account'];
            }
        }

        if (!$accountNumber) {
            // Log error or cannot generate
            log_message('error', "Cannot generate EA License for Transaksi #$transaksiId: No Account Number found.");
            return false;
        }

        // 3. Generate License Key
        // Format: NAME-ACCOUNT-RANDOM
        $productNameSlug = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $item['title'] ?? $item['name']), 0, 10));
        $random = strtoupper(substr(md5(uniqid()), 0, 4));
        $licenseKey = $productNameSlug . '-' . $accountNumber . '-' . $random;

        // 4. Calculate Expiration
        $expiresAt = null;
        if (!empty($item['license_duration']) && $item['license_duration'] > 0) {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$item['license_duration']} days"));
        }

        // 5. Insert to ea_licenses
        $licenseModel = new \App\Models\EaLicenseModel();

        // Check duplication
        $exist = $licenseModel->where('transaksi_id', $transaksiId)->first();
        if ($exist) return true; // Already generated

        $licenseId = $licenseModel->insert([
            'transaksi_id' => $transaksiId,
            'user_id' => $transaksi['user_id'],
            'broker_name' => 'Auto', // Can be refined
            'account_trading_number' => $accountNumber,
            'license_key' => $licenseKey,
            'status' => 'active',
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($licenseId) {
 
            return true;
        }

        return false;
    }
}
