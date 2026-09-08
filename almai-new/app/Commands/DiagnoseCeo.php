<?php

namespace App\Commands;

use App\Models\LevelModel;
use App\Services\CeoDashboardService;
use App\Services\CeoReportExportService;
use App\Services\ExecutiveCalendarService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DiagnoseCeo extends BaseCommand
{
    protected $group = 'CEO';
    protected $name = 'ceo:diagnose';
    protected $description = 'Memeriksa role, schema, agregasi KPI, kalender, dan data CEO Executive Suite.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $required = ['levels', 'users', 'transaksi', 'ea_meetings', 'ea_tasks', 'ea_reminders', 'ea_approvals', 'notifications', 'audit_logs'];
        $missing = array_values(array_filter($required, static fn(string $table): bool => !$db->tableExists($table)));
        if ($missing) {
            CLI::error('Tabel wajib belum tersedia: ' . implode(', ', $missing));
            CLI::write('Jalankan: php spark migrate', 'yellow');
            return;
        }

        $ceoLevel = $db->table('levels')->where('id', LevelModel::LEVEL_CEO)->get()->getRowArray();
        if (!$ceoLevel) {
            CLI::error('Level CEO ID 10 belum tersedia. Jalankan: php spark db:seed CeoSeeder');
            return;
        }

        try {
            $dashboard = (new CeoDashboardService($db))->getDashboard('mtd');
            $calendar = (new ExecutiveCalendarService($db))->getEvents(
                date('Y-m-01 00:00:00'),
                date('Y-m-t 23:59:59')
            );
            $reportBinary = (new CeoReportExportService())->toBinary($dashboard);
            if (!str_starts_with($reportBinary, 'PK')) {
                throw new \RuntimeException('Generator laporan tidak menghasilkan workbook XLSX yang valid.');
            }
        } catch (\Throwable $e) {
            CLI::error('Agregasi CEO gagal: ' . $e->getMessage());
            return;
        }

        CLI::write('Role CEO: OK (' . $ceoLevel['name'] . ')', 'green');
        CLI::write('Schema executive: OK', 'green');
        CLI::write('Revenue MTD non-poin: Rp ' . number_format($dashboard['summary']['revenue'], 0, ',', '.'), 'white');
        CLI::write('Approval pending: ' . $dashboard['summary']['approvals']['pending'], 'white');
        CLI::write('Alert aktif: ' . count($dashboard['alerts']), 'white');
        CLI::write('Event kalender bulan ini: ' . count($calendar), 'white');
        CLI::write('Ekspor Excel: OK (' . number_format(strlen($reportBinary) / 1024, 1) . ' KB)', 'green');
        CLI::write('Generated at: ' . $dashboard['generated_at'], 'dark_gray');
    }
}
