<?php

namespace Tests\Unit;

use App\Services\CeoReportExportService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPUnit\Framework\TestCase;

class CeoReportExportTest extends TestCase
{
    public function testCreatesReadableNativeExcelReport(): void
    {
        $data = [
            'range' => ['period' => 'mtd', 'label' => 'Bulan berjalan', 'start' => '2026-09-01', 'end' => '2026-09-03'],
            'generated_at' => '2026-09-03T15:24:37+07:00',
            'summary' => [
                'revenue' => 125000000.0,
                'revenue_growth' => 12.5,
                'transactions_confirmed' => 18,
                'transactions_total' => 22,
                'new_users' => 9,
                'user_growth' => 5.0,
                'conversion_rate' => 20.0,
                'cash_balance' => 5340000000.0,
                'approvals' => ['pending' => 3, 'urgent' => 1, 'overdue' => 1, 'amount' => 75000000.0],
                'withdrawals' => ['pending' => 2, 'amount' => 3000000.0],
                'invoices' => ['open' => 4, 'overdue' => 1, 'amount' => 45000000.0],
                'events' => ['upcoming' => 2, 'participants' => 80, 'capacity' => 100, 'occupancy' => 80.0],
                'crm' => ['open' => 6, 'unassigned' => 1, 'sla_breach' => 2],
                'bills' => ['open' => 2, 'overdue' => 1, 'due_soon' => 1, 'amount' => 12000000.0],
                'goals' => ['active' => 2, 'off_track' => 1, 'overdue' => 0],
            ],
            'trend' => [
                ['date' => '2026-09-01', 'revenue' => 50000000.0, 'transactions' => 8],
                ['date' => '2026-09-02', 'revenue' => 75000000.0, 'transactions' => 10],
            ],
            'recent_transactions' => [[
                'id' => 1, 'invoice_number' => 'INV-001', 'product_name' => 'Program ALMAI',
                'user_name' => 'Customer', 'created_at' => '2026-09-02 10:15:00', 'status' => 'confirmed', 'total' => 75000000.0,
            ]],
            'pending_approvals' => [[
                'id' => 3, 'title' => 'Pembelian perangkat', 'category' => 'Procurement', 'requester_name' => 'Finance',
                'priority' => 'urgent', 'due_date' => '2026-09-04 17:00:00', 'amount' => 75000000.0, 'status' => 'pending',
            ]],
            'goals' => [[
                'objective' => 'Pertumbuhan pengguna', 'owner' => 'Growth', 'period_start' => '2026-09-01',
                'period_end' => '2026-12-31', 'target_value' => 1000, 'actual_value' => 400,
                'unit' => 'users', 'status' => 'on_track',
            ]],
            'alerts' => [[
                'severity' => 'high', 'title' => 'Invoice overdue', 'reason' => 'Satu invoice melewati jatuh tempo.',
                'source' => 'customer_invoices', 'link' => '/ceo/dashboard#finance',
            ]],
            'definitions' => [
                'revenue' => 'Total transaksi confirmed non-poin.',
                'cash_balance' => 'Saldo debit dikurangi kredit akun kas dan bank.',
            ],
        ];

        $service = new CeoReportExportService();
        $binary = $service->toBinary($data);
        $this->assertStringStartsWith('PK', $binary);

        $verificationOutput = getenv('CEO_REPORT_TEST_OUTPUT');
        if (is_string($verificationOutput) && $verificationOutput !== '') {
            file_put_contents($verificationOutput, $binary);
        }

        $path = tempnam(sys_get_temp_dir(), 'ceo-report-') . '.xlsx';
        file_put_contents($path, $binary);
        $reader = IOFactory::createReader('Xlsx');
        $reader->setIncludeCharts(true);
        $workbook = $reader->load($path);

        $this->assertSame(6, $workbook->getSheetCount());
        $this->assertSame('LAPORAN EKSEKUTIF ALMAI', $workbook->getSheetByName('Ringkasan')->getCell('A1')->getValue());
        $this->assertSame(125000000.0, $workbook->getSheetByName('Ringkasan')->getCell('B12')->getValue());
        $this->assertSame('"Rp" #,##0;[Red]("Rp" #,##0);-', $workbook->getSheetByName('Ringkasan')->getStyle('B12')->getNumberFormat()->getFormatCode());
        $this->assertSame(34.0, $workbook->getSheetByName('Ringkasan')->getColumnDimension('A')->getWidth());
        $this->assertSame('A11', $workbook->getSheetByName('Ringkasan')->getFreezePane());
        $this->assertCount(1, $workbook->getSheetByName('Tren Pendapatan')->getChartCollection());
        $this->assertSame(75000000.0, $workbook->getSheetByName('Transaksi Terkini')->getCell('G5')->getValue());

        $workbook->disconnectWorksheets();
        unlink($path);
    }
}
