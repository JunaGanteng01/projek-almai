<?php

namespace App\Controllers\AdminWpa;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\ArtikelModel;
use App\Models\ToolsModel;
use App\Models\TransaksiModel;
use App\Models\AdminWpaAssignmentModel;
use App\Models\LevelModel;

class Dashboard extends BaseController
{
    protected $wpaModel;
    protected $assignmentModel;
    protected $userModel;

    public function __construct()
    {
        $this->wpaModel = new WpaModel();
        $this->assignmentModel = new AdminWpaAssignmentModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        
        if (!in_array($currentLevelId, [LevelModel::LEVEL_ADMIN_WPA, LevelModel::LEVEL_SUPER_ADMIN])) {
            return redirect()->to('/admin-wpa/login')->with('error', 'Akses ditolak.');
        }

        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) {
            $data = [
                'title' => 'Admin WPA Dashboard',
                'assignedWPAs' => [],
                'totalEarnings' => 0,
                'totalSales' => 0,
                'totalUsers' => 0,
                'wpaStats' => [],
                'recentTransactions' => [],
                'message' => 'Anda belum ditugaskan untuk mengelola WPA manapun.'
            ];
            return view('admin_wpa/dashboard', $data);
        }

        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        
        $db = \Config\Database::connect();
        
        // Define all possible tables that might contain WPA products
        // Format: 'table_name' => 'display_name_column'
        $productTableMap = [
            'layanan' => 'name',
            'layanan_event' => 'title',
            'layanan_tools' => 'name',
            'layanan_subscription' => 'name',
            'kelas' => 'title',
            'tools' => 'name',
            'artikel' => 'title'
        ];

        // Ensure we check which tables actually exist and have wpa_id to avoid errors
        $existingTables = $db->listTables();
        $productTables = [];
        foreach ($productTableMap as $table => $col) {
            if (in_array($table, $existingTables)) {
                $fields = $db->getFieldNames($table);
                if (in_array('wpa_id', $fields) && in_array($col, $fields)) {
                    $productTables[$table] = $col;
                }
            }
        }

        $allProductNames = [];
        $allLayananIds = [];

        foreach ($productTables as $table => $nameCol) {
            $prods = $db->table($table)->select("id, $nameCol")->whereIn('wpa_id', $assignedWpaIds)->get()->getResultArray();
            foreach ($prods as $p) {
                if (!empty($p[$nameCol])) $allProductNames[] = $p[$nameCol];
                if (!empty($p['id'])) $allLayananIds[] = $p['id'];
            }
        }

        // Clean arrays
        $allProductNames = array_unique(array_filter($allProductNames));
        $allLayananIds = array_unique(array_filter($allLayananIds));

        $transaksiModel = new TransaksiModel();

        // Robust filter function
        $applyWpaFilter = function($builder) use ($allLayananIds, $allProductNames) {
            if (!empty($allLayananIds) || !empty($allProductNames)) {
                $builder->groupStart();
                $hasCondition = false;
                
                if (!empty($allLayananIds)) {
                    $builder->whereIn('layanan_id', $allLayananIds);
                    $hasCondition = true;
                }
                
                if (!empty($allProductNames)) {
                    if ($hasCondition) {
                        $builder->orGroupStart();
                    } else {
                        $builder->groupStart();
                    }
                    
                    foreach ($allProductNames as $index => $name) {
                        if ($index === 0) {
                            $builder->like('product_name', $name, 'both');
                        } else {
                            $builder->orLike('product_name', $name, 'both');
                        }
                    }
                    $builder->groupEnd();
                    $hasCondition = true;
                }
                
                $builder->groupEnd();
                
                if (!$hasCondition) {
                    $builder->where('1 = 0');
                }
            } else {
                $builder->where('1 = 0');
            }
            return $builder;
        };

        // Calculate Overview Stats
        $statsQuery = $transaksiModel->builder();
        $statsQuery->where('status', 'confirmed');
        $applyWpaFilter($statsQuery);
        
        $overviewStats = $statsQuery->select('
            COUNT(*) as total_sales,
            SUM(CASE WHEN payment_method != "poin" THEN total ELSE 0 END) as total_earnings
        ')->get()->getRowArray();

        $totalEarnings = (float)($overviewStats['total_earnings'] ?? 0);
        $totalSales = (int)($overviewStats['total_sales'] ?? 0);

        // Fetch all user network IDs for assigned WPAs to match "Kelola User" logic
        $allNetworkIds = [];
        foreach ($WPAs as $wpa) {
            $networkIds = $this->userModel->getNetworkIds($wpa['user_id'], 10);
            $allNetworkIds = array_merge($allNetworkIds, $networkIds);
        }
        $totalUsers = count(array_unique($allNetworkIds));

        // Get Recent Transactions
        $recentBuilder = $transaksiModel->builder();
        $recentBuilder->select('transaksi.*, users.name as user_name')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.status', 'confirmed');
        $applyWpaFilter($recentBuilder);
        $recentTransactions = $recentBuilder->orderBy('transaksi.created_at', 'DESC')->limit(5)->get()->getResultArray();

        // Stats per WPA
        $wpaStats = [];
        foreach ($WPAs as $wpa) {
            $wpaId = (int)$wpa['id'];
            $sql = "
                SELECT 
                    COUNT(transaksi.id) as sales_count,
                    SUM(CASE WHEN transaksi.payment_method != 'poin' THEN transaksi.total ELSE 0 END) as total_revenue
                FROM transaksi
                LEFT JOIN layanan ON layanan.id = transaksi.layanan_id
                LEFT JOIN layanan_event ON layanan_event.id = transaksi.layanan_id
                LEFT JOIN layanan_tools ON layanan_tools.id = transaksi.layanan_id
                LEFT JOIN layanan_subscription ON layanan_subscription.id = transaksi.layanan_id
                WHERE COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id) = ?
                AND transaksi.status = 'confirmed'
            ";
            
            $query = $db->query($sql, [$wpaId]);
            $wpaRes = $query->getRowArray();

            $wpaStats[] = [
                'id' => $wpa['id'],
                'name' => $wpa['name'],
                'photo' => $wpa['photo'],
                'earnings' => (float)($wpaRes['total_revenue'] ?? 0),
                'sales' => (int)($wpaRes['sales_count'] ?? 0)
            ];
        }

        $data = [
            'title' => 'Admin WPA Dashboard ',
            'assignedWPAs' => $WPAs,
            'totalEarnings' => $totalEarnings,
            'totalSales' => $totalSales,
            'totalUsers' => $totalUsers,
            'recentTransactions' => $recentTransactions,
            'wpaStats' => $wpaStats,
            'revenueData' => $this->getMonthlyRevenue($assignedWpaIds, $allLayananIds, $allProductNames),
            'userGrowthData' => $this->getMonthlyUserGrowth($assignedWpaIds),
            'categoryRevenueData' => $this->getRevenueByCategory($allLayananIds, $allProductNames),
            'activeMenu' => 'admin_wpa'
        ];

        return view('admin_wpa/dashboard', $data);
    }

    private function getMonthlyRevenue($wpaIds, $layananIds = [], $layananNames = [])
    {
        $transaksiModel = new TransaksiModel();
        
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $builder = $transaksiModel->builder();
            $builder->where('status', 'confirmed')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->where('payment_method !=', 'poin');

            if (!empty($layananIds) || !empty($layananNames)) {
                $builder->groupStart();
                if (!empty($layananIds)) {
                    $builder->whereIn('layanan_id', $layananIds);
                }
                if (!empty($layananNames)) {
                    foreach ($layananNames as $name) {
                        $builder->orLike('product_name', $name, 'both');
                    }
                }
                $builder->groupEnd();
            } else {
                $builder->where('1 = 0');
            }

            $revenue = $builder->selectSum('total')->get()->getRowArray()['total'] ?? 0;
            $revenues[] = (int) $revenue;
        }

        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    private function getMonthlyUserGrowth($wpaIds, $days = 365)
    {
        $months = [];
        $users = [];
        
        $WPAs = $this->wpaModel->whereIn('id', $wpaIds)->findAll();
        $allNetworkIds = [];
        foreach ($WPAs as $wpa) {
            $networkIds = $this->userModel->getNetworkIds($wpa['user_id'], 10);
            $allNetworkIds = array_merge($allNetworkIds, $networkIds);
        }
        $allNetworkIds = array_unique($allNetworkIds);

        if (empty($allNetworkIds)) {
            return ['labels' => [], 'data' => []];
        }

        $periods = min(12, max(1, ceil($days / 30)));
        $db = \Config\Database::connect();

        for ($i = $periods - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $count = $db->table('users')
                ->whereIn('id', $allNetworkIds)
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->countAllResults();

            $users[] = (int) $count;
        }

        return [
            'labels' => $months,
            'data' => $users
        ];
    }

    private function getRevenueByCategory($layananIds = [], $layananNames = [])
    {
        $transaksiModel = new TransaksiModel();
        $builder = $transaksiModel->where('status', 'confirmed');
        
        if (!empty($layananIds) || !empty($layananNames)) {
            $builder->groupStart();
            if (!empty($layananIds)) {
                $builder->whereIn('layanan_id', $layananIds);
            }
            if (!empty($layananNames)) {
                foreach ($layananNames as $name) {
                    $builder->orLike('product_name', $name, 'both');
                }
            }
            $builder->groupEnd();
        } else {
            $builder->where('1 = 0');
        }

        $confirmedTransactions = $builder->findAll();

        // Get all layanan data for mapping
        $layananController = new \App\Controllers\Layanan();
        $method = new \ReflectionMethod(\App\Controllers\Layanan::class, 'getLayananData');
        $method->setAccessible(true);
        $allLayanan = $method->invoke($layananController);
        
        $map = [];
        foreach ($allLayanan as $l) { $map[$l['name']] = $l['subcategory']; }

        $revenueByCategory = [];
        foreach ($confirmedTransactions as $trx) {
            if (($trx['payment_method'] ?? '') === 'poin') continue;

            $subcategory = 'Lain-lain';
            if ($trx['product_type'] === 'kelas') {
                $subcategory = 'Kelas';
            } elseif ($trx['product_type'] === 'tools') {
                $subcategory = 'Tools';
            } elseif ($trx['product_type'] === 'layanan') {
                $name = $trx['product_name'];
                $baseName = explode(' - ', $name)[0];
                if (isset($map[$name])) $subcategory = $map[$name];
                elseif (isset($map[$baseName])) $subcategory = $map[$baseName];
                else {
                    foreach ($map as $mName => $mSub) {
                        if (stripos($name, $mName) !== false) { $subcategory = $mSub; break; }
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

        arsort($revenueByCategory);
        $labels = array_keys($revenueByCategory);
        $data = array_values($revenueByCategory);

        $colors = ['#33e818', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#10b981', '#06b6d4', '#6366f1', '#f97316'];

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    public function revenueData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        $db = \Config\Database::connect();
        $productTableMap = [
            'layanan' => 'name', 'layanan_event' => 'title', 'layanan_tools' => 'name',
            'layanan_subscription' => 'name', 'kelas' => 'title', 'tools' => 'name'
        ];
        
        $existingTables = $db->listTables();
        $allProductNames = [];
        $allLayananIds = [];

        foreach ($productTableMap as $table => $nameCol) {
            if (in_array($table, $existingTables)) {
                $fields = $db->getFieldNames($table);
                if (in_array('wpa_id', $fields)) {
                    $prods = $db->table($table)->select("id, $nameCol")->whereIn('wpa_id', $assignedWpaIds)->get()->getResultArray();
                    foreach ($prods as $p) {
                        if (!empty($p[$nameCol])) $allProductNames[] = $p[$nameCol];
                        if (!empty($p['id'])) $allLayananIds[] = $p['id'];
                    }
                }
            }
        }

        $periods = min(12, max(1, ceil($days / 30)));
        $months = [];
        $revenues = [];
        $transaksiModel = new TransaksiModel();

        for ($i = $periods - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $builder = $transaksiModel->builder();
            $builder->where('status', 'confirmed')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->where('payment_method !=', 'poin');

            if (!empty($allLayananIds) || !empty($allProductNames)) {
                $builder->groupStart();
                if (!empty($allLayananIds)) $builder->whereIn('layanan_id', $allLayananIds);
                if (!empty($allProductNames)) {
                    foreach (array_unique($allProductNames) as $name) $builder->orLike('product_name', $name, 'both');
                }
                $builder->groupEnd();
            } else {
                $builder->where('1 = 0');
            }

            $revenue = $builder->selectSum('total')->get()->getRowArray()['total'] ?? 0;
            $revenues[] = (int) $revenue;
        }

        return $this->response->setJSON(['labels' => $months, 'data' => $revenues]);
    }

    public function userGrowthData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        return $this->response->setJSON($this->getMonthlyUserGrowth($assignedWpaIds, $days));
    }
}
