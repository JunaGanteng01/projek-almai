<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    // Updated to match the imported users.sql schema exactly
    protected $allowedFields = [
        // Basic info
        'name',
        'email',
        'phone',
        'password',
        'address',

        // Profile & verification
        'avatar',
        'profile',
        'email_verified_at',
        'verification_token',
        'remember_token',
        'status',
        'otp_status',

        // Referral & affiliate
        'affiliator_code',  // DB column is affiliator_code
        'code_referral',    // DB column is code_referral
        'referral_group_id',

        // User level & roles
        'level_id',
        'crm_role',
        'is_pro',
        'pro_expires_at',

        // Balance & points
        'balance',

        // Promo & marketing
        'promo_link_redirected_when_first_login',
        'registration_types',

        // KYC
        'kyc_status',
        'kyc_submitted_at',
        'secondary_level_ids', // Multi-role support
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';

    protected $beforeInsert = ['hashPassword', 'generateReferralCode'];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterInsert = ['notifyTelegramRegister', 'createCrmSession'];
    protected $afterUpdate = ['notifyTelegram'];

    protected function notifyTelegramRegister(array $data)
    {
        if (isset($data['id'])) {
            $user = $this->find($data['id']);
            if ($user) {
                (new \App\Libraries\TelegramService())->notifyAdmin('register', $user);
            }
        }
        return $data;
    }

    protected function createCrmSession(array $data)
    {
        $id = (int) ($data['id'] ?? 0);
        if ($id > 0) {
            try {
                (new \App\Services\CrmService())->onUserRegistered($id);
            } catch (\Throwable $e) {
                // CRM/WhatsApp tidak boleh menggagalkan proses registrasi utama.
                log_message('error', 'CRM registration hook failed: ' . $e->getMessage());
            }
        }
        return $data;
    }

    protected function notifyTelegram(array $data)
    {
        // Check if kyc_status was updated
        if (isset($data['data']['kyc_status'])) {
            $user = $this->find($data['id'][0] ?? $data['id'] ?? null);
            if ($user) {
                (new \App\Libraries\TelegramService())->notifyAdmin('kyc', $user);
            }
        }
        return $data;
    }

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    protected function generateReferralCode(array $data)
    {
        // Check for 'code_referral' instead of 'referral_code'
        if (!isset($data['data']['code_referral']) || empty($data['data']['code_referral'])) {
            $data['data']['code_referral'] = $this->createUniqueReferralCode();
        }
        return $data;
    }

    public function createUniqueReferralCode()
    {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while ($this->where('code_referral', $code)->first()); // Use code_referral

        return $code;
    }

    public function findByReferralCode($code)
    {
        // Case-insensitive search for referral code
        return $this->where('LOWER(code_referral)', strtolower($code))->first();
    }

    /**
     * Calculate total network count up to 10 levels (MLM style)
     * Robust version that avoids Query Builder regex limits
     */
    public function getNetworkCount($userId, $maxLevels = 10)
    {
        $ids = $this->getNetworkIds($userId, $maxLevels);
        return count($ids);
    }

    /**
     * Get all network user IDs up to X levels (MLM style)
     * SECURITY: Refactored to use CI4 Query Builder with parameter binding
     * to prevent SQL injection via string interpolation.
     */
    public function getNetworkIds($userId, $maxLevels = 10)
    {
        $db = \Config\Database::connect();
        $user = $this->find($userId);
        if (!$user) return [];

        $processedIds = [];
        $collectedIds = [];
        $currentLevelUsers = [];

        if (!empty($user['referral_group_id'])) {
            $groupMembers = $this->where('referral_group_id', $user['referral_group_id'])->findAll();
            foreach ($groupMembers as $member) {
                $processedIds[] = (int)$member['id'];
                $currentLevelUsers[] = $member;
            }
        } else {
            $processedIds[] = (int)$userId;
            $currentLevelUsers[] = $user;
        }

        for ($level = 1; $level <= $maxLevels; $level++) {
            if (empty($currentLevelUsers)) break;

            $parentIds      = [];
            $parentCodes    = [];
            $parentNamesLower = [];

            foreach ($currentLevelUsers as $u) {
                if (isset($u['id'])) $parentIds[] = (int)$u['id'];
                if (!empty($u['code_referral'])) $parentCodes[] = $u['code_referral'];
                if (!empty($u['referral_code'])) $parentCodes[] = $u['referral_code'];
                if (!empty($u['name'])) $parentNamesLower[] = strtolower($u['name']);
            }

            $parentIds        = array_unique($parentIds);
            $parentCodes      = array_unique(array_filter($parentCodes));
            $parentNamesLower = array_unique(array_filter($parentNamesLower));

            if (empty($parentIds) && empty($parentCodes) && empty($parentNamesLower)) break;

            // SECURITY FIX: Use CI4 Query Builder with proper parameter binding
            // instead of string interpolation to prevent SQL injection.
            $builder = $db->table('users')
                ->select('id, name, code_referral, referral_code');

            $builder->groupStart();

            $hasCondition = false;

            // Condition 1: referred_by matches any parent integer ID
            if (!empty($parentIds)) {
                // Cast to int to guarantee no injection from any source
                $safeParentIds = array_map('intval', $parentIds);
                $builder->whereIn('referred_by', $safeParentIds);
                $hasCondition = true;
            }

            // Condition 2: affiliator_code matches any parent referral code (bound via whereIn)
            if (!empty($parentCodes)) {
                $method = $hasCondition ? 'orWhereIn' : 'whereIn';
                $builder->{$method}('affiliator_code', $parentCodes);
                $hasCondition = true;
            }

            // Condition 3: name-based fallback — only when affiliator_code is NOT an
            // existing referral code (prevents cross-user data leakage).
            // Uses LOWER() comparison with bound values via whereIn.
            if (!empty($parentNamesLower)) {
                // Subquery: get all active referral codes so we can exclude them
                $activeCodes = $db->table('users')
                    ->select('code_referral')
                    ->where('code_referral IS NOT NULL')
                    ->where('code_referral !=', '')
                    ->get()->getResultArray();
                $activeCodeValues = array_column($activeCodes, 'code_referral');
                $activeCodeValuesLower = array_map('strtolower', $activeCodeValues);

                // Only keep names that are NOT existing referral codes
                $safeFallbackNames = array_filter(
                    $parentNamesLower,
                    fn($name) => !in_array($name, $activeCodeValuesLower, true)
                );

                if (!empty($safeFallbackNames)) {
                    // Use raw LOWER() with whereIn binding — CI4 whereIn escapes each value
                    $builder->orGroupStart();
                    $builder->whereIn('LOWER(affiliator_code)', $safeFallbackNames);
                    $builder->groupEnd();
                }
            }

            $builder->groupEnd();

            // Exclude already-processed IDs (all are int-cast, safe)
            if (!empty($processedIds)) {
                $safeProcessedIds = array_map('intval', $processedIds);
                $builder->whereNotIn('id', $safeProcessedIds);
            }

            $newDownlines = $builder->get()->getResultArray();

            if (empty($newDownlines)) break;

            $currentLevelUsers = [];
            foreach ($newDownlines as $d) {
                $uid = (int)$d['id'];
                $collectedIds[]    = $uid;
                $processedIds[]    = $uid;
                $currentLevelUsers[] = $d;
            }

            if (count($processedIds) > 10000) break; // Hard limit
        }

        return $collectedIds;
    }
}
