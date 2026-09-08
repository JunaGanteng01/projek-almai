<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TransaksiModel;
use App\Models\CwpaModel;
use App\Models\LayananModel;
use App\Models\WpaModel;

class User extends BaseController
{
    public function index()
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login')->with('error', 'Profil CWPA tidak ditemukan');

        $userId = $this->session->get('userId');
        $userModel = new UserModel();
        $transaksiModel = new TransaksiModel();
        $layananModel = new LayananModel();

        // Get CWPA's own referral code
        $cwpaUser = $userModel->find($userId);
        $referralCode = $cwpaUser['code_referral'] ?? null;

        // Get CWPA's all services (to identify buyers)
        $allLayanan = $layananModel->getByCwpaId($cwpaId);

        // Tab filter
        $tab = $this->request->getGet('tab') ?? 'all';
        $search = $this->request->getGet('search');

        $users = [];
        $referralUserIds = [];
        $buyerUserIds = [];

        // 1. Get Recursive Downlines (Max 10 Levels)
        $referralUserIds = []; // Direct (Level 1)
        $allDownlineIds = [];  // Total Recursive (Level 1-10)

        // Start from CWPA User
        $currentLevelUsers = [$cwpaUser];
        $processedIds = [$userId]; // Prevent cycles (exclude self)

        for ($level = 1; $level <= 10; $level++) {
            if (empty($currentLevelUsers)) break;

            $parentIds = [];
            $parentNamesLower = [];
            $parentCodes = [];

            foreach ($currentLevelUsers as $p) {
                if (!$p) continue;
                $parentIds[] = $p['id'];
                if (!empty($p['name'])) $parentNamesLower[] = strtolower($p['name']);
                if (!empty($p['code_referral'])) $parentCodes[] = $p['code_referral'];
                // Check 'referral_code' legacy column just in case
                if (isset($p['referral_code']) && !empty($p['referral_code'])) $parentCodes[] = $p['referral_code'];
            }

            $parentIds = array_unique($parentIds);
            $parentNamesLower = array_unique(array_filter($parentNamesLower));
            $parentCodes = array_unique(array_filter($parentCodes));

            if (empty($parentIds) && empty($parentCodes) && empty($parentNamesLower)) break;

            // Optimized Search: Limit exclusion list if it's huge, or handle in PHP
            $searchModel = new UserModel();
            $subBuilder = $searchModel;
            
            // Only use whereNotIn if the list is manageable for CI's regex
            if (count($processedIds) < 1000) {
                $subBuilder->whereNotIn('id', $processedIds);
            }

            $subBuilder->groupStart();
            $hasCriteria = false;

            if (!empty($parentIds)) {
                $subBuilder->whereIn('referred_by', $parentIds);
                $hasCriteria = true;
            }

            if (!empty($parentCodes)) {
                if ($hasCriteria) $subBuilder->orWhereIn('affiliator_code', $parentCodes);
                else {
                    $subBuilder->whereIn('affiliator_code', $parentCodes);
                    $hasCriteria = true;
                }
            }

            if (!empty($parentNamesLower)) {
                // Limit names list to prevent SQL length issues
                $slicedNames = array_slice($parentNamesLower, 0, 500);
                $namesStr = implode("','", array_map(function ($n) {
                    return \Config\Database::connect()->escapeString($n);
                }, $slicedNames));

                $rawSql = "LOWER(affiliator_code) IN ('$namesStr')";
                if ($hasCriteria) $subBuilder->orWhere($rawSql);
                else {
                    $subBuilder->where($rawSql);
                    $hasCriteria = true;
                }
            }
            $subBuilder->groupEnd();

            $newDownlines = $subBuilder->findAll();
            
            // Manual filter if we skipped whereNotIn
            if (count($processedIds) >= 1000) {
                $newDownlines = array_filter($newDownlines, fn($u) => !in_array($u['id'], $processedIds));
            }

            if (empty($newDownlines)) break;

            $nextLevelUsers = [];
            foreach ($newDownlines as $d) {
                if (!in_array($d['id'], $processedIds)) {
                    $allDownlineIds[] = $d['id'];
                    $processedIds[] = $d['id'];
                    $nextLevelUsers[] = $d;

                    // Capture Direct (Level 1)
                    if ($level === 1) {
                        $referralUserIds[] = $d['id'];
                    }
                }
            }
            $currentLevelUsers = $nextLevelUsers;
        }

        // 2. Get Buyer Users
        if (!empty($allLayanan)) {
            $layananIds = array_filter(array_column($allLayanan, 'id'));
            $layananNames = array_unique(array_filter(array_map(function ($item) {
                return $item['name'] ?? $item['title'] ?? null;
            }, $allLayanan)));

            $tModel = new TransaksiModel();
            $tModel->select('DISTINCT(user_id) as user_id');
            $tModel->where('status', 'confirmed');
            
            $tModel->groupStart();
            if (!empty($layananIds)) {
                $tModel->whereIn('layanan_id', $layananIds);
            }
            if (!empty($layananNames)) {
                $tModel->orWhereIn('product_name', $layananNames);
                if (count($layananNames) < 50) {
                    foreach ($layananNames as $name) {
                        $tModel->orLike('product_name', $name . ' - ', 'after');
                    }
                }
            }
            $tModel->groupEnd();

            $buyerUsersResult = $tModel->findAll();
            $buyerUserIds = array_column($buyerUsersResult, 'user_id');
        }

        // 3. Filter Users based on Tab
        $targetUserIds = [];
        if ($tab === 'referral') {
            $targetUserIds = array_unique($allDownlineIds);
        } elseif ($tab === 'buyer') {
            $targetUserIds = $buyerUserIds;
        } else {
            $targetUserIds = array_unique(array_merge($allDownlineIds, $buyerUserIds));
        }

        $pager = null;
        if (!empty($targetUserIds)) {
            $finalUserModel = new UserModel();
            
            // If target list is massive, split it to avoid regex issues in CI4
            if (count($targetUserIds) > 2000) {
                $targetUserIds = array_slice($targetUserIds, 0, 2000);
            }
            
            $builder = $finalUserModel->whereIn('id', $targetUserIds);
            if ($search) {
                $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
            }
            $users = $builder->orderBy('created_at', 'DESC')->paginate(20);
            $pager = $finalUserModel->pager;
        }

        // 4. Enrich User Data with Stats (Batched)
        if (!empty($users)) {
            $pageUserIds = array_column($users, 'id');
            $statsMap = [];

            if (!empty($allLayanan)) {
                $currentLayananIds = array_filter(array_column($allLayanan, 'id'));
                $currentLayananNames = array_unique(array_filter(array_map(function ($item) {
                    return $item['name'] ?? $item['title'] ?? null;
                }, $allLayanan)));

                $stModel = new TransaksiModel();
                $stModel->select('user_id, SUM(total) as total_spent, COUNT(id) as total_purchases');
                $stModel->whereIn('user_id', $pageUserIds);
                $stModel->where('status', 'confirmed');

                $stModel->groupStart();
                if (!empty($currentLayananIds)) {
                    $stModel->whereIn('layanan_id', $currentLayananIds);
                }
                if (!empty($currentLayananNames)) {
                    $stModel->orWhereIn('product_name', $currentLayananNames);
                    if (count($currentLayananNames) < 30) {
                        foreach ($currentLayananNames as $name) {
                            $stModel->orLike('product_name', $name . ' - ', 'after');
                        }
                    }
                }
                $stModel->groupEnd();
                $stModel->groupBy('user_id');

                $allStats = $stModel->findAll();
                foreach ($allStats as $s) {
                    $statsMap[$s['user_id']] = $s;
                }
            }

            foreach ($users as &$user) {
                $user['is_referral'] = in_array($user['id'], $allDownlineIds);
                $user['total_purchases'] = $statsMap[$user['id']]['total_purchases'] ?? 0;
                $user['total_spent'] = $statsMap[$user['id']]['total_spent'] ?? 0;
            }
        }

        // Combine Direct Referrals + Recursive Downlines + Buyers
        $allAssociatedUserIds = array_unique(array_merge($allDownlineIds, $buyerUserIds));

        $totalUserCount = count($allAssociatedUserIds);
        $totalDirectCount = count($referralUserIds); // Direct is strictly Level 1
        $totalCwpaCount = 0;
        $totalProCount = 0;
        $totalStandardUserCount = 0;

        if (!empty($allAssociatedUserIds)) {
            $statsModel = new UserModel();
            $statsQuery = $statsModel->whereIn('id', $allAssociatedUserIds)
                ->select('level_id, count(id) as count')
                ->groupBy('level_id')
                ->findAll();

            foreach ($statsQuery as $stat) {
                if ($stat['level_id'] == \App\Models\LevelModel::LEVEL_CWPA) {
                    $totalCwpaCount = $stat['count'];
                } elseif ($stat['level_id'] == \App\Models\LevelModel::LEVEL_PRO) {
                    $totalProCount = $stat['count'];
                } elseif ($stat['level_id'] == \App\Models\LevelModel::LEVEL_USER) {
                    $totalStandardUserCount = $stat['count'];
                }
            }
        }

        $totalTransactions = 0;
        if (!empty($allLayanan)) {
            $transactionModel = new TransaksiModel();
            $transactionModel->where('status', 'confirmed');
            
            $transactionModel->groupStart();
            if (!empty($layananIds)) {
                $transactionModel->whereIn('layanan_id', $layananIds);
            }
            if (!empty($layananNames)) {
                $transactionModel->orWhereIn('product_name', $layananNames);
                if (count($layananNames) < 50) {
                    foreach ($layananNames as $name) {
                        $transactionModel->orLike('product_name', $name . ' - ', 'after');
                    }
                }
            }
            $transactionModel->groupEnd();
            $totalTransactions = $transactionModel->countAllResults();
        }

        return view('cwpa/user/index', [
            'title' => 'User Management - CWPA Dashboard',
            'activeMenu' => 'user',
            'users' => $users,
            'pager' => $pager,
            'tab' => $tab,
            'search' => $search,
            'totalUserCount' => $totalUserCount,
            'totalDirectCount' => $totalDirectCount,
            'totalCwpaCount' => $totalCwpaCount,
            'totalProCount' => $totalProCount,
            'totalStandardUserCount' => $totalStandardUserCount,
            'totalTransactions' => $totalTransactions,
            'referralCode' => $referralCode,
        ]);
    }

    private function getCwpaId()
    {
        $cwpaId = $this->session->get('cwpaId');
        if (!$cwpaId) {
            $userId = $this->session->get('userId');
            $cwpaModel = new CwpaModel();
            $cwpa = $cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) {
                $cwpaId = $cwpa['id'];
                $this->session->set('cwpaId', $cwpaId);
            }
        }
        return $cwpaId;
    }
}
