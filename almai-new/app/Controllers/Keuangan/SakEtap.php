<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\SakEtapModel;
use App\Libraries\SakEtapPdfGenerator;

class SakEtap extends BaseController
{
    protected SakEtapModel $sakEtapModel;

    public function __construct()
    {
        $this->sakEtapModel = new SakEtapModel();
    }

    /**
     * Dashboard SAK-ETAP - Pilih laporan dan periode
     */
    public function index()
    {
        $data = [
            'title' => 'SAK-ETAP - Laporan Keuangan',
            'activeMenu' => 'sak-etap',
            'tahunBuku' => date('Y'),
            'bulanBuku' => date('m')
        ];

        return view('keuangan/sak-etap/index', $data);
    }

    /**
     * Tampilkan Neraca (Balance Sheet)
     */
    public function neraca()
    {
        $tahunBuku = $this->request->getGet('tahun') ?? date('Y');
        $bulanBuku = $this->request->getGet('bulan') ?? date('m');

        // Tentukan periode berdasarkan pilihan
        $startDate = "{$tahunBuku}-01-01";
        $endDate = "{$tahunBuku}-12-31";

        if ($bulanBuku && $bulanBuku != 'all') {
            $startDate = "{$tahunBuku}-{$bulanBuku}-01";
            $endDate = "{$tahunBuku}-{$bulanBuku}-" . date('t', strtotime($startDate));
        }

        $neraca = $this->sakEtapModel->getNeraca($startDate, $endDate, $tahunBuku);

        $data = [
            'title' => 'Neraca (Balance Sheet)',
            'activeMenu' => 'sak-etap',
            'neraca' => $neraca,
            'tahunBuku' => $tahunBuku,
            'bulanBuku' => $bulanBuku,
            'format' => $this->request->getGet('format') ?? 'html'
        ];

        // Jika format PDF diminta
        if ($data['format'] === 'pdf') {
            return $this->generateNeracaPdf($neraca, $tahunBuku, $bulanBuku);
        }

        return view('keuangan/sak-etap/neraca', $data);
    }

    /**
     * Tampilkan Laporan Laba Rugi (Income Statement)
     */
    public function labaRugi()
    {
        $tahunBuku = $this->request->getGet('tahun') ?? date('Y');
        $bulanBuku = $this->request->getGet('bulan') ?? date('m');

        // Tentukan periode berdasarkan pilihan
        $startDate = "{$tahunBuku}-01-01";
        $endDate = "{$tahunBuku}-12-31";

        if ($bulanBuku && $bulanBuku != 'all') {
            $startDate = "{$tahunBuku}-{$bulanBuku}-01";
            $endDate = "{$tahunBuku}-{$bulanBuku}-" . date('t', strtotime($startDate));
        }

        $labaRugi = $this->sakEtapModel->getLapaRugi($startDate, $endDate, $tahunBuku);

        $data = [
            'title' => 'Laporan Laba Rugi (Income Statement)',
            'activeMenu' => 'sak-etap',
            'labaRugi' => $labaRugi,
            'tahunBuku' => $tahunBuku,
            'bulanBuku' => $bulanBuku,
            'format' => $this->request->getGet('format') ?? 'html'
        ];

        // Jika format PDF diminta
        if ($data['format'] === 'pdf') {
            return $this->generateLabaRugiPdf($labaRugi, $tahunBuku, $bulanBuku);
        }

        return view('keuangan/sak-etap/laba-rugi', $data);
    }

    /**
     * Tampilkan Laporan Arus Kas (Cash Flow Statement)
     */
    public function arusKas()
    {
        $tahunBuku = $this->request->getGet('tahun') ?? date('Y');
        $bulanBuku = $this->request->getGet('bulan') ?? date('m');

        // Tentukan periode berdasarkan pilihan
        $startDate = "{$tahunBuku}-01-01";
        $endDate = "{$tahunBuku}-12-31";

        if ($bulanBuku && $bulanBuku != 'all') {
            $startDate = "{$tahunBuku}-{$bulanBuku}-01";
            $endDate = "{$tahunBuku}-{$bulanBuku}-" . date('t', strtotime($startDate));
        }

        $arusKas = $this->sakEtapModel->getArusKas($startDate, $endDate, $tahunBuku);

        $data = [
            'title' => 'Laporan Arus Kas (Cash Flow Statement)',
            'activeMenu' => 'sak-etap',
            'arusKas' => $arusKas,
            'tahunBuku' => $tahunBuku,
            'bulanBuku' => $bulanBuku,
            'format' => $this->request->getGet('format') ?? 'html'
        ];

        // Jika format PDF diminta
        if ($data['format'] === 'pdf') {
            return $this->generateArusKasPdf($arusKas, $tahunBuku, $bulanBuku);
        }

        return view('keuangan/sak-etap/arus-kas', $data);
    }

    /**
     * Tampilkan Laporan Perubahan Ekuitas (Statement of Changes in Equity)
     */
    public function perubahanEkuitas()
    {
        $tahunBuku = $this->request->getGet('tahun') ?? date('Y');
        $bulanBuku = $this->request->getGet('bulan') ?? date('m');

        // Tentukan periode berdasarkan pilihan
        $startDate = "{$tahunBuku}-01-01";
        $endDate = "{$tahunBuku}-12-31";

        if ($bulanBuku && $bulanBuku != 'all') {
            $startDate = "{$tahunBuku}-{$bulanBuku}-01";
            $endDate = "{$tahunBuku}-{$bulanBuku}-" . date('t', strtotime($startDate));
        }

        $perubahanEkuitas = $this->sakEtapModel->getPerubahanEkuitas($startDate, $endDate, $tahunBuku);

        $data = [
            'title' => 'Laporan Perubahan Ekuitas (Statement of Changes in Equity)',
            'activeMenu' => 'sak-etap',
            'perubahanEkuitas' => $perubahanEkuitas,
            'tahunBuku' => $tahunBuku,
            'bulanBuku' => $bulanBuku,
            'format' => $this->request->getGet('format') ?? 'html'
        ];

        // Jika format PDF diminta
        if ($data['format'] === 'pdf') {
            return $this->generatePerubahanEkuitasPdf($perubahanEkuitas, $tahunBuku, $bulanBuku);
        }

        return view('keuangan/sak-etap/perubahan-ekuitas', $data);
    }

    /**
     * Generate Neraca PDF
     */
    private function generateNeracaPdf(array $neraca, string $tahunBuku, string $bulanBuku)
    {
        $pdfGenerator = new SakEtapPdfGenerator();
        return $pdfGenerator->generateNeracaPdf($neraca, $tahunBuku, $bulanBuku);
    }

    /**
     * Generate Laba Rugi PDF
     */
    private function generateLabaRugiPdf(array $labaRugi, string $tahunBuku, string $bulanBuku)
    {
        $pdfGenerator = new SakEtapPdfGenerator();
        return $pdfGenerator->generateLabaRugiPdf($labaRugi, $tahunBuku, $bulanBuku);
    }

    /**
     * Generate Arus Kas PDF
     */
    private function generateArusKasPdf(array $arusKas, string $tahunBuku, string $bulanBuku)
    {
        $pdfGenerator = new SakEtapPdfGenerator();
        return $pdfGenerator->generateArusKasPdf($arusKas, $tahunBuku, $bulanBuku);
    }

    /**
     * Generate Perubahan Ekuitas PDF
     */
    private function generatePerubahanEkuitasPdf(array $perubahanEkuitas, string $tahunBuku, string $bulanBuku)
    {
        $pdfGenerator = new SakEtapPdfGenerator();
        return $pdfGenerator->generatePerubahanEkuitasPdf($perubahanEkuitas, $tahunBuku, $bulanBuku);
    }

    /**
     * Generate Laporan Keuangan Lengkap (All Reports in One PDF)
     */
    public function laporanLengkap()
    {
        $tahunBuku = $this->request->getGet('tahun') ?? date('Y');
        $bulanBuku = $this->request->getGet('bulan') ?? date('m');

        // Tentukan periode berdasarkan pilihan
        $startDate = "{$tahunBuku}-01-01";
        $endDate = "{$tahunBuku}-12-31";

        if ($bulanBuku && $bulanBuku != 'all') {
            $startDate = "{$tahunBuku}-{$bulanBuku}-01";
            $endDate = "{$tahunBuku}-{$bulanBuku}-" . date('t', strtotime($startDate));
        }

        // Get all reports
        $neraca = $this->sakEtapModel->getNeraca($startDate, $endDate, $tahunBuku);
        $labaRugi = $this->sakEtapModel->getLapaRugi($startDate, $endDate, $tahunBuku);
        $arusKas = $this->sakEtapModel->getArusKas($startDate, $endDate, $tahunBuku);
        $perubahanEkuitas = $this->sakEtapModel->getPerubahanEkuitas($startDate, $endDate, $tahunBuku);

        $pdfGenerator = new SakEtapPdfGenerator();
        return $pdfGenerator->generateLaporanLengkapPdf($neraca, $labaRugi, $arusKas, $perubahanEkuitas, $tahunBuku, $bulanBuku);
    }

    /**
     * Export data ke Excel
     */
    public function exportExcel()
    {
        $tahunBuku = $this->request->getGet('tahun') ?? date('Y');
        $bulanBuku = $this->request->getGet('bulan') ?? date('m');
        $tipelaporan = $this->request->getGet('tipe') ?? 'neraca';

        // Tentukan periode berdasarkan pilihan
        $startDate = "{$tahunBuku}-01-01";
        $endDate = "{$tahunBuku}-12-31";

        if ($bulanBuku && $bulanBuku != 'all') {
            $startDate = "{$tahunBuku}-{$bulanBuku}-01";
            $endDate = "{$tahunBuku}-{$bulanBuku}-" . date('t', strtotime($startDate));
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        switch ($tipelaporan) {
            case 'neraca':
                $neraca = $this->sakEtapModel->getNeraca($startDate, $endDate, $tahunBuku);
                $this->populateNeracaSheet($sheet, $neraca);
                $filename = "Neraca_{$tahunBuku}.xlsx";
                break;

            case 'laba-rugi':
                $labaRugi = $this->sakEtapModel->getLapaRugi($startDate, $endDate, $tahunBuku);
                $this->populateLabaRugiSheet($sheet, $labaRugi);
                $filename = "Laba_Rugi_{$tahunBuku}.xlsx";
                break;

            case 'arus-kas':
                $arusKas = $this->sakEtapModel->getArusKas($startDate, $endDate, $tahunBuku);
                $this->populateArusKasSheet($sheet, $arusKas);
                $filename = "Arus_Kas_{$tahunBuku}.xlsx";
                break;

            case 'perubahan-ekuitas':
                $perubahanEkuitas = $this->sakEtapModel->getPerubahanEkuitas($startDate, $endDate, $tahunBuku);
                $this->populatePerubahanEkuitasSheet($sheet, $perubahanEkuitas);
                $filename = "Perubahan_Ekuitas_{$tahunBuku}.xlsx";
                break;

            default:
                return redirect()->back()->with('error', 'Tipe laporan tidak valid');
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        $writer->save('php://output');
        exit;
    }

    /**
     * Helper: Populate Neraca Sheet
     */
    private function populateNeracaSheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet &$sheet, array $neraca)
    {
        $row = 1;
        $sheet->setCellValue("A{$row}", "NERACA");
        $sheet->setCellValue("B{$row}", "Per " . date('d M Y', strtotime($neraca['periode']['end_date'])));
        $row += 2;

        // ASET
        $sheet->setCellValue("A{$row}", "ASET");
        $row++;

        $sheet->setCellValue("A{$row}", "Aset Lancar");
        $row++;
        foreach ($neraca['aset']['aset_lancar']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total Aset Lancar");
        $sheet->setCellValue("B{$row}", $neraca['aset']['aset_lancar']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "Aset Tidak Lancar");
        $row++;
        foreach ($neraca['aset']['aset_tidak_lancar']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total Aset Tidak Lancar");
        $sheet->setCellValue("B{$row}", $neraca['aset']['aset_tidak_lancar']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "TOTAL ASET");
        $sheet->setCellValue("B{$row}", $neraca['aset']['total_aset']);
        $row += 2;

        // LIABILITAS & EKUITAS
        $sheet->setCellValue("A{$row}", "LIABILITAS & EKUITAS");
        $row++;

        $sheet->setCellValue("A{$row}", "Liabilitas Jangka Pendek");
        $row++;
        foreach ($neraca['liabilitas']['liabilitas_jangka_pendek']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total Liabilitas Jangka Pendek");
        $sheet->setCellValue("B{$row}", $neraca['liabilitas']['liabilitas_jangka_pendek']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "Liabilitas Jangka Panjang");
        $row++;
        foreach ($neraca['liabilitas']['liabilitas_jangka_panjang']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total Liabilitas Jangka Panjang");
        $sheet->setCellValue("B{$row}", $neraca['liabilitas']['liabilitas_jangka_panjang']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "Total Liabilitas");
        $sheet->setCellValue("B{$row}", $neraca['liabilitas']['total_liabilitas']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "Ekuitas");
        $row++;
        $sheet->setCellValue("A{$row}", "Total Ekuitas");
        $sheet->setCellValue("B{$row}", $neraca['ekuitas']['total_ekuitas']);
        $row++;
        $sheet->setCellValue("A{$row}", "Saldo Laba Ditahan");
        $sheet->setCellValue("B{$row}", $neraca['ekuitas']['laba_rugi_tahun_lalu']);
        $row++;
        $sheet->setCellValue("A{$row}", "Laba/Rugi Tahun Berjalan");
        $sheet->setCellValue("B{$row}", $neraca['ekuitas']['laba_rugi_tahun_berjalan']);
        $row++;
        $sheet->setCellValue("A{$row}", "Total Ekuitas");
        $sheet->setCellValue("B{$row}", $neraca['ekuitas']['total_ekuitas_with_profit']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "TOTAL LIABILITAS & EKUITAS");
        $sheet->setCellValue("B{$row}", $neraca['total_liabilitas_ekuitas']);
    }

    /**
     * Helper: Populate Laba Rugi Sheet
     */
    private function populateLabaRugiSheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet &$sheet, array $labaRugi)
    {
        $row = 1;
        $sheet->setCellValue("A{$row}", "LAPORAN LABA RUGI");
        $sheet->setCellValue("B{$row}", "Untuk Tahun yang Berakhir " . date('d M Y', strtotime($labaRugi['periode']['end_date'])));
        $row += 2;

        $sheet->setCellValue("A{$row}", "PENDAPATAN");
        $row++;
        foreach ($labaRugi['pendapatan']['pendapatan_usaha']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total Pendapatan Usaha");
        $sheet->setCellValue("B{$row}", $labaRugi['pendapatan']['pendapatan_usaha']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "BEBAN POKOK PENJUALAN");
        $row++;
        foreach ($labaRugi['hpp']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total HPP");
        $sheet->setCellValue("B{$row}", $labaRugi['hpp']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "LABA KOTOR");
        $sheet->setCellValue("B{$row}", $labaRugi['laba_kotor']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "BEBAN OPERASIONAL");
        $row++;
        foreach ($labaRugi['beban']['beban_operasional']['detail'] as $akun) {
            $sheet->setCellValue("A{$row}", $akun['nama_akun']);
            $sheet->setCellValue("B{$row}", $akun['saldo']);
            $row++;
        }
        $sheet->setCellValue("A{$row}", "Total Beban Operasional");
        $sheet->setCellValue("B{$row}", $labaRugi['beban']['beban_operasional']['total']);
        $row += 2;

        $sheet->setCellValue("A{$row}", "LABA/RUGI TAHUN BERJALAN");
        $sheet->setCellValue("B{$row}", $labaRugi['laba_rugi_tahun_berjalan']);
    }

    /**
     * Helper: Populate Arus Kas Sheet
     */
    private function populateArusKasSheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet &$sheet, array $arusKas)
    {
        // 1. Header Laporan (Mirip Gambar)
        $sheet->setCellValue("A1", "PT ALMA INDONESIA RAYA");
        $sheet->setCellValue("A2", "LAPORAN ARUS KAS");
        $sheet->setCellValue("A3", "Per " . date('d M Y', strtotime($arusKas['periode']['end_date'])));
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
        $sheet->setCellValue("C{$row}", date('M Y', strtotime($arusKas['periode']['end_date']))); 
        $sheet->setCellValue("D{$row}", date('M Y', strtotime($arusKas['periode']['start_date'])));
        
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        $row++;

        // Helper untuk set row dengan border
        $setRowData = function($row, $colA, $colB, $colC, $colD, $isBold = false) use (&$sheet) {
            $sheet->setCellValue("A{$row}", $colA);
            $sheet->setCellValue("B{$row}", $colB);
            
            // Format numbers correctly to avoid issues if string is passed
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
        $setRowData($row++, "Laba/Rugi Tahun Berjalan", "", $arusKas['aktivitas_operasi']['laba_rugi_tahun_berjalan'], 0);
        $setRowData($row++, "Penyusutan & Amortisasi", "", $arusKas['aktivitas_operasi']['penyusutan_amortisasi']['total'], 0);
        $setRowData($row++, "Jumlah arus kas dari aktivitas operasi", "", $arusKas['aktivitas_operasi']['arus_kas_operasi'], 0, true);
        
        // ARUS KAS DARI AKTIVITAS INVESTASI
        $setRowData($row++, "ARUS KAS DARI AKTIVITAS INVESTASI", "", "", "", true);
        $setRowData($row++, "Arus Kas dari Aktivitas Investasi", "", $arusKas['aktivitas_investasi']['arus_kas_investasi'], 0);
        $setRowData($row++, "Jumlah arus dari aktivitas investasi", "", $arusKas['aktivitas_investasi']['arus_kas_investasi'], 0, true);

        // ARUS KAS DARI AKTIVITAS PENDANAAN
        $setRowData($row++, "ARUS KAS DARI AKTIVITAS PENDANAAN", "", "", "", true);
        $setRowData($row++, "Arus Kas dari Aktivitas Pendanaan", "", $arusKas['aktivitas_pendanaan']['arus_kas_pendanaan'], 0);
        $setRowData($row++, "Jumlah arus kas dari aktivitas pendanaan", "", $arusKas['aktivitas_pendanaan']['arus_kas_pendanaan'], 0, true);

        // KAS DAN SETARA KAS
        $setRowData($row++, "Kenaikan (penurunan) arus kas neto", "", $arusKas['perubahan_kas_bersih'], 0, true);
        $setRowData($row++, "Saldo kas dan setara kas awal periode", "", $arusKas['kas_awal_periode'], 0, true);
        $setRowData($row++, "Saldo kas dan setara kas akhir periode", "", $arusKas['kas_akhir_periode'], 0, true);

        // Adjust column widths to avoid ####
        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
    }

    /**
     * Helper: Populate Perubahan Ekuitas Sheet
     */
    private function populatePerubahanEkuitasSheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet &$sheet, array $perubahanEkuitas)
    {
        $row = 1;
        $sheet->setCellValue("A{$row}", "LAPORAN PERUBAHAN EKUITAS");
        $sheet->setCellValue("B{$row}", "Untuk Tahun yang Berakhir " . date('d M Y', strtotime($perubahanEkuitas['periode']['end_date'])));
        $row += 2;

        $sheet->setCellValue("A{$row}", "Ekuitas Awal Periode");
        $sheet->setCellValue("B{$row}", $perubahanEkuitas['ekuitas_awal_periode']);
        $row++;
        $sheet->setCellValue("A{$row}", "Laba/Rugi Tahun Berjalan");
        $sheet->setCellValue("B{$row}", $perubahanEkuitas['laba_rugi_tahun_berjalan']);
        $row++;
        $sheet->setCellValue("A{$row}", "Tambahan Modal");
        $sheet->setCellValue("B{$row}", $perubahanEkuitas['tambahan_modal']);
        $row++;
        $sheet->setCellValue("A{$row}", "Dividen/Prive");
        $sheet->setCellValue("B{$row}", $perubahanEkuitas['dividen']);
        $row++;
        $sheet->setCellValue("A{$row}", "Ekuitas Akhir Periode");
        $sheet->setCellValue("B{$row}", $perubahanEkuitas['ekuitas_akhir_periode']);
    }
}
