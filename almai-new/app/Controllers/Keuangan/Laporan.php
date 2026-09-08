<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\JurnalModel;
use App\Models\AkunModel;
use App\Services\Keuangan\CalkService;

class Laporan extends BaseController
{
    // Redirect /keuangan/laporan to /keuangan/laba-rugi as default? Or create a landing page.
    public function index()
    {
        // For now, redirect to Laba Rugi as the main report
        return redirect()->to('/keuangan/laba-rugi');
    }

    public function deleteAllJurnal()
    {
        $db = \Config\Database::connect();
        
        // This will permanently delete all entries in the jurnal table
        $db->table('jurnal')->emptyTable();
        
        return redirect()->to(base_url('keuangan/jurnal-umum'))->with('success', 'Semua data Jurnal Umum telah berhasil dihapus.');
    }

    public function deleteJurnalByYear()
    {
        $year = $this->request->getPost('year');
        if (empty($year) || !is_numeric($year)) {
            return redirect()->to(base_url('keuangan/jurnal-umum'))->with('error', 'Tahun tidak valid.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        
        // permanently delete entries in the specified year
        $builder->where('YEAR(tanggal)', $year);
        $builder->delete();
        
        $deletedCount = $db->affectedRows();

        if ($deletedCount > 0) {
            return redirect()->to(base_url('keuangan/jurnal-umum'))->with('success', "Berhasil menghapus $deletedCount data jurnal pada tahun $year.");
        } else {
            return redirect()->to(base_url('keuangan/jurnal-umum'))->with('error', "Tidak ada data jurnal yang ditemukan pada tahun $year.");
        }
    }

    public function jurnalUmum()
    {
        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $search = $this->request->getGet('search');
        
        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $jurnalData = $jurnalModel->getJurnalWithAkun($startDate, $endDate, $search)->paginate(50);

        // Identifikasi data duplikat (akun_id, debit, kredit, deskripsi sama walau tanggal beda) KHUSUS untuk Saldo Awal
        $db = \Config\Database::connect();
        $duplicatesResult = $db->query("
            SELECT akun_id, debit, kredit, deskripsi, COUNT(*) as cnt
            FROM jurnal
            WHERE deleted_at IS NULL
              AND deskripsi LIKE 'Saldo Awal:%'
            GROUP BY akun_id, debit, kredit, deskripsi
            HAVING COUNT(*) > 1
        ")->getResultArray();

        $duplicatesMap = [];
        foreach ($duplicatesResult as $dup) {
            $key = $dup['akun_id'] . '_' . $dup['debit'] . '_' . $dup['kredit'] . '_' . md5($dup['deskripsi']);
            $duplicatesMap[$key] = true;
        }

        foreach ($jurnalData as &$row) {
            $key = $row['akun_id'] . '_' . $row['debit'] . '_' . $row['kredit'] . '_' . md5($row['deskripsi']);
            $row['is_duplicate'] = isset($duplicatesMap[$key]);
        }

        $data = [
            'search' => $search,
            'title' => 'Jurnal Umum',
            'activeMenu' => 'laporan',
            'jurnalData' => $jurnalData,
            'pager' => $jurnalModel->pager,
            'akunList' => $akunModel->orderBy('kode_akun', 'ASC')->findAll(),
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('keuangan/jurnal/index', $data);
    }

    public function exportPdfJurnalUmum()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(300);

        $jurnalModel = new JurnalModel();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $search = $this->request->getGet('search');

        if (empty($startDate) && empty($endDate)) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $jurnalData = $jurnalModel->getJurnalWithAkun($startDate, $endDate, $search, 'ASC')->findAll();

        $data = [
            'title' => 'Jurnal Umum',
            'jurnalData' => $jurnalData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $search
        ];

        $html = view('keuangan/laporan/pdf_jurnal_umum', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $output = $dompdf->output();
        
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Jurnal_Umum_' . date('Ymd') . '.pdf"')
                              ->setBody($output);
    }


    public function updateJurnal()
    {
        $id = $this->request->getPost('id');
        
        if ($id === null || $id === '') {
            return redirect()->back()->with('error', 'ID Jurnal tidak valid.');
        }

        $db = \Config\Database::connect();
        $jurnal = $db->table('jurnal')->where('id', $id)->get()->getRowArray();
        
        if (!$jurnal) {
            return redirect()->back()->with('error', 'Jurnal tidak ditemukan.');
        }

        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'no_reff' => $this->request->getPost('no_reff'),
            'akun_id' => $this->request->getPost('akun_id'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'debit' => str_replace(',', '', $this->request->getPost('debit')),
            'kredit' => str_replace(',', '', $this->request->getPost('kredit')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('jurnal')->where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Data jurnal berhasil diperbarui.');
    }

    public function hapusDuplikatJurnal()
    {
        $db = \Config\Database::connect();
        
        // Cari kelompok duplikat dan ambil id pertama sebagai yang asli (keep_id)
        $duplicatesResult = $db->query("
            SELECT akun_id, debit, kredit, deskripsi, MIN(id) as keep_id
            FROM jurnal
            WHERE deleted_at IS NULL 
              AND deskripsi LIKE 'Saldo Awal:%'
            GROUP BY akun_id, debit, kredit, deskripsi
            HAVING COUNT(*) > 1
        ")->getResultArray();

        $deletedCount = 0;
        foreach ($duplicatesResult as $dup) {
            // Hapus yang duplikat (id tidak sama dengan keep_id)
            if ($dup['keep_id'] !== null && $dup['keep_id'] !== '') {
                $db->table('jurnal')
                   ->where('deskripsi LIKE', 'Saldo Awal:%')
                   ->where('akun_id', $dup['akun_id'])
                   ->where('debit', $dup['debit'])
                   ->where('kredit', $dup['kredit'])
                   ->where('deskripsi', $dup['deskripsi'])
                   ->where('id !=', $dup['keep_id'])
                   ->delete();
                   
                $deletedCount += $db->affectedRows();
            }
        }

        return redirect()->to(base_url('keuangan/jurnal-umum'))->with('success', "Berhasil menghapus $deletedCount entri jurnal yang duplikat (menyisakan 1 yang asli).");
    }

    public function duplikatJurnal()
    {
        $jurnalModel = new JurnalModel();
        $db = \Config\Database::connect();
        
        $builder = $db->table('jurnal');
        $builder->select('jurnal.id, jurnal.tanggal, jurnal.no_reff, jurnal.deskripsi, jurnal.debit, jurnal.kredit, jurnal.akun_id, akun.kode_akun, akun.nama_akun');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->where('jurnal.deleted_at', null);
        $builder->where("jurnal.deskripsi LIKE", "Saldo Awal:%");
        $builder->orderBy('jurnal.tanggal', 'DESC');
        $allJurnals = $builder->get()->getResultArray();

        $duplicateGroups = [];
        foreach ($allJurnals as $j) {
            $key = $j['akun_id'] . '_' . $j['debit'] . '_' . $j['kredit'] . '_' . md5(strtolower(trim($j['deskripsi'])));
            $duplicateGroups[$key][] = $j;
        }

        $duplicatesOnly = [];
        foreach ($duplicateGroups as $key => $items) {
            if (count($items) > 1) {
                $duplicatesOnly[$key] = $items;
            }
        }

        $data = [
            'title' => 'Kelola Duplikat Jurnal Umum',
            'activeMenu' => 'jurnal_umum',
            'duplicateGroups' => $duplicatesOnly
        ];

        return view('keuangan/jurnal/duplikat', $data);
    }

    public function hapusDuplikatJurnalItem()
    {
        $id = $this->request->getPost('id');
        if ($id !== null && $id !== '') {
            $db = \Config\Database::connect();
            // Gunakan Query Builder langsung untuk menghapus (menghindari bug CI4 jika id = 0)
            $db->table('jurnal')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Satu baris duplikat jurnal berhasil dihapus.');
        }
        log_message('error', 'Hapus Duplikat Jurnal Item: ID tidak ditemukan di POST request.');
        return redirect()->back()->with('error', 'Gagal menghapus item duplikat (Data tidak valid).');
    }

    public function saveJurnal()
    {
        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();
        $db = \Config\Database::connect();

        $tanggal = $this->request->getPost('tanggal');
        $no_reff = $this->request->getPost('no_reff');
        $deskripsi = $this->request->getPost('deskripsi');
        $lines = $this->request->getPost('lines');

        if (!$tanggal || !$no_reff || !$lines || !is_array($lines)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom jurnal wajib diisi');
        }

        $totalDebit = 0;
        $totalKredit = 0;
        $validLines = [];

        // Validate lines and calculate balance
        foreach ($lines as $line) {
            if (empty($line['akun_id'])) continue;

            // Check if account exists
            $akun = $akunModel->find($line['akun_id']);
            if (!$akun) {
                return redirect()->back()->withInput()->with('error', 'Salah satu akun yang dipilih tidak valid atau tidak ditemukan');
            }

            $debit = (float) ($line['debit'] ?? 0);
            $kredit = (float) ($line['kredit'] ?? 0);

            if ($debit < 0 || $kredit < 0) {
                return redirect()->back()->withInput()->with('error', 'Nilai debit atau kredit tidak boleh negatif');
            }

            if ($debit == 0 && $kredit == 0) {
                continue; // Skip zero line
            }

            $totalDebit += $debit;
            $totalKredit += $kredit;

            $validLines[] = [
                'akun_id' => $line['akun_id'],
                'debit' => $debit,
                'kredit' => $kredit
            ];
        }

        if (empty($validLines)) {
            return redirect()->back()->withInput()->with('error', 'Jurnal harus memiliki minimal satu transaksi');
        }

        // Allow micro deviations due to float precision (within 0.01 margin)
        if (abs($totalDebit - $totalKredit) > 0.01) {
            return redirect()->back()->withInput()->with('error', 'Total Debit (Rp ' . number_format($totalDebit, 0, ',', '.') . ') dan Kredit (Rp ' . number_format($totalKredit, 0, ',', '.') . ') tidak balance (seimbang). Selisih: Rp ' . number_format(abs($totalDebit - $totalKredit), 0, ',', '.'));
        }

        // Check if accounting period is closed
        $time = strtotime($tanggal);
        $bulan = date('n', $time);
        $tahun = date('Y', $time);

        $period = $db->table('periode_akuntansi')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('is_closed', 1)
            ->get()
            ->getRow();

        if ($period) {
            return redirect()->back()->withInput()->with('error', "Periode akuntansi {$bulan}/{$tahun} sudah ditutup. Tidak dapat memodifikasi atau menambah jurnal.");
        }

        $userId = session()->get('userId');

        // Execute db transaction
        $db->transStart();

        foreach ($validLines as $validLine) {
            $jurnalModel->insert([
                'tanggal' => $tanggal,
                'no_reff' => $no_reff,
                'deskripsi' => $deskripsi,
                'akun_id' => $validLine['akun_id'],
                'debit' => $validLine['debit'],
                'kredit' => $validLine['kredit'],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan entri jurnal ke database');
        }

        return redirect()->to('/keuangan/jurnal-umum')->with('success', 'Jurnal berhasil disimpan dengan aman (Double-Entry Balance)');
    }

    public function labaRugi()
    {
        $jurnalModel = new JurnalModel();

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $query = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'pendapatan lainnya', 'harga pokok penjualan', 'beban', 'beban lainya', 'beban lainnya'])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC');

        $reportData = $query->findAll();

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
            $kategori = strtolower($row['kategori']);
            $net = 0;

            if (strpos($kategori, 'pendapatan') !== false) {
                $net = $row['total_kredit'] - $row['total_debit'];
            } else {
                $net = $row['total_debit'] - $row['total_kredit'];
            }

            if ($kategori == 'pendapatan') {
                $groupedData['pendapatan'][] = array_merge($row, ['net' => $net]);
                $totals['pendapatan'] += $net;
            } elseif ($kategori == 'harga pokok penjualan') {
                $groupedData['hpp'][] = array_merge($row, ['net' => $net]);
                $totals['hpp'] += $net;
            } elseif ($kategori == 'beban') {
                $groupedData['beban'][] = array_merge($row, ['net' => $net]);
                $totals['beban'] += $net;
            } elseif ($kategori == 'pendapatan lainya' || $kategori == 'pendapatan lainnya') {
                $groupedData['pendapatan_lain'][] = array_merge($row, ['net' => $net]);
                $totals['pendapatan_lain'] += $net;
            } elseif ($kategori == 'beban lainya' || $kategori == 'beban lainnya') {
                $groupedData['beban_lain'][] = array_merge($row, ['net' => $net]);
                $totals['beban_lain'] += $net;
            }
        }

        $grossProfit = $totals['pendapatan'] - $totals['hpp'];
        $operatingProfit = $grossProfit - $totals['beban'];
        $netProfit = $operatingProfit + $totals['pendapatan_lain'] - $totals['beban_lain'];

        $data = [
            'title' => 'Laporan Laba Rugi',
            'activeMenu' => 'laporan',
            'groupedData' => $groupedData,
            'totals' => $totals,
            'grossProfit' => $grossProfit,
            'operatingProfit' => $operatingProfit,
            'netProfit' => $netProfit,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('keuangan/laporan/laba_rugi', $data);
    }

    public function neraca()
    {
        $jurnalModel = new JurnalModel();
        $date = $this->request->getGet('date') ?? date('Y-m-d');

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

        $labaBerjalan = 0;

        foreach ($allData as $row) {
            $cat = $row['kategori'];

            if (in_array($cat, ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])) {
                $net = 0;
                if (strpos($cat, 'pendapatan') !== false) {
                    $net = $row['total_kredit'] - $row['total_debit'];
                } else {
                    $net = $row['total_debit'] - $row['total_kredit'];
                    $net = -$net;
                }
                $labaBerjalan += $net;
                continue;
            }

            if (in_array($cat, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_lancar'][] = array_merge($row, ['total' => $val]);
                $totals['aset'] += $val;
            } elseif (in_array($cat, ['aktiva tetap', 'aktiva lainya', 'depresiasi & amortisasi'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_tetap'][] = array_merge($row, ['total' => $val]);
                $totals['aset'] += $val;
            } elseif (in_array($cat, ['akun hutang', 'kewajiban lancar lainya'])) {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['kewajiban'][] = array_merge($row, ['total' => $val]);
                $totals['kewajiban'] += $val;
            } elseif ($cat == 'ekuitas') {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['ekuitas'][] = array_merge($row, ['total' => $val]);
                $totals['ekuitas'] += $val;
            }
        }

        $totals['ekuitas'] += $labaBerjalan;

        $data = [
            'title' => 'Neraca (Balance Sheet)',
            'activeMenu' => 'laporan',
            'neracaData' => $neracaData,
            'totals' => $totals,
            'labaBerjalan' => $labaBerjalan,
            'date' => $date
        ];

        return view('keuangan/laporan/neraca', $data);
    }

    public function arusKas()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $db = \Config\Database::connect();
        
        // 1. Get all kas & bank account IDs
        $kasBankAccounts = $db->table('akun')
            ->where('kategori', 'kas & bank')
            ->get()
            ->getResultArray();
        $kasBankIds = array_column($kasBankAccounts, 'id');

        // 2. Calculate Opening Balance of Kas & Bank (all transactions before $startDate)
        $openingBalance = 0;
        if (!empty($kasBankIds)) {
            $openingSum = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->whereIn('akun_id', $kasBankIds)
                ->where('tanggal <', $startDate)
                ->get()
                ->getRowArray();
            $openingBalance = ($openingSum['total_debit'] ?? 0) - ($openingSum['total_kredit'] ?? 0);
        }

        // 3. Find all journal entries in the period
        $entries = [];
        if (!empty($kasBankIds)) {
            $entries = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('jurnal.*, akun.kategori as akun_kategori, akun.nama_akun')
                ->join('akun', 'akun.id = jurnal.akun_id')
                ->where('jurnal.tanggal >=', $startDate)
                ->where('jurnal.tanggal <=', $endDate)
                ->get()
                ->getResultArray();
        }

        // Group entries by transaction (using no_reff and tanggal)
        $transactions = [];
        foreach ($entries as $entry) {
            $key = $entry['no_reff'] . '_' . $entry['tanggal'];
            $transactions[$key][] = $entry;
        }

        $penerimaanPelanggan = 0;
        $pembayaranPemasok = 0;
        $pembayaranBiaya = 0;
        $perolehanAset = 0;
        $tambahanModal = 0;

        foreach ($transactions as $key => $txLines) {
            // Find kas & bank entries in this transaction
            $kasDebit = 0;
            $kasKredit = 0;
            $otherLines = [];

            foreach ($txLines as $line) {
                if (in_array($line['akun_id'], $kasBankIds)) {
                    $kasDebit += $line['debit'];
                    $kasKredit += $line['kredit'];
                } else {
                    $otherLines[] = $line;
                }
            }

            $netCash = $kasDebit - $kasKredit;
            if ($netCash == 0) {
                continue; // No net cash movement (transfer or zero entry)
            }

            // Classify based on the first non-kas line or default behavior
            $primaryCategory = '';
            if (!empty($otherLines)) {
                $primaryCategory = $otherLines[0]['akun_kategori'];
            }

            if ($netCash > 0) {
                // Cash Inflow
                if ($primaryCategory === 'ekuitas') {
                    $tambahanModal += $netCash;
                } else {
                    $penerimaanPelanggan += $netCash;
                }
            } else {
                // Cash Outflow
                $outflow = abs($netCash);
                if (in_array($primaryCategory, ['aktiva tetap', 'aktiva lainya', 'depresiasi & amortisasi'])) {
                    $perolehanAset += $outflow;
                } elseif (in_array($primaryCategory, ['akun hutang', 'persediaan', 'harga pokok penjualan'])) {
                    $pembayaranPemasok += $outflow;
                } else {
                    $pembayaranBiaya += $outflow;
                }
            }
        }

        $totalOperasional = $penerimaanPelanggan - $pembayaranPemasok - $pembayaranBiaya;
        $totalInvestasi = -$perolehanAset;
        $totalPendanaan = $tambahanModal;
        $netChange = $totalOperasional + $totalInvestasi + $totalPendanaan;

        $data = [
            'title' => 'Laporan Arus Kas',
            'activeMenu' => 'laporan',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'cashData' => [
                'operasional' => [
                    ['nama' => 'Penerimaan dari Pelanggan', 'total' => $penerimaanPelanggan],
                    ['nama' => 'Pembayaran ke Pemasok', 'total' => -$pembayaranPemasok],
                    ['nama' => 'Pembayaran Biaya Operasional', 'total' => -$pembayaranBiaya],
                ],
                'investasi' => [
                    ['nama' => 'Perolehan Aset Tetap', 'total' => -$perolehanAset],
                ],
                'pendanaan' => [
                    ['nama' => 'Tambahan Modal Pemilik', 'total' => $tambahanModal],
                ]
            ],
            'totalOperasional' => $totalOperasional,
            'totalInvestasi' => $totalInvestasi,
            'totalPendanaan' => $totalPendanaan,
            'openingBalance' => $openingBalance,
            'netChange' => $netChange
        ];

        return view('keuangan/laporan/arus_kas', $data);
    }

    public function perubahanModal()
    {
        $jurnalModel = new JurnalModel();
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        // Get Equity Accounts
        $equityData = $jurnalModel->select('akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit - jurnal.debit) as total')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('akun.kategori', 'ekuitas')
            ->where('jurnal.tanggal <=', $endDate)
            ->groupBy('akun.id')
            ->findAll();

        $data = [
            'title' => 'Laporan Perubahan Modal',
            'activeMenu' => 'laporan',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'equityData' => $equityData,
            'labaBerjalan' => 0 // Should be calculated similar to Neraca
        ];

        return view('keuangan/laporan/perubahan_modal', $data);
    }

    public function calk()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }
        $tahun = (int) date('Y', strtotime($endDate));

        $calkService = new CalkService();
        $calkData = $calkService->getCalkData($tahun);

        $data = [
            'title' => 'Catatan Atas Laporan Keuangan (CALK)',
            'activeMenu' => 'laporan',
            'calkData' => $calkData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tahun' => $tahun
        ];

        return view('keuangan/laporan/calk', $data);
    }

    public function saveCalk()
    {
        $endDate = $this->request->getPost('end_date') ?? date('Y-m-d');
        $tahun = (int) date('Y', strtotime($endDate));

        $calkService = new CalkService();
        $calkData = $calkService->getCalkData($tahun);

        // Update narrative sections from form
        if (isset($calkData['sections'])) {
            foreach ($calkData['sections'] as &$section) {
                if ($section['type'] === 'narrative' && isset($section['subsections'])) {
                    foreach ($section['subsections'] as &$subsection) {
                        $fieldName = 'section_' . $subsection['id'];
                        if ($this->request->getPost($fieldName)) {
                            $subsection['content'] = $this->request->getPost($fieldName);
                        }
                    }
                }
            }
        }

        $calkService->saveCalkData($tahun, $calkData);

        return redirect()->to('/keuangan/calk?start_date=' . ($this->request->getPost('start_date') ?? date('Y-m-01')) . '&end_date=' . $endDate)->with('success', 'Catatan Atas Laporan Keuangan berhasil diperbarui di database.');
    }

    public function sakEtap()
    {
        $startYear = $this->request->getGet('start_year');
        $endYear = $this->request->getGet('end_year');

        if ($startYear && $endYear) {
            $startDate = $startYear . '-01-01';
            $endDate = $endYear . '-12-31';
        } else {
            $startDate = $this->request->getGet('start_date') ?? date('Y-01-01');
            $endDate = $this->request->getGet('end_date') ?? date('Y-12-31');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }
        }
        
        $sakEtapHelper = new \App\Models\SakEtapHelper();

        $data = [
            'title' => 'Laporan SAK ETAP',
            'activeMenu' => 'laporan',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'neraca' => $sakEtapHelper->getNeraca($startDate, $endDate),
            'labaRugi' => $sakEtapHelper->getLabaRugi($startDate, $endDate),
            'arusKas' => $sakEtapHelper->getArusKas($startDate, $endDate),
            'perubahanEkuitas' => $sakEtapHelper->getPerubahanEkuitas($startDate, $endDate)
        ];

        return view('keuangan/laporan/sak_etap', $data);
    }

    public function exportPdfSakEtap()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }
        $jurnalModel = new JurnalModel();

        // --- 1. LABA RUGI DATA ---
        $queryLR = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')->findAll();

        $lrGrouped = ['pendapatan'=>[], 'hpp'=>[], 'beban'=>[], 'pendapatan_lain'=>[], 'beban_lain'=>[]];
        $lrTotals = ['pendapatan'=>0, 'hpp'=>0, 'beban'=>0, 'pendapatan_lain'=>0, 'beban_lain'=>0];

        foreach ($queryLR as $row) {
            $cat = strtolower($row['kategori']);
            $net = (strpos($cat, 'pendapatan') !== false) ? ($row['total_kredit'] - $row['total_debit']) : ($row['total_debit'] - $row['total_kredit']);
            if ($cat == 'pendapatan') { $lrGrouped['pendapatan'][] = array_merge($row, ['net' => $net]); $lrTotals['pendapatan'] += $net; }
            elseif ($cat == 'harga pokok penjualan') { $lrGrouped['hpp'][] = array_merge($row, ['net' => $net]); $lrTotals['hpp'] += $net; }
            elseif ($cat == 'beban') { $lrGrouped['beban'][] = array_merge($row, ['net' => $net]); $lrTotals['beban'] += $net; }
            elseif ($cat == 'pendapatan lainya') { $lrGrouped['pendapatan_lain'][] = array_merge($row, ['net' => $net]); $lrTotals['pendapatan_lain'] += $net; }
            elseif ($cat == 'beban lainya') { $lrGrouped['beban_lain'][] = array_merge($row, ['net' => $net]); $lrTotals['beban_lain'] += $net; }
        }
        $grossProfit = $lrTotals['pendapatan'] - $lrTotals['hpp'];
        $operatingProfit = $grossProfit - $lrTotals['beban'];
        $netProfit = $operatingProfit + $lrTotals['pendapatan_lain'] - $lrTotals['beban_lain'];

        // --- 2. NERACA DATA ---
        $queryNeraca = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal <=', $endDate)
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')->findAll();

        $neracaData = ['aset_lancar'=>[], 'aset_tetap'=>[], 'kewajiban'=>[], 'ekuitas'=>[]];
        $neracaTotals = ['aset'=>0, 'kewajiban'=>0, 'ekuitas'=>0];
        $labaBerjalan = 0;

        foreach ($queryNeraca as $row) {
            $cat = $row['kategori'];
            if (in_array($cat, ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])) {
                $net = (strpos($cat, 'pendapatan') !== false) ? ($row['total_kredit'] - $row['total_debit']) : -($row['total_debit'] - $row['total_kredit']);
                $labaBerjalan += $net;
                continue;
            }
            if (in_array($cat, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_lancar'][] = array_merge($row, ['total' => $val]);
                $neracaTotals['aset'] += $val;
            } elseif (in_array($cat, ['aktiva tetap', 'aktiva lainya', 'depresiasi & amortisasi'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_tetap'][] = array_merge($row, ['total' => $val]);
                $neracaTotals['aset'] += $val;
            } elseif (in_array($cat, ['akun hutang', 'kewajiban lancar lainya'])) {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['kewajiban'][] = array_merge($row, ['total' => $val]);
                $neracaTotals['kewajiban'] += $val;
            } elseif ($cat == 'ekuitas') {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['ekuitas'][] = array_merge($row, ['total' => $val]);
                $neracaTotals['ekuitas'] += $val;
            }
        }
        $neracaTotals['ekuitas'] += $labaBerjalan;

        // --- 3. PERUBAHAN MODAL ---
        $equityData = $jurnalModel->select('akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit - jurnal.debit) as total')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('akun.kategori', 'ekuitas')
            ->where('jurnal.tanggal <=', $endDate)
            ->groupBy('akun.id')
            ->findAll();

        // --- 4. ARUS KAS ---
        $db = \Config\Database::connect();
        $kasBankAccounts = $db->table('akun')->where('kategori', 'kas & bank')->get()->getResultArray();
        $kasBankIds = array_column($kasBankAccounts, 'id');

        $openingBalance = 0;
        if (!empty($kasBankIds)) {
            $openingSum = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->whereIn('akun_id', $kasBankIds)
                ->where('tanggal <', $startDate)
                ->get()
                ->getRowArray();
            $openingBalance = ($openingSum['total_debit'] ?? 0) - ($openingSum['total_kredit'] ?? 0);
        }

        $entries = [];
        if (!empty($kasBankIds)) {
            $entries = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('jurnal.*, akun.kategori as akun_kategori, akun.nama_akun')
                ->join('akun', 'akun.id = jurnal.akun_id')
                ->where('jurnal.tanggal >=', $startDate)
                ->where('jurnal.tanggal <=', $endDate)
                ->get()
                ->getResultArray();
        }

        $transactions = [];
        foreach ($entries as $entry) {
            $key = $entry['no_reff'] . '_' . $entry['tanggal'];
            $transactions[$key][] = $entry;
        }

        $penerimaanPelanggan = 0;
        $pembayaranPemasok = 0;
        $pembayaranBiaya = 0;
        $perolehanAset = 0;
        $tambahanModal = 0;

        foreach ($transactions as $key => $txLines) {
            $kasDebit = 0;
            $kasKredit = 0;
            $otherLines = [];

            foreach ($txLines as $line) {
                if (in_array($line['akun_id'], $kasBankIds)) {
                    $kasDebit += $line['debit'];
                    $kasKredit += $line['kredit'];
                } else {
                    $otherLines[] = $line;
                }
            }

            $netCash = $kasDebit - $kasKredit;
            if ($netCash == 0) {
                continue;
            }

            $primaryCategory = '';
            if (!empty($otherLines)) {
                $primaryCategory = $otherLines[0]['akun_kategori'];
            }

            if ($netCash > 0) {
                if ($primaryCategory === 'ekuitas') {
                    $tambahanModal += $netCash;
                } else {
                    $penerimaanPelanggan += $netCash;
                }
            } else {
                $outflow = abs($netCash);
                if (in_array($primaryCategory, ['aktiva tetap', 'aktiva lainya', 'depresiasi & amortisasi'])) {
                    $perolehanAset += $outflow;
                } elseif (in_array($primaryCategory, ['akun hutang', 'persediaan', 'harga pokok penjualan'])) {
                    $pembayaranPemasok += $outflow;
                } else {
                    $pembayaranBiaya += $outflow;
                }
            }
        }

        $cashData = [
            'operasional' => [
                ['nama' => 'Penerimaan dari Pelanggan', 'total' => $penerimaanPelanggan],
                ['nama' => 'Pembayaran ke Pemasok', 'total' => -$pembayaranPemasok],
                ['nama' => 'Pembayaran Biaya Operasional', 'total' => -$pembayaranBiaya],
            ],
            'investasi' => [
                ['nama' => 'Perolehan Aset Tetap', 'total' => -$perolehanAset],
            ],
            'pendanaan' => [
                ['nama' => 'Tambahan Modal Pemilik', 'total' => $tambahanModal],
            ]
        ];

        // --- 5. CALK ---
        $tahun = (int) date('Y', strtotime($endDate));
        $record = $db->table('calk_data')->where('tahun', $tahun)->get()->getRowArray();
        
        if ($record) {
            $calkData = json_decode($record['konten'], true) ?? [];
        } else {
            $calkData = [
                'gambaran_umum' => 'Perusahaan bergerak di bidang penyediaan layanan prop firm trading dan advokasi edukasi.',
                'kebijakan_akuntansi' => 'Laporan keuangan disusun berdasarkan Standar Akuntansi Keuangan untuk Entitas Tanpa Akuntabilitas Publik (SAK ETAP).',
                'rincian_kas' => 'Kas di Bank terdiri dari beberapa rekening operasional.',
                'rincian_piutang' => 'Piutang merupakan tagihan kepada mitra dan peserta yang belum terselesaikan.',
                'rincian_aset_tetap' => 'Aset tetap disusutkan menggunakan metode garis lurus.',
                'rincian_hutang' => 'Hutang usaha merupakan kewajiban kepada pihak ketiga atas layanan operasional.',
            ];
        }

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'lrGrouped' => $lrGrouped,
            'lrTotals' => $lrTotals,
            'grossProfit' => $grossProfit,
            'operatingProfit' => $operatingProfit,
            'netProfit' => $netProfit,
            'neracaData' => $neracaData,
            'neracaTotals' => $neracaTotals,
            'labaBerjalan' => $labaBerjalan,
            'equityData' => $equityData,
            'cashData' => $cashData,
            'calkData' => $calkData,
            'openingBalance' => $openingBalance,
        ];

        $html = view('keuangan/laporan/pdf_sak_etap', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();
        
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Laporan_SAK_ETAP_' . $endDate . '.pdf"')
                              ->setBody($output);
    }

    public function neracaSaldo()
    {
        $endDate = $this->request->getGet('date') ?? date('Y-m-t');
        $db = \Config\Database::connect();

        $reportData = $db->table('akun')
            ->select('akun.id, akun.kode_akun, akun.nama_akun, akun.kategori, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL AND jurnal.tanggal <= ' . $db->escape($endDate), 'left')
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Neraca Saldo (Trial Balance)',
            'activeMenu' => 'laporan',
            'endDate' => $endDate,
            'reportData' => $reportData,
        ];

        return view('keuangan/laporan/neraca_saldo', $data);
    }

    public function bukuBesarDetail()
    {
        $akunModel = new AkunModel();
        $db = \Config\Database::connect();

        $akunId = $this->request->getGet('akun_id');
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $akunList = $akunModel->orderBy('kode_akun', 'ASC')->findAll();
        
        $selectedAkun = null;
        $openingBalance = 0;
        $mutations = [];

        if ($akunId) {
            $selectedAkun = $akunModel->find($akunId);
            if ($selectedAkun) {
                // Determine normal balance
                $kategori = strtolower($selectedAkun['kategori']);
                $isDebitNormal = in_array($kategori, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya', 'aktiva tetap', 'aktiva lainya', 'harga pokok penjualan', 'beban', 'beban lainya']);
                if ($kategori === 'depresiasi & amortisasi') {
                    $isDebitNormal = false; // Contra-asset
                }

                // 1. Calculate opening balance before $startDate
                $opSum = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                    ->select('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                    ->where('akun_id', $akunId)
                    ->where('tanggal <', $startDate)
                    ->get()
                    ->getRowArray();
                
                $debits = $opSum['total_debit'] ?? 0;
                $credits = $opSum['total_kredit'] ?? 0;
                
                if ($isDebitNormal) {
                    $openingBalance = $debits - $credits;
                } else {
                    $openingBalance = $credits - $debits;
                }

                // 2. Fetch mutations in period
                $mutations = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                    ->where('akun_id', $akunId)
                    ->where('tanggal >=', $startDate)
                    ->where('tanggal <=', $endDate)
                    ->orderBy('tanggal', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->get()
                    ->getResultArray();
            }
        }

        $data = [
            'title' => 'Buku Besar Detail',
            'activeMenu' => 'laporan',
            'akunList' => $akunList,
            'selectedAkun' => $selectedAkun,
            'openingBalance' => $openingBalance,
            'mutations' => $mutations,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'akunId' => $akunId
        ];

        return view('keuangan/laporan/buku_besar', $data);
    }

    // ═══════════════════════════════════════════════
    //  SPRINT A — TUTUP BUKU & PERIODE AKUNTANSI
    // ═══════════════════════════════════════════════

    /**
     * Halaman daftar periode akuntansi per bulan/tahun
     */
    public function periodeAkuntansi()
    {
        $db  = \Config\Database::connect();
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        // Ambil semua periode yang tersimpan untuk tahun ini
        $savedPeriods = $db->table('periode_akuntansi')
            ->where('tahun', $tahun)
            ->get()->getResultArray();

        // Index by bulan for quick lookup
        $periodMap = [];
        foreach ($savedPeriods as $p) {
            $periodMap[(int)$p['bulan']] = $p;
        }

        // Hitung jurnal per bulan di tahun ini
        $jurnalPerBulan = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
            ->select('MONTH(tanggal) as bulan, COUNT(DISTINCT no_reff) as total_transaksi, SUM(debit) as total_debit')
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('MONTH(tanggal)')
            ->get()->getResultArray();

        $jurnalMap = [];
        foreach ($jurnalPerBulan as $j) {
            $jurnalMap[(int)$j['bulan']] = $j;
        }

        $bulanNames = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        return view('keuangan/periode/index', [
            'title'       => 'Periode Akuntansi',
            'tahun'       => $tahun,
            'periodMap'   => $periodMap,
            'jurnalMap'   => $jurnalMap,
            'bulanNames'  => $bulanNames,
            'currentYear' => (int) date('Y'),
        ]);
    }

    /**
     * Tutup buku (lock) untuk bulan & tahun tertentu
     */
    public function tutupBuku()
    {
        $db    = \Config\Database::connect();
        $bulan = (int) $this->request->getPost('bulan');
        $tahun = (int) $this->request->getPost('tahun');
        $userId = session()->get('userId');

        if (!$bulan || !$tahun || $bulan < 1 || $bulan > 12 || $tahun < 1900 || $tahun > 2100) {
            return redirect()->back()->with('error', 'Bulan dan tahun tidak valid.');
        }

        try {
            // Cek apakah periode sudah ditutup
            $periodeModel = new \App\Models\PeriodeAkuntansiModel();
            if ($periodeModel->isClosed($bulan, $tahun)) {
                return redirect()->back()->with('error', "Periode sudah ditutup sebelumnya. Tidak dapat menutup dua kali.");
            }

            // Cek apakah ada jurnal di periode ini
            $count = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->where('MONTH(tanggal)', $bulan)
                ->where('YEAR(tanggal)', $tahun)
                ->countAllResults();

            if ($count == 0) {
                return redirect()->back()->with('error', 'Tidak ada transaksi jurnal untuk periode ini. Periode kosong tidak dapat ditutup.');
            }

            // Tutup periode
            $db->transStart();
            
            $periodeModel->closePeriod($bulan, $tahun, $userId);
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan perubahan periode akuntansi.');
            }

            $bulanNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                           7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

            return redirect()->to('/keuangan/periode-akuntansi?tahun=' . $tahun)
                ->with('success', "✓ Periode {$bulanNames[$bulan]} {$tahun} berhasil DITUTUP. ({$count} transaksi dikunci)");
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', 'Gagal menutup periode: ' . $e->getMessage());
        }
    }

    /**
     * Buka kembali periode yang sudah ditutup (unlock)
     */
    public function bukaBuku()
    {
        $db    = \Config\Database::connect();
        $bulan = (int) $this->request->getPost('bulan');
        $tahun = (int) $this->request->getPost('tahun');

        if (!$bulan || !$tahun || $bulan < 1 || $bulan > 12 || $tahun < 1900 || $tahun > 2100) {
            return redirect()->back()->with('error', 'Bulan dan tahun tidak valid.');
        }

        try {
            // Cek apakah periode sudah ditutup
            $periodeModel = new \App\Models\PeriodeAkuntansiModel();
            $period = $periodeModel->getPeriod($bulan, $tahun);

            if (!$period || !$period['is_closed']) {
                return redirect()->back()->with('error', 'Periode ini belum ditutup. Tidak perlu dibuka kembali.');
            }

            // Buka periode
            $db->transStart();
            
            $periodeModel->openPeriod($bulan, $tahun);
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan perubahan periode akuntansi.');
            }

            $bulanNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                           7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

            return redirect()->to('/keuangan/periode-akuntansi?tahun=' . $tahun)
                ->with('success', "✓ Periode {$bulanNames[$bulan]} {$tahun} berhasil DIBUKA kembali.");
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuka periode: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════
    //  PIUTANG & HUTANG REPORTS
    // ═══════════════════════════════════════════════════════

    /**
     * Halaman daftar piutang (receivables)
     */
    public function piutang()
    {
        $transaksiModel = new \App\Models\TransaksiModel();
        
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        // Ambil data piutang dari invoice yang belum lunas
        $query = $transaksiModel
            ->select('transaksi.id, transaksi.invoice_number, transaksi.user_id, transaksi.total, transaksi.status, transaksi.created_at, users.name as customer_name')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.status', 'pending')
            ->where('transaksi.created_at >=', $startDate . ' 00:00:00')
            ->where('transaksi.created_at <=', $endDate . ' 23:59:59')
            ->orderBy('transaksi.created_at', 'DESC');
        
        $piutangData = $query->paginate(50);

        // Hitung total piutang
        $totalPiutang = $transaksiModel
            ->selectSum('total')
            ->where('status', 'pending')
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->get()
            ->getRow()
            ->total ?? 0;

        $data = [
            'title' => 'Daftar Piutang',
            'activeMenu' => 'laporan',
            'piutangData' => $piutangData,
            'pager' => $transaksiModel->pager,
            'totalPiutang' => $totalPiutang,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return view('keuangan/piutang/index', $data);
    }

    /**
     * Halaman daftar hutang (payables)
     */
    public function hutang()
    {
        $pembelianModel = new \App\Models\PembelianModel();
        
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        // Ambil data hutang dari pembelian yang belum dibayar
        $query = $pembelianModel
            ->select('pembelian.id, pembelian.no_faktur, pembelian.supplier_id, pembelian.total, pembelian.status, pembelian.created_at, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = pembelian.supplier_id', 'left')
            ->where('pembelian.status', 'pending')
            ->where('pembelian.created_at >=', $startDate . ' 00:00:00')
            ->where('pembelian.created_at <=', $endDate . ' 23:59:59')
            ->orderBy('pembelian.created_at', 'DESC');
        
        $hutangData = $query->paginate(50);

        // Hitung total hutang
        $totalHutang = $pembelianModel
            ->selectSum('total')
            ->where('status', 'pending')
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->get()
            ->getRow()
            ->total ?? 0;

        $data = [
            'title' => 'Daftar Hutang',
            'activeMenu' => 'laporan',
            'hutangData' => $hutangData,
            'pager' => $pembelianModel->pager,
            'totalHutang' => $totalHutang,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return view('keuangan/hutang/index', $data);
    }

    private function _getLabaRugiData($start, $end)
    {
        $jurnalModel = new JurnalModel();
        $query = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $start)
            ->where('jurnal.tanggal <=', $end)
            ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'pendapatan lainnya', 'harga pokok penjualan', 'beban', 'beban lainya', 'beban lainnya'])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC');

        $reportData = $query->findAll();
        $groupedData = [
            'pendapatan' => [], 'hpp' => [], 'beban' => [], 'pendapatan_lain' => [], 'beban_lain' => []
        ];
        $totals = [
            'pendapatan' => 0, 'hpp' => 0, 'beban' => 0, 'pendapatan_lain' => 0, 'beban_lain' => 0
        ];

        foreach ($reportData as $row) {
            $kategori = strtolower($row['kategori']);
            $net = (strpos($kategori, 'pendapatan') !== false) ? ($row['total_kredit'] - $row['total_debit']) : ($row['total_debit'] - $row['total_kredit']);

            if ($kategori == 'pendapatan') { $groupedData['pendapatan'][] = array_merge($row, ['net' => $net]); $totals['pendapatan'] += $net; }
            elseif ($kategori == 'harga pokok penjualan') { $groupedData['hpp'][] = array_merge($row, ['net' => $net]); $totals['hpp'] += $net; }
            elseif ($kategori == 'beban') { $groupedData['beban'][] = array_merge($row, ['net' => $net]); $totals['beban'] += $net; }
            elseif ($kategori == 'pendapatan lainya' || $kategori == 'pendapatan lainnya') { $groupedData['pendapatan_lain'][] = array_merge($row, ['net' => $net]); $totals['pendapatan_lain'] += $net; }
            elseif ($kategori == 'beban lainya' || $kategori == 'beban lainnya') { $groupedData['beban_lain'][] = array_merge($row, ['net' => $net]); $totals['beban_lain'] += $net; }
        }

        $grossProfit = $totals['pendapatan'] - $totals['hpp'];
        $operatingProfit = $grossProfit - $totals['beban'];
        $netProfit = $operatingProfit + $totals['pendapatan_lain'] - $totals['beban_lain'];

        return [
            'groupedData' => $groupedData,
            'totals' => $totals,
            'grossProfit' => $grossProfit,
            'operatingProfit' => $operatingProfit,
            'netProfit' => $netProfit,
        ];
    }

    public function exportPdfLabaRugi()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate; $startDate = $endDate; $endDate = $temp;
        }

        $currMonthStart = date('Y-m-01', strtotime($startDate));
        $currMonthEnd = date('Y-m-t', strtotime($endDate));
        $prevMonthStart = date('Y-m-01', strtotime($currMonthStart . ' -1 month'));
        $prevMonthEnd = date('Y-m-t', strtotime($currMonthStart . ' -1 month'));

        $currData = $this->_getLabaRugiData($currMonthStart, $currMonthEnd);
        $prevData = $this->_getLabaRugiData($prevMonthStart, $prevMonthEnd);

        $data = [
            'currData' => $currData,
            'prevData' => $prevData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currMonthName' => date('F Y', strtotime($currMonthEnd)),
            'prevMonthName' => date('F Y', strtotime($prevMonthEnd))
        ];

        $html = view('keuangan/laporan/pdf_laba_rugi', $data);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Laba_Rugi_' . date('Ymd', strtotime($endDate)) . '.pdf"')
                              ->setBody($output);
    }

    public function exportExcelLabaRugi()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate; $startDate = $endDate; $endDate = $temp;
        }

        $currMonthStart = date('Y-m-01', strtotime($startDate));
        $currMonthEnd = date('Y-m-t', strtotime($endDate));
        $prevMonthStart = date('Y-m-01', strtotime($currMonthStart . ' -1 month'));
        $prevMonthEnd = date('Y-m-t', strtotime($currMonthStart . ' -1 month'));

        $currData = $this->_getLabaRugiData($currMonthStart, $currMonthEnd);
        $prevData = $this->_getLabaRugiData($prevMonthStart, $prevMonthEnd);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Header Laporan (Mirip Gambar)
        $sheet->setCellValue("A1", "PT ALMA INDONESIA RAYA");
        $sheet->setCellValue("A2", "LAPORAN LABA RUGI KOMPREHENSIF");
        $sheet->setCellValue("A3", "Per " . date('d M Y', strtotime($endDate)));
        $sheet->setCellValue("A4", "(Dalam Rupiah)");
        
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');
        
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        $row = 6;
        
        // 2. Table Headers
        $sheet->setCellValue("C{$row}", date('F Y', strtotime($currMonthEnd))); 
        $sheet->setCellValue("D{$row}", date('F Y', strtotime($prevMonthEnd)));
        
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle("C{$row}:D{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $row++;

        // Helper untuk set row dengan border
        $setRowData = function($row, $colA, $colB, $colC, $colD, $isBold = false, $mergeAB = false) use (&$sheet) {
            $sheet->setCellValue("A{$row}", $colA);
            $sheet->setCellValue("B{$row}", $colB);
            
            if (is_numeric($colC)) $sheet->setCellValueExplicit("C{$row}", $colC, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            else $sheet->setCellValue("C{$row}", $colC);
            
            if (is_numeric($colD)) $sheet->setCellValueExplicit("D{$row}", $colD, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            else $sheet->setCellValue("D{$row}", $colD);

            if ($isBold) {
                $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
            }
            if ($mergeAB) {
                $sheet->mergeCells("A{$row}:B{$row}");
            }
            if (is_numeric($colC) || is_numeric($colD)) {
                $sheet->getStyle("C{$row}:D{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            }
            $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        };

        // Match accounts by name
        $getAllAccounts = function($key) use ($currData, $prevData) {
            $accounts = [];
            foreach (array_merge($currData['groupedData'][$key], $prevData['groupedData'][$key]) as $ac) {
                $accounts[$ac['nama_akun']] = 1;
            }
            return array_keys($accounts);
        };

        $getNet = function($key, $name, $data) {
            foreach ($data['groupedData'][$key] as $ac) {
                if ($ac['nama_akun'] === $name) return $ac['net'];
            }
            return 0;
        };

        // PENGHASILAN
        $setRowData($row++, "PENGHASILAN", "", "", "", true, true);
        foreach ($getAllAccounts('pendapatan') as $name) {
            $setRowData($row++, $name, "", $getNet('pendapatan', $name, $currData), $getNet('pendapatan', $name, $prevData), false, true);
        }
        foreach ($getAllAccounts('pendapatan_lain') as $name) {
            $setRowData($row++, $name, "", $getNet('pendapatan_lain', $name, $currData), $getNet('pendapatan_lain', $name, $prevData), false, true);
        }
        $setRowData($row++, "TOTAL PENDAPATAN", "", $currData['totals']['pendapatan'] + $currData['totals']['pendapatan_lain'], $prevData['totals']['pendapatan'] + $prevData['totals']['pendapatan_lain'], true, true);

        // HPP
        foreach ($getAllAccounts('hpp') as $name) {
            $setRowData($row++, $name, "", $getNet('hpp', $name, $currData), $getNet('hpp', $name, $prevData), false, true);
        }
        $setRowData($row++, "TOTAL BEBAN POKOK", "", $currData['totals']['hpp'], $prevData['totals']['hpp'], true, true);
        $setRowData($row++, "LABA KOTOR", "", $currData['grossProfit'], $prevData['grossProfit'], true, true);

        // BEBAN
        $setRowData($row++, "BEBAN", "", "", "", true, true);
        $bebanLainTitleShown = false;
        foreach ($getAllAccounts('beban') as $name) {
            $setRowData($row++, $name, "", $getNet('beban', $name, $currData), $getNet('beban', $name, $prevData), false, true);
        }
        foreach ($getAllAccounts('beban_lain') as $idx => $name) {
            if (!$bebanLainTitleShown) {
                $setRowData($row++, "Beban lain-lain (uraikan", $name, $getNet('beban_lain', $name, $currData), $getNet('beban_lain', $name, $prevData));
                $bebanLainTitleShown = true;
            } else {
                $setRowData($row++, "", $name, $getNet('beban_lain', $name, $currData), $getNet('beban_lain', $name, $prevData));
            }
        }
        $setRowData($row++, "Total beban", "", $currData['totals']['beban'] + $currData['totals']['beban_lain'], $prevData['totals']['beban'] + $prevData['totals']['beban_lain'], true, true);
        
        $setRowData($row++, "", "", "", "", false, true);

        $setRowData($row++, "Laba (rugi) sebelum pajak", "", $currData['netProfit'], $prevData['netProfit'], true, true);
        $setRowData($row++, "Pajak Penghasilan kini", "", 0, 0, false, true);
        $setRowData($row++, "Pajak Penghasilan Tangguhan", "", 0, 0, false, true);
        $setRowData($row++, "Laba (rugi) tahun berjalan", "", $currData['netProfit'], $prevData['netProfit'], true, true);

        $setRowData($row++, "", "", "", "", false, true);
        $setRowData($row++, "Pendapatan Komprehensif lain", "", "", "", true, true);
        $setRowData($row++, "Pos-pos yang tidak akan direklasifikasi ke laba rugi:", "", "", "", true, true);
        $setRowData($row++, "      Keuntungan dari perubahan nilai aset keuangan yang diukur pada nilai wajar melalui", "", 0, 0, false, true);
        $setRowData($row++, "      Keuntungan dari revaluasi atas aset tetap", "", 0, 0, false, true);
        $setRowData($row++, "      Keuntungan (kerugian) aktuarial dari program pascakerja imbalan pasti", "", 0, 0, false, true);
        $setRowData($row++, "      Bagian pendapatan komprehensif lain dari entitas asosiasi dan pengendalian bersama", "", 0, 0, false, true);
        $setRowData($row++, "      Pajak penghasilan terkait", "", 0, 0, false, true);
        $setRowData($row++, "Pos-pos yang akan direklasifikasi ke laba rugi:", "", "", "", true, true);
        $setRowData($row++, "      Keuntungan dari perubahan nilai aset keuangan yang diukur pada nilai wajar melalui", "", 0, 0, false, true);
        $setRowData($row++, "      Pajak penghasilan terkait", "", 0, 0, false, true);
        $setRowData($row++, "Total Pendapatan Komprehensif lain", "", 0, 0, true, true);
        $setRowData($row++, "Laba (rugi) komprehensif tahun berjalan", "", $currData['netProfit'], $prevData['netProfit'], true, true);
        
        $setRowData($row++, "Laba (rugi) yang dapat didistribusikan kepada :", "", "", "", true, true);
        $setRowData($row++, "      Pemilik entitas induk", "", 0, 0, false, true);
        $setRowData($row++, "      Kepentingan nonpengendali", "", 0, 0, false, true);
        $setRowData($row++, "Laba (rugi) komprehensif yang dapat didistribusikan kepada :", "", "", "", true, true);
        $setRowData($row++, "      Pemilik entitas induk", "", 0, 0, false, true);
        $setRowData($row++, "      Kepentingan nonpengendali", "", 0, 0, false, true);

        // Adjust column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Laporan_Laba_Rugi_" . date('Ymd', strtotime($endDate)) . ".xlsx";
        
        ob_start();
        $writer->save('php://output');
        $excelData = ob_get_clean();

        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                              ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                              ->setBody($excelData);
    }

    private function _getNeracaData($date)
    {
        $jurnalModel = new JurnalModel();
        $query = $jurnalModel->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal <=', $date)
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC');

        $allData = $query->findAll();
        $neracaData = [ 'aset_lancar' => [], 'aset_tetap' => [], 'kewajiban' => [], 'kewajiban_panjang' => [], 'ekuitas' => [] ];
        $totals = ['aset' => 0, 'kewajiban' => 0, 'ekuitas' => 0, 'aset_lancar' => 0, 'aset_tetap' => 0, 'kewajiban_pendek' => 0, 'kewajiban_panjang' => 0, 'ekuitas_saham' => 0];
        $labaBerjalan = 0;

        foreach ($allData as $row) {
            $cat = $row['kategori'];

            if (in_array($cat, ['pendapatan', 'pendapatan lainya', 'pendapatan lainnya', 'harga pokok penjualan', 'beban', 'beban lainya', 'beban lainnya'])) {
                $net = 0;
                if (strpos($cat, 'pendapatan') !== false) {
                    $net = $row['total_kredit'] - $row['total_debit'];
                } else {
                    $net = -($row['total_debit'] - $row['total_kredit']);
                }
                $labaBerjalan += $net;
                continue;
            }

            if (in_array($cat, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya', 'aktiva lancar lainnya'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_lancar'][] = array_merge($row, ['total' => $val]);
                $totals['aset'] += $val;
                $totals['aset_lancar'] += $val;
            } elseif (in_array($cat, ['aktiva tetap', 'aktiva lainya', 'aktiva lainnya', 'depresiasi & amortisasi'])) {
                $val = $row['total_debit'] - $row['total_kredit'];
                $neracaData['aset_tetap'][] = array_merge($row, ['total' => $val]);
                $totals['aset'] += $val;
                $totals['aset_tetap'] += $val;
            } elseif (in_array($cat, ['akun hutang', 'kewajiban lancar lainya', 'kewajiban lancar lainnya', 'kartu kredit'])) {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['kewajiban'][] = array_merge($row, ['total' => $val]);
                $totals['kewajiban'] += $val;
                $totals['kewajiban_pendek'] += $val;
            } elseif (in_array($cat, ['kewajiban jangka panjang'])) {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['kewajiban_panjang'][] = array_merge($row, ['total' => $val]);
                $totals['kewajiban'] += $val;
                $totals['kewajiban_panjang'] += $val;
            } elseif ($cat == 'ekuitas') {
                $val = $row['total_kredit'] - $row['total_debit'];
                $neracaData['ekuitas'][] = array_merge($row, ['total' => $val]);
                $totals['ekuitas'] += $val;
                $totals['ekuitas_saham'] += $val;
            }
        }
        $totals['ekuitas'] += $labaBerjalan;

        return [
            'neracaData' => $neracaData,
            'totals' => $totals,
            'labaBerjalan' => $labaBerjalan
        ];
    }

    public function exportPdfNeraca()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-t');
        $currMonthEnd = date('Y-m-t', strtotime($date));
        $prevMonthEnd = date('Y-m-t', strtotime($currMonthEnd . ' -1 month'));

        $currData = $this->_getNeracaData($currMonthEnd);
        $prevData = $this->_getNeracaData($prevMonthEnd);

        $data = [
            'currData' => $currData,
            'prevData' => $prevData,
            'currMonthName' => date('F Y', strtotime($currMonthEnd)),
            'prevMonthName' => date('F Y', strtotime($prevMonthEnd)),
            'endDate' => $currMonthEnd
        ];

        $html = view('keuangan/laporan/pdf_neraca', $data);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $output = $dompdf->output();
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Neraca_' . date('Ymd', strtotime($currMonthEnd)) . '.pdf"')
                              ->setBody($output);
    }

    public function exportExcelNeraca()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-t');
        $currMonthEnd = date('Y-m-t', strtotime($date));
        $prevMonthEnd = date('Y-m-t', strtotime($currMonthEnd . ' -1 month'));

        $currData = $this->_getNeracaData($currMonthEnd);
        $prevData = $this->_getNeracaData($prevMonthEnd);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Header Laporan
        $sheet->setCellValue("A1", "PT ALMA INDONESIA RAYA");
        $sheet->setCellValue("A2", "LAPORAN POSISI KEUANGAN");
        $sheet->setCellValue("A3", "Per " . date('d M Y', strtotime($currMonthEnd)));
        $sheet->setCellValue("A4", "(Dalam Rupiah)");
        
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('A4:F4');
        
        $sheet->getStyle('A1:F4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        $row = 6;
        
        // 2. Table Headers
        $sheet->setCellValue("B{$row}", date('F Y', strtotime($currMonthEnd))); 
        $sheet->setCellValue("C{$row}", date('F Y', strtotime($prevMonthEnd)));
        $sheet->setCellValue("E{$row}", date('F Y', strtotime($currMonthEnd))); 
        $sheet->setCellValue("F{$row}", date('F Y', strtotime($prevMonthEnd)));
        
        $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle("B{$row}:C{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E{$row}:F{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $row++;

        $maxRows = 0;
        $asetLancarNames = [];
        $asetTetapNames = [];
        $kewajibanPendekNames = [];
        $kewajibanPanjangNames = [];
        $ekuitasNames = [];

        $getAllNames = function($key) use ($currData, $prevData) {
            $accounts = [];
            foreach (array_merge($currData['neracaData'][$key], $prevData['neracaData'][$key]) as $ac) {
                $accounts[$ac['nama_akun']] = 1;
            }
            return array_keys($accounts);
        };

        $getNet = function($key, $name, $data) {
            foreach ($data['neracaData'][$key] as $ac) {
                if ($ac['nama_akun'] === $name) return $ac['total'];
            }
            return 0;
        };

        $asetLancarNames = $getAllNames('aset_lancar');
        $asetTetapNames = $getAllNames('aset_tetap');
        $kewajibanPendekNames = $getAllNames('kewajiban');
        $kewajibanPanjangNames = $getAllNames('kewajiban_panjang');
        
        // Layout: Left side (Aset) vs Right side (Liabilitas & Ekuitas)
        $leftRows = [];
        $leftRows[] = ["ASET", "", "", true];
        $leftRows[] = ["", "", "", false];
        $leftRows[] = ["ASET LANCAR", "", "", true];
        foreach ($asetLancarNames as $n) {
            $leftRows[] = ["    " . $n, $getNet('aset_lancar', $n, $currData), $getNet('aset_lancar', $n, $prevData), false];
        }
        $leftRows[] = ["Biaya dibayar dimuka", 0, 0, false];
        $leftRows[] = ["Pajak dibayar dimuka", 0, 0, false];
        $leftRows[] = ["PPN Masukan", 0, 0, false];
        $leftRows[] = ["Aset keuangan lancar lain", 0, 0, false];
        $leftRows[] = ["    Total Aset Lancar", $currData['totals']['aset_lancar'], $prevData['totals']['aset_lancar'], true];
        
        $leftRows[] = ["", "", "", false];
        $leftRows[] = ["ASET TIDAK LANCAR", "", "", true];
        foreach ($asetTetapNames as $n) {
            $leftRows[] = ["    " . $n, $getNet('aset_tetap', $n, $currData), $getNet('aset_tetap', $n, $prevData), false];
        }
        $leftRows[] = ["Aset keuangan tidak lancar lain", 0, 0, false];
        $leftRows[] = ["    Total Aset Tidak Lancar", $currData['totals']['aset_tetap'], $prevData['totals']['aset_tetap'], true];
        $leftRows[] = ["", "", "", false];
        $leftRows[] = ["TOTAL ASET*", $currData['totals']['aset'], $prevData['totals']['aset'], true];


        $rightRows = [];
        $rightRows[] = ["LIABILITAS DAN EKUITAS", "", "", true];
        $rightRows[] = ["LIABILITAS", "", "", true];
        $rightRows[] = ["LIABILITAS JANGKA PENDEK", "", "", true];
        foreach ($kewajibanPendekNames as $n) {
            $rightRows[] = ["    " . $n, $getNet('kewajiban', $n, $currData), $getNet('kewajiban', $n, $prevData), false];
        }
        $rightRows[] = ["Utang kepada pihak terafiliasi", 0, 0, false];
        $rightRows[] = ["Utang pajak", 0, 0, false];
        $rightRows[] = ["Beban akrual/biaya masih harus dibayar", 0, 0, false];
        $rightRows[] = ["Liabilitas keuangan jangka pendek lain", 0, 0, false];
        $rightRows[] = ["    Total Liabilitas Jangka Pendek", $currData['totals']['kewajiban_pendek'], $prevData['totals']['kewajiban_pendek'], true];

        $rightRows[] = ["", "", "", false];
        $rightRows[] = ["LIABILITAS JANGKA PANJANG", "", "", true];
        foreach ($kewajibanPanjangNames as $n) {
            $rightRows[] = ["    " . $n, $getNet('kewajiban_panjang', $n, $currData), $getNet('kewajiban_panjang', $n, $prevData), false];
        }
        $rightRows[] = ["Imbalan kerja jangka panjang", 0, 0, false];
        $rightRows[] = ["Utang subordinasi", 0, 0, false];
        $rightRows[] = ["Liabilitas keuangan jangka panjang lain", 0, 0, false];
        $rightRows[] = ["    Total Liabilitas Jangka Panjang", $currData['totals']['kewajiban_panjang'], $prevData['totals']['kewajiban_panjang'], true];
        
        $rightRows[] = ["", "", "", false];
        $rightRows[] = ["TOTAL LIABILITAS", $currData['totals']['kewajiban'], $prevData['totals']['kewajiban'], true];
        $rightRows[] = ["EKUITAS", "", "", true];
        $rightRows[] = ["Ekuitas yang diatribusikan pada pemilik entitas induk:", "", "", false];
        $rightRows[] = ["    Modal saham:", "", "", false];
        foreach ($getAllNames('ekuitas') as $n) {
            $rightRows[] = ["        " . $n, $getNet('ekuitas', $n, $currData), $getNet('ekuitas', $n, $prevData), false];
        }
        $rightRows[] = ["        Tambahan modal disetor", 0, 0, false];
        $rightRows[] = ["    Saldo laba/rugi", $currData['labaBerjalan'], $prevData['labaBerjalan'], false];
        $rightRows[] = ["    Komponen ekuitas lain", 0, 0, false];
        $rightRows[] = ["Kepentingan nonpengendali", 0, 0, false];
        
        $rightRows[] = ["", "", "", false];
        $rightRows[] = ["TOTAL EKUITAS", $currData['totals']['ekuitas'], $prevData['totals']['ekuitas'], true];
        $rightRows[] = ["TOTAL KEWAJIBAN DAN EKUITAS", $currData['totals']['kewajiban'] + $currData['totals']['ekuitas'], $prevData['totals']['kewajiban'] + $prevData['totals']['ekuitas'], true];

        $maxRows = max(count($leftRows), count($rightRows));

        for ($i = 0; $i < $maxRows; $i++) {
            $lr = $leftRows[$i] ?? ["", "", "", false];
            $rr = $rightRows[$i] ?? ["", "", "", false];

            $sheet->setCellValue("A{$row}", ltrim($lr[0]));
            if (strpos($lr[0], '    ') === 0) $sheet->getStyle("A{$row}")->getAlignment()->setIndent(1);

            $val1 = is_numeric($lr[1]) ? $lr[1] : 0;
            $val2 = is_numeric($lr[2]) ? $lr[2] : 0;
            if ($lr[1] === "") { $val1 = ""; }
            if ($lr[2] === "") { $val2 = ""; }

            if ($val1 !== "") { $sheet->setCellValueExplicit("B{$row}", $val1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); }
            if ($val2 !== "") { $sheet->setCellValueExplicit("C{$row}", $val2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); }
            if ($lr[3]) $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);


            $sheet->setCellValue("D{$row}", ltrim($rr[0]));
            if (strpos($rr[0], '        ') === 0) $sheet->getStyle("D{$row}")->getAlignment()->setIndent(2);
            elseif (strpos($rr[0], '    ') === 0) $sheet->getStyle("D{$row}")->getAlignment()->setIndent(1);

            $val3 = is_numeric($rr[1]) ? $rr[1] : 0;
            $val4 = is_numeric($rr[2]) ? $rr[2] : 0;
            if ($rr[1] === "") { $val3 = ""; }
            if ($rr[2] === "") { $val4 = ""; }

            if ($val3 !== "") { $sheet->setCellValueExplicit("E{$row}", $val3, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); }
            if ($val4 !== "") { $sheet->setCellValueExplicit("F{$row}", $val4, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); }
            if ($rr[3]) $sheet->getStyle("D{$row}:F{$row}")->getFont()->setBold(true);

            foreach(['B','C','E','F'] as $col) {
                if ($sheet->getCell("{$col}{$row}")->getValue() !== "") {
                    $sheet->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    if ($sheet->getCell("{$col}{$row}")->getValue() == 0) {
                        $sheet->setCellValue("{$col}{$row}", "-");
                        $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    }
                }
            }
            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            $row++;
        }

        // Adjust column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Laporan_Posisi_Keuangan_" . date('Ymd', strtotime($currMonthEnd)) . ".xlsx";
        
        ob_start();
        $writer->save('php://output');
        $excelData = ob_get_clean();

        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                              ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                              ->setBody($excelData);
    }

    public function exportPdfArusKas()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $db = \Config\Database::connect();
        
        $kasBankAccounts = $db->table('akun')
            ->where('kategori', 'kas & bank')
            ->get()
            ->getResultArray();
        $kasBankIds = array_column($kasBankAccounts, 'id');

        $openingBalance = 0;
        if (!empty($kasBankIds)) {
            $openingSum = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->whereIn('akun_id', $kasBankIds)
                ->where('tanggal <', $startDate)
                ->get()
                ->getRowArray();
            $openingBalance = ($openingSum['total_debit'] ?? 0) - ($openingSum['total_kredit'] ?? 0);
        }

        $entries = [];
        if (!empty($kasBankIds)) {
            $entries = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('jurnal.*, akun.kategori as akun_kategori, akun.nama_akun')
                ->join('akun', 'akun.id = jurnal.akun_id')
                ->where('jurnal.tanggal >=', $startDate)
                ->where('jurnal.tanggal <=', $endDate)
                ->get()
                ->getResultArray();
        }

        $transactions = [];
        foreach ($entries as $entry) {
            $key = $entry['no_reff'] . '_' . $entry['tanggal'];
            $transactions[$key][] = $entry;
        }

        $cashData = [
            'operasional' => [],
            'investasi' => [],
            'pendanaan' => []
        ];

        foreach ($transactions as $key => $txLines) {
            $kasDebit = 0;
            $kasKredit = 0;
            $otherLines = [];

            foreach ($txLines as $line) {
                if (in_array($line['akun_id'], $kasBankIds)) {
                    $kasDebit += $line['debit'];
                    $kasKredit += $line['kredit'];
                } else {
                    $otherLines[] = $line;
                }
            }

            $netCash = $kasDebit - $kasKredit;
            if ($netCash == 0) continue;

            $primaryCategory = '';
            if (!empty($otherLines)) {
                $primaryCategory = $otherLines[0]['akun_kategori'];
            }

            $type = 'operasional';
            if (in_array($primaryCategory, ['aktiva tetap', 'aktiva lainya', 'depresiasi & amortisasi'])) {
                $type = 'investasi';
            } elseif (in_array($primaryCategory, ['ekuitas', 'kewajiban jangka panjang'])) {
                $type = 'pendanaan';
            }

            $desc = !empty($otherLines) ? $otherLines[0]['nama_akun'] : 'Lainnya';
            $cashData[$type][] = [
                'nama' => $desc,
                'total' => $netCash
            ];
        }

        $totalOperasional = array_sum(array_column($cashData['operasional'], 'total'));
        $totalInvestasi = array_sum(array_column($cashData['investasi'], 'total'));
        $totalPendanaan = array_sum(array_column($cashData['pendanaan'], 'total'));
        $netChange = $totalOperasional + $totalInvestasi + $totalPendanaan;

        $data = [
            'cashData' => $cashData,
            'totalOperasional' => $totalOperasional,
            'totalInvestasi' => $totalInvestasi,
            'totalPendanaan' => $totalPendanaan,
            'netChange' => $netChange,
            'openingBalance' => $openingBalance,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $html = view('keuangan/laporan/pdf_arus_kas', $data);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Arus_Kas_' . date('Ymd', strtotime($endDate)) . '.pdf"')
                              ->setBody($output);
    }


    public function exportExcelArusKas()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $db = \Config\Database::connect();
        
        $kasBankAccounts = $db->table('akun')
            ->where('kategori', 'kas & bank')
            ->get()
            ->getResultArray();
        $kasBankIds = array_column($kasBankAccounts, 'id');

        $openingBalance = 0;
        if (!empty($kasBankIds)) {
            $openingSum = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->whereIn('akun_id', $kasBankIds)
                ->where('tanggal <', $startDate)
                ->get()
                ->getRowArray();
            $openingBalance = ($openingSum['total_debit'] ?? 0) - ($openingSum['total_kredit'] ?? 0);
        }

        $entries = [];
        if (!empty($kasBankIds)) {
            $entries = $db->table('jurnal')->where('jurnal.deleted_at IS NULL')
                ->select('jurnal.*, akun.kategori as akun_kategori, akun.nama_akun')
                ->join('akun', 'akun.id = jurnal.akun_id')
                ->where('jurnal.tanggal >=', $startDate)
                ->where('jurnal.tanggal <=', $endDate)
                ->get()
                ->getResultArray();
        }

        $transactions = [];
        foreach ($entries as $entry) {
            $key = $entry['no_reff'] . '_' . $entry['tanggal'];
            $transactions[$key][] = $entry;
        }

        $cashData = [
            'operasional' => [],
            'investasi' => [],
            'pendanaan' => []
        ];

        foreach ($transactions as $key => $txLines) {
            $kasDebit = 0;
            $kasKredit = 0;
            $otherLines = [];

            foreach ($txLines as $line) {
                if (in_array($line['akun_id'], $kasBankIds)) {
                    $kasDebit += $line['debit'];
                    $kasKredit += $line['kredit'];
                } else {
                    $otherLines[] = $line;
                }
            }

            $netCash = $kasDebit - $kasKredit;
            if ($netCash == 0) continue;

            $primaryCategory = '';
            if (!empty($otherLines)) {
                $primaryCategory = $otherLines[0]['akun_kategori'];
            }

            $type = 'operasional';
            if (in_array($primaryCategory, ['aktiva tetap', 'aktiva lainya', 'depresiasi & amortisasi'])) {
                $type = 'investasi';
            } elseif (in_array($primaryCategory, ['ekuitas', 'kewajiban jangka panjang'])) {
                $type = 'pendanaan';
            }

            $desc = !empty($otherLines) ? $otherLines[0]['nama_akun'] : 'Lainnya';
            
            // Check if already exists and add it
            $found = false;
            foreach ($cashData[$type] as &$cd) {
                if ($cd['nama'] == $desc) {
                    $cd['total'] += $netCash;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cashData[$type][] = [
                    'nama' => $desc,
                    'total' => $netCash
                ];
            }
        }

        $totalOperasional = array_sum(array_column($cashData['operasional'], 'total'));
        $totalInvestasi = array_sum(array_column($cashData['investasi'], 'total'));
        $totalPendanaan = array_sum(array_column($cashData['pendanaan'], 'total'));
        $netChange = $totalOperasional + $totalInvestasi + $totalPendanaan;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Header Laporan (Mirip Gambar)
        $sheet->setCellValue("A1", "PT ALMA INDONESIA RAYA");
        $sheet->setCellValue("A2", "LAPORAN ARUS KAS");
        $sheet->setCellValue("A3", "Periode " . date('d M Y', strtotime($startDate)) . " s/d " . date('d M Y', strtotime($endDate)));
        $sheet->setCellValue("A4", "(Dalam Rupiah)");
        
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');
        
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        $row = 6;
        
        // 2. Table Headers
        $sheet->setCellValue("A{$row}", "Keterangan");
        $sheet->setCellValue("B{$row}", "Catatan");
        $sheet->setCellValue("C{$row}", date('M Y', strtotime($endDate))); 
        $sheet->setCellValue("D{$row}", date('M Y', strtotime($startDate . ' -1 month')));
        
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        $row++;

        // Helper untuk set row dengan border
        $setRowData = function($row, $colA, $colB, $colC, $colD, $isBold = false) use (&$sheet) {
            $sheet->setCellValue("A{$row}", $colA);
            $sheet->setCellValue("B{$row}", $colB);
            
            if (is_numeric($colC)) $sheet->setCellValueExplicit("C{$row}", $colC, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            else $sheet->setCellValue("C{$row}", $colC);
            
            if (is_numeric($colD)) $sheet->setCellValueExplicit("D{$row}", $colD, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            else $sheet->setCellValue("D{$row}", $colD);

            if ($isBold) {
                $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
                if (strpos($colA, 'ARUS KAS DARI') !== false) {
                    $sheet->getStyle("A{$row}")->getFont()->setUnderline(true);
                }
            }
            if (is_numeric($colC) || is_numeric($colD)) {
                $sheet->getStyle("C{$row}:D{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            }
            $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        };

        // ARUS KAS DARI AKTIVITAS OPERASI
        $setRowData($row++, "ARUS KAS DARI AKTIVITAS OPERASI", "", "", "", true);
        
        $penerimaanOperasi = [];
        $pengeluaranOperasi = [];
        foreach ($cashData['operasional'] as $item) {
            if ($item['total'] > 0) $penerimaanOperasi[] = $item;
            else $pengeluaranOperasi[] = $item;
        }

        $setRowData($row++, "Penerimaan kas dari:", "", "", "");
        foreach ($penerimaanOperasi as $item) {
            $setRowData($row++, "  " . $item['nama'], "", $item['total'], 0);
        }
        $totalPenerimaanOp = array_sum(array_column($penerimaanOperasi, 'total'));
        $setRowData($row++, "Total penerimaan", "", $totalPenerimaanOp, 0, true);

        $setRowData($row++, "Pembayaran kas untuk:", "", "", "");
        foreach ($pengeluaranOperasi as $item) {
            $setRowData($row++, "  " . $item['nama'], "", $item['total'], 0); // They are negative
        }
        $totalPengeluaranOp = array_sum(array_column($pengeluaranOperasi, 'total'));
        $setRowData($row++, "Total pengeluaran", "", $totalPengeluaranOp, 0, true);

        $setRowData($row++, "Jumlah arus kas dari aktivitas operasi", "", $totalOperasional, 0, true);

        // ARUS KAS DARI AKTIVITAS INVESTASI
        $setRowData($row++, "ARUS KAS DARI AKTIVITAS INVESTASI", "", "", "", true);
        $penerimaanInv = [];
        $pengeluaranInv = [];
        foreach ($cashData['investasi'] as $item) {
            if ($item['total'] > 0) $penerimaanInv[] = $item;
            else $pengeluaranInv[] = $item;
        }
        $setRowData($row++, "Penerimaan kas dari:", "", "", "");
        foreach ($penerimaanInv as $item) {
            $setRowData($row++, "  " . $item['nama'], "", $item['total'], 0);
        }
        $totalPenerimaanInv = array_sum(array_column($penerimaanInv, 'total'));
        $setRowData($row++, "Total penerimaan", "", $totalPenerimaanInv, 0, true);

        $setRowData($row++, "Pembayaran kas untuk:", "", "", "");
        foreach ($pengeluaranInv as $item) {
            $setRowData($row++, "  " . $item['nama'], "", $item['total'], 0);
        }
        $totalPengeluaranInv = array_sum(array_column($pengeluaranInv, 'total'));
        $setRowData($row++, "Total pengeluaran", "", $totalPengeluaranInv, 0, true);

        $setRowData($row++, "Jumlah arus dari aktivitas investasi", "", $totalInvestasi, 0, true);

        // ARUS KAS DARI AKTIVITAS PENDANAAN
        $setRowData($row++, "ARUS KAS DARI AKTIVITAS PENDANAAN", "", "", "", true);
        $penerimaanPen = [];
        $pengeluaranPen = [];
        foreach ($cashData['pendanaan'] as $item) {
            if ($item['total'] > 0) $penerimaanPen[] = $item;
            else $pengeluaranPen[] = $item;
        }
        $setRowData($row++, "Penerimaan kas dari:", "", "", "");
        foreach ($penerimaanPen as $item) {
            $setRowData($row++, "  " . $item['nama'], "", $item['total'], 0);
        }
        $totalPenerimaanPen = array_sum(array_column($penerimaanPen, 'total'));
        $setRowData($row++, "Total penerimaan", "", $totalPenerimaanPen, 0, true);

        $setRowData($row++, "Pembayaran kas untuk:", "", "", "");
        foreach ($pengeluaranPen as $item) {
            $setRowData($row++, "  " . $item['nama'], "", $item['total'], 0);
        }
        $totalPengeluaranPen = array_sum(array_column($pengeluaranPen, 'total'));
        $setRowData($row++, "Total pengeluaran", "", $totalPengeluaranPen, 0, true);

        $setRowData($row++, "Jumlah arus kas dari aktivitas pendanaan", "", $totalPendanaan, 0, true);

        // KAS DAN SETARA KAS
        $setRowData($row++, "Kenaikan (penurunan) arus kas neto", "", $netChange, 0, true);
        $setRowData($row++, "Saldo kas dan setara kas awal periode", "", $openingBalance, 0, true);
        $setRowData($row++, "Saldo kas dan setara kas akhir periode", "", $openingBalance + $netChange, 0, true);

        // Adjust column widths to avoid ####
        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Laporan_Arus_Kas_" . date('Ymd', strtotime($endDate)) . ".xlsx";
        
        ob_start();
        $writer->save('php://output');
        $excelData = ob_get_clean();

        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                              ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                              ->setBody($excelData);
    }

    public function exportPdfPerubahanModal()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $db = \Config\Database::connect();
        $currMonthStart = date('Y-m-01', strtotime($startDate));
        $currMonthEnd = date('Y-m-t', strtotime($endDate));
        $prevMonthStart = date('Y-m-01', strtotime($currMonthStart . ' -1 month'));
        $prevMonthEnd = date('Y-m-t', strtotime($currMonthStart . ' -1 month'));
        $prevPrevMonthEnd = date('Y-m-t', strtotime($prevMonthStart . ' -1 month'));

        $fetchMutasi = function($start, $end) use ($db) {
            $labaQuery = $db->table('akun')
                ->select('akun.kategori, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('jurnal.tanggal >=', $start)
                ->where('jurnal.tanggal <=', $end)
                ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])
                ->groupBy('akun.kategori')
                ->get()
                ->getResultArray();
            $labaBerjalan = 0;
            foreach ($labaQuery as $row) {
                if (strpos($row['kategori'], 'pendapatan') !== false) {
                    $labaBerjalan += ($row['total_kredit'] - $row['total_debit']);
                } else {
                    $labaBerjalan -= ($row['total_debit'] - $row['total_kredit']);
                }
            }

            $equityData = $db->table('akun')
                ->select('akun.id, akun.nama_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('akun.kategori', 'ekuitas')
                ->where('jurnal.tanggal >=', $start)
                ->where('jurnal.tanggal <=', $end)
                ->groupBy('akun.id')
                ->get()
                ->getResultArray();
            
            $setoranModal = 0;
            $dividen = 0;
            foreach ($equityData as $eq) {
                $net = $eq['total_kredit'] - $eq['total_debit'];
                $nama = strtolower($eq['nama_akun']);
                if (strpos($nama, 'prive') !== false || strpos($nama, 'dividen') !== false) {
                    $dividen += $net; 
                } elseif (strpos($nama, 'modal') !== false || strpos($nama, 'saham') !== false) {
                    $setoranModal += $net;
                }
            }
            return ['laba' => $labaBerjalan, 'modal' => $setoranModal, 'dividen' => $dividen];
        };

        $fetchSaldo = function($date) use ($db) {
            $labaQuery = $db->table('akun')
                ->select('akun.kategori, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('jurnal.tanggal <=', $date)
                ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])
                ->groupBy('akun.kategori')
                ->get()
                ->getResultArray();
            $labaDitahan = 0;
            foreach ($labaQuery as $row) {
                if (strpos($row['kategori'], 'pendapatan') !== false) {
                    $labaDitahan += ($row['total_kredit'] - $row['total_debit']);
                } else {
                    $labaDitahan -= ($row['total_debit'] - $row['total_kredit']);
                }
            }

            $equityData = $db->table('akun')
                ->select('akun.id, akun.nama_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('akun.kategori', 'ekuitas')
                ->where('jurnal.tanggal <=', $date)
                ->groupBy('akun.id')
                ->get()
                ->getResultArray();
            
            $modalDisetor = 0;
            foreach ($equityData as $eq) {
                $net = $eq['total_kredit'] - $eq['total_debit'];
                $nama = strtolower($eq['nama_akun']);
                if (strpos($nama, 'modal') !== false || strpos($nama, 'saham') !== false || strpos($nama, 'prive') !== false || strpos($nama, 'dividen') !== false) {
                    $modalDisetor += $net;
                }
            }
            return ['laba' => $labaDitahan, 'modal' => $modalDisetor];
        };

        $saldoPrevPrev = $fetchSaldo($prevPrevMonthEnd);
        $mutasiPrev = $fetchMutasi($prevMonthStart, $prevMonthEnd);
        $saldoPrev = $fetchSaldo($prevMonthEnd);
        $mutasiCurr = $fetchMutasi($currMonthStart, $currMonthEnd);
        $saldoCurr = $fetchSaldo($currMonthEnd);

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'prevMonthEnd' => $prevMonthEnd,
            'prevPrevMonthEnd' => $prevPrevMonthEnd,
            'currMonthEnd' => $currMonthEnd,
            'saldoPrevPrev' => $saldoPrevPrev,
            'mutasiPrev' => $mutasiPrev,
            'saldoPrev' => $saldoPrev,
            'mutasiCurr' => $mutasiCurr,
            'saldoCurr' => $saldoCurr
        ];

        $html = view('keuangan/laporan/pdf_perubahan_modal', $data);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Important for matrix layout
        $dompdf->render();
        $output = $dompdf->output();
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Perubahan_Modal_' . date('Ymd', strtotime($endDate)) . '.pdf"')
                              ->setBody($output);
    }


    public function exportExcelPerubahanModal()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        if (strtotime($startDate) > strtotime($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $db = \Config\Database::connect();
        $currMonthStart = date('Y-m-01', strtotime($startDate));
        $currMonthEnd = date('Y-m-t', strtotime($endDate));
        $prevMonthStart = date('Y-m-01', strtotime($currMonthStart . ' -1 month'));
        $prevMonthEnd = date('Y-m-t', strtotime($currMonthStart . ' -1 month'));
        $prevPrevMonthEnd = date('Y-m-t', strtotime($prevMonthStart . ' -1 month'));

        // Helper to fetch data for a specific period (mutasi)
        $fetchMutasi = function($start, $end) use ($db) {
            // Laba bersih
            $labaQuery = $db->table('akun')
                ->select('akun.kategori, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('jurnal.tanggal >=', $start)
                ->where('jurnal.tanggal <=', $end)
                ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])
                ->groupBy('akun.kategori')
                ->get()
                ->getResultArray();
            $labaBerjalan = 0;
            foreach ($labaQuery as $row) {
                if (strpos($row['kategori'], 'pendapatan') !== false) {
                    $labaBerjalan += ($row['total_kredit'] - $row['total_debit']);
                } else {
                    $labaBerjalan -= ($row['total_debit'] - $row['total_kredit']);
                }
            }

            // Ekuitas changes
            $equityData = $db->table('akun')
                ->select('akun.id, akun.nama_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('akun.kategori', 'ekuitas')
                ->where('jurnal.tanggal >=', $start)
                ->where('jurnal.tanggal <=', $end)
                ->groupBy('akun.id')
                ->get()
                ->getResultArray();
            
            $setoranModal = 0;
            $dividen = 0;
            foreach ($equityData as $eq) {
                $net = $eq['total_kredit'] - $eq['total_debit'];
                $nama = strtolower($eq['nama_akun']);
                if (strpos($nama, 'prive') !== false || strpos($nama, 'dividen') !== false) {
                    $dividen += $net; // Usually negative if debit is higher
                } elseif (strpos($nama, 'modal') !== false || strpos($nama, 'saham') !== false) {
                    $setoranModal += $net;
                }
            }
            return ['laba' => $labaBerjalan, 'modal' => $setoranModal, 'dividen' => $dividen];
        };

        // Helper to fetch balance up to a date
        $fetchSaldo = function($date) use ($db) {
            $labaQuery = $db->table('akun')
                ->select('akun.kategori, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('jurnal.tanggal <=', $date)
                ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya', 'harga pokok penjualan', 'beban', 'beban lainya'])
                ->groupBy('akun.kategori')
                ->get()
                ->getResultArray();
            $labaDitahan = 0;
            foreach ($labaQuery as $row) {
                if (strpos($row['kategori'], 'pendapatan') !== false) {
                    $labaDitahan += ($row['total_kredit'] - $row['total_debit']);
                } else {
                    $labaDitahan -= ($row['total_debit'] - $row['total_kredit']);
                }
            }

            $equityData = $db->table('akun')
                ->select('akun.id, akun.nama_akun, SUM(jurnal.kredit) as total_kredit, SUM(jurnal.debit) as total_debit')
                ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL')
                ->where('akun.kategori', 'ekuitas')
                ->where('jurnal.tanggal <=', $date)
                ->groupBy('akun.id')
                ->get()
                ->getResultArray();
            
            $modalDisetor = 0;
            foreach ($equityData as $eq) {
                $net = $eq['total_kredit'] - $eq['total_debit'];
                $nama = strtolower($eq['nama_akun']);
                if (strpos($nama, 'modal') !== false || strpos($nama, 'saham') !== false || strpos($nama, 'prive') !== false || strpos($nama, 'dividen') !== false) {
                    $modalDisetor += $net;
                }
            }
            return ['laba' => $labaDitahan, 'modal' => $modalDisetor];
        };

        $saldoPrevPrev = $fetchSaldo($prevPrevMonthEnd);
        $mutasiPrev = $fetchMutasi($prevMonthStart, $prevMonthEnd);
        $saldoPrev = $fetchSaldo($prevMonthEnd);
        $mutasiCurr = $fetchMutasi($currMonthStart, $currMonthEnd);
        $saldoCurr = $fetchSaldo($currMonthEnd);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Header Laporan
        $sheet->setCellValue("A1", "PT ALMA INDONESIA RAYA");
        $sheet->setCellValue("A2", "LAPORAN PERUBAHAN EKUITAS");
        $sheet->setCellValue("A3", "Per " . date('d M Y', strtotime($endDate)));
        $sheet->setCellValue("A4", "(Dalam Rupiah)");
        
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');
        $sheet->mergeCells('A4:K4');
        
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        // 2. Table Headers
        $row = 6;
        $headers = [
            'A' => "Keterangan",
            'B' => "Modal disetor dan
tambahan modal disetor",
            'C' => "Ditentukan
penggunaannya",
            'D' => "Belum ditentukan
penggunaannya",
            'E' => "Selisih penilaian
Aset Keuangan",
            'F' => "Keuntungan dan
kerugian aktuarial",
            'G' => "Surplus
Revaluasi",
            'H' => "Selisih kurs penjabaran
laporan keuangan dalam
mata uang asing",
            'I' => "Ekuitas Lainnya",
            'J' => "Kepentingan
nonpengendali",
            'K' => "Jumlah Ekuitas"
        ];

        foreach ($headers as $col => $title) {
            if ($col == 'C') {
                $sheet->setCellValue("C{$row}", "Saldo laba");
                $sheet->mergeCells("C{$row}:D{$row}");
                $sheet->setCellValue("C" . ($row+1), $title);
            } elseif ($col == 'D') {
                $sheet->setCellValue("D" . ($row+1), $title);
            } else {
                $sheet->setCellValue("{$col}{$row}", $title);
                $sheet->mergeCells("{$col}{$row}:{$col}" . ($row+1));
            }
        }
        
        $sheet->getStyle("A{$row}:K" . ($row+1))->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:K" . ($row+1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:K" . ($row+1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$row}:K" . ($row+1))->getAlignment()->setWrapText(true);
        $sheet->getStyle("A{$row}:K" . ($row+1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        $row += 2;

        $printRow = function($r, $ket, $modal, $labaDitentukan, $labaBelum, $isBold = false) use (&$sheet) {
            $jumlah = $modal + $labaDitentukan + $labaBelum;
            $data = [
                'A' => $ket, 'B' => $modal, 'C' => $labaDitentukan, 'D' => $labaBelum,
                'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0, 'I' => 0, 'J' => 0, 'K' => $jumlah
            ];
            foreach ($data as $col => $val) {
                if ($col == 'A') {
                    $sheet->setCellValue("{$col}{$r}", $val);
                } else {
                    $sheet->setCellValueExplicit("{$col}{$r}", $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0.00');
                }
                $sheet->getStyle("{$col}{$r}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            if ($isBold) {
                $sheet->getStyle("A{$r}:K{$r}")->getFont()->setBold(true);
            }
        };

        // Saldo Prev Prev
        $printRow($row++, "Saldo per " . date('d M Y', strtotime($prevPrevMonthEnd)), $saldoPrevPrev['modal'], 0, $saldoPrevPrev['laba'], true);
        $printRow($row++, "Setoran Modal", $mutasiPrev['modal'], 0, 0);
        $printRow($row++, "Laba Bersih", 0, 0, $mutasiPrev['laba']);
        $printRow($row++, "Cadangan umum", 0, 0, 0);
        $printRow($row++, "Cadangan tujuan", 0, 0, 0);
        $printRow($row++, "Dividen", 0, 0, $mutasiPrev['dividen']); // dividen usually negative
        $printRow($row++, "Penghasilan komprehensif tahun", 0, 0, 0);
        
        // Saldo Prev
        $printRow($row++, "Saldo per " . date('d M Y', strtotime($prevMonthEnd)), $saldoPrev['modal'], 0, $saldoPrev['laba'], true);
        $printRow($row++, "Setoran Modal", $mutasiCurr['modal'], 0, 0);
        $printRow($row++, "Cadangan umum", 0, 0, 0);
        $printRow($row++, "Cadangan tujuan", 0, 0, 0);
        $printRow($row++, "Dividen", 0, 0, $mutasiCurr['dividen']);
        $printRow($row++, "Penghasilan komprehensif tahun", 0, 0, 0);

        // Saldo Curr
        $printRow($row++, "Saldo per " . date('d M Y', strtotime($currMonthEnd)), $saldoCurr['modal'], 0, $saldoCurr['laba'], true);

        // Adjust widths
        $sheet->getColumnDimension('A')->setWidth(30);
        foreach (range('B', 'K') as $col) {
            $sheet->getColumnDimension($col)->setWidth(18);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Perubahan_Ekuitas_" . date('Ymd', strtotime($endDate)) . ".xlsx";
        
        ob_start();
        $writer->save('php://output');
        $excelData = ob_get_clean();

        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                              ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                              ->setBody($excelData);
    }

    public function exportPdfNeracaSaldo()
    {
        $endDate = $this->request->getGet('date') ?? date('Y-m-t');
        $db = \Config\Database::connect();

        $reportData = $db->table('akun')
            ->select('akun.id, akun.kode_akun, akun.nama_akun, akun.kategori, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('jurnal', 'jurnal.akun_id = akun.id AND jurnal.deleted_at IS NULL AND jurnal.tanggal <= ' . $db->escape($endDate), 'left')
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'reportData' => $reportData,
            'endDate' => $endDate
        ];

        $html = view('keuangan/laporan/pdf_neraca_saldo', $data);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setHeader('Content-Disposition', 'inline; filename="Neraca_Saldo_' . date('Ymd', strtotime($endDate)) . '.pdf"')
                              ->setBody($output);
    }
}