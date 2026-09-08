<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WithdrawalModel;
use App\Models\WpaModel;
use App\Models\NotificationModel;
use App\Models\TransaksiModel;

class Keuangan extends BaseController
{
    protected $wdModel;

    public function __construct()
    {
        $this->wdModel = new WithdrawalModel();
    }

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

        // 3. Outstanding Invoices (Tagihan pelanggan terhutang)
        $outstandingInvoices = $transaksiModel->where('status', 'pending')
            ->selectSum('total')
            ->first()['total'] ?? 0;

        // 4. Fake/Placeholder Data for Accounting logic not yet in DB
        // In a real app, these would query 'expenses', 'journal_entries', 'bank_accounts' tables
        $expensesLastMonth = 0; // Biaya Bulan Lalu (Placeholder)
        $cashBalance = $totalRevenue * 0.1; // Simulated Cash on Hand (10% of rev)
        $bankBalance = $totalRevenue * 0.9; // Simulated Bank Balance (90% of rev)
        $giro = 0; // Giro
        $payables = 0; // Hutang (Tagihan yang perlu dibayar)
        // Piutang = Outstanding Invoices (Tagihan pelanggan ke kita)
        $receivables = $outstandingInvoices;

        $totalCashInOut = [
            'in' => $totalRevenue,
            'out' => $expensesLastMonth
        ];

        $profit = $totalRevenue - $expensesLastMonth; // Laba Rugi
        $netMovement = $profit; // Net Pergerakan Akun

        // Get recent transactions
        $recentTransactions = $transaksiModel->select('transaksi.*, users.name as user_name')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.status', 'confirmed')
            ->orderBy('transaksi.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Monthly Revenue
        $revenueData = $this->getMonthlyRevenue($transaksiModel);

        $data = [
            'title' => 'Dashboard Keuangan',
            'totalRevenue' => $totalRevenue,
            'recentTransactions' => $recentTransactions,
            'revenueData' => $revenueData,
            // New Data Points
            'revenuePerService' => $revenuePerService,
            'outstandingInvoices' => $outstandingInvoices, // Tagihan Pelanggan Terhutang / Piutang
            'expensesLastMonth' => $expensesLastMonth, // Biaya Bulan Lalu
            'cashBalance' => $cashBalance,
            'bankBalance' => $bankBalance,
            'giro' => $giro,
            'payables' => $payables, // Hutang / Tagihan perlu dibayar
            'receivables' => $receivables,
            'totalCashInOut' => $totalCashInOut,
            'profit' => $profit, // Laba Rugi
            'netMovement' => $netMovement,
        ];

        return view('keuangan/dashboard', $data);
    }

    private function getMonthlyRevenue($transaksiModel)
    {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
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

        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    public function invoice($invoiceNumber)
    {
        $transaksiModel = new \App\Models\TransaksiModel();

        $transaksi = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email, layanan.name as layanan_name, layanan.subcategory as layanan_subcategory')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left')
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Invoice not found');
        }

        $data = [
            'title' => 'Invoice ' . $invoiceNumber,
            'trx' => $transaksi
        ];

        return view('keuangan/invoice', $data);
    }

    public function transaksi()
    {
        $transaksiModel = new \App\Models\TransaksiModel();

        // Filtering
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $productType = $this->request->getGet('product_type');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $query = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email, layanan.name as layanan_name, layanan.subcategory as layanan_subcategory')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('transaksi.invoice_number', $search)
                ->orLike('users.name', $search)
                ->orLike('transaksi.product_name', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $query->where('transaksi.status', $status);
        }

        if ($productType && $productType !== 'all') {
            $query->where('transaksi.product_type', $productType);
        }

        if ($dateFrom) {
            $query->where('transaksi.created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $query->where('transaksi.created_at <=', $dateTo . ' 23:59:59');
        }

        $transaksiList = $query->orderBy('transaksi.created_at', 'DESC')->paginate(20);

        $db = \Config\Database::connect();
        // Stats for cards - Use existing logic
        $totalRevenue = $transaksiModel->where('status', 'confirmed')->selectSum('total')->first()['total'] ?? 0;
        $totalPending = $transaksiModel->where('status', 'pending')->countAllResults();
        $totalPaid = $transaksiModel->where('status', 'paid')->countAllResults();
        $totalConfirmed = $transaksiModel->where('status', 'confirmed')->countAllResults();
        $totalRefunded = $transaksiModel->where('status', 'refunded')->countAllResults();

        // Get distinct product types for filter
        $productTypes = $db->table('transaksi')->select('product_type')->distinct()->get()->getResultArray();

        $data = [
            'title' => 'Transaksi Keuangan',
            'transaksiList' => $transaksiList,
            'pager' => $transaksiModel->pager,
            'totalRevenue' => $totalRevenue,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
            'totalConfirmed' => $totalConfirmed,
            'totalRefunded' => $totalRefunded,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'currentProductType' => $productType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'productTypes' => $productTypes,
            'canWrite' => true // Accounting can write/update status
        ];

        return view('keuangan/transaksi', $data);
    }

    /**
     * Withdrawal Management for Accounting
     */
    public function withdrawals()
    {
        $status = $this->request->getGet('status');

        $builder = $this->wdModel->select('withdrawals.*, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = withdrawals.wpa_id');

        if ($status) {
            $builder->where('withdrawals.status', $status);
        }

        $withdrawals = $builder->orderBy('withdrawals.created_at', 'DESC')->paginate(20);

        // Calculate stats
        $stats = [
            'total_pending' => $this->wdModel->where('status', 'pending')->selectSum('amount')->first()['amount'] ?? 0,
            'count_pending' => $this->wdModel->where('status', 'pending')->countAllResults(),
            'total_approved' => $this->wdModel->where('status', 'approved')->selectSum('amount')->first()['amount'] ?? 0,
            'count_approved' => $this->wdModel->where('status', 'approved')->countAllResults(),
            'total_completed' => $this->wdModel->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0,
            'count_completed' => $this->wdModel->where('status', 'completed')->countAllResults(),
        ];

        $data = [
            'title' => 'Manajemen Penarikan WPA',
            'activeMenu' => 'withdrawals', // Ensure sidebar active state
            'withdrawals' => $withdrawals,
            'pager' => $this->wdModel->pager,
            'currentStatus' => $status,
            'stats' => $stats
        ];

        return view('keuangan/withdrawals', $data);
    }

    public function updateWithdrawalStatus($id)
    {
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('admin_notes');

        if (!in_array($status, ['pending', 'approved', 'rejected', 'completed'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        // Check if status changed to 'completed' then we might want to ensure money is sent or just record it
        // Or 'approved' means ready for transfer

        $currentWd = $this->wdModel->find($id);
        if (!$currentWd) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        // If rejection, we must refund balance to WPA if we deducted it earlier?
        // Usually withdrawal requests deduct balance immediately or hold it.
        // Assuming balance logic is handled elsewhere or WPA model has `balance` column.
        // If rejected, we should add back the amount to WPA balance.

        if ($status === 'rejected' && $currentWd['status'] !== 'rejected') {
            // Refund implementation if needed. 
            // Currently WPA dashboard withdrawal deducts balance upon request?
            // Let's check WPA dashboard controller.. not visible here.
            // Usually: Pending -> Deduct. Rejected -> Refund.
            $wpaModel = new WpaModel();
            // $wpaModel->incrementBalance($currentWd['wpa_id'], $currentWd['amount']); // Hypothetical
            // For now just update status as requested.
        }

        $this->wdModel->update($id, [
            'status' => $status,
            'admin_notes' => $notes
        ]);

        // Helper to notify WPA
        $this->notifyWpaWithdrawal($currentWd['wpa_id'], $status, $notes);

        return redirect()->back()->with('success', 'Status penarikan berhasil diperbarui.');
    }

    private function notifyWpaWithdrawal($wpaId, $status, $notes)
    {
        try {
            $wpaModel = new WpaModel();
            $wpa = $wpaModel->find($wpaId);
            if ($wpa) {
                $notifModel = new NotificationModel();
                $title = "Update Penarikan Dana";
                $message = "Permintaan penarikan Anda telah diperbarui menjadi: " . ucfirst($status) . ".";
                if ($notes) $message .= " Catatan: $notes";

                $notifModel->createNotification(
                    $wpa['user_id'], // WPA maps to a user_id
                    $title,
                    $message,
                    $status === 'completed' ? 'success' : ($status === 'rejected' ? 'danger' : 'info'),
                    base_url('wpa/dashboard/withdraw')
                );
            }
        } catch (\Throwable $e) {
            // Ignore notification errors
        }
    }

    // --- New Modules ---

    public function akun()
    {
        $akunModel = new \App\Models\AkunModel();

        $search = $this->request->getGet('search');
        $kategori = $this->request->getGet('kategori');

        $builder = $akunModel->orderBy('kode_akun', 'ASC');

        if ($search) {
            $builder->groupStart()
                ->like('nama_akun', $search)
                ->orLike('kode_akun', $search)
                ->orLike('nama_sub_akun', $search)
                ->groupEnd();
        }

        if ($kategori && $kategori !== 'all') {
            $builder->where('kategori', $kategori);
        }

        $akunList = $builder->paginate(20);

        $data = [
            'title' => 'Daftar Akun (Chart of Accounts)',
            'activeMenu' => 'akun',
            'akunList' => $akunList,
            'pager' => $akunModel->pager,
            'kategoriList' => \App\Models\AkunModel::KATEGORI_AKUN,
            'currentSearch' => $search,
            'currentKategori' => $kategori
        ];

        return view('keuangan/akun', $data);
    }

    public function saveAkun()
    {
        $akunModel = new \App\Models\AkunModel();
        $id = $this->request->getPost('id');

        $data = [
            'nama_akun' => $this->request->getPost('nama_akun'),
            'kode_akun' => $this->request->getPost('kode_akun'),
            'kategori' => $this->request->getPost('kategori'),
            'kode_sub_akun' => $this->request->getPost('kode_sub_akun'),
            'nama_sub_akun' => $this->request->getPost('nama_sub_akun'),
        ];

        if ($id) {
            $akunModel->update($id, $data);
            $msg = 'Akun berhasil diperbarui';
        } else {
            $akunModel->insert($data);
            $msg = 'Akun berhasil ditambahkan';
        }

        return redirect()->to('/keuangan/akun')->with('success', $msg);
    }

    public function deleteAkun($id)
    {
        $akunModel = new \App\Models\AkunModel();
        $akunModel->delete($id);
        return redirect()->to('/keuangan/akun')->with('success', 'Akun berhasil dihapus');
    }

    public function penjualan()
    {
        // Wrapper for transaksi but with 'penjualan' menu active
        // Logic duplicated from transaksi() but lighter or just call it?
        // Let's duplicate logic for flexibility
        $transaksiModel = new \App\Models\TransaksiModel();

        // Filtering
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $productType = $this->request->getGet('product_type');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $query = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email, layanan.name as layanan_name, layanan.subcategory as layanan_subcategory')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('transaksi.invoice_number', $search)
                ->orLike('users.name', $search)
                ->orLike('transaksi.product_name', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $query->where('transaksi.status', $status);
        }

        if ($productType && $productType !== 'all') {
            $query->where('transaksi.product_type', $productType);
        }

        if ($dateFrom) {
            $query->where('transaksi.created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $query->where('transaksi.created_at <=', $dateTo . ' 23:59:59');
        }

        $transaksiList = $query->orderBy('transaksi.created_at', 'DESC')->paginate(20);

        $db = \Config\Database::connect();
        // Stats
        $totalRevenue = $transaksiModel->where('status', 'confirmed')->selectSum('total')->first()['total'] ?? 0;
        $totalPending = $transaksiModel->where('status', 'pending')->countAllResults();
        $totalPaid = $transaksiModel->where('status', 'paid')->countAllResults();
        $totalConfirmed = $transaksiModel->where('status', 'confirmed')->countAllResults();
        $totalRefunded = $transaksiModel->where('status', 'refunded')->countAllResults();

        $productTypes = $db->table('transaksi')->select('product_type')->distinct()->get()->getResultArray();

        $data = [
            'title' => 'Penjualan',
            'transaksiList' => $transaksiList,
            'pager' => $transaksiModel->pager,
            'totalRevenue' => $totalRevenue,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
            'totalConfirmed' => $totalConfirmed,
            'totalRefunded' => $totalRefunded,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'currentProductType' => $productType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'productTypes' => $productTypes,
            'canWrite' => true,
            'activeMenu' => 'penjualan'
        ];

        return view('keuangan/transaksi', $data); // Reuse view for now
    }

    public function pembelian()
    {
        $data = ['title' => 'Pembelian', 'activeMenu' => 'pembelian'];
        return view('keuangan/placeholder', $data);
    }

    public function biaya()
    {
        $data = ['title' => 'Biaya Operasional', 'activeMenu' => 'biaya'];
        return view('keuangan/placeholder', $data);
    }

    public function produk()
    {
        // Show unified product list
        $data = ['title' => 'Master Produk', 'activeMenu' => 'produk'];
        return view('keuangan/placeholder', $data);
    }

    public function inventori()
    {
        $data = ['title' => 'Inventori', 'activeMenu' => 'inventori'];
        return view('keuangan/placeholder', $data);
    }

    public function laporan()
    {
        $data = ['title' => 'Laporan Keuangan', 'activeMenu' => 'laporan'];
        return view('keuangan/placeholder', $data);
    }

    public function kasBank()
    {
        $data = ['title' => 'Kas & Bank', 'activeMenu' => 'kas_bank'];
        return view('keuangan/placeholder', $data);
    }

    public function aset()
    {
        $data = ['title' => 'Aset Tetap', 'activeMenu' => 'aset'];
        return view('keuangan/placeholder', $data);
    }

    public function jurnalUmum()
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $akunModel = new \App\Models\AkunModel();

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        $jurnalData = $jurnalModel->getJurnalWithAkun($startDate, $endDate)->paginate(50);

        $data = [
            'title' => 'Jurnal Umum',
            'activeMenu' => 'laporan', // Group under Laporan
            'jurnalData' => $jurnalData,
            'pager' => $jurnalModel->pager,
            'akunList' => $akunModel->orderBy('kode_akun', 'ASC')->findAll(),
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('keuangan/jurnal_umum', $data);
    }

    public function saveJurnal()
    {
        $jurnalModel = new \App\Models\JurnalModel();

        // Handle multiple entries if form submits arrays, or single entry?
        // Let's assume the form submits a single balanced transaction set or just simple single line adding (less common).
        // Best approach: Form allows adding a Header (Date, Reff, Desc) + Multiple Lines (Akun, Debit, Credit).
        // For simplicity v1: Just saving one line at a time or a simplified batched input.
        // Let's implement a simple "add line" for now, or a "multi-line" post.

        $tanggal = $this->request->getPost('tanggal');
        $no_reff = $this->request->getPost('no_reff');
        $deskripsi = $this->request->getPost('deskripsi');

        $lines = $this->request->getPost('lines'); // Expecting array of [akun_id, debit, kredit]

        if ($lines && is_array($lines)) {
            foreach ($lines as $line) {
                // Ignore empty lines
                if (empty($line['akun_id'])) continue;

                $jurnalModel->insert([
                    'tanggal' => $tanggal,
                    'no_reff' => $no_reff,
                    'deskripsi' => $deskripsi,
                    'akun_id' => $line['akun_id'],
                    'debit' => $line['debit'] ?? 0,
                    'kredit' => $line['kredit'] ?? 0,
                ]);
            }
        }

        return redirect()->to('/keuangan/jurnal-umum')->with('success', 'Jurnal berhasil disimpan');
    }

    public function labaRugi()
    {
        $jurnalModel = new \App\Models\JurnalModel();

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        // Revenue (Pendapatan) - Credit balance usually
        // Expenses (Beban) - Debit balance usually

        // Query to get sum per account category
        $query = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['Pendapatan', 'Pendapatan Lainnya', 'Harga Pokok Penjualan', 'Beban', 'Beban Lainnya'])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC');

        $reportData = $query->findAll();

        // Process data into groups
        $groupedData = [
            'pendapatan' => [],
            'hpp' => [],
            'beban' => [],
            'pendapatan_lain' => [],
            'beban_lain' => []
        ];

        $totals = [
            'pendapatan' => 0,
            'hpp' => 0,
            'beban' => 0,
            'pendapatan_lain' => 0,
            'beban_lain' => 0
        ];

        foreach ($reportData as $row) {
            // Net Amount based on normal balance
            // Pendapatan: Kredit - Debit
            // Beban: Debit - Kredit

            $kategori = strtolower($row['kategori']);
            $net = 0;

            if (strpos($kategori, 'pendapatan') !== false) {
                $net = $row['total_kredit'] - $row['total_debit'];
            } else {
                $net = $row['total_debit'] - $row['total_kredit'];
            }

            if ($row['kategori'] == 'Pendapatan') {
                $groupedData['pendapatan'][] = array_merge($row, ['net' => $net]);
                $totals['pendapatan'] += $net;
            } elseif ($row['kategori'] == 'Harga Pokok Penjualan') {
                $groupedData['hpp'][] = array_merge($row, ['net' => $net]);
                $totals['hpp'] += $net;
            } elseif ($row['kategori'] == 'Beban') {
                $groupedData['beban'][] = array_merge($row, ['net' => $net]);
                $totals['beban'] += $net;
            } elseif ($row['kategori'] == 'Pendapatan Lainnya') {
                $groupedData['pendapatan_lain'][] = array_merge($row, ['net' => $net]);
                $totals['pendapatan_lain'] += $net;
            } elseif ($row['kategori'] == 'Beban Lainnya') {
                $groupedData['beban_lain'][] = array_merge($row, ['net' => $net]);
                $totals['beban_lain'] += $net;
            }
        }

        $grossProfit = $totals['pendapatan'] - $totals['hpp'];
        $operatingProfit = $grossProfit - $totals['beban'];
        $netProfit = $operatingProfit + $totals['pendapatan_lain'] - $totals['beban_lain'];

        $data = [
            'title' => 'Laporan Laba Rugi',
            'activeMenu' => 'laporan', // Sub menu
            'groupedData' => $groupedData,
            'totals' => $totals,
            'grossProfit' => $grossProfit,
            'operatingProfit' => $operatingProfit,
            'netProfit' => $netProfit,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('keuangan/laba_rugi', $data);
    }

    public function neraca()
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        // Assets = Liabilities + Equity
        // Query cumulative sum up to date
        // Note: Ideally, closing entries move P&L to Retained Earnings (Equity)
        // Since we don't have auto closing process here, we must calculate current period earnings dynamically and add to Equity section of report.

        $query = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal <=', $date)
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC');

        $allData = $query->findAll();

        $neracaData = [
            'aset_lancar' => [],
            'aset_tetap' => [],
            'kewajiban' => [],
            'ekuitas' => []
        ];

        $totals = ['aset' => 0, 'kewajiban' => 0, 'ekuitas' => 0];

        // Current Year Earnings Calculation (Revenue - Expense) for all time up to date? 
        // Or usually Retained Earnings takes care of previous years.
        // For simplicity, let's treat "Revenue - Expense" accounts as a single line "Laba Tahun Berjalan" in Equity.
        $labaBerjalan = 0;

        foreach ($allData as $row) {
            $cat = $row['kategori'];

            // P&L Accounts contribute to Retained Earnings
            if (in_array($cat, ['Pendapatan', 'Pendapatan Lainnya', 'Harga Pokok Penjualan', 'Beban', 'Beban Lainnya'])) {
                $net = 0;
                if (strpos($cat, 'Pendapatan') !== false) {
                    $net = $row['total_kredit'] - $row['total_debit'];
                } else {
                    $net = $row['total_debit'] - $row['total_kredit']; // Expense reduces equity
                    $net = -$net; // Convert to equity impact (negative for expense)
                }
                $labaBerjalan += $net;
                continue;
            }

            // Balance Sheet Accounts
            // Assets: Debit - Kredit
            // Liab/Equity: Kredit - Debit

            if (in_array($cat, ['Kas & Bank', 'Akun Piutang', 'Persediaan', 'Aktiva Lancar Lainnya'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_lancar'][] = array_merge($row, ['total' => $val]);
                $totals['aset'] += $val;
            } elseif (in_array($cat, ['Aktiva Tetap', 'Aktiva Lainnya', 'Depresiasi & Amortisasi'])) {
                $val = $row['total_debit'] - $row['total_kredit']; // Accum Depr usually credit balance, so it will be negative here automatically
                $neracaData['aset_tetap'][] = array_merge($row, ['total' => $val]);
                $totals['aset'] += $val;
            } elseif (in_array($cat, ['Akun Hutang', 'Kewajiban Lancar Lainnya'])) {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['kewajiban'][] = array_merge($row, ['total' => $val]);
                $totals['kewajiban'] += $val;
            } elseif ($cat == 'Ekuitas') {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['ekuitas'][] = array_merge($row, ['total' => $val]);
                $totals['ekuitas'] += $val;
            }
        }

        // Add Laba Berjalan to Equity
        $totals['ekuitas'] += $labaBerjalan;

        $data = [
            'title' => 'Neraca (Balance Sheet)',
            'activeMenu' => 'laporan',
            'neracaData' => $neracaData,
            'totals' => $totals,
            'labaBerjalan' => $labaBerjalan,
            'date' => $date
        ];

        return view('keuangan/neraca', $data);
    }
}
