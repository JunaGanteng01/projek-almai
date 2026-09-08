<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanBankIndonesia extends BaseController
{
    public function index()
    {
        $wpaModel = new WpaModel();
        
        $wpaList = $wpaModel->where('no_sertifikat_bi !=', '')
                            ->where('no_sertifikat_bi IS NOT NULL')
                            ->findAll();

        $data = [
            'title' => 'Laporan Bank Indonesia - Almai',
            'activeMenu' => 'laporan-bank-indonesia',
            'wpaList' => $wpaList
        ];

        return view('laporan-kegiatan/laporan_bank_indonesia/index', $data);
    }

    public function exportPdf()
    {
        $wpaModel = new WpaModel();
        
        $wpaList = $wpaModel->where('no_sertifikat_bi !=', '')
                            ->where('no_sertifikat_bi IS NOT NULL')
                            ->findAll();

        $data = [
            'title' => 'Laporan Bank Indonesia',
            'wpaList' => $wpaList
        ];

        $html = view('laporan-kegiatan/laporan_bank_indonesia/pdf', $data);
        $filename = 'Laporan_Bank_Indonesia.pdf';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream($filename, ["Attachment" => true]);
    }

    public function exportExcel()
    {
        $wpaModel = new WpaModel();
        
        $wpaList = $wpaModel->where('no_sertifikat_bi !=', '')
                            ->where('no_sertifikat_bi IS NOT NULL')
                            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Laporan Bank Indonesia');

        // Title Row
        $sheet->setCellValue('A1', 'Laporan Wakil Penasihat Derivatif PUVA dan Kepemilikan sertifikat kompetensi');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        // Header
        $headers = [
            'A' => 'No',
            'B' => 'Nama Lengkap',
            'C' => 'Jabatan',
            'D' => 'Tanggal Menjabat',
            'E' => 'Nomor WPA Bank Indonesia',
            'F' => 'Nomor Sertifikat',
            'G' => 'Tanggal Kadaluarsa Sertifikat',
            'H' => 'Penyelenggara Sertifikasi',
            'I' => 'Nomor Anggota Asosiasi'
        ];
        
        foreach ($headers as $col => $title) {
            $sheet->setCellValue($col . '2', $title);
        }

        // Header styles
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A2:I2')->applyFromArray($headerStyle);

        // Data
        $row = 3;
        $no = 1;
        
        if (empty($wpaList)) {
            $sheet->setCellValue('A' . $row, 'Belum ada data laporan Bank Indonesia');
            $sheet->mergeCells('A' . $row . ':I' . $row);
            $sheet->getStyle('A' . $row . ':I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row . ':I' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        } else {
            foreach ($wpaList as $wpa) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $wpa['name'] ?? '-');
                $sheet->setCellValue('C' . $row, $wpa['jabatan'] ?? '-');
                
                $tanggalMenjabat = (!empty($wpa['tanggal_menjabat']) && $wpa['tanggal_menjabat'] !== '0000-00-00') ? date('d-m-Y', strtotime($wpa['tanggal_menjabat'])) : '-';
                $sheet->setCellValue('D' . $row, $tanggalMenjabat);
                
                $sheet->setCellValueExplicit('E' . $row, $wpa['no_sertifikat_bi'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $row, $wpa['nomor_izin_wpa'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                
                $masaBerlaku = (!empty($wpa['masa_berlaku']) && $wpa['masa_berlaku'] !== '0000-00-00') ? date('d-m-Y', strtotime($wpa['masa_berlaku'])) : '-';
                $sheet->setCellValue('G' . $row, $masaBerlaku);
                
                $sheet->setCellValue('H' . $row, 'Bappebti / Aspebtindo');
                $sheet->setCellValueExplicit('I' . $row, $wpa['no_sertifikat_bnsp'] ?? $wpa['no_sertifikat_aspebtindo'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                // Add borders to the row
                $sheet->getStyle('A' . $row . ':I' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Align columns A, C, D, E, F, G, H, I to center
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $row . ':I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row++;
            }
        }

        // Auto size columns
        foreach(range('A','I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $quarter = ceil(date('n') / 3);
        $filename = 'Sandi Pelapor_wpa3_' . date('Ymd') . '_Q' . $quarter . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
