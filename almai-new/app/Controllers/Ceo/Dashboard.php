<?php

namespace App\Controllers\Ceo;

use App\Controllers\BaseController;
use App\Services\CeoDashboardService;
use App\Services\CeoReportExportService;

class Dashboard extends BaseController
{
    private CeoDashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new CeoDashboardService();
    }

    public function index()
    {
        $period = (string) ($this->request->getGet('period') ?? 'mtd');
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $dashboard = $this->dashboardService->getDashboard($period, $start, $end);

        return view('ceo/dashboard', [
            'title' => 'CEO Executive Dashboard',
            'activeMenu' => 'dashboard',
            'dashboard' => $dashboard,
        ]);
    }

    public function summary()
    {
        $period = (string) ($this->request->getGet('period') ?? 'mtd');
        $data = $this->dashboardService->getDashboard($period, $this->request->getGet('start'), $this->request->getGet('end'));
        return $this->response->setJSON(['ok' => true, 'data' => $data]);
    }

    public function exportXlsx()
    {
        $period = (string) ($this->request->getGet('period') ?? 'mtd');
        $data = $this->dashboardService->getDashboard($period, $this->request->getGet('start'), $this->request->getGet('end'));
        $binary = (new CeoReportExportService())->toBinary($data);

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="laporan-ceo-' . date('Ymd-His') . '.xlsx"')
            ->setHeader('Content-Length', (string) strlen($binary))
            ->setHeader('Cache-Control', 'private, no-store, max-age=0')
            ->setBody($binary);
    }

    public function exportCsv()
    {
        return $this->exportXlsx();
    }
}
