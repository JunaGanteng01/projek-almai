<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\AkunModel;
use App\Models\JurnalModel;

class Rekonsiliasi extends BaseController
{
    public function index()
    {
        $akunModel = new AkunModel();
        $jurnalModel = new JurnalModel();

        $akunId = (int) ($this->request->getGet('akun_id') ?? 0);
        $bulan = (int) ($this->request->getGet('bulan') ?? date('n'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        $kasAccounts = $akunModel->where('kategori', 'kas & bank')->orderBy('kode_akun', 'ASC')->findAll();

        if (!$akunId && !empty($kasAccounts)) {
            $akunId = (int) $kasAccounts[0]['id'];
        }

        $startDate = sprintf('%04d-%02d-01', $tahun, $bulan);
        $endDate = date('Y-m-t', strtotime($startDate));

        $mutasi = [];
        $saldoBuku = 0.0;

        if ($akunId) {
            $rows = $jurnalModel
                ->select('jurnal.*, akun.kode_akun, akun.nama_akun')
                ->join('akun', 'akun.id = jurnal.akun_id')
                ->where('jurnal.akun_id', $akunId)
                ->where('jurnal.tanggal >=', $startDate)
                ->where('jurnal.tanggal <=', $endDate)
                ->orderBy('jurnal.tanggal', 'ASC')
                ->orderBy('jurnal.id', 'ASC')
                ->findAll();

            foreach ($rows as $row) {
                $net = (float) $row['debit'] - (float) $row['kredit'];
                $saldoBuku += $net;
                $mutasi[] = array_merge($row, ['saldo_berjalan' => $saldoBuku]);
            }
        }

        $saldoBank = (float) ($this->request->getGet('saldo_bank') ?? 0);
        
        $selisih = $saldoBank - $saldoBuku;

        $page = (int)($this->request->getVar('page') ?? 1);
        $perPage = 20;
        $total = count($mutasi);
        $pager = \Config\Services::pager();
        $mutasi = array_slice($mutasi, ($page - 1) * $perPage, $perPage);

        return view('keuangan/rekonsiliasi/index', [
            'pagerLinks' => $pager->makeLinks($page, $perPage, $total, 'tailwind_full'),

            'title' => 'Rekonsiliasi Bank',
            'activeMenu' => 'rekonsiliasi',
            'kasAccounts' => $kasAccounts,
            'akunId' => $akunId,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'mutasi' => $mutasi,
            'saldoBuku' => $saldoBuku,
            'saldoBank' => $saldoBank,
            'selisih' => $selisih,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
