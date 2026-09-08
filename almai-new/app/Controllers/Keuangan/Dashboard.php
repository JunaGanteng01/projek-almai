<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $transaksiModel = new TransaksiModel();

        // 1. Total Revenue (Confirmed)
        $totalRevenue = $transaksiModel->where('status', 'confirmed')
            ->selectSum('total')
            ->first()['total'] ?? 0;

        // 2. Revenue per Service (Grouped by product_type)
        $revenuePerService = $transaksiModel->select('product_type, SUM(total) as total')
            ->where('status', 'confirmed')
            ->groupBy('product_type')
            ->findAll();

        // 3. Outstanding Invoices (Tagihan pelanggan terhutang) dari Invoice Manual
        $invoiceModel = new \App\Models\CustomerInvoiceModel();
        $outstandingInvoices = $invoiceModel->where('status', 'pending')
            ->selectSum('total')
            ->first()['total'] ?? 0;

        // 4. Data for Accounting logic
        $db = \Config\Database::connect();

        $expensesLastMonth = 0;
        $cashBalance = 0;
        $bankBalance = 0;
        $giro = 0;
        $payables = 0;
        $kasBankAccounts = [];

        // Try to get real data if tables exist
        if ($db->tableExists('jurnal')) {
            $jurnalModel = new \App\Models\JurnalModel();

            // Expenses (Beban & HPP) - Usually Debit - Kredit
            $lastMonthStart = date('Y-m-01', strtotime('last month'));
            $lastMonthEnd = date('Y-m-t', strtotime('last month'));
            
            $thisMonthStart = date('Y-m-01');
            $thisMonthEnd = date('Y-m-t');

            $expenseAccounts = $db->table('akun')->whereIn('kategori', ['beban', 'harga pokok penjualan', 'beban lainya'])->get()->getResultArray();
            $expenseAccountIds = array_column($expenseAccounts, 'id');
            
            if (!empty($expenseAccountIds)) {
                $expenseQuery = $jurnalModel
                    ->selectSum('debit')
                    ->selectSum('kredit')
                    ->whereIn('akun_id', $expenseAccountIds)
                    ->where('tanggal >=', $lastMonthStart)
                    ->where('tanggal <=', $lastMonthEnd)
                    ->first();
                $expensesLastMonth = ($expenseQuery['debit'] ?? 0) - ($expenseQuery['kredit'] ?? 0);
            } else {
                $expensesLastMonth = 0;
            }

            $totalSaldoKasBank = 0;
            // Get dynamic Kas & Bank accounts
            $kasBankAccounts = $db->table('akun')->whereIn('kategori', ['kas & bank', 'kas', 'bank'])->orderBy('kode_akun', 'ASC')->get()->getResultArray();
            $totalKasMasuk = 0;
            $totalKasKeluar = 0;
            foreach ($kasBankAccounts as &$acc) {
                $balQuery = $jurnalModel
                    ->selectSum('debit')
                    ->selectSum('kredit')
                    ->where('akun_id', $acc['id'])
                    ->first();
                $d = $balQuery['debit'] ?? 0;
                $k = $balQuery['kredit'] ?? 0;
                
                $totalKasMasuk += $d;
                $totalKasKeluar += $k;
                
                $acc['balance'] = $d - $k;
                $totalSaldoKasBank += $acc['balance'];
            }
        }

        if ($db->tableExists('pembelian')) {
            $pembelianModel = new \App\Models\PembelianModel();
            $payables = $pembelianModel->where('status', 'pending')->selectSum('total')->first()['total'] ?? 0;
        }


        $receivables = $outstandingInvoices;

        $totalCashInOut = [
            'in' => $totalKasMasuk ?? 0,
            'out' => $totalKasKeluar ?? 0
        ];
        $netMovement = $totalSaldoKasBank; // Net Pergerakan Akun

        // Get recent transactions
        $recentTransactions = $transaksiModel->select('transaksi.*, users.name as user_name')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.status', 'confirmed')
            ->orderBy('transaksi.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Monthly Revenue
        $revenueData = $this->getMonthlyRevenue($transaksiModel);

        // Xendit Stats
        $xendit = new \App\Libraries\XenditService();
        $xenditBalance = $xendit->getBalance('CASH');
        
        // Fetch stats (limit 50 is safer for dashboard summary)
        $xenditTransactions = $xendit->getTransactions(['limit' => 50]);
        $xenditDisbursements = $xendit->getDisbursements(['limit' => 50]);
        
        $totalXenditVolume = 0;
        $totalXenditWithdraw = 0;

        // 1. Calculate from Transactions List (Catch all money flow)
        if ($xenditTransactions['success']) {
            $trxList = $xenditTransactions['data']['data'] ?? [];
            foreach ($trxList as $trx) {
                if ($trx['status'] === 'SUCCESS') {
                    $amount = abs($trx['amount']);
                    // Money In (Volume)
                    if ($trx['cashflow'] === 'MONEY_IN') {
                        $totalXenditVolume += $amount;
                    } 
                    // Money Out (Withdrawals - Only Manual types here to avoid double counting with Disbursements API)
                    elseif (in_array($trx['type'], ['WITHDRAWAL', 'TRANSFER_OUT', 'REMITTANCE_PAYOUT'])) {
                        $totalXenditWithdraw += $amount;
                    }
                }
            }
        }

        // 2. Sum up from Disbursements API (Automated payouts)
        if ($xenditDisbursements['success']) {
            $disbList = $xenditDisbursements['data'] ?? [];
            foreach ($disbList as $disb) {
                if (in_array($disb['status'], ['COMPLETED', 'SUCCESS'])) {
                    $totalXenditWithdraw += abs($disb['amount']);
                }
            }
        }

        $data = [
            'title' => 'Dashboard Keuangan',
            'activeMenu' => 'dashboard',
            'xendit_stats' => [
                'balance' => $xenditBalance['success'] ? $xenditBalance['data']['balance'] : 0,
                'total_withdraw' => $totalXenditWithdraw + ((new \App\Models\WithdrawalModel())->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0),
                'total_volume' => $totalXenditVolume
            ],
            'totalRevenue' => $totalRevenue,
            'recentTransactions' => $recentTransactions,
            'revenueData' => $revenueData,
            'revenuePerService' => $revenuePerService,
            'outstandingInvoices' => $outstandingInvoices,
            'expensesLastMonth' => $expensesLastMonth,
            'kasBankAccounts' => $kasBankAccounts,
            'payables' => $payables,
            'receivables' => $receivables,
            'totalCashInOut' => $totalCashInOut,
            'totalSaldoKasBank' => $totalSaldoKasBank,
            'netMovement' => $netMovement,
        ];

        return view('keuangan/dashboard/index', $data);
    }

    private function getMonthlyRevenue($model)
    {
        $year = date('Y');
        $query = $model->select("MONTH(created_at) as month, SUM(total) as revenue")
            ->where('status', 'confirmed')
            ->where("YEAR(created_at)", $year)
            ->groupBy("MONTH(created_at)")
            ->orderBy("MONTH(created_at)", "ASC")
            ->findAll();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = array_fill(0, 12, 0);

        foreach ($query as $row) {
            $data[$row['month'] - 1] = (int)$row['revenue'];
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    /**
     * Roadmap halaman pengembangan sistem keuangan ERP v1.0 → v3.0
     */
    public function roadmap()
    {
        return view('keuangan/roadmap/index', [
            'title' => 'Roadmap Pengembangan — Finance ERP',
        ]);
    }
}
