<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Services\Keuangan\CalkService;

class Calk extends BaseController
{
    protected CalkService $calkService;

    public function __construct()
    {
        $this->calkService = new CalkService();
    }

    public function index()
    {
        $startDate = (string) ($this->request->getGet('start_date') ?? date('Y-m-01'));
        $endDate = (string) ($this->request->getGet('end_date') ?? date('Y-m-t'));
        $tahun = (int) date('Y', strtotime($endDate));

        $calkData = $this->calkService->getCalkData($tahun);

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

    public function save()
    {
        $endDate = $this->request->getPost('end_date') ?? date('Y-m-d');
        $tahun = (int) date('Y', strtotime($endDate));

        $calkData = [
            'gambaran_umum' => $this->request->getPost('gambaran_umum'),
            'kebijakan_akuntansi' => $this->request->getPost('kebijakan_akuntansi'),
            'rincian_kas' => $this->request->getPost('rincian_kas'),
            'rincian_piutang' => $this->request->getPost('rincian_piutang'),
            'rincian_aset_tetap' => $this->request->getPost('rincian_aset_tetap'),
            'rincian_hutang' => $this->request->getPost('rincian_hutang')
        ];

        $this->calkService->saveCalkData($tahun, $calkData);

        return redirect()->to('/keuangan/calk?start_date=' . ($this->request->getPost('start_date') ?? date('Y-m-01')) . '&end_date=' . $endDate)->with('success', 'Catatan Atas Laporan Keuangan berhasil diperbarui.');
    }

    public function sync()
    {
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        
        try {
            $this->calkService->syncWithFinancialStatements((int)$tahun);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'CALK berhasil disinkronisasi dengan data laporan keuangan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getData()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $sectionId = $this->request->getGet('section_id');

        try {
            if ($sectionId) {
                $data = $this->calkService->getFinancialTableData($sectionId, (int)$tahun);
            } else {
                $data = $this->calkService->getCalkData((int)$tahun);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
