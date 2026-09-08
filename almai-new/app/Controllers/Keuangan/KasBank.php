<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\AkunModel;
use App\Models\JurnalModel;

class KasBank extends BaseController
{
    public function index()
    {
        $akunModel = new AkunModel();
        $jurnalModel = new JurnalModel();

        // Get all accounts in 'kas & bank' category
        $accounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();

        // Calculate current balance for each account
        foreach ($accounts as &$account) {
            $balance = $jurnalModel->selectSum('debit')
                ->selectSum('kredit')
                ->where('akun_id', $account['id'])
                ->first();
            
            $account['balance'] = ($balance['debit'] ?? 0) - ($balance['kredit'] ?? 0);
        }

        $ekuitasAccounts = $akunModel->where('kategori', 'ekuitas')->orderBy('kode_akun', 'ASC')->findAll();

        $data = [
            'title' => 'Kas & Bank',
            'activeMenu' => 'kas-bank',
            'accounts' => $accounts,
            'ekuitasAccounts' => $ekuitasAccounts,
            'totalBalance' => array_sum(array_column($accounts, 'balance'))
        ];

        return view('keuangan/kas-bank/index', $data);
    }

    public function setSaldoAwal()
    {
        $jurnalModel = new JurnalModel();
        $db = \Config\Database::connect();
        
        $kasId = $this->request->getPost('akun_kas_id');
        $ekuitasId = $this->request->getPost('akun_ekuitas_id');
        $amount = preg_replace('/[^0-9]/', '', $this->request->getPost('amount') ?? '0');
        $date = $this->request->getPost('tanggal') ?? date('Y-m-d');
        
        if (!$kasId || !$ekuitasId || $amount <= 0) {
            return redirect()->back()->with('error', 'Semua form wajib diisi dengan nominal lebih dari 0.');
        }

        $db->transStart();
        $noReff = 'SA-' . time();
        $note = 'Saldo Awal Kas & Bank';

        // Debit the Kas account
        $jurnalModel->insert([
            'tanggal' => $date,
            'no_reff' => $noReff,
            'deskripsi' => $note,
            'akun_id' => $kasId,
            'debit' => $amount,
            'kredit' => 0
        ]);

        // Credit the Ekuitas account
        $jurnalModel->insert([
            'tanggal' => $date,
            'no_reff' => $noReff,
            'deskripsi' => $note,
            'akun_id' => $ekuitasId,
            'debit' => 0,
            'kredit' => $amount
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal mengatur saldo awal.');
        }

        return redirect()->back()->with('success', 'Saldo awal berhasil ditambahkan.');
    }

    public function transfer()
    {
        $jurnalModel = new JurnalModel();
        $db = \Config\Database::connect();
        
        $fromId = $this->request->getPost('from_account_id');
        $toId = $this->request->getPost('to_account_id');
        $amount = $this->request->getPost('amount');
        $date = $this->request->getPost('tanggal') ?? date('Y-m-d');
        $note = $this->request->getPost('catatan') ?? 'Transfer antar kas/bank';

        if ($fromId == $toId) {
            return redirect()->back()->with('error', 'Akun asal dan tujuan tidak boleh sama');
        }

        $db->transStart();

        // Credit the from account
        $jurnalModel->insert([
            'tanggal' => $date,
            'no_reff' => 'TRF-' . time(),
            'deskripsi' => $note,
            'akun_id' => $fromId,
            'debit' => 0,
            'kredit' => $amount
        ]);

        // Debit the to account
        $jurnalModel->insert([
            'tanggal' => $date,
            'no_reff' => 'TRF-' . time(),
            'deskripsi' => $note,
            'akun_id' => $toId,
            'debit' => $amount,
            'kredit' => 0
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses transfer');
        }

        return redirect()->to('/keuangan/kas-bank')->with('success', 'Transfer berhasil dilakukan');
    }

    public function detailTransfer($no_reff)
    {
        $jurnalModel = new JurnalModel();
        // A transfer has exactly two entries with the same no_reff
        $entries = $jurnalModel->select('jurnal.*, akun.nama_akun')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('no_reff', $no_reff)
            ->findAll();

        if (count($entries) != 2) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid transfer record']);
        }

        $from = null;
        $to = null;
        foreach ($entries as $entry) {
            if ($entry['kredit'] > 0) {
                $from = $entry;
            } else if ($entry['debit'] > 0) {
                $to = $entry;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'no_reff' => $no_reff,
                'tanggal' => $entries[0]['tanggal'],
                'deskripsi' => $entries[0]['deskripsi'],
                'amount' => $from['kredit'],
                'from_account_id' => $from['akun_id'],
                'from_account_name' => $from['nama_akun'],
                'to_account_id' => $to['akun_id'],
                'to_account_name' => $to['nama_akun'],
            ]
        ]);
    }

    public function updateTransfer()
    {
        $jurnalModel = new JurnalModel();
        $db = \Config\Database::connect();
        
        $noReff = $this->request->getPost('no_reff');
        $fromId = $this->request->getPost('from_account_id');
        $toId = $this->request->getPost('to_account_id');
        $amount = $this->request->getPost('amount');
        $date = $this->request->getPost('tanggal');
        $note = $this->request->getPost('catatan');

        if ($fromId == $toId) {
            return redirect()->back()->with('error', 'Akun asal dan tujuan tidak boleh sama');
        }

        $db->transStart();

        // Delete old entries
        $jurnalModel->where('no_reff', $noReff)->delete();

        // Credit the from account
        $jurnalModel->insert([
            'tanggal' => $date,
            'no_reff' => $noReff,
            'deskripsi' => $note,
            'akun_id' => $fromId,
            'debit' => 0,
            'kredit' => $amount
        ]);

        // Debit the to account
        $jurnalModel->insert([
            'tanggal' => $date,
            'no_reff' => $noReff,
            'deskripsi' => $note,
            'akun_id' => $toId,
            'debit' => $amount,
            'kredit' => 0
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses update transfer');
        }

        return redirect()->back()->with('success', 'Transfer berhasil diupdate');
    }


    public function import()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        $extension = $file->getClientExtension();
        if (!in_array($extension, ['xls', 'xlsx', 'csv'])) {
            return redirect()->back()->with('error', 'Format file harus xls, xlsx, atau csv');
        }

        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            if ($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();

            $db = \Config\Database::connect();
            $jurnalModel = new JurnalModel();
            $akunModel = new AkunModel();

            $db->transStart();
            $count = 0;

            for ($row = 2; $row <= $highestRow; $row++) {
                $tanggal = $sheet->getCell('A' . $row)->getFormattedValue();
                // Extract kode_akun from "1110 - Kas Kecil" format
                $kodeKasFull = trim($sheet->getCell('B' . $row)->getValue());
                $kodeEkuitasFull = trim($sheet->getCell('C' . $row)->getValue());
                
                $kodeKasParts = explode(' - ', $kodeKasFull);
                $kodeKas = trim($kodeKasParts[0]);

                $kodeEkuitasParts = explode(' - ', $kodeEkuitasFull);
                $kodeEkuitas = trim($kodeEkuitasParts[0]);

                // Get Calculated Value for Nominal
                $nominalRaw = $sheet->getCell('D' . $row)->getCalculatedValue();
                $nominal = (float) $nominalRaw;
                
                $deskripsi = $sheet->getCell('E' . $row)->getValue();

                if (empty($tanggal) || empty($kodeKas) || empty($kodeEkuitas) || empty($nominal)) {
                    continue; // Skip incomplete rows
                }

                // Format tanggal if it's not standard
                $cleanDate = str_replace(['/', '.'], '-', $tanggal);
                $timestamp = strtotime($cleanDate);
                
                if ($timestamp !== false && $timestamp > 0) {
                    $tanggalDb = date('Y-m-d', $timestamp);
                } else {
                    $parts = explode("-", $cleanDate);
                    if (count($parts) === 3 && strlen($parts[2]) === 4) {
                        $ts1 = strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]); // d-m-Y
                        $ts2 = strtotime($parts[2] . '-' . $parts[0] . '-' . $parts[1]); // m-d-Y
                        
                        if ($ts1 !== false && $ts1 > 0) {
                            $tanggalDb = date('Y-m-d', $ts1);
                        } elseif ($ts2 !== false && $ts2 > 0) {
                            $tanggalDb = date('Y-m-d', $ts2);
                        } else {
                            $tanggalDb = $tanggal;
                        }
                    } else {
                        $tanggalDb = $tanggal;
                    }
                }

                $akunKas = $akunModel->where('kode_akun', $kodeKas)->first();
                $akunEkuitas = $akunModel->where('kode_akun', $kodeEkuitas)->first();

                if (!$akunKas || !$akunEkuitas) {
                    continue; // Skip if accounts not found
                }

                $noReff = 'SA-IMP-' . time() . '-' . $count;

                // Debit Kas
                $jurnalModel->insert([
                    'tanggal' => $tanggalDb,
                    'no_reff' => $noReff,
                    'deskripsi' => $deskripsi ?? 'Import Saldo Awal',
                    'akun_id' => $akunKas['id'],
                    'debit' => $nominal,
                    'kredit' => 0
                ]);

                // Credit Ekuitas
                $jurnalModel->insert([
                    'tanggal' => $tanggalDb,
                    'no_reff' => $noReff,
                    'deskripsi' => $deskripsi ?? 'Import Saldo Awal',
                    'akun_id' => $akunEkuitas['id'],
                    'debit' => 0,
                    'kredit' => $nominal
                ]);

                $count++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal mengimpor data. Pastikan format sesuai.');
            }

            return redirect()->back()->with('success', "$count data transfer berhasil diimpor.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $akunModel = new AkunModel();
        $kasBankAccounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();
        $ekuitasAccounts = $akunModel->where('kategori', 'ekuitas')->orderBy('kode_akun', 'ASC')->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
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
        
        // B: Ekuitas
        $row = 1;
        foreach ($ekuitasAccounts as $akun) {
            $dataSheet->setCellValue('B' . $row, $akun['kode_akun'] . ' - ' . $akun['nama_akun']);
            $row++;
        }
        $ekuitasCount = count($ekuitasAccounts);

        // Hide the data sheet
        $dataSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

        // 2. Setup the main template sheet
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setTitle('Template Import Saldo');

        // Set Header
        $sheet->setCellValue('A1', 'Tanggal (YYYY-MM-DD)');
        $sheet->setCellValue('B1', 'Kode Akun Kas/Bank (Debit)');
        $sheet->setCellValue('C1', 'Kode Akun Modal/Ekuitas (Kredit)');
        $sheet->setCellValue('D1', 'Nominal');
        $sheet->setCellValue('E1', 'Deskripsi');

        // Make headers bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(45);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(40);

        // Add Data Validation (Dropdowns) for rows 2 to 200
        for ($i = 2; $i <= 200; $i++) {
            // Akun Kas Dropdown
            if ($kasCount > 0) {
                $valKas = $sheet->getCell("B$i")->getDataValidation();
                $valKas->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $valKas->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $valKas->setAllowBlank(true);
                $valKas->setShowDropDown(true);
                $valKas->setFormula1('\'DataCOA\'!$A$1:$A$' . $kasCount);
            }

            // Akun Ekuitas Dropdown
            if ($ekuitasCount > 0) {
                $valEkuitas = $sheet->getCell("C$i")->getDataValidation();
                $valEkuitas->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $valEkuitas->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $valEkuitas->setAllowBlank(true);
                $valEkuitas->setShowDropDown(true);
                $valEkuitas->setFormula1('\'DataCOA\'!$B$1:$B$' . $ekuitasCount);
            }
        }

        // Sample Data
        $sheet->setCellValue('A2', date('Y-m-d'));
        if ($kasCount > 0) $sheet->setCellValue('B2', $kasBankAccounts[0]['kode_akun'] . ' - ' . $kasBankAccounts[0]['nama_akun']);
        if ($ekuitasCount > 0) $sheet->setCellValue('C2', $ekuitasAccounts[0]['kode_akun'] . ' - ' . $ekuitasAccounts[0]['nama_akun']);
        $sheet->setCellValue('D2', '15000000');
        $sheet->setCellValue('E2', 'Saldo Awal (Hapus baris ini)');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Template_Import_Saldo_Awal.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }

    public function mutasi(string $id)
    {
        $akunModel = new AkunModel();
        $jurnalModel = new JurnalModel();

        $account = $akunModel->find($id);
        if (!$account) {
            return redirect()->to('/keuangan/kas-bank')->with('error', 'Akun tidak ditemukan');
        }

        $mutasi = $jurnalModel->where('akun_id', $id)
            ->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Mutasi Akun: ' . $account['nama_akun'],
            'activeMenu' => 'kas-bank',
            'account' => $account,
            'mutasi' => $mutasi
        ];

        return view('keuangan/kas-bank/mutasi', $data);
    }

    public function deleteMutasi($no_reff)
    {
        $jurnalModel = new JurnalModel();
        
        try {
            // This will delete all journal entries related to the transaction (both debit and credit legs)
            $jurnalModel->where('no_reff', $no_reff)->delete();
            return redirect()->back()->with('success', 'Transaksi mutasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus transaksi mutasi: ' . $e->getMessage());
        }
    }

    public function resetData()
    {
        $jurnalModel = new JurnalModel();
        $akunModel = new AkunModel();

        // Cari semua akun Kas & Bank
        $kasAccounts = $akunModel->where('kategori', 'kas & bank')->findAll();
        $kasIds = array_column($kasAccounts, 'id');

        if (empty($kasIds)) {
            return redirect()->back()->with('error', 'Tidak ada akun Kas & Bank ditemukan.');
        }

        // Cari semua jurnal yang melibatkan Kas & Bank
        $jurnalKas = $jurnalModel->whereIn('akun_id', $kasIds)->findAll();
        if (empty($jurnalKas)) {
            return redirect()->back()->with('success', 'Tidak ada data transaksi yang perlu dihapus.');
        }

        $noReffs = array_unique(array_column($jurnalKas, 'no_reff'));

        try {
            // Hapus semua jurnal yang memiliki no_reff tersebut (agar balance debit/kredit terhapus semua)
            $jurnalModel->whereIn('no_reff', $noReffs)->delete();
            return redirect()->back()->with('success', 'Semua data transaksi Kas & Bank berhasil dihapus. Sistem bersih dan siap untuk Setup Saldo Awal.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

}
