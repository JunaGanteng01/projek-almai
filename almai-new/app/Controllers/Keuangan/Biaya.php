<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\JurnalModel;
use App\Models\AkunModel;

class Biaya extends BaseController
{
    public function index()
    {
        $jurnalModel = new JurnalModel();
        
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $search = $this->request->getGet('search');

        $query = $jurnalModel->select('jurnal.*, akun.nama_akun, akun.kode_akun')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->whereIn('akun.kategori', ['beban', 'beban lainya'])
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate);

        if ($search) {
            $query->like('jurnal.deskripsi', $search);
        }

        $expenses = $query->orderBy('jurnal.tanggal', 'DESC')->findAll();

        $data = [
            'title' => 'Biaya Operasional',
            'activeMenu' => 'biaya',
            'expenses' => $expenses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalBiaya' => array_sum(array_column($expenses, 'debit'))
        ];

        return view('keuangan/biaya/index', $data);
    }
}
