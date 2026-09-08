<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AiAssistant extends BaseController
{
    use ResponseTrait;

    /**
     * Endpoint POST untuk chat AI Susani Accounting Assistant
     */
    public function chat()
    {
        $json = $this->request->getJSON();
        $message = $json->message ?? '';
        $history = $json->history ?? [];

        if (empty($message)) {
            return $this->fail('Pesan tidak boleh kosong');
        }

        $apiKey = env('GROQ_API_KEY');
        $model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');

        if (empty($apiKey)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Susani AI sedang dalam pemeliharaan. API Key belum dikonfigurasi.',
            ]);
        }

        // Fetch real-time financial data
        $financialContext = $this->getFinancialContext();

        // Build system prompt
        $systemPrompt = $this->buildSystemPrompt($financialContext);

        // Build messages array
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Add history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg->role === 'bot' ? 'assistant' : 'user',
                'content' => $msg->content
            ];
        }

        // Add current message
        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $client = \Config\Services::curlrequest();

            $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.5,
                    'max_tokens' => 2048,
                ],
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody());

            if (isset($result->choices[0]->message->content)) {
                return $this->respond([
                    'status' => 'success',
                    'reply' => $result->choices[0]->message->content,
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                $errorMessage = $result->error->message ?? 'Gagal mendapatkan respon dari AI.';
                return $this->respond([
                    'status' => 'error',
                    'message' => 'AI Error: ' . $errorMessage,
                ]);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Sistem Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Build system prompt Susani Accounting AI
     */
    private function buildSystemPrompt(array $ctx): string
    {
        $today = date('d F Y');
        $bulanIni = date('F Y');

        return "Anda adalah **Susani**, AI Accounting Assistant profesional dari ALMAI (almai.id).
Anda adalah seorang akuntan berpengalaman dan auditor keuangan bersertifikasi yang membantu tim keuangan ALMAI.

TANGGAL HARI INI: {$today}

## PERSONALITY & STYLE
- Profesional, analitis, dan teliti seperti auditor bersertifikasi
- Gunakan Bahasa Indonesia yang formal namun mudah dipahami
- Selalu berikan analisis berbasis data, bukan opini
- Gunakan emoji secukupnya untuk keterbacaan (📊 💰 ⚠️ ✅ 📈 📉)
- Format jawaban dengan heading, bullet points, dan tabel jika perlu
- Jika diminta analisis, selalu sertakan: Temuan → Analisis → Rekomendasi

## KAPABILITAS UTAMA
1. **Audit Keuangan** — Cek balance sheet (Aset = Liabilities + Ekuitas), identifikasi ketidakseimbangan
2. **Analisis Cashflow** — Evaluasi arus kas masuk vs keluar, identifikasi tren
3. **Analisis Laba Rugi** — Evaluasi profitabilitas, margin, dan efisiensi biaya
4. **Analisis Piutang & Hutang** — Cek aging, risiko kredit macet
5. **Audit Neraca** — Verifikasi keseimbangan dan posisi keuangan
6. **Rekomendasi Keuangan** — Berikan saran berbasis data untuk optimasi keuangan
7. **Deteksi Anomali** — Identifikasi transaksi tidak wajar atau pola mencurigakan

## DATA KEUANGAN REAL-TIME (Per {$bulanIni})

### 💰 POSISI KAS & BANK
{$ctx['kasBankText']}
- **Total Saldo Kas & Bank**: Rp {$ctx['totalSaldoKasBankFormatted']}

### 📊 ARUS KAS (CASHFLOW)
- Kas Masuk (Debit): Rp {$ctx['kasMasukFormatted']}
- Kas Keluar (Kredit): Rp {$ctx['kasKeluarFormatted']}
- Net Cashflow: Rp {$ctx['netCashflowFormatted']}

### 📋 PIUTANG & HUTANG
- Piutang (Invoice Pending/Outstanding): Rp {$ctx['piutangFormatted']}
- Hutang (Pembelian Pending): Rp {$ctx['hutangFormatted']}

### 📈 PENDAPATAN (REVENUE)
- Total Revenue (Confirmed): Rp {$ctx['totalRevenueFormatted']}
- Revenue per Layanan:
{$ctx['revenuePerServiceText']}

### 💸 BEBAN & BIAYA
- Beban Bulan Lalu: Rp {$ctx['bebanBulanLaluFormatted']}
- Beban Bulan Ini: Rp {$ctx['bebanBulanIniFormatted']}

### 📊 LABA RUGI
- Laba Kotor: Rp {$ctx['labaKotorFormatted']}
- Laba Bersih (Estimasi): Rp {$ctx['labaBersihFormatted']}

### 🏦 XENDIT (PAYMENT GATEWAY)
- Saldo Xendit: Rp {$ctx['xenditBalanceFormatted']}
- Total Volume Transaksi: Rp {$ctx['xenditVolumeFormatted']}
- Total Withdraw: Rp {$ctx['xenditWithdrawFormatted']}

### ⚖️ NERACA RINGKAS
- Total Aset: Rp {$ctx['totalAsetFormatted']}
- Total Kewajiban: Rp {$ctx['totalKewajibanFormatted']}
- Total Ekuitas: Rp {$ctx['totalEkuitasFormatted']}
- Balance Check (Aset - Liab - Equity): Rp {$ctx['balanceCheckFormatted']}
- Status Balance: {$ctx['balanceStatus']}

### 🔍 HASIL AUDIT JURNAL (Cek Balance per Transaksi)
{$ctx['auditJurnalText']}

### 🚨 AKUN DENGAN SALDO ABNORMAL
{$ctx['auditAnomalyText']}

### 💸 HASIL AUDIT KAS MASUK & KELUAR
{$ctx['auditKasText']}

## HALAMAN NAVIGASI (Gunakan link ini saat mengarahkan user)
- Dashboard: {$ctx['links']['dashboard']}
- Jurnal Umum: {$ctx['links']['jurnal_umum']} ← Untuk cek/perbaiki jurnal tidak balance
- Neraca: {$ctx['links']['neraca']}
- Laba Rugi: {$ctx['links']['laba_rugi']}
- Arus Kas: {$ctx['links']['arus_kas']}
- Neraca Saldo: {$ctx['links']['neraca_saldo']}
- Buku Besar: {$ctx['links']['buku_besar']}
- Chart of Accounts (Akun): {$ctx['links']['akun']}
- Piutang: {$ctx['links']['piutang']}
- Hutang: {$ctx['links']['hutang']}
- Kas & Bank: {$ctx['links']['kas_bank']}
- Kas Masuk: {$ctx['links']['kas_masuk']}
- Kas Keluar: {$ctx['links']['kas_keluar']}
- Pengeluaran: {$ctx['links']['pengeluaran']}
- Invoices: {$ctx['links']['invoices']}
- Transaksi: {$ctx['links']['transaksi']}
- Rekonsiliasi: {$ctx['links']['rekonsiliasi']}

## RULES
- Selalu jawab berdasarkan DATA di atas. Jangan mengarang angka.
- Jika data tidak tersedia atau nol, informasikan bahwa data belum diinput.
- Jika diminta audit neraca: cek apakah Total Aset = Total Kewajiban + Total Ekuitas.
- Jika ada ketidakseimbangan (balance tidak nol), beri WARNING dan saran perbaikan.
- **Jika ditemukan jurnal tidak balance, WAJIB sebutkan No. Reff, tanggal, selisih, dan berikan LINK ke halaman Jurnal Umum untuk perbaikan.**
- **Jika ditemukan akun abnormal, WAJIB sebutkan nama akun, kode, dan saldo, serta berikan LINK ke halaman yang relevan.**
- Untuk analisis tren, gunakan perbandingan bulan ini vs bulan lalu.
- Jika user bertanya di luar konteks keuangan, arahkan kembali ke topik keuangan dengan sopan.
- Berikan jawaban yang actionable dan spesifik, bukan generik.
- Saat mengarahkan ke halaman, SELALU sertakan URL lengkap dari daftar HALAMAN NAVIGASI di atas.";
    }

    /**
     * Ambil data keuangan real-time dari database
     */
    private function getFinancialContext(): array
    {
        $db = \Config\Database::connect();
        $fmt = function ($num) {
            return number_format((float)$num, 0, ',', '.');
        };

        // === KAS & BANK ===
        $kasBankAccounts = [];
        $totalSaldoKasBank = 0;
        $totalKasMasuk = 0;
        $totalKasKeluar = 0;
        $kasBankText = '';

        if ($db->tableExists('jurnal') && $db->tableExists('akun')) {
            $jurnalModel = new \App\Models\JurnalModel();

            $accounts = $db->table('akun')
                ->whereIn('kategori', ['kas & bank', 'kas', 'bank'])
                ->orderBy('kode_akun', 'ASC')
                ->get()->getResultArray();

            foreach ($accounts as $acc) {
                $bal = $jurnalModel
                    ->selectSum('debit')
                    ->selectSum('kredit')
                    ->where('akun_id', $acc['id'])
                    ->first();
                $d = $bal['debit'] ?? 0;
                $k = $bal['kredit'] ?? 0;
                $balance = $d - $k;

                $totalKasMasuk += $d;
                $totalKasKeluar += $k;
                $totalSaldoKasBank += $balance;

                $kasBankText .= "- {$acc['nama_akun']} ({$acc['kode_akun']}): Rp {$fmt($balance)}\n";
                $kasBankAccounts[] = array_merge($acc, ['balance' => $balance]);
            }

            if (empty($kasBankText)) {
                $kasBankText = "- Belum ada akun Kas & Bank terdaftar.\n";
            }
        } else {
            $kasBankText = "- Data jurnal/akun belum tersedia.\n";
        }

        // === PIUTANG (Invoice Outstanding) ===
        $piutang = 0;
        if ($db->tableExists('customer_invoices')) {
            $invoiceModel = new \App\Models\CustomerInvoiceModel();
            $piutang = $invoiceModel->where('status', 'pending')
                ->selectSum('total')
                ->first()['total'] ?? 0;
        }

        // === HUTANG (Pembelian Pending) ===
        $hutang = 0;
        if ($db->tableExists('pembelian')) {
            $pembelianModel = new \App\Models\PembelianModel();
            $hutang = $pembelianModel->where('status', 'pending')
                ->selectSum('total')
                ->first()['total'] ?? 0;
        }

        // === REVENUE ===
        $transaksiModel = new \App\Models\TransaksiModel();
        $totalRevenue = $transaksiModel->where('status', 'confirmed')
            ->selectSum('total')
            ->first()['total'] ?? 0;

        $revenuePerService = $transaksiModel->select('product_type, SUM(total) as total')
            ->where('status', 'confirmed')
            ->groupBy('product_type')
            ->findAll();

        $revenuePerServiceText = '';
        if (!empty($revenuePerService)) {
            foreach ($revenuePerService as $rps) {
                $revenuePerServiceText .= "  - {$rps['product_type']}: Rp {$fmt($rps['total'])}\n";
            }
        } else {
            $revenuePerServiceText = "  - Belum ada data pendapatan per layanan.\n";
        }

        // === BEBAN / EXPENSES ===
        $bebanBulanLalu = 0;
        $bebanBulanIni = 0;

        if ($db->tableExists('jurnal') && $db->tableExists('akun')) {
            $jurnalModel = new \App\Models\JurnalModel();

            $expenseAccounts = $db->table('akun')
                ->whereIn('kategori', ['beban', 'harga pokok penjualan', 'beban lainya', 'Beban', 'Harga Pokok Penjualan', 'Beban Lainnya'])
                ->get()->getResultArray();
            $expenseIds = array_column($expenseAccounts, 'id');

            if (!empty($expenseIds)) {
                // Bulan lalu
                $lastMonthStart = date('Y-m-01', strtotime('last month'));
                $lastMonthEnd = date('Y-m-t', strtotime('last month'));
                $q1 = $jurnalModel->selectSum('debit')->selectSum('kredit')
                    ->whereIn('akun_id', $expenseIds)
                    ->where('tanggal >=', $lastMonthStart)
                    ->where('tanggal <=', $lastMonthEnd)
                    ->first();
                $bebanBulanLalu = ($q1['debit'] ?? 0) - ($q1['kredit'] ?? 0);

                // Bulan ini
                $thisMonthStart = date('Y-m-01');
                $thisMonthEnd = date('Y-m-t');
                $q2 = $jurnalModel->selectSum('debit')->selectSum('kredit')
                    ->whereIn('akun_id', $expenseIds)
                    ->where('tanggal >=', $thisMonthStart)
                    ->where('tanggal <=', $thisMonthEnd)
                    ->first();
                $bebanBulanIni = ($q2['debit'] ?? 0) - ($q2['kredit'] ?? 0);
            }
        }

        // === LABA RUGI ===
        $labaKotor = $totalRevenue - $bebanBulanIni;
        $labaBersih = $labaKotor; // Simplified, would need more detail

        // Add revenue from jurnal if available
        if ($db->tableExists('jurnal') && $db->tableExists('akun')) {
            $jurnalModel = new \App\Models\JurnalModel();
            $thisMonthStart = date('Y-m-01');
            $thisMonthEnd = date('Y-m-t');

            // Revenue from jurnal
            $revenueAccounts = $db->table('akun')
                ->whereIn('kategori', ['Pendapatan', 'pendapatan', 'Pendapatan Lainnya', 'pendapatan lainnya'])
                ->get()->getResultArray();
            $revenueIds = array_column($revenueAccounts, 'id');

            if (!empty($revenueIds)) {
                $qRev = $jurnalModel->selectSum('kredit')->selectSum('debit')
                    ->whereIn('akun_id', $revenueIds)
                    ->where('tanggal >=', $thisMonthStart)
                    ->where('tanggal <=', $thisMonthEnd)
                    ->first();
                $jurnalRevenue = ($qRev['kredit'] ?? 0) - ($qRev['debit'] ?? 0);

                if ($jurnalRevenue > 0) {
                    $labaKotor = $jurnalRevenue;
                    $labaBersih = $jurnalRevenue - $bebanBulanIni;
                }
            }
        }

        // === NERACA ===
        $totalAset = 0;
        $totalKewajiban = 0;
        $totalEkuitas = 0;

        if ($db->tableExists('jurnal') && $db->tableExists('akun')) {
            $jurnalModel = new \App\Models\JurnalModel();
            $allData = $jurnalModel->select('akun.kategori, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('akun', 'akun.id = jurnal.akun_id')
                ->groupBy('akun.kategori')
                ->findAll();

            $labaBerjalan = 0;

            foreach ($allData as $row) {
                $cat = $row['kategori'];

                // P&L accounts → retained earnings
                if (in_array($cat, ['Pendapatan', 'pendapatan', 'Pendapatan Lainnya', 'pendapatan lainnya'])) {
                    $labaBerjalan += ($row['total_kredit'] - $row['total_debit']);
                    continue;
                }
                if (in_array($cat, ['Harga Pokok Penjualan', 'harga pokok penjualan', 'Beban', 'beban', 'Beban Lainnya', 'beban lainya'])) {
                    $labaBerjalan -= ($row['total_debit'] - $row['total_kredit']);
                    continue;
                }

                // Assets
                if (in_array(strtolower($cat), ['kas & bank', 'kas', 'bank', 'akun piutang', 'persediaan', 'aktiva lancar lainnya', 'aktiva tetap', 'aktiva lainnya'])) {
                    $totalAset += ($row['total_debit'] - $row['total_kredit']);
                }
                // Contra assets
                elseif (in_array(strtolower($cat), ['depresiasi & amortisasi'])) {
                    $totalAset += ($row['total_debit'] - $row['total_kredit']); // negative naturally
                }
                // Liabilities
                elseif (in_array(strtolower($cat), ['akun hutang', 'kewajiban lancar lainnya'])) {
                    $totalKewajiban += ($row['total_kredit'] - $row['total_debit']);
                }
                // Equity
                elseif (strtolower($cat) === 'ekuitas') {
                    $totalEkuitas += ($row['total_kredit'] - $row['total_debit']);
                }
            }

            $totalEkuitas += $labaBerjalan;
        }

        $balanceCheck = $totalAset - $totalKewajiban - $totalEkuitas;

        // === XENDIT ===
        $xenditBalance = 0;
        $xenditVolume = 0;
        $xenditWithdraw = 0;

        try {
            $xendit = new \App\Libraries\XenditService();
            $balResult = $xendit->getBalance('CASH');
            $xenditBalance = $balResult['success'] ? ($balResult['data']['balance'] ?? 0) : 0;

            $xenditTrx = $xendit->getTransactions(['limit' => 50]);
            if ($xenditTrx['success']) {
                $trxList = $xenditTrx['data']['data'] ?? [];
                foreach ($trxList as $trx) {
                    if ($trx['status'] === 'SUCCESS') {
                        $amount = abs($trx['amount']);
                        if ($trx['cashflow'] === 'MONEY_IN') {
                            $xenditVolume += $amount;
                        } elseif (in_array($trx['type'], ['WITHDRAWAL', 'TRANSFER_OUT', 'REMITTANCE_PAYOUT'])) {
                            $xenditWithdraw += $amount;
                        }
                    }
                }
            }

            $xenditDisb = $xendit->getDisbursements(['limit' => 50]);
            if ($xenditDisb['success']) {
                foreach ($xenditDisb['data'] ?? [] as $disb) {
                    if (in_array($disb['status'], ['COMPLETED', 'SUCCESS'])) {
                        $xenditWithdraw += abs($disb['amount']);
                    }
                }
            }

            // Add internal withdrawals
            $wdModel = new \App\Models\WithdrawalModel();
            $internalWd = $wdModel->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0;
            $xenditWithdraw += $internalWd;
        } catch (\Throwable $e) {
            // Xendit service may not be available
        }

        return [
            'kasBankText' => $kasBankText,
            'totalSaldoKasBankFormatted' => $fmt($totalSaldoKasBank),
            'kasMasukFormatted' => $fmt($totalKasMasuk),
            'kasKeluarFormatted' => $fmt($totalKasKeluar),
            'netCashflowFormatted' => $fmt($totalKasMasuk - $totalKasKeluar),
            'piutangFormatted' => $fmt($piutang),
            'hutangFormatted' => $fmt($hutang),
            'totalRevenueFormatted' => $fmt($totalRevenue),
            'revenuePerServiceText' => $revenuePerServiceText,
            'bebanBulanLaluFormatted' => $fmt($bebanBulanLalu),
            'bebanBulanIniFormatted' => $fmt($bebanBulanIni),
            'labaKotorFormatted' => $fmt($labaKotor),
            'labaBersihFormatted' => $fmt($labaBersih),
            'xenditBalanceFormatted' => $fmt($xenditBalance),
            'xenditVolumeFormatted' => $fmt($xenditVolume),
            'xenditWithdrawFormatted' => $fmt($xenditWithdraw),
            'totalAsetFormatted' => $fmt($totalAset),
            'totalKewajibanFormatted' => $fmt($totalKewajiban),
            'totalEkuitasFormatted' => $fmt($totalEkuitas),
            'balanceCheckFormatted' => $fmt($balanceCheck),
            'balanceStatus' => abs($balanceCheck) < 1 ? '✅ BALANCE (Seimbang)' : '⚠️ TIDAK BALANCE — Selisih Rp ' . $fmt(abs($balanceCheck)),

            // Audit results
            'auditJurnalText' => $this->auditJurnalBalance($db, $fmt),
            'auditAnomalyText' => $this->auditAccountAnomalies($db, $fmt),
            'auditKasText' => $this->auditKasMasukKeluar($db, $fmt),

            // Navigation links
            'links' => [
                'dashboard' => base_url('keuangan/dashboard'),
                'jurnal_umum' => base_url('keuangan/jurnal-umum'),
                'neraca' => base_url('keuangan/neraca'),
                'laba_rugi' => base_url('keuangan/laba-rugi'),
                'arus_kas' => base_url('keuangan/arus-kas'),
                'neraca_saldo' => base_url('keuangan/neraca-saldo'),
                'buku_besar' => base_url('keuangan/buku-besar'),
                'akun' => base_url('keuangan/akun'),
                'piutang' => base_url('keuangan/piutang'),
                'hutang' => base_url('keuangan/hutang'),
                'kas_bank' => base_url('keuangan/kas-bank'),
                'kas_masuk' => base_url('keuangan/kas-masuk'),
                'kas_keluar' => base_url('keuangan/kas-keluar'),
                'pengeluaran' => base_url('keuangan/pengeluaran'),
                'invoices' => base_url('keuangan/invoices'),
                'transaksi' => base_url('keuangan/transaksi'),
                'rekonsiliasi' => base_url('keuangan/rekonsiliasi'),
            ],
        ];
    }

    /**
     * Audit jurnal: cek setiap grup transaksi (no_reff) apakah debit = kredit
     */
    private function auditJurnalBalance(\CodeIgniter\Database\BaseConnection $db, callable $fmt): string
    {
        if (!$db->tableExists('jurnal')) {
            return "- Data jurnal belum tersedia.\n";
        }

        // Find journal groups where debit ≠ credit
        $unbalanced = $db->table('jurnal')
            ->select('no_reff, MIN(tanggal) as tanggal, SUM(debit) as total_debit, SUM(kredit) as total_kredit, ABS(SUM(debit) - SUM(kredit)) as selisih, COUNT(*) as jumlah_baris')
            ->groupBy('no_reff')
            ->having('ABS(SUM(debit) - SUM(kredit)) >', 0.01)
            ->orderBy('MIN(tanggal)', 'DESC')
            ->limit(20)
            ->get()->getResultArray();

        if (empty($unbalanced)) {
            return "✅ Semua jurnal balance. Tidak ditemukan transaksi dengan debit ≠ kredit.\n";
        }

        $text = "⚠️ DITEMUKAN " . count($unbalanced) . " TRANSAKSI JURNAL TIDAK BALANCE:\n";
        foreach ($unbalanced as $i => $row) {
            $no = $i + 1;
            $text .= "  {$no}. No.Reff: {$row['no_reff']} | Tgl: {$row['tanggal']} | Debit: Rp {$fmt($row['total_debit'])} | Kredit: Rp {$fmt($row['total_kredit'])} | Selisih: Rp {$fmt($row['selisih'])} | Baris: {$row['jumlah_baris']}\n";
        }
        $text .= "  → Perbaikan: Buka halaman Jurnal Umum untuk edit transaksi yang tidak balance.\n";

        return $text;
    }

    /**
     * Audit akun: deteksi saldo abnormal (misal aset credit balance, hutang debit balance)
     */
    private function auditAccountAnomalies(\CodeIgniter\Database\BaseConnection $db, callable $fmt): string
    {
        if (!$db->tableExists('jurnal') || !$db->tableExists('akun')) {
            return "- Data belum tersedia untuk audit akun.\n";
        }

        // Get all account balances
        $accounts = $db->table('jurnal')
            ->select('akun.id, akun.kode_akun, akun.nama_akun, akun.kategori, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->get()->getResultArray();

        $anomalies = [];

        // Normal balance rules:
        // Assets (Kas, Bank, Piutang, Persediaan, Aktiva) → should be Debit balance (D > K)
        // Liabilities (Hutang, Kewajiban) → should be Credit balance (K > D)
        // Equity → should be Credit balance (K > D)
        // Revenue (Pendapatan) → should be Credit balance (K > D)
        // Expenses (Beban, HPP) → should be Debit balance (D > K)

        $debitNormal = ['kas & bank', 'kas', 'bank', 'akun piutang', 'persediaan', 'aktiva lancar lainnya', 'aktiva tetap', 'aktiva lainnya', 'beban', 'harga pokok penjualan', 'beban lainya', 'beban lainnya'];
        $creditNormal = ['akun hutang', 'kewajiban lancar lainnya', 'ekuitas', 'pendapatan', 'pendapatan lainnya'];

        foreach ($accounts as $acc) {
            $cat = strtolower($acc['kategori']);
            $balance = $acc['total_debit'] - $acc['total_kredit'];

            // Skip zero balances
            if (abs($balance) < 0.01) continue;

            $isAbnormal = false;
            $expectedSide = '';
            $actualSide = '';

            if (in_array($cat, $debitNormal) && $balance < 0) {
                $isAbnormal = true;
                $expectedSide = 'Debit (positif)';
                $actualSide = 'Kredit (negatif)';
            } elseif (in_array($cat, $creditNormal) && $balance > 0) {
                // Credit normal accounts: balance = kredit - debit, so if debit > kredit, it's abnormal
                $isAbnormal = true;
                $expectedSide = 'Kredit (positif)';
                $actualSide = 'Debit (negatif)';
            }

            // Special: Depresiasi usually credit balance (contra asset)
            if (strpos($cat, 'depresiasi') !== false && $balance > 0) {
                $isAbnormal = true;
                $expectedSide = 'Kredit (contra asset)';
                $actualSide = 'Debit';
            }

            if ($isAbnormal) {
                $anomalies[] = [
                    'kode' => $acc['kode_akun'],
                    'nama' => $acc['nama_akun'],
                    'kategori' => $acc['kategori'],
                    'saldo' => $balance,
                    'expected' => $expectedSide,
                    'actual' => $actualSide,
                ];
            }
        }

        if (empty($anomalies)) {
            return "✅ Semua akun memiliki saldo normal. Tidak ditemukan anomali.\n";
        }

        $text = "⚠️ DITEMUKAN " . count($anomalies) . " AKUN DENGAN SALDO ABNORMAL:\n";
        foreach ($anomalies as $i => $a) {
            $no = $i + 1;
            $text .= "  {$no}. [{$a['kode']}] {$a['nama']} ({$a['kategori']}) | Saldo: Rp {$fmt(abs($a['saldo']))} | Seharusnya: {$a['expected']}, Aktual: {$a['actual']}\n";
        }
        $text .= "  → Perbaikan: Cek Buku Besar atau Jurnal Umum untuk akun-akun di atas.\n";

        return $text;
    }

    /**
     * Audit Kas Masuk & Kas Keluar: 
     * - KM- harus mendebit akun Kas & Bank
     * - KK- harus mengkredit akun Kas & Bank
     */
    private function auditKasMasukKeluar(\CodeIgniter\Database\BaseConnection $db, callable $fmt): string
    {
        if (!$db->tableExists('jurnal') || !$db->tableExists('akun')) {
            return "- Data belum tersedia.\n";
        }

        $transaksiKas = $db->table('jurnal')
            ->select('jurnal.no_reff, jurnal.tanggal, jurnal.debit, jurnal.kredit, akun.kategori, akun.nama_akun')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->groupStart()
                ->like('jurnal.no_reff', 'KM-', 'after')
                ->orLike('jurnal.no_reff', 'KK-', 'after')
            ->groupEnd()
            ->orderBy('jurnal.tanggal', 'DESC')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($transaksiKas as $row) {
            $grouped[$row['no_reff']][] = $row;
        }

        $anomalies = [];
        foreach ($grouped as $reff => $lines) {
            $isKM = (strpos($reff, 'KM-') === 0);
            $isKK = (strpos($reff, 'KK-') === 0);

            $hasKasBank = false;
            $hasCorrectSide = false;
            
            foreach ($lines as $line) {
                $cat = strtolower($line['kategori']);
                $isAkunKas = in_array($cat, ['kas & bank', 'kas', 'bank']);
                
                if ($isAkunKas) {
                    $hasKasBank = true;
                    if ($isKM && $line['debit'] > 0) {
                        $hasCorrectSide = true;
                    }
                    if ($isKK && $line['kredit'] > 0) {
                        $hasCorrectSide = true;
                    }
                }
            }

            if (!$hasKasBank) {
                $anomalies[] = [
                    'no_reff' => $reff,
                    'tanggal' => $lines[0]['tanggal'],
                    'masalah' => 'Tidak ada akun Kas & Bank yang terlibat',
                ];
            } elseif (!$hasCorrectSide) {
                $anomalies[] = [
                    'no_reff' => $reff,
                    'tanggal' => $lines[0]['tanggal'],
                    'masalah' => $isKM ? 'Kas Masuk tapi akun Kas/Bank tidak di-Debit' : 'Kas Keluar tapi akun Kas/Bank tidak di-Kredit',
                ];
            }
        }

        if (empty($anomalies)) {
            return "✅ Semua transaksi Kas Masuk & Kas Keluar valid (Akun Kas berada pada posisi debit/kredit yang benar).\n";
        }

        $text = "⚠️ DITEMUKAN " . count($anomalies) . " ANOMALI KAS MASUK/KELUAR:\n";
        $anomalies = array_slice($anomalies, 0, 15);
        foreach ($anomalies as $i => $a) {
            $no = $i + 1;
            $text .= "  {$no}. No.Reff: {$a['no_reff']} | Tgl: {$a['tanggal']} | Masalah: {$a['masalah']}\n";
        }
        $text .= "  → Perbaikan: Cek menu Kas Masuk / Kas Keluar atau Jurnal Umum.\n";

        return $text;
    }
}
