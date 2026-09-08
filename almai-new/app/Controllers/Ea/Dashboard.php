<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TransaksiModel;
use App\Models\WpaModel;
use App\Models\LevelModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $transaksiModel = new TransaksiModel();
        $wpaModel = new WpaModel();

        $totalUsers = $userModel->countAllResults();
        
        $totalAdmin = clone $userModel;
        $totalAdmin = $totalAdmin->whereIn('level_id', [
            LevelModel::LEVEL_ADMIN,
            LevelModel::LEVEL_SUPER_ADMIN,
            LevelModel::LEVEL_ACCOUNTING
        ])->countAllResults();

        $totalWpa = clone $userModel;
        $totalWpa = $totalWpa->where('level_id', LevelModel::LEVEL_WPA)->countAllResults();

        $totalCwpa = clone $userModel;
        $totalCwpa = $totalCwpa->where('level_id', LevelModel::LEVEL_CWPA)->countAllResults();

        $totalUserPro = clone $userModel;
        $totalUserPro = $totalUserPro->where('level_id', LevelModel::LEVEL_PRO)->countAllResults();

        $totalUserBiasa = clone $userModel;
        $totalUserBiasa = $totalUserBiasa->where('level_id', LevelModel::LEVEL_USER)->countAllResults();

        $totalRevenue = $transaksiModel->where('status', 'confirmed')->selectSum('total')->first()['total'] ?? 0;

        // Fetch recent transactions
        $recentTransactions = $transaksiModel->select('transaksi.*, users.name as user_name')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->orderBy('transaksi.created_at', 'DESC')
            ->limit(3)
            ->findAll();

        // Fetch popular WPA
        $wpaList = $wpaModel->orderBy('rating', 'DESC')->limit(5)->findAll();

        $revenueData = $this->getMonthlyRevenue($transaksiModel);
        $userGrowthData = $this->getMonthlyUserGrowth($userModel);
        $categoryRevenueData = $this->getRevenueByCategory($transaksiModel);
        $rekapKegiatan = $this->getRekapKegiatan();

        // Get Kas & Bank Balances
        $kasBankAccounts = [];
        $db = \Config\Database::connect();
        if ($db->tableExists('jurnal')) {
            $jurnalModel = new \App\Models\JurnalModel();
            $kasBankAccounts = $db->table('akun')->whereIn('kategori', ['kas & bank', 'kas', 'bank'])->orderBy('kode_akun', 'ASC')->get()->getResultArray();
            foreach ($kasBankAccounts as &$acc) {
                $balQuery = $jurnalModel
                    ->selectSum('debit')
                    ->selectSum('kredit')
                    ->where('akun_id', $acc['id'])
                    ->first();
                $d = $balQuery['debit'] ?? 0;
                $k = $balQuery['kredit'] ?? 0;
                $acc['balance'] = $d - $k;
            }
        }

        $data = [
            'title' => 'Dashboard Executive Assistant',
            'kasBankAccounts' => $kasBankAccounts,
            'summary' => [
                'total_users' => $totalUsers,
                'admin' => $totalAdmin,
                'wpa' => $totalWpa,
                'cwpa' => $totalCwpa,
                'user_pro' => $totalUserPro,
                'user_biasa' => $totalUserBiasa,
                'revenue' => $totalRevenue,
                'active_projects' => 12, // Dummy
                'pending_tasks' => 5,    // Dummy
                'total_kegiatan' => array_sum(array_column(array_column($rekapKegiatan, 'data'), 'count')),
                'total_seminar' => $rekapKegiatan[0]['data']['count'] + $rekapKegiatan[1]['data']['count'],
                'total_signal' => $rekapKegiatan[2]['data']['count'],
                'total_konsultasi' => $rekapKegiatan[3]['data']['count'],
                'total_ea' => $rekapKegiatan[4]['data']['count']
            ],
            'recentTransactions' => $recentTransactions,
            'wpaList' => $wpaList,
            'revenueData' => $revenueData,
            'userGrowthData' => $userGrowthData,
            'categoryRevenueData' => $categoryRevenueData,
            'rekapKegiatan' => $rekapKegiatan,
            'ai_summary' => "Kinerja perusahaan minggu ini stabil. Pendapatan mencapai Rp " . number_format($totalRevenue/1000000, 1, ',', '.') . " Juta dengan total pengguna " . number_format($totalUsers, 0, ',', '.') . "."
        ];
        
        return view('ea/dashboard', $data);
    }

    private function getRekapKegiatan()
    {
        $seminarModel = new \App\Models\SeminarModel();
        $pelatihanModel = new \App\Models\PelatihanModel();
        $signalModel = new \App\Models\SignalModel();
        $konsultasiModel = new \App\Models\KonsultasiModel();
        $eaModel = new \App\Models\ExpertAdvisorModel();

        // Data for this month
        $currentMonth = date('m');
        $currentYear = date('Y');

        $getSummary = function($model, $sumField = null) use ($currentMonth, $currentYear) {
            $builder = $model->where('MONTH(tanggal)', $currentMonth)->where('YEAR(tanggal)', $currentYear);
            $count = $builder->countAllResults(false);
            $sum = 0;
            if ($sumField) {
                $sum = $builder->selectSum($sumField)->first()[$sumField] ?? 0;
            } else {
                $sum = $count; // fallback if 1 record = 1 client
            }
            return [
                'count' => $count,
                'total' => $sum
            ];
        };

        return [
            ['name' => 'Seminar/FGD', 'data' => $getSummary($seminarModel, 'jml_peserta'), 'label' => 'Peserta'],
            ['name' => 'Pelatihan Simulasi', 'data' => $getSummary($pelatihanModel, 'jml_peserta'), 'label' => 'Peserta'],
            ['name' => 'Pemberian Signal', 'data' => $getSummary($signalModel, 'jml_peserta'), 'label' => 'Klien'],
            ['name' => 'Konsultasi', 'data' => $getSummary($konsultasiModel, null), 'label' => 'Klien'],
            ['name' => 'Expert Advisor', 'data' => $getSummary($eaModel, 'jml_klien'), 'label' => 'Klien'],
        ];
    }

    private function getMonthlyRevenue($transaksiModel)
    {
        $months = [];
        $revenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));
            $months[] = date('M', strtotime("-$i months"));

            $total = $transaksiModel->where('status', 'confirmed')
                ->where('MONTH(created_at)', $month)
                ->where('YEAR(created_at)', $year)
                ->selectSum('total')
                ->first()['total'] ?? 0;
            
            $revenue[] = (float)$total;
        }
        return ['labels' => $months, 'data' => $revenue];
    }

    private function getMonthlyUserGrowth($userModel)
    {
        $months = [];
        $users = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));
            $months[] = date('M', strtotime("-$i months"));

            $total = $userModel->where('MONTH(created_at)', $month)
                ->where('YEAR(created_at)', $year)
                ->countAllResults();
            
            $users[] = (int)$total;
        }
        return ['labels' => $months, 'data' => $users];
    }

    private function getRevenueByCategory($transaksiModel)
    {
        $confirmedTransactions = $transaksiModel->where('status', 'confirmed')->findAll();
        $revenueByCategory = [];

        foreach ($confirmedTransactions as $trx) {
            $subcategory = 'Lain-lain';

            if ($trx['product_type'] === 'kelas') {
                $subcategory = 'Kelas';
            } elseif ($trx['product_type'] === 'tools') {
                $subcategory = 'Tools';
            } elseif ($trx['product_type'] === 'layanan') {
                $subcategory = 'Layanan';
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
        $colors = ['#10b981', '#3b82f6', '#8b5cf6', '#6b7280']; // Emerald, Blue, Purple, Gray

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'raw' => $revenueByCategory
        ];
    }

    /**
     * AJAX endpoint for revenue data
     */
    public function revenueData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $transaksiModel = new TransaksiModel();

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

            $revenue = $transaksiModel->where('status', 'confirmed')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->selectSum('total')
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

            $result = $db->query(
                "SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ?",
                [$startDate, $endDate]
            )->getRow();

            $users[] = (int) ($result->count ?? 0);
        }

        return $this->response->setJSON([
            'labels' => $months,
            'data' => $users
        ]);
    }
}
