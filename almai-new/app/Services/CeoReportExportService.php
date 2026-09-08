<?php

namespace App\Services;

use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CeoReportExportService
{
    private const GREEN = '33E818';
    private const DARK = '111827';
    private const DARKER = '090D14';
    private const MUTED = '64748B';
    private const LIGHT = 'F8FAFC';
    private const BORDER = 'DCE3EA';
    private const CURRENCY_FORMAT = '"Rp" #,##0;[Red]("Rp" #,##0);-';
    private const INTEGER_FORMAT = '#,##0;[Red](#,##0);-';

    public function createSpreadsheet(array $data): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('ALMAI CEO Executive Suite')
            ->setLastModifiedBy('ALMAI CEO Executive Suite')
            ->setTitle('Laporan Eksekutif ALMAI')
            ->setSubject('Ringkasan kinerja eksekutif')
            ->setDescription('Laporan yang dihasilkan dari data operasional ALMAI pada periode terpilih.');

        $summary = $spreadsheet->getActiveSheet();
        $summary->setTitle('Ringkasan');
        $this->buildSummarySheet($summary, $data);
        $this->buildTrendSheet($spreadsheet->createSheet(), $data);
        $this->buildTransactionsSheet($spreadsheet->createSheet(), $data);
        $this->buildApprovalsSheet($spreadsheet->createSheet(), $data);
        $this->buildGoalsSheet($spreadsheet->createSheet(), $data);
        $this->buildAlertsSheet($spreadsheet->createSheet(), $data);

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }

    public function toBinary(array $data): string
    {
        $spreadsheet = $this->createSpreadsheet($data);
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);

        ob_start();
        $writer->save('php://output');
        $binary = (string) ob_get_clean();
        $spreadsheet->disconnectWorksheets();

        return $binary;
    }

    private function buildSummarySheet(Worksheet $sheet, array $data): void
    {
        $summary = $data['summary'];
        $range = $data['range'];

        $this->prepareSheet($sheet, 'A1:H28');
        $sheet->mergeCells('A1:H2');
        $sheet->setCellValue('A1', 'LAPORAN EKSEKUTIF ALMAI');
        $sheet->getStyle('A1:H2')->applyFromArray($this->titleStyle());
        $sheet->getStyle('A1')->getFont()->setSize(20);

        $metadata = [
            ['Periode', (string) $range['label']],
            ['Rentang data', (string) $range['start'] . ' s.d. ' . (string) $range['end']],
            ['Dibuat pada', $this->displayTimestamp((string) $data['generated_at'])],
            ['Sumber', 'Database operasional ALMAI'],
        ];
        $sheet->fromArray($metadata, null, 'A4');
        $sheet->getStyle('A4:A7')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::MUTED));
        $sheet->getStyle('A4:B7')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_HAIR)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::BORDER));

        $sheet->mergeCells('A9:H9');
        $sheet->setCellValue('A9', 'EXECUTIVE PULSE');
        $sheet->getStyle('A9:H9')->applyFromArray($this->sectionStyle());

        $kpis = [
            ['Pendapatan confirmed (non-poin)', (float) $summary['revenue'], 'IDR', $this->growthLabel($summary['revenue_growth'])],
            ['Saldo kas & bank', $summary['cash_balance'], 'IDR', 'Saldo jurnal terkini'],
            ['Transaksi confirmed', (int) $summary['transactions_confirmed'], 'Transaksi', (int) $summary['transactions_total'] . ' transaksi total'],
            ['Pengguna baru', (int) $summary['new_users'], 'Pengguna', $this->growthLabel($summary['user_growth'])],
            ['Conversion rate', $summary['conversion_rate'] === null ? null : ((float) $summary['conversion_rate'] / 100), '%', 'Confirmed / pengguna baru'],
            ['Approval pending', (int) $summary['approvals']['pending'], 'Item', $this->money((float) $summary['approvals']['amount'])],
            ['Withdrawal pending', (int) $summary['withdrawals']['pending'], 'Item', $this->money((float) $summary['withdrawals']['amount'])],
            ['Invoice overdue', (int) $summary['invoices']['overdue'], 'Invoice', $this->money((float) $summary['invoices']['amount']) . ' invoice terbuka'],
            ['CRM SLA breach', (int) $summary['crm']['sla_breach'], 'Percakapan', (int) $summary['crm']['open'] . ' percakapan aktif'],
            ['Tagihan operasional', (float) $summary['bills']['amount'], 'IDR', (int) $summary['bills']['overdue'] . ' overdue'],
            ['Sasaran off-track', (int) $summary['goals']['off_track'], 'Sasaran', (int) $summary['goals']['active'] . ' sasaran aktif'],
            ['Event mendatang', (int) $summary['events']['upcoming'], 'Event', $summary['events']['occupancy'] === null ? 'Okupansi belum tersedia' : number_format((float) $summary['events']['occupancy'], 1) . '% okupansi'],
        ];

        $sheet->fromArray([['KPI', 'Nilai', 'Unit', 'Konteks']], null, 'A11');
        $sheet->fromArray($kpis, null, 'A12');
        $lastRow = 11 + count($kpis);
        $this->styleTable($sheet, 'A11:D' . $lastRow);
        $sheet->getStyle('B12:B13')->getNumberFormat()->setFormatCode(self::CURRENCY_FORMAT);
        $sheet->getStyle('B14:B15')->getNumberFormat()->setFormatCode(self::INTEGER_FORMAT);
        $sheet->getStyle('B16')->getNumberFormat()->setFormatCode('0.0%');
        $sheet->getStyle('B17:B20')->getNumberFormat()->setFormatCode(self::INTEGER_FORMAT);
        $sheet->getStyle('B21')->getNumberFormat()->setFormatCode(self::CURRENCY_FORMAT);
        $sheet->getStyle('B22:B23')->getNumberFormat()->setFormatCode(self::INTEGER_FORMAT);

        $sheet->mergeCells('F11:H11');
        $sheet->setCellValue('F11', 'STATUS RISIKO');
        $sheet->getStyle('F11:H11')->applyFromArray($this->tableHeaderStyle());
        $riskRows = [
            ['Approval overdue', (int) $summary['approvals']['overdue']],
            ['Approval urgent', (int) $summary['approvals']['urgent']],
            ['Invoice overdue', (int) $summary['invoices']['overdue']],
            ['Tagihan overdue', (int) $summary['bills']['overdue']],
            ['SLA CRM breach', (int) $summary['crm']['sla_breach']],
            ['Goal off-track', (int) $summary['goals']['off_track']],
        ];
        $sheet->fromArray($riskRows, null, 'F12');
        $sheet->getStyle('F12:G17')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_HAIR)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::BORDER));
        $sheet->getStyle('G12:G17')->getNumberFormat()->setFormatCode(self::INTEGER_FORMAT);
        $sheet->getStyle('G12:G17')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B91C1C'));

        $sheet->setCellValue('A26', 'Catatan definisi');
        $sheet->getStyle('A26')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::MUTED));
        $definitions = array_values($data['definitions'] ?? []);
        $sheet->mergeCells('A27:H28');
        $sheet->setCellValueExplicit('A27', implode("\n", array_map(static fn(string $text, int $index): string => ($index + 1) . '. ' . $text, $definitions, array_keys($definitions))), DataType::TYPE_STRING);
        $sheet->getStyle('A27:H28')->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle('A27:H28')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');

        $sheet->getColumnDimension('A')->setWidth(34);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(31);
        $sheet->getColumnDimension('E')->setWidth(3);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(3);
        $sheet->freezePane('A11');
    }

    private function buildTrendSheet(Worksheet $sheet, array $data): void
    {
        $sheet->setTitle('Tren Pendapatan');
        $this->prepareSheet($sheet, 'A1:L22');
        $this->writeSheetTitle($sheet, 'TREN PENDAPATAN', 'Pendapatan confirmed non-poin pada periode terpilih');
        $sheet->fromArray([['Tanggal', 'Pendapatan (IDR)', 'Transaksi', 'Label Grafik']], null, 'A4');

        $row = 5;
        foreach ($data['trend'] as $point) {
            $this->setDate($sheet, 'A' . $row, (string) $point['date']);
            $sheet->setCellValue('B' . $row, (float) $point['revenue']);
            $sheet->setCellValue('C' . $row, (int) $point['transactions']);
            $this->forceText($sheet, 'D' . $row, date('d M', strtotime((string) $point['date'])));
            $row++;
        }
        if ($row === 5) {
            $sheet->setCellValue('A5', 'Tidak ada transaksi confirmed pada periode ini.');
            $sheet->mergeCells('A5:C5');
        }
        $lastRow = max(5, $row - 1);
        $this->styleTable($sheet, 'A4:D' . $lastRow);
        $sheet->getStyle('A5:A' . $lastRow)->getNumberFormat()->setFormatCode('dd mmm yyyy');
        $sheet->getStyle('B5:B' . $lastRow)->getNumberFormat()->setFormatCode(self::CURRENCY_FORMAT);
        $sheet->getStyle('C5:C' . $lastRow)->getNumberFormat()->setFormatCode(self::INTEGER_FORMAT);
        $sheet->setAutoFilter('A4:C' . $lastRow);
        $sheet->freezePane('A5');
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(24);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setVisible(false);

        if ($row > 5) {
            $labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Tren Pendapatan'!\$B\$4", null, 1)];
            $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Tren Pendapatan'!\$D\$5:\$D\$$lastRow", null, $lastRow - 4)];
            $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Tren Pendapatan'!\$B\$5:\$B\$$lastRow", null, $lastRow - 4)];
            $series = new DataSeries(DataSeries::TYPE_LINECHART, DataSeries::GROUPING_STANDARD, [0], $labels, $categories, $values);
            $chart = new Chart('revenue_trend', new Title('Tren Pendapatan (IDR)'), new Legend(Legend::POSITION_BOTTOM, null, false), new PlotArea(null, [$series]));
            $chart->setTopLeftPosition('E4');
            $chart->setBottomRightPosition('L21');
            $sheet->addChart($chart);
        }
    }

    private function buildTransactionsSheet(Worksheet $sheet, array $data): void
    {
        $sheet->setTitle('Transaksi Terkini');
        $this->prepareSheet($sheet, 'A1:G20');
        $this->writeSheetTitle($sheet, 'TRANSAKSI TERKINI', 'Transaksi terbaru dalam periode laporan');
        $headers = ['ID', 'Invoice', 'Produk', 'Pengguna', 'Tanggal', 'Status', 'Total (IDR)'];
        $sheet->fromArray([$headers], null, 'A4');
        $row = 5;
        foreach ($data['recent_transactions'] as $transaction) {
            $values = [
                (string) $transaction['id'],
                (string) ($transaction['invoice_number'] ?? ''),
                (string) ($transaction['product_name'] ?? ''),
                (string) ($transaction['user_name'] ?? 'Pengguna'),
                null,
                (string) ($transaction['status'] ?? ''),
                (float) ($transaction['total'] ?? 0),
            ];
            $sheet->fromArray([$values], null, 'A' . $row);
            $this->forceText($sheet, 'A' . $row, $values[0]);
            $this->forceText($sheet, 'B' . $row, $values[1]);
            $this->forceText($sheet, 'C' . $row, $values[2]);
            $this->forceText($sheet, 'D' . $row, $values[3]);
            $this->setDateTime($sheet, 'E' . $row, (string) ($transaction['created_at'] ?? ''));
            $this->forceText($sheet, 'F' . $row, $values[5]);
            $row++;
        }
        $this->finishDataSheet($sheet, $headers, $row, ['A'=>12,'B'=>23,'C'=>34,'D'=>24,'E'=>21,'F'=>16,'G'=>22]);
        $sheet->getStyle('E5:E' . max(5, $row - 1))->getNumberFormat()->setFormatCode('dd mmm yyyy hh:mm');
        $sheet->getStyle('G5:G' . max(5, $row - 1))->getNumberFormat()->setFormatCode(self::CURRENCY_FORMAT);
    }

    private function buildApprovalsSheet(Worksheet $sheet, array $data): void
    {
        $sheet->setTitle('Approval Pending');
        $this->prepareSheet($sheet, 'A1:H20');
        $this->writeSheetTitle($sheet, 'APPROVAL PENDING', 'Daftar keputusan yang menunggu tindakan CEO');
        $headers = ['ID', 'Judul', 'Kategori', 'Pemohon', 'Prioritas', 'Jatuh Tempo', 'Nominal', 'Status'];
        $sheet->fromArray([$headers], null, 'A4');
        $row = 5;
        foreach ($data['pending_approvals'] as $approval) {
            $this->forceText($sheet, 'A' . $row, (string) ($approval['id'] ?? ''));
            $this->forceText($sheet, 'B' . $row, (string) ($approval['title'] ?? 'Approval'));
            $this->forceText($sheet, 'C' . $row, (string) ($approval['category'] ?? $approval['source_type'] ?? 'Umum'));
            $this->forceText($sheet, 'D' . $row, (string) ($approval['requester_name'] ?? $approval['requester'] ?? '-'));
            $this->forceText($sheet, 'E' . $row, strtoupper((string) ($approval['priority'] ?? 'medium')));
            $this->setDateTime($sheet, 'F' . $row, (string) ($approval['due_date'] ?? ''));
            $sheet->setCellValue('G' . $row, (float) ($approval['amount'] ?? 0));
            $this->forceText($sheet, 'H' . $row, strtoupper((string) ($approval['status'] ?? 'pending')));
            $row++;
        }
        $this->finishDataSheet($sheet, $headers, $row, ['A'=>11,'B'=>34,'C'=>20,'D'=>24,'E'=>15,'F'=>21,'G'=>22,'H'=>16]);
        $sheet->getStyle('F5:F' . max(5, $row - 1))->getNumberFormat()->setFormatCode('dd mmm yyyy hh:mm');
        $sheet->getStyle('G5:G' . max(5, $row - 1))->getNumberFormat()->setFormatCode(self::CURRENCY_FORMAT);
    }

    private function buildGoalsSheet(Worksheet $sheet, array $data): void
    {
        $sheet->setTitle('OKR & Sasaran');
        $this->prepareSheet($sheet, 'A1:I20');
        $this->writeSheetTitle($sheet, 'OKR & SASARAN', 'Kemajuan sasaran strategis yang aktif');
        $headers = ['Objective', 'Owner', 'Mulai', 'Selesai', 'Target', 'Aktual', 'Unit', 'Progress', 'Status'];
        $sheet->fromArray([$headers], null, 'A4');
        $row = 5;
        foreach ($data['goals'] as $goal) {
            $this->forceText($sheet, 'A' . $row, (string) ($goal['objective'] ?? ''));
            $this->forceText($sheet, 'B' . $row, (string) ($goal['owner'] ?? ''));
            $this->setDate($sheet, 'C' . $row, (string) ($goal['period_start'] ?? ''));
            $this->setDate($sheet, 'D' . $row, (string) ($goal['period_end'] ?? ''));
            $sheet->setCellValue('E' . $row, (float) ($goal['target_value'] ?? 0));
            $sheet->setCellValue('F' . $row, (float) ($goal['actual_value'] ?? 0));
            $this->forceText($sheet, 'G' . $row, (string) ($goal['unit'] ?? 'number'));
            $sheet->setCellValue('H' . $row, (float) ($goal['target_value'] ?? 0) > 0 ? (float) $goal['actual_value'] / (float) $goal['target_value'] : 0);
            $this->forceText($sheet, 'I' . $row, strtoupper(str_replace('_', ' ', (string) ($goal['status'] ?? ''))));
            $row++;
        }
        $this->finishDataSheet($sheet, $headers, $row, ['A'=>40,'B'=>23,'C'=>16,'D'=>16,'E'=>16,'F'=>16,'G'=>13,'H'=>14,'I'=>18]);
        $sheet->getStyle('C5:D' . max(5, $row - 1))->getNumberFormat()->setFormatCode('dd mmm yyyy');
        $sheet->getStyle('E5:F' . max(5, $row - 1))->getNumberFormat()->setFormatCode(self::INTEGER_FORMAT);
        $sheet->getStyle('H5:H' . max(5, $row - 1))->getNumberFormat()->setFormatCode('0.0%');
    }

    private function buildAlertsSheet(Worksheet $sheet, array $data): void
    {
        $sheet->setTitle('Risk & Alert');
        $this->prepareSheet($sheet, 'A1:E20');
        $this->writeSheetTitle($sheet, 'RISK & ALERT CENTER', 'Pengecualian yang memerlukan perhatian eksekutif');
        $headers = ['Severity', 'Judul', 'Penjelasan', 'Sumber', 'Tautan Dashboard'];
        $sheet->fromArray([$headers], null, 'A4');
        $row = 5;
        foreach ($data['alerts'] as $alert) {
            $this->forceText($sheet, 'A' . $row, strtoupper((string) ($alert['severity'] ?? 'info')));
            $this->forceText($sheet, 'B' . $row, (string) ($alert['title'] ?? ''));
            $this->forceText($sheet, 'C' . $row, (string) ($alert['reason'] ?? ''));
            $this->forceText($sheet, 'D' . $row, (string) ($alert['source'] ?? ''));
            $this->forceText($sheet, 'E' . $row, (string) ($alert['link'] ?? ''));
            $row++;
        }
        $this->finishDataSheet($sheet, $headers, $row, ['A'=>15,'B'=>32,'C'=>58,'D'=>24,'E'=>38]);
        $sheet->getStyle('C5:C' . max(5, $row - 1))->getAlignment()->setWrapText(true);
    }

    private function finishDataSheet(Worksheet $sheet, array $headers, int $nextRow, array $widths): void
    {
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $lastRow = max(5, $nextRow - 1);
        if ($nextRow === 5) {
            $sheet->setCellValue('A5', 'Tidak ada data pada periode ini.');
            if ($lastColumn !== 'A') {
                $sheet->mergeCells('A5:' . $lastColumn . '5');
            }
            $sheet->getStyle('A5')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::MUTED));
        }
        $this->styleTable($sheet, 'A4:' . $lastColumn . $lastRow);
        $sheet->setAutoFilter('A4:' . $lastColumn . $lastRow);
        $sheet->freezePane('A5');
        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function prepareSheet(Worksheet $sheet, string $printArea): void
    {
        $sheet->setShowGridlines(false);
        $sheet->getSheetView()->setZoomScale(90);
        $sheet->getDefaultRowDimension()->setRowHeight(19);
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Aptos')->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::DARK));
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);
        $sheet->getPageSetup()->setPrintArea($printArea);
        $sheet->getPageMargins()->setTop(0.4)->setRight(0.35)->setBottom(0.4)->setLeft(0.35);
        $sheet->getHeaderFooter()->setOddFooter('&LALMAI Executive Suite&CPage &P of &N&RConfidential');
    }

    private function writeSheetTitle(Worksheet $sheet, string $title, string $subtitle): void
    {
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A1', $title);
        $sheet->setCellValue('A2', $subtitle);
        $sheet->getStyle('A1:I1')->applyFromArray($this->titleStyle());
        $sheet->getStyle('A1')->getFont()->setSize(17);
        $sheet->getStyle('A2:I2')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::MUTED));
    }

    private function styleTable(Worksheet $sheet, string $range): void
    {
        $start = explode(':', $range)[0];
        $end = explode(':', $range)[1];
        preg_match('/([A-Z]+)(\d+)/', $start, $startParts);
        preg_match('/([A-Z]+)(\d+)/', $end, $endParts);
        $headerRange = $startParts[1] . $startParts[2] . ':' . $endParts[1] . $startParts[2];
        $sheet->getStyle($headerRange)->applyFromArray($this->tableHeaderStyle());
        $sheet->getStyle($range)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_HAIR)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(self::BORDER));
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension((int) $startParts[2])->setRowHeight(25);
    }

    private function titleStyle(): array
    {
        return [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::DARKER]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
    }

    private function sectionStyle(): array
    {
        return [
            'font' => ['bold' => true, 'color' => ['argb' => 'FF' . self::DARKER]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::GREEN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
    }

    private function tableHeaderStyle(): array
    {
        return [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::DARK]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
    }

    private function setDate(Worksheet $sheet, string $cell, string $value): void
    {
        if ($value !== '' && strtotime($value) !== false) {
            $sheet->setCellValue($cell, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new \DateTimeImmutable($value)));
        }
    }

    private function setDateTime(Worksheet $sheet, string $cell, string $value): void
    {
        $this->setDate($sheet, $cell, $value);
    }

    private function forceText(Worksheet $sheet, string $cell, string $value): void
    {
        $sheet->setCellValueExplicit($cell, $value, DataType::TYPE_STRING);
    }

    private function growthLabel(mixed $growth): string
    {
        return $growth === null ? 'Pembanding belum tersedia' : number_format((float) $growth, 1) . '% vs periode sebelumnya';
    }

    private function money(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    private function displayTimestamp(string $value): string
    {
        try {
            return (new DateTimeImmutable($value))->format('d M Y H:i T');
        } catch (\Throwable) {
            return $value;
        }
    }
}
