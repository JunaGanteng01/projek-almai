<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\JurnalModel;
use App\Models\AkunModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KasMasukKeluar extends BaseController
{
    private function getPindahDanaAccounts(AkunModel $akunModel): array
    {
        $labels = [
            '1-1120' => 'Pindah Dana BCA',
            '1-1112' => 'Pindah Dana Mandiri',
            '1-1123' => 'Pindah Dana Danamon',
            '1-1121' => 'Pindah Dana BRI',
        ];

        $accounts = $akunModel
            ->whereIn('kode_akun', array_keys($labels))
            ->where('kategori', 'kas & bank')
            ->findAll();
        $accountsByCode = array_column($accounts, null, 'kode_akun');

        $result = [];
        foreach ($labels as $kodeAkun => $label) {
            if (!isset($accountsByCode[$kodeAkun])) {
                continue;
            }

            $account = $accountsByCode[$kodeAkun];
            $account['nama_akun'] = $label;
            $result[] = $account;
        }

        return $result;
    }

    private function splitTransactionLines(array $lines, string $tipe): array
    {
        $kasLine = null;
        $lawanLine = null;
        $kasColumn = $tipe === 'masuk' ? 'debit' : 'kredit';
        $lawanColumn = $tipe === 'masuk' ? 'kredit' : 'debit';

        foreach ($lines as $line) {
            if ($kasLine === null && (float) $line[$kasColumn] > 0) {
                $kasLine = $line;
            }
            if ($lawanLine === null && (float) $line[$lawanColumn] > 0) {
                $lawanLine = $line;
            }
        }

        if (!$kasLine || !$lawanLine) {
            $kasLine = $lines[0] ?? null;
            $lawanLine = $lines[1] ?? null;
        }

        return [$kasLine, $lawanLine];
    }

    public function index()
    {
        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();

        // Get all kas/bank accounts
        $kasBankAccounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();

        // Pindah Dana Accounts
        $pindahDanaAccounts = $this->getPindahDanaAccounts($akunModel);

        // Get income and expense accounts for dropdowns
        $pendapatanAccounts = $akunModel->whereIn('kategori', ['pendapatan', 'pendapatan lainya'])->orderBy('kode_akun', 'ASC')->findAll();
        $pengeluaranAccounts = $akunModel->whereIn('kategori', ['beban', 'harga pokok penjualan', 'beban lainya'])->orderBy('kode_akun', 'ASC')->findAll();

        // Get history of Kas Masuk / Keluar by filtering no_reff starting with KM- or KK-
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        $builder->select('jurnal.*, akun.nama_akun, akun.kategori');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->groupStart()
                ->like('jurnal.no_reff', 'KM-', 'after')
                ->orLike('jurnal.no_reff', 'KK-', 'after')
                ->groupEnd();
        $builder->where('jurnal.deleted_at', null); // Ignore soft-deleted rows
        $builder->orderBy('jurnal.tanggal', 'DESC');
        $builder->orderBy('jurnal.id', 'DESC');
        $allJurnals = $builder->get()->getResultArray();

        $transactions = [];
        $grouped = [];
        foreach ($allJurnals as $j) {
            $grouped[$j['no_reff']][] = $j;
        }

        foreach ($grouped as $reff => $lines) {
            if (count($lines) < 2) continue;
            
            $tipe = strpos($reff, 'KM-') === 0 ? 'Kas Masuk' : 'Kas Keluar';
            
            [$kasLine, $lawanLine] = $this->splitTransactionLines(
                $lines,
                $tipe === 'Kas Masuk' ? 'masuk' : 'keluar'
            );

            $nominal = max($kasLine['debit'], $kasLine['kredit']);
            
            $transactions[] = [
                'no_reff' => $reff,
                'tanggal' => $kasLine['tanggal'],
                'tipe' => $tipe,
                'akun_kas' => $kasLine['nama_akun'],
                'akun_tujuan' => $lawanLine['nama_akun'],
                'nominal' => $nominal,
                'keterangan' => $kasLine['deskripsi']
            ];
        }

        $transactions = array_slice($transactions, 0, 100);

        $data = [
            'title' => 'Kas Masuk & Keluar',
            'activeMenu' => 'kas_masuk_keluar',
            'kasBankAccounts' => $kasBankAccounts,
            'pindahDanaAccounts' => $pindahDanaAccounts,
            'pendapatanAccounts' => $pendapatanAccounts,
            'pengeluaranAccounts' => $pengeluaranAccounts,
            'transactions' => $transactions
        ];

        return view('keuangan/kas_masuk_keluar/index', $data);
    }

    /**
     * Display only Kas Masuk (Cash In) transactions
     */
    public function kasMasuk()
    {
        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();

        // Get all kas/bank accounts
        $kasBankAccounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();

        // Pindah Dana Accounts
        $pindahDanaAccounts = $this->getPindahDanaAccounts($akunModel);

        // Get income accounts for dropdowns
        $pendapatanAccounts = $akunModel->whereIn('kategori', ['pendapatan', 'pendapatan lainya'])->orderBy('kode_akun', 'ASC')->findAll();

        $filterBulan = $this->request->getGet('bulan');
        $filterAkun = $this->request->getGet('akun_id');
        $search = $this->request->getGet('search');

        // Get history of Kas Masuk only (filtering no_reff starting with KM-)
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        $builder->select('jurnal.*, akun.nama_akun, akun.kategori');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->like('jurnal.no_reff', 'KM-', 'after');
        $builder->where('jurnal.deleted_at', null);

        if ($search) {
            $builder->groupStart()
                ->like('jurnal.no_reff', $search)
                ->orLike('jurnal.deskripsi', $search)
                ->orLike('akun.nama_akun', $search)
                ->groupEnd();
        }

        if ($filterBulan) {
            $builder->like('jurnal.tanggal', $filterBulan, 'after');
        }

        if ($filterAkun) {
            $reffs = $db->table('jurnal')
                ->select('no_reff')
                ->where('akun_id', $filterAkun)
                ->like('no_reff', 'KM-', 'after')
                ->where('deleted_at', null);
            
            if ($filterBulan) {
                $reffs->like('tanggal', $filterBulan, 'after');
            }
            
            $reffsArray = $reffs->get()->getResultArray();
            $reffList = array_column($reffsArray, 'no_reff');

            if (!empty($reffList)) {
                $builder->whereIn('jurnal.no_reff', $reffList);
            } else {
                $builder->where('jurnal.id', -1); // force empty result
            }
        }
    

        $builder->orderBy('jurnal.tanggal', 'DESC');
        $builder->orderBy('jurnal.id', 'DESC');
        $allJurnals = $builder->get()->getResultArray();

        $transactions = [];
        $grouped = [];
        foreach ($allJurnals as $j) {
            $grouped[$j['no_reff']][] = $j;
        }

        foreach ($grouped as $reff => $lines) {
            if (count($lines) < 2) continue;
            
            [$kasLine, $lawanLine] = $this->splitTransactionLines($lines, 'masuk');

            $nominal = max($kasLine['debit'], $kasLine['kredit']);
            
            $transactions[] = [
                'no_reff' => $reff,
                'tanggal' => $kasLine['tanggal'],
                'akun_kas_id' => $kasLine['akun_id'],
                'akun_tujuan_id' => $lawanLine['akun_id'],
                'akun_kas' => $kasLine['nama_akun'],
                'akun_tujuan' => $lawanLine['nama_akun'],
                'nominal' => $nominal,
                'keterangan' => $kasLine['deskripsi'],
                'is_duplicate' => false // default
            ];
        }

        // Detect duplicates
        $duplicateGroups = [];
        foreach ($transactions as $index => $t) {
            $key = $t['akun_kas_id'] . '_' . $t['akun_tujuan_id'] . '_' . $t['nominal'] . '_' . md5(strtolower(trim($t['keterangan'])));
            $duplicateGroups[$key][] = $index;
        }

        foreach ($duplicateGroups as $key => $indices) {
            if (count($indices) > 1) {
                // Mark all as duplicate except the first one (or mark all as duplicate if we want to show a warning on all of them)
                // Let's mark all as duplicate so the user knows they are grouped duplicates
                foreach ($indices as $idx) {
                    $transactions[$idx]['is_duplicate'] = true;
                }
            }
        }

        $page = (int)($this->request->getVar('page') ?? 1);
        $perPage = 20;
        $total = count($transactions);
        $pager = \Config\Services::pager();
        
        $transactions = array_slice($transactions, ($page - 1) * $perPage, $perPage);

        $data = [
            'title' => 'Kas Masuk',
            'activeMenu' => 'kas_masuk',
            'kasBankAccounts' => $kasBankAccounts,
            'pindahDanaAccounts' => $pindahDanaAccounts,
            'pendapatanAccounts' => $pendapatanAccounts,
            'transactions' => $transactions,
            'pagerLinks' => $pager->makeLinks($page, $perPage, $total, 'tailwind_full')
        ];

        return view('keuangan/kas-masuk/index', $data);
    }

    /**
     * Display only Kas Keluar (Cash Out) transactions
     */
    public function kasKeluar()
    {
        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();

        // Get all kas/bank accounts
        $kasBankAccounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();

        // Get expense accounts for dropdowns
        $pengeluaranAccounts = $akunModel->whereIn('kategori', ['beban', 'harga pokok penjualan', 'beban lainya'])->orderBy('kode_akun', 'ASC')->findAll();
        $pindahDanaAccounts = $this->getPindahDanaAccounts($akunModel);

        $filterBulan = $this->request->getGet('bulan');
        $filterAkun = $this->request->getGet('akun_id');

        $search = $this->request->getGet('search');

        // Get history of Kas Keluar only (filtering no_reff starting with KK-)
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        $builder->select('jurnal.*, akun.nama_akun, akun.kategori');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->like('jurnal.no_reff', 'KK-', 'after');
        $builder->where('jurnal.deleted_at', null);

        if ($search) {
            $builder->groupStart()
                ->like('jurnal.no_reff', $search)
                ->orLike('jurnal.deskripsi', $search)
                ->orLike('akun.nama_akun', $search)
                ->groupEnd();
        }

        if ($filterBulan) {
            $builder->like('jurnal.tanggal', $filterBulan, 'after');
        }

        if ($filterAkun) {
            $reffs = $db->table('jurnal')
                ->select('no_reff')
                ->where('akun_id', $filterAkun)
                ->like('no_reff', 'KK-', 'after')
                ->where('deleted_at', null);
            
            if ($filterBulan) {
                $reffs->like('tanggal', $filterBulan, 'after');
            }
            
            $reffsArray = $reffs->get()->getResultArray();
            $reffList = array_column($reffsArray, 'no_reff');

            if (!empty($reffList)) {
                $builder->whereIn('jurnal.no_reff', $reffList);
            } else {
                $builder->where('jurnal.id', -1); // force empty result
            }
        }

        $builder->orderBy('jurnal.tanggal', 'DESC');
        $builder->orderBy('jurnal.id', 'DESC');
        $allJurnals = $builder->get()->getResultArray();

        $transactions = [];
        $grouped = [];
        foreach ($allJurnals as $j) {
            $grouped[$j['no_reff']][] = $j;
        }

        foreach ($grouped as $reff => $lines) {
            if (count($lines) < 2) continue;
            
            [$kasLine, $lawanLine] = $this->splitTransactionLines($lines, 'keluar');

            $nominal = max($kasLine['debit'], $kasLine['kredit']);
            
            $transactions[] = [
                'no_reff' => $reff,
                'tanggal' => $kasLine['tanggal'],
                'akun_kas_id' => $kasLine['akun_id'],
                'akun_tujuan_id' => $lawanLine['akun_id'],
                'akun_kas' => $kasLine['nama_akun'],
                'akun_tujuan' => $lawanLine['nama_akun'],
                'nominal' => $nominal,
                'keterangan' => $kasLine['deskripsi'],
                'is_duplicate' => false // default
            ];
        }

        // Detect duplicates
        $duplicateGroups = [];
        foreach ($transactions as $index => $t) {
            $key = $t['akun_kas_id'] . '_' . $t['akun_tujuan_id'] . '_' . $t['nominal'] . '_' . md5(strtolower(trim($t['keterangan'])));
            $duplicateGroups[$key][] = $index;
        }

        foreach ($duplicateGroups as $key => $indices) {
            if (count($indices) > 1) {
                // Mark all as duplicate
                foreach ($indices as $idx) {
                    $transactions[$idx]['is_duplicate'] = true;
                }
            }
        }

        $page = (int)($this->request->getVar('page') ?? 1);
        $perPage = 20;
        $total = count($transactions);
        $pager = \Config\Services::pager();
        
        $transactions = array_slice($transactions, ($page - 1) * $perPage, $perPage);

        $data = [
            'title' => 'Kas Keluar',
            'activeMenu' => 'kas_keluar',
            'kasBankAccounts' => $kasBankAccounts,
            'pindahDanaAccounts' => $pindahDanaAccounts,
            'pengeluaranAccounts' => $pengeluaranAccounts,
            'transactions' => $transactions,
            'pagerLinks' => $pager->makeLinks($page, $perPage, $total, 'tailwind_full')
        ];

        return view('keuangan/kas-keluar/index', $data);
    }

    public function save()
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $db = \Config\Database::connect();

        $tipe = $this->request->getPost('tipe'); // 'masuk' or 'keluar'
        $akun_kas_id = $this->request->getPost('akun_kas_id');
        $akun_lawan_id = $this->request->getPost('akun_lawan_id');
        $nominal = $this->request->getPost('nominal');
        $tanggal = $this->request->getPost('tanggal');
        $deskripsi = $this->request->getPost('deskripsi');
        $no_reff_existing = $this->request->getPost('no_reff');

        if (!$akun_kas_id || !$akun_lawan_id || $nominal <= 0) {
            return redirect()->back()->with('error', 'Semua form wajib diisi dengan nominal lebih dari 0.');
        }

        if ((int) $akun_kas_id === (int) $akun_lawan_id) {
            return redirect()->back()->with('error', 'Akun asal dan akun tujuan pindah dana tidak boleh sama.');
        }

        if ($no_reff_existing) {
            $no_reff = $no_reff_existing;
            $jurnalModel->where('no_reff', $no_reff)->delete();
        } else {
            $no_reff = ($tipe == 'masuk' ? 'KM-' : 'KK-') . time();
        }

        $journal = new \App\Services\Keuangan\JournalService();
        $lines = $tipe === 'masuk'
            ? [
                ['akun_id' => (int) $akun_kas_id, 'debit' => (float) $nominal, 'kredit' => 0],
                ['akun_id' => (int) $akun_lawan_id, 'debit' => 0, 'kredit' => (float) $nominal],
            ]
            : [
                ['akun_id' => (int) $akun_lawan_id, 'debit' => (float) $nominal, 'kredit' => 0],
                ['akun_id' => (int) $akun_kas_id, 'debit' => 0, 'kredit' => (float) $nominal],
            ];

        $result = $journal->post($tanggal, $no_reff, $deskripsi, $lines, false);
        if (!$result['ok']) {
            return redirect()->back()->with('error', $result['message'] ?? 'Gagal menyimpan transaksi.');
        }

        $redirect = $tipe === 'masuk' ? '/keuangan/kas-masuk' : '/keuangan/kas-keluar';
        return redirect()->to($redirect)->with('success', 'Transaksi kas berhasil dicatat (balance).');
    }

    public function delete($no_reff)
    {
        $jurnalModel = new \App\Models\JurnalModel();
        
        // Ensure only KM- or KK- can be deleted from this module
        if (strpos($no_reff, 'KM-') !== 0 && strpos($no_reff, 'KK-') !== 0) {
            return redirect()->back()->with('error', 'Hanya bisa menghapus referensi KM atau KK.');
        }

        try {
            $jurnalModel->where('no_reff', $no_reff)->delete();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function get($no_reff)
    {
        $db = \Config\Database::connect();
        $lines = $db->table('jurnal')
                    ->select('jurnal.*, akun.nama_akun, akun.kategori')
                    ->join('akun', 'akun.id = jurnal.akun_id')
                    ->where('jurnal.no_reff', $no_reff)
                    ->where('jurnal.deleted_at', null)
                    ->get()->getResultArray();
        
        if (count($lines) < 2) return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
        
        $tipe = strpos($no_reff, 'KM-') === 0 ? 'masuk' : 'keluar';
        [$kasLine, $lawanLine] = $this->splitTransactionLines($lines, $tipe);
        
        $nominal = max($kasLine['debit'], $kasLine['kredit']);
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'no_reff' => $no_reff,
                'tanggal' => $kasLine['tanggal'],
                'akun_kas_id' => $kasLine['akun_id'],
                'akun_lawan_id' => $lawanLine['akun_id'],
                'nominal' => $nominal,
                'deskripsi' => $kasLine['deskripsi'],
            ]
        ]);
    }

    public function downloadTemplate()
    {
        $akunModel = new AkunModel();
        $kasBankAccounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();
        // Pisah pendapatan dan beban untuk Dependent Dropdown (INDIRECT)
        $pendapatanAccounts = $akunModel->whereIn('kategori', ['pendapatan', 'pendapatan lainya'])->orderBy('kode_akun', 'ASC')->findAll();
        $bebanAccounts = $akunModel->whereIn('kategori', ['beban', 'harga pokok penjualan', 'beban lainya'])->orderBy('kode_akun', 'ASC')->findAll();

        $spreadsheet = new Spreadsheet();
        
        // 1. Create a hidden sheet for the dropdown data
        $dataSheet = $spreadsheet->createSheet(1);
        $dataSheet->setTitle('DataCOA');
        
        // A: Kas & Bank
        $row = 1;
        foreach ($kasBankAccounts as $akun) {
            $dataSheet->setCellValue('A' . $row, $akun['kode_akun'] . ' - ' . $akun['nama_akun']);
            $row++;
        }
        $kasCount = count($kasBankAccounts);
        
        // B: Pendapatan (Untuk KM)
        $row = 1;
        foreach ($pendapatanAccounts as $akun) {
            $dataSheet->setCellValue('B' . $row, $akun['kode_akun'] . ' - ' . $akun['nama_akun']);
            $row++;
        }
        $pendapatanCount = count($pendapatanAccounts);

        // C: Beban (Untuk KK)
        $row = 1;
        foreach ($bebanAccounts as $akun) {
            $dataSheet->setCellValue('C' . $row, $akun['kode_akun'] . ' - ' . $akun['nama_akun']);
            $row++;
        }
        $bebanCount = count($bebanAccounts);
        
        // Create Named Ranges for Dependent Dropdown
        if ($pendapatanCount > 0) {
            $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('KM', $dataSheet, '$B$1:$B$' . $pendapatanCount));
        }
        if ($bebanCount > 0) {
            $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('KK', $dataSheet, '$C$1:$C$' . $bebanCount));
        }

        // Hide the data sheet
        $dataSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

        // 2. Setup the main template sheet
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setTitle('Template Import');
        
        $sheet->setCellValue('A1', 'TIPE (KM/KK)');
        $sheet->setCellValue('B1', 'TANGGAL (YYYY-MM-DD)');
        $sheet->setCellValue('C1', 'AKUN KAS/BANK');
        $sheet->setCellValue('D1', 'AKUN TUJUAN (PENDAPATAN/BEBAN)');
        $sheet->setCellValue('E1', 'NOMINAL');
        $sheet->setCellValue('F1', 'KETERANGAN');
        
        // Make headers bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(40);

        // Add Data Validation (Dropdowns) for rows 2 to 200
        for ($i = 2; $i <= 200; $i++) {
            // Tipe (KM / KK) Dropdown
            $valTipe = $sheet->getCell("A$i")->getDataValidation();
            $valTipe->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $valTipe->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $valTipe->setAllowBlank(true);
            $valTipe->setShowDropDown(true);
            $valTipe->setFormula1('"KM,KK"');

            // Akun Kas Dropdown
            if ($kasCount > 0) {
                $valKas = $sheet->getCell("C$i")->getDataValidation();
                $valKas->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $valKas->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $valKas->setAllowBlank(true);
                $valKas->setShowDropDown(true);
                $valKas->setFormula1('\'DataCOA\'!$A$1:$A$' . $kasCount);
            }

            // Akun Lawan Dropdown (Dependent on Column A using INDIRECT)
            $valLawan = $sheet->getCell("D$i")->getDataValidation();
            $valLawan->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $valLawan->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $valLawan->setAllowBlank(true);
            $valLawan->setShowDropDown(true);
            // Gunakan rumus INDIRECT sehingga refers ke range bernama KM atau KK
            $valLawan->setFormula1('=INDIRECT(A' . $i . ')');
        }

        // Dummy Data for guide
        $sheet->setCellValue('A2', 'KM');
        $sheet->setCellValue('B2', date('Y-m-d'));
        if ($kasCount > 0) $sheet->setCellValue('C2', $kasBankAccounts[0]['kode_akun'] . ' - ' . $kasBankAccounts[0]['nama_akun']);
        if ($pendapatanCount > 0) $sheet->setCellValue('D2', $pendapatanAccounts[0]['kode_akun'] . ' - ' . $pendapatanAccounts[0]['nama_akun']);
        $sheet->setCellValue('E2', '5000000');
        $sheet->setCellValue('F2', 'Contoh Kas Masuk (Hapus Baris Ini)');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Kas.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }

    public function exportExcel()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        $builder->select('jurnal.*, akun.nama_akun, akun.kategori');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->groupStart()
                ->like('jurnal.no_reff', 'KM-', 'after')
                ->orLike('jurnal.no_reff', 'KK-', 'after')
                ->groupEnd();
        $builder->where('jurnal.deleted_at', null);
        $builder->orderBy('jurnal.tanggal', 'DESC');
        $builder->orderBy('jurnal.id', 'DESC');
        $allJurnals = $builder->get()->getResultArray();

        $transactions = [];
        $grouped = [];
        foreach ($allJurnals as $j) {
            $grouped[$j['no_reff']][] = $j;
        }

        foreach ($grouped as $reff => $lines) {
            if (count($lines) < 2) continue;
            $tipe = strpos($reff, 'KM-') === 0 ? 'Kas Masuk' : 'Kas Keluar';
            $kasLine = null;
            $lawanLine = null;
            foreach ($lines as $line) {
                if ($line['kategori'] == 'kas & bank') {
                    $kasLine = $line;
                } else {
                    $lawanLine = $line;
                }
            }
            if (!$kasLine || !$lawanLine) {
                $kasLine = $lines[0];
                $lawanLine = $lines[1];
            }
            $nominal = max($kasLine['debit'], $kasLine['kredit']);
            $transactions[] = [
                'tanggal' => $kasLine['tanggal'],
                'tipe' => $tipe,
                'akun_kas' => $kasLine['nama_akun'],
                'akun_tujuan' => $lawanLine['nama_akun'],
                'nominal' => $nominal,
                'keterangan' => $kasLine['deskripsi']
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'TANGGAL');
        $sheet->setCellValue('B1', 'AKUN KAS/BANK');
        $sheet->setCellValue('C1', 'AKUN TUJUAN');
        $sheet->setCellValue('D1', 'NOMINAL');
        $sheet->setCellValue('E1', 'KETERANGAN');
        $sheet->setCellValue('F1', 'TIPE');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($transactions as $tx) {
            $sheet->setCellValue('A' . $row, $tx['tanggal']);
            $sheet->setCellValue('B' . $row, $tx['akun_kas']);
            $sheet->setCellValue('C' . $row, $tx['akun_tujuan']);
            $sheet->setCellValue('D' . $row, $tx['nominal']);
            $sheet->setCellValue('E' . $row, $tx['keterangan']);
            $sheet->setCellValue('F' . $row, $tx['tipe']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Export_Kas_Masuk_Keluar_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau belum dipilih.');
        }

        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();
        $db = \Config\Database::connect();

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header
            unset($rows[0]);

            $db->transStart();
            $successCount = 0;

            foreach ($rows as $index => $row) {
                // Check if row is empty
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4])) {
                    continue;
                }

                $tipe = strtoupper(trim($row[0])); // KM / KK
                
                // Parse date properly to YYYY-MM-DD
                $rawDate = trim($row[1]);
                if (is_numeric($rawDate)) {
                    $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDate)->format("Y-m-d");
                } else {
                    $cleanDate = str_replace(["/", "."], "-", $rawDate);
                    $timestamp = strtotime($cleanDate);
                    
                    if ($timestamp !== false && $timestamp > 0) {
                        $tanggal = date("Y-m-d", $timestamp);
                    } else {
                        // Coba urai manual jika format d-m-Y atau m-d-Y
                        $parts = explode("-", $cleanDate);
                        if (count($parts) === 3 && strlen($parts[2]) === 4) {
                            $ts1 = strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]); // Asumsikan d-m-Y
                            $ts2 = strtotime($parts[2] . '-' . $parts[0] . '-' . $parts[1]); // Asumsikan m-d-Y
                            
                            if ($ts1 !== false && $ts1 > 0) {
                                $tanggal = date("Y-m-d", $ts1);
                            } elseif ($ts2 !== false && $ts2 > 0) {
                                $tanggal = date("Y-m-d", $ts2);
                            } else {
                                $tanggal = $rawDate;
                            }
                        } else {
                            $tanggal = $rawDate;
                        }
                    }
                }

                // Extract kode_akun from "1101 - Kas Kecil" format
                $kodeKasFull = trim($row[2]);
                $kodeKasParts = explode(' - ', $kodeKasFull);
                $kodeKas = trim($kodeKasParts[0]);

                $kodeLawanFull = trim($row[3]);
                $kodeLawanParts = explode(' - ', $kodeLawanFull);
                $kodeLawan = trim($kodeLawanParts[0]);

                // Fix for decimal bug: get calculated value directly from the cell to avoid formatting issues
                $rowNum = $index + 1; // row array is 0-indexed, but Excel is 1-indexed
                $nominalRaw = $sheet->getCell('E' . $rowNum)->getCalculatedValue();
                $nominal = (float) $nominalRaw;
                
                $deskripsi = trim($row[5] ?? 'Imported Data');

                // Find Akun IDs
                $akunKas = $akunModel->where('kode_akun', $kodeKas)->first();
                $akunLawan = $akunModel->where('kode_akun', $kodeLawan)->first();

                if (!$akunKas || !$akunLawan) {
                    throw new \Exception("Kode Akun tidak ditemukan pada baris ke-" . ($index + 2));
                }

                $no_reff = ($tipe == 'KM' ? 'KM-' : 'KK-') . time() . rand(100, 999);

                if ($tipe == 'KM') {
                    $jurnalModel->insert(['tanggal' => $tanggal, 'no_reff' => $no_reff, 'deskripsi' => $deskripsi, 'akun_id' => $akunKas['id'], 'debit' => $nominal, 'kredit' => 0]);
                    $jurnalModel->insert(['tanggal' => $tanggal, 'no_reff' => $no_reff, 'deskripsi' => $deskripsi, 'akun_id' => $akunLawan['id'], 'debit' => 0, 'kredit' => $nominal]);
                } else {
                    $jurnalModel->insert(['tanggal' => $tanggal, 'no_reff' => $no_reff, 'deskripsi' => $deskripsi, 'akun_id' => $akunLawan['id'], 'debit' => $nominal, 'kredit' => 0]);
                    $jurnalModel->insert(['tanggal' => $tanggal, 'no_reff' => $no_reff, 'deskripsi' => $deskripsi, 'akun_id' => $akunKas['id'], 'debit' => 0, 'kredit' => $nominal]);
                }
                $successCount++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses file Excel.');
            }

            return redirect()->back()->with('success', "Berhasil mengimpor $successCount transaksi.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }

    public function deleteAll($type)
    {
        $jurnalModel = new \App\Models\JurnalModel();
        
        $prefix = $type === 'masuk' ? 'KM-' : 'KK-';
        
        try {
            $jurnalModel->like('no_reff', $prefix, 'after')->delete();
            return redirect()->back()->with('success', 'Semua data Kas ' . ucfirst($type) . ' berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function hapusDuplikat($type)
    {
        $prefix = $type == 'masuk' ? 'KM-' : 'KK-';
        
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        $builder->select('jurnal.*, akun.kategori, akun.nama_akun');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->like('jurnal.no_reff', $prefix, 'after');
        $builder->where('jurnal.deleted_at', null);
        $allJurnals = $builder->get()->getResultArray();

        $transactions = [];
        $groupedByReff = [];
        foreach ($allJurnals as $j) {
            $groupedByReff[$j['no_reff']][] = $j;
        }

        foreach ($groupedByReff as $reff => $lines) {
            if (count($lines) < 2) continue;
            
            $kasLine = null;
            $lawanLine = null;
            
            foreach ($lines as $line) {
                if ($line['kategori'] == 'kas & bank') {
                    $kasLine = $line;
                } else {
                    $lawanLine = $line;
                }
            }
            
            if (!$kasLine || !$lawanLine) {
                $kasLine = $lines[0];
                $lawanLine = $lines[1];
            }

            $nominal = max($kasLine['debit'], $kasLine['kredit']);
            
            $transactions[$reff] = [
                'akun_kas' => $kasLine['akun_id'],
                'akun_tujuan' => $lawanLine['akun_id'],
                'nominal' => $nominal,
                'keterangan' => $kasLine['deskripsi']
            ];
        }

        // Group by combination to find duplicates
        $duplicateGroups = [];
        foreach ($transactions as $reff => $t) {
            // Gabungkan akun kas, akun lawan, nominal, dan keterangan
            $key = $t['akun_kas'] . '_' . $t['akun_tujuan'] . '_' . $t['nominal'] . '_' . md5(strtolower(trim($t['keterangan'])));
            $duplicateGroups[$key][] = $reff;
        }

        $deletedReffs = 0;
        foreach ($duplicateGroups as $key => $reffs) {
            if (count($reffs) > 1) {
                // Keep the first one, delete the rest
                array_shift($reffs);
                
                foreach ($reffs as $delReff) {
                    $db->table('jurnal')->where('no_reff', $delReff)->delete();
                    $deletedReffs++;
                }
            }
        }

        $url = $type == 'masuk' ? 'keuangan/kas-masuk' : 'keuangan/kas-keluar';
        $label = $type == 'masuk' ? 'Kas Masuk' : 'Kas Keluar';
        return redirect()->to(base_url($url))->with('success', "Berhasil menghapus $deletedReffs transaksi $label yang duplikat (menyisakan 1 yang asli).");
    }

    public function duplikat($type)
    {
        $prefix = $type == 'masuk' ? 'KM-' : 'KK-';
        
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal');
        $builder->select('jurnal.*, akun.kategori, akun.nama_akun');
        $builder->join('akun', 'akun.id = jurnal.akun_id');
        $builder->like('jurnal.no_reff', $prefix, 'after');
        $builder->where('jurnal.deleted_at', null);
        $builder->orderBy('jurnal.tanggal', 'DESC');
        $allJurnals = $builder->get()->getResultArray();

        $transactions = [];
        $groupedByReff = [];
        foreach ($allJurnals as $j) {
            $groupedByReff[$j['no_reff']][] = $j;
        }

        foreach ($groupedByReff as $reff => $lines) {
            if (count($lines) < 2) continue;
            
            $kasLine = null;
            $lawanLine = null;
            
            foreach ($lines as $line) {
                if ($line['kategori'] == 'kas & bank') {
                    $kasLine = $line;
                } else {
                    $lawanLine = $line;
                }
            }
            
            if (!$kasLine || !$lawanLine) {
                $kasLine = $lines[0];
                $lawanLine = $lines[1];
            }

            $nominal = max($kasLine['debit'], $kasLine['kredit']);
            
            $transactions[$reff] = [
                'no_reff' => $reff,
                'tanggal' => $kasLine['tanggal'],
                'akun_kas' => $kasLine['nama_akun'],
                'akun_tujuan' => $lawanLine['nama_akun'],
                'akun_kas_id' => $kasLine['akun_id'],
                'akun_tujuan_id' => $lawanLine['akun_id'],
                'nominal' => $nominal,
                'keterangan' => $kasLine['deskripsi']
            ];
        }

        // Group by combination to find duplicates
        $duplicateGroups = [];
        foreach ($transactions as $reff => $t) {
            $key = $t['akun_kas_id'] . '_' . $t['akun_tujuan_id'] . '_' . $t['nominal'] . '_' . md5(strtolower(trim($t['keterangan'])));
            $duplicateGroups[$key][] = $t;
        }

        // Filter only groups that have > 1 items
        $duplicatesOnly = [];
        foreach ($duplicateGroups as $key => $items) {
            if (count($items) > 1) {
                $duplicatesOnly[$key] = $items;
            }
        }

        $data = [
            'title' => 'Kelola Duplikat ' . ($type == 'masuk' ? 'Kas Masuk' : 'Kas Keluar'),
            'type' => $type,
            'activeMenu' => $type == 'masuk' ? 'kas_masuk' : 'kas_keluar',
            'duplicateGroups' => $duplicatesOnly
        ];

        return view('keuangan/kas_masuk_keluar/duplikat', $data);
    }

    public function hapusDuplikatItem($type)
    {
        $reff = $this->request->getPost('no_reff');
        if ($reff) {
            $db = \Config\Database::connect();
            $db->table('jurnal')->where('no_reff', $reff)->delete();
            return redirect()->back()->with('success', 'Satu item duplikat berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus item duplikat.');
    }
}
