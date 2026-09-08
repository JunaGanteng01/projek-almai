<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\ArtikelModel;
use App\Models\ToolsModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $wpaModel = new WpaModel();
        $kelasModel = new KelasModel();
        $userModel = new UserModel();
        $artikelModel = new ArtikelModel();
        $toolsModel = new ToolsModel();
        $transaksiModel = new TransaksiModel();

        // Get current admin info
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        // Apply admin filter helper function
        $applyAdminFilter = function ($builder) use ($currentLevelId, $currentReferralCode) {
            if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
                if (!empty($currentReferralCode)) {
                    return $builder->where('affiliator_code', $currentReferralCode);
                } else {
                    return $builder->where('1', '0'); // No results if no referral code
                }
            }
            return $builder;
        };

        // Count users with admin filter
        $wpaBuilder = clone $userModel;
        $totalWpa = $applyAdminFilter($wpaBuilder)
            ->join('wpa', 'wpa.user_id = users.id')
            ->where('users.level_id', \App\Models\LevelModel::LEVEL_WPA)
            ->countAllResults();

        $cwpaBuilder = clone $userModel;
        $totalCwpa = $applyAdminFilter($cwpaBuilder)
            ->join('cwpa', 'cwpa.user_id = users.id')
            ->where('users.level_id', \App\Models\LevelModel::LEVEL_CWPA)
            ->countAllResults();

        $userBuilder = clone $userModel;
        $totalUsers = $applyAdminFilter($userBuilder)->countAllResults();

        $proBuilder = clone $userModel;
        $totalUserPro = $applyAdminFilter($proBuilder)->where('level_id', \App\Models\LevelModel::LEVEL_PRO)->countAllResults();

        $adminBuilder = clone $userModel;
        $totalAdmin = $applyAdminFilter($adminBuilder)->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN,
            \App\Models\LevelModel::LEVEL_ACCOUNTING
        ])->countAllResults();

        // Get total revenue (confirmed transactions)
        $revenueBuilder = $transaksiModel->where('transaksi.status', 'confirmed');
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $revenueBuilder->join('users', 'users.id = transaksi.user_id')
                ->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $revenueBuilder->where('1', '0');
        }
        $totalRevenue = $revenueBuilder->selectSum('transaksi.total')
            ->first()['total'] ?? 0;

        // Get monthly revenue for chart (last 6 months)
        $revenueData = $this->getMonthlyRevenue($transaksiModel, $currentLevelId, $currentReferralCode);

        // Get monthly user growth for chart (last 6 months)
        $userGrowthData = $this->getMonthlyUserGrowth($userModel, $currentLevelId, $currentReferralCode);

        // Get recent transactions
        $recentTrxBuilder = $transaksiModel->select('transaksi.*, users.name as user_name')
            ->join('users', 'users.id = transaksi.user_id', 'left');
        
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $recentTrxBuilder->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $recentTrxBuilder->where('1', '0');
        }

        $recentTransactions = $recentTrxBuilder->orderBy('transaksi.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title' => 'Admin Dashboard - Almai',
            'totalWpa' => $totalWpa,
            'totalCwpa' => $totalCwpa, // Added CWPA
            'totalKelas' => $kelasModel->countAll(),
            'totalUsers' => $totalUsers,
            'totalUserPro' => $totalUserPro,
            'totalAdmin' => $totalAdmin,
            'totalArtikel' => $artikelModel->countAll(),
            'totalTools' => $toolsModel->countAll(),
            'totalRevenue' => $totalRevenue,
            'wpaList' => $wpaModel->orderBy('rating', 'DESC')->limit(5)->findAll(),
            'kelasList' => $kelasModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'recentTransactions' => $recentTransactions,
            'revenueData' => $revenueData,
            'userGrowthData' => $userGrowthData,
            'categoryRevenueData' => $this->getRevenueByCategory($transaksiModel, $currentLevelId, $currentReferralCode),
            'isAdmin' => $currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN,
            'isSuperAdmin' => $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN,
        ];

        return view('admin/dashboard', $data);
    }

    private function getRevenueByCategory($transaksiModel, $currentLevelId = null, $currentReferralCode = null)
    {
        $builder = $transaksiModel->where('transaksi.status', 'confirmed');
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $builder->join('users', 'users.id = transaksi.user_id')
                ->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $builder->where('1', '0');
        }
        $confirmedTransactions = $builder->findAll();

        // Get all layanan data for mapping
        $layananController = new \App\Controllers\Layanan();
        $method = new \ReflectionMethod(\App\Controllers\Layanan::class, 'getLayananData');
        $method->setAccessible(true);
        $allLayanan = $method->invoke($layananController);

        // Map Name to Subcategory (Name is more reliable than ID here due to potential collisions)
        $map = [];
        foreach ($allLayanan as $l) {
            $map[$l['name']] = $l['subcategory'];
        }

        $revenueByCategory = [];

        foreach ($confirmedTransactions as $trx) {
            $subcategory = 'Lain-lain';

            if ($trx['product_type'] === 'kelas') {
                $subcategory = 'Kelas';
            } elseif ($trx['product_type'] === 'tools') {
                $subcategory = 'Tools';
            } elseif ($trx['product_type'] === 'layanan') {
                $name = $trx['product_name'];
                // Handle package suffix if any (e.g. "Webinar Title - Package Name")
                $nameParts = explode(' - ', $name);
                $baseName = $nameParts[0];

                if (isset($map[$name])) {
                    $subcategory = $map[$name];
                } elseif (isset($map[$baseName])) {
                    $subcategory = $map[$baseName];
                } else {
                    // Try partial match if name is slightly different
                    foreach ($map as $mName => $mSub) {
                        if (stripos($name, $mName) !== false) {
                            $subcategory = $mSub;
                            break;
                        }
                    }
                }
            } elseif ($trx['product_type'] === 'poin') {
                $subcategory = 'Top Up Poin';
            }

            if (!isset($revenueByCategory[$subcategory])) {
                $revenueByCategory[$subcategory] = 0;
            }
            $revenueByCategory[$subcategory] += $trx['total'];
        }

        // Sort by revenue
        arsort($revenueByCategory);

        $labels = array_keys($revenueByCategory);
        $data = array_values($revenueByCategory);

        $colors = [
            '#33e818',
            '#3b82f6',
            '#f59e0b',
            '#ef4444',
            '#8b5cf6',
            '#ec4899',
            '#10b981',
            '#06b6d4',
            '#6366f1',
            '#f97316'
        ];

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    private function getMonthlyRevenue($transaksiModel, $currentLevelId = null, $currentReferralCode = null)
    {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $builder = $transaksiModel->where('transaksi.status', 'confirmed')
                ->where('transaksi.created_at >=', $startDate)
                ->where('transaksi.created_at <=', $endDate);

            if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
                $builder->join('users', 'users.id = transaksi.user_id')
                    ->where('users.affiliator_code', $currentReferralCode);
            } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
                $builder->where('1', '0');
            }

            $revenue = $builder->selectSum('transaksi.total')
                ->first()['total'] ?? 0;

            $revenues[] = (int) $revenue;
        }

        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    private function getMonthlyUserGrowth($userModel, $currentLevelId = null, $currentReferralCode = null)
    {
        $months = [];
        $users = [];

        $db = \Config\Database::connect();

        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            // Apply admin filter if needed
            if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
                $result = $db->query(
                    "SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ? AND affiliator_code = ?",
                    [$startDate, $endDate, $currentReferralCode]
                )->getRow();
            } else {
                // Use raw query to avoid builder state issues
                $result = $db->query(
                    "SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ?",
                    [$startDate, $endDate]
                )->getRow();
            }

            $users[] = (int) ($result->count ?? 0);
        }

        return [
            'labels' => $months,
            'data' => $users
        ];
    }

    /**
     * AJAX endpoint for revenue data
     */
    public function revenueData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $transaksiModel = new TransaksiModel();
        $userModel = new UserModel();

        // Get current admin info
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        $months = [];
        $revenues = [];

        // Calculate number of periods based on days
        $periods = min(12, max(1, ceil($days / 30)));

        for ($i = $periods - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $builder = $transaksiModel->where('transaksi.status', 'confirmed')
                ->where('transaksi.created_at >=', $startDate)
                ->where('transaksi.created_at <=', $endDate);

            if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
                $builder->join('users', 'users.id = transaksi.user_id')
                    ->where('users.affiliator_code', $currentReferralCode);
            } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
                $builder->where('1', '0');
            }

            $revenue = $builder->selectSum('transaksi.total')
                ->first()['total'] ?? 0;

            $revenues[] = (int) $revenue;
        }

        return $this->response->setJSON([
            'labels' => $months,
            'data' => $revenues
        ]);
    }

    /**
     * AJAX endpoint for user growth data
     */
    public function userGrowthData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $userModel = new UserModel();

        // Get current admin info
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        $months = [];
        $users = [];
        $db = \Config\Database::connect();

        // Calculate number of periods based on days
        $periods = min(12, max(1, ceil($days / 30)));

        for ($i = $periods - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            // Apply admin filter if needed
            if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
                $result = $db->query(
                    "SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ? AND affiliator_code = ?",
                    [$startDate, $endDate, $currentReferralCode]
                )->getRow();
            } else {
                $result = $db->query(
                    "SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ?",
                    [$startDate, $endDate]
                )->getRow();
            }

            $users[] = (int) ($result->count ?? 0);
        }

        return $this->response->setJSON([
            'labels' => $months,
            'data' => $users
        ]);
    }
}
