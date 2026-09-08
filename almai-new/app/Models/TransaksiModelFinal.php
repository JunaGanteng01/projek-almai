<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModelFinal extends Model
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

    // ... (helper methods omitted for brevity as they are not used in this test)

    public function processReferralPoin($transaksiId)
    {
        $transaksi = $this->find($transaksiId);
        if (!$transaksi) { echo "TRX NOT FOUND<br>"; return false; }

        $totalPoinGiven = 0;
        $userModel = new \App\Models\UserModel();
        $db = \Config\Database::connect();

        echo "DEBUG: Provoke Process $transaksiId. RefPoinFlag: {$transaksi['referral_poin']}<br>";
        
        if ($transaksi['referral_poin'] == 0) {
            
            $rewardConfig = [
                'total_poin' => 0,
                'percentage' => 50, 
                'max_depth' => 10
            ];

            $type = strtolower($transaksi['product_type'] ?? 'layanan');
            $id = $transaksi['layanan_id']; 

            echo "DEBUG: Type: $type, ID: $id<br>";

            $item = null;
            if ($type === 'layanan' || $type === 'course') {
                $item = $db->table('layanan')->where('id', $id)->get()->getRowArray();
            } 
            // ... (other types omitted for brevity, logic likely layanan)

            if ($item) {
                if (!empty($item['referral_user_poin']) && $item['referral_user_poin'] > 0) {
                    $rewardConfig['total_poin'] = (int) $item['referral_user_poin'];
                    $rewardConfig['percentage'] = (int) ($item['referral_distribution_percentage'] ?? 50);
                    $rewardConfig['max_depth'] = (int) ($item['referral_max_depth'] ?? 10);
                    echo "DEBUG: Config Loaded: P={$rewardConfig['total_poin']}, %={$rewardConfig['percentage']}, D={$rewardConfig['max_depth']}<br>";
                } else {
                    echo "DEBUG: Config Missing/Zero in Item. Using Fallback.<br>";
                    $rewardConfig['total_poin'] = 100; // Hardcoded fallback for test
                    $rewardConfig['max_depth'] = 1; 
                    $rewardConfig['percentage'] = 100;
                }
            } else {
                 echo "DEBUG: Item Not Found.<br>";
                 $rewardConfig['total_poin'] = 100;
                 $rewardConfig['max_depth'] = 1;
                 $rewardConfig['percentage'] = 100;
            }

            $currentUserId = $transaksi['referrer_id']; 
            $currentPool = $rewardConfig['total_poin'];
            $depth = 1;
            
            echo "DEBUG: Starting Chain. ReferrerId: $currentUserId. Pool: $currentPool<br>";

            if ($currentPool > 0 && !empty($currentUserId)) {
                while ($depth <= $rewardConfig['max_depth'] && $currentPool >= 1 && $currentUserId) {
                    $user = $userModel->find($currentUserId);
                    if (!$user) {
                         echo "DEBUG: User $currentUserId not found.<br>";
                         break;
                    }

                    $share = floor($currentPool * ($rewardConfig['percentage'] / 100));
                    echo "DEBUG: Depth $depth. User {$user['username']}. Share: $share. PoolLeft: $currentPool<br>";

                    if ($share < 1) break;

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

                    $affiliatorCode = $user['affiliator_code'];
                    if ($affiliatorCode) {
                        $upline = $userModel->where('code_referral', $affiliatorCode)->first();
                        $currentUserId = $upline['id'] ?? null;
                         echo "DEBUG: Next Upline: $affiliatorCode -> ID $currentUserId<br>";
                    } else {
                        $currentUserId = null;
                        echo "DEBUG: End of Chain.<br>";
                    }
                    
                    $depth++;
                }
            } else {
                 echo "DEBUG: Bad Start Condition within Loop.<br>";
            }

            if ($totalPoinGiven > 0) {
                $this->update($transaksiId, ['referral_poin' => $totalPoinGiven]);
                echo "DEBUG: Loop Done. Total Given: $totalPoinGiven. Updated.<br>";
            } else {
                echo "DEBUG: Loop Done. No Points Given.<br>";
            }
        } else {
            echo "DEBUG: Skipped. ReferralPoin not 0.<br>";
        }

        return $totalPoinGiven;
    }
}
