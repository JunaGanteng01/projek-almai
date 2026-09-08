<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModelDebug extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'invoice_number', 'user_id', 'layanan_id', 'product_type', 'product_name',
        'amount', 'discount', 'total', 'payment_method', 'payment_proof',
        'status', 'notes', 'paid_at', 'confirmed_at',
        'referral_code', 'referrer_id', 'referral_poin', 'voucher_id'
    ];
    protected $useTimestamps = true;

    public function getWithUser()
    {
        return $this->select('transaksi.*, users.name as user_name, users.email as user_email, layanan.name as layanan_name, layanan.category as layanan_category, layanan.subcategory as layanan_subcategory')
                    ->join('users', 'users.id = transaksi.user_id', 'left')
                    ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left')
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
        return $this->where('status', $status)->selectSum('total')->first()['total'] ?? 0;
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
        $userModel = new \App\Models\UserModel();
        $db = \Config\Database::connect();

        // 1. Process REFERRER COMMISSION (MLM SCHEME)
        log_message('error', "MLM DEBUG: Processing Transaction $transaksiId. ReferralPoin: {$transaksi['referral_poin']}");
        
        if ($transaksi['referral_poin'] == 0) {
            
            // A. Determine Reward Configuration
            $rewardConfig = [
                'total_poin' => 0,
                'percentage' => 50, // Default 50% decay
                'max_depth' => 10
            ];

            $type = strtolower($transaksi['product_type'] ?? 'layanan');
            $id = $transaksi['layanan_id']; 

            log_message('error', "MLM DEBUG: Type: $type, ID: $id");

            // Fetch config from specific table
            $item = null;
            if ($type === 'layanan' || $type === 'course') {
                $item = $db->table('layanan')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
                $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'tool' || $type === 'tools') {
                $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
            } elseif ($type === 'artikel') {
                $item = $db->table('layanan_artikel')->where('id', $id)->get()->getRowArray();
            }

            if ($item) {
                if (!empty($item['referral_user_poin']) && $item['referral_user_poin'] > 0) {
                    $rewardConfig['total_poin'] = (int) $item['referral_user_poin'];
                    $rewardConfig['percentage'] = (int) ($item['referral_distribution_percentage'] ?? 50);
                    $rewardConfig['max_depth'] = (int) ($item['referral_max_depth'] ?? 10);
                    log_message('error', "MLM DEBUG: Item Config Found - Pool: {$rewardConfig['total_poin']}, Dist: {$rewardConfig['percentage']}, MaxDepth: {$rewardConfig['max_depth']}");
                } else {
                    $settingModel = new SettingModel();
                    $poinPercent = (int) $settingModel->get('poin_per_transaksi', 1);
                    $rewardConfig['total_poin'] = floor(($transaksi['total'] * $poinPercent) / 100);
                    $rewardConfig['max_depth'] = 1; 
                    $rewardConfig['percentage'] = 100;
                    log_message('error', "MLM DEBUG: Item Config Missing or Zero. Using Fallback."); 
                }
            } else {
                 $rewardConfig['total_poin'] = floor(($transaksi['total'] * 1) / 100);
                 $rewardConfig['max_depth'] = 1;
                 $rewardConfig['percentage'] = 100;
                 log_message('error', "MLM DEBUG: Item Not Found. Using Global Fallback.");
            }

            // B. Distribute Rewards Recursively
            $currentUserId = $transaksi['referrer_id']; 
            $currentPool = $rewardConfig['total_poin'];
            $depth = 1;
            
            log_message('error', "MLM DEBUG: Starting Loop. CurrentUser: $currentUserId, Pool: $currentPool");

            if ($currentPool > 0 && !empty($currentUserId)) {
                while ($depth <= $rewardConfig['max_depth'] && $currentPool >= 1 && $currentUserId) {
                    $user = $userModel->find($currentUserId);
                    if (!$user) {
                         log_message('error', "MLM DEBUG: User $currentUserId not found. Break.");
                         break;
                    }

                    $share = floor($currentPool * ($rewardConfig['percentage'] / 100));
                    
                    log_message('error', "MLM DEBUG: Depth $depth. User {$user['email']} (ID {$user['id']}). Share: $share. Current Pool: $currentPool");

                    if ($share < 1) break;

                    // Award Points
                    $poinModel = new PoinModel();
                    $poinModel->insert([
                        'user_id' => $currentUserId,
                        'type' => 'earn',
                        'point' => $share,
                        'description' => "Test Komisi referral (Level $depth)",
                        'pointable_type' => 'transaksi',
                        'pointable_id' => $transaksiId,
                    ]);

                    $totalPoinGiven += $share;
                    $currentPool -= $share;

                    // Move to Upline
                    $affiliatorCode = $user['affiliator_code'];
                    if ($affiliatorCode) {
                        $upline = $userModel->where('code_referral', $affiliatorCode)->first();
                        $currentUserId = $upline['id'] ?? null;
                         log_message('error', "MLM DEBUG: Creating next step. Upline Code: $affiliatorCode. Next User ID: $currentUserId");
                    } else {
                        $currentUserId = null;
                        log_message('error', "MLM DEBUG: No Upline Code. End of chain.");
                    }
                    
                    $depth++;
                }
            } else {
                 log_message('error', "MLM DEBUG: Pool or User Invalid/Empty start.");
            }

            if ($totalPoinGiven > 0) {
                $this->update($transaksiId, ['referral_poin' => $totalPoinGiven]);
                log_message('error', "MLM DEBUG: Updated Transaction with Total Given: $totalPoinGiven");
            }
        } // End if referral_poin == 0

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
}
