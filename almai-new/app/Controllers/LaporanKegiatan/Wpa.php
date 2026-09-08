<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\WpaModel;

class Wpa extends BaseController
{
    public function index()
    {
        $wpaModel = new WpaModel();
        
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $wpaModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('nik_wpa', $search)
                ->groupEnd();
        }

        if (!empty($status) && $status !== 'all') {
            $builder->where('status', $status);
        }

        // Apply soft deletes logic if necessary, but BaseModel usually handles it for find/findAll, 
        // since we are using builder directly we might need where('deleted_at', null) if soft deletes are on.
        // It's safer to use the Model's methods to build the query.
        
        $queryModel = (new WpaModel());
        
        if (!empty($search)) {
            $queryModel->groupStart()
                ->like('name', $search)
                ->orLike('nik_wpa', $search)
                ->groupEnd();
        }

        if (!empty($status) && $status !== 'all') {
            $queryModel->where('status', $status);
        }

        $wpas = $queryModel->orderBy('name', 'ASC')->findAll();

        $statsModel = new WpaModel();
        $stats = [
            'total' => $statsModel->countAllResults(),
            'active' => (new WpaModel())->where('status', 'active')->countAllResults(),
            'inactive' => (new WpaModel())->where('status', 'inactive')->countAllResults(),
        ];
        
        $data = [
            'title' => 'Data WPA - Almai',
            'activeMenu' => 'wpa',
            'wpas' => $wpas,
            'stats' => $stats,
            'search' => $search,
            'currentStatus' => $status,
        ];

        return view('laporan-kegiatan/wpa/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data WPA - Almai',
            'activeMenu' => 'wpa'
        ];
        return view('laporan-kegiatan/wpa/form', $data);
    }

    public function store()
    {
        $wpaModel = new WpaModel();
        
        $data = [
            'name' => $this->request->getPost('name'),
            'nik_wpa' => $this->request->getPost('nik_wpa'),
            'nomor_izin_wpa' => $this->request->getPost('nomor_izin_wpa'),
            'tanggal_izin_wpa' => $this->request->getPost('tanggal_izin_wpa') ?: null,
            'no_sertifikat_aspebtindo' => $this->request->getPost('no_sertifikat_aspebtindo'),
            'no_sertifikat_bi' => $this->request->getPost('no_sertifikat_bi'),
            'no_sertifikat_bnsp' => $this->request->getPost('no_sertifikat_bnsp'),
            'masa_berlaku' => $this->request->getPost('masa_berlaku'),
            'status' => $this->request->getPost('status') ?? 'active',
            'keterangan' => $this->request->getPost('keterangan'),
            'user_id' => 0 // Set as 0 or null if manually managed
        ];

        $wpaModel->insert($data);

        return redirect()->to('/laporan-kegiatan/wpa')->with('success', 'Data WPA berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $wpaModel = new WpaModel();
        $wpa = $wpaModel->find($id);

        if (!$wpa) {
            return redirect()->to('/laporan-kegiatan/wpa')->with('error', 'Data WPA tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data WPA - Almai',
            'activeMenu' => 'wpa',
            'wpa' => $wpa
        ];
        return view('laporan-kegiatan/wpa/form', $data);
    }

    public function update($id)
    {
        $wpaModel = new WpaModel();
        $wpa = $wpaModel->find($id);

        if (!$wpa) {
            return redirect()->to('/laporan-kegiatan/wpa')->with('error', 'Data WPA tidak ditemukan.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'nik_wpa' => $this->request->getPost('nik_wpa'),
            'nomor_izin_wpa' => $this->request->getPost('nomor_izin_wpa'),
            'tanggal_izin_wpa' => $this->request->getPost('tanggal_izin_wpa') ?: null,
            'no_sertifikat_aspebtindo' => $this->request->getPost('no_sertifikat_aspebtindo'),
            'no_sertifikat_bi' => $this->request->getPost('no_sertifikat_bi'),
            'no_sertifikat_bnsp' => $this->request->getPost('no_sertifikat_bnsp'),
            'masa_berlaku' => $this->request->getPost('masa_berlaku'),
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $wpaModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/wpa')->with('success', 'Data WPA berhasil diperbarui.');
    }

    public function delete($id)
    {
        $wpaModel = new WpaModel();
        $wpaModel->delete($id);

        return redirect()->to('/laporan-kegiatan/wpa')->with('success', 'Data WPA berhasil dihapus.');
    }

    public function view($id)
    {
        $wpaModel = new WpaModel();
        $wpa = $wpaModel->find($id);

        if (!$wpa) {
            return redirect()->to('/laporan-kegiatan/wpa')->with('error', 'WPA tidak ditemukan');
        }

        return view('laporan-kegiatan/wpa/view', [
            'title' => 'Detail WPA',
            'activeMenu' => 'wpa',
            'wpa' => $wpa
        ]);
    }

    public function exportCsv()
    {
        $wpaModel = new WpaModel();
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $queryModel = new WpaModel();
        
        if (!empty($search)) {
            $queryModel->groupStart()
                ->like('name', $search)
                ->orLike('nik_wpa', $search)
                ->groupEnd();
        }

        if (!empty($status) && $status !== 'all') {
            $queryModel->where('status', $status);
        }

        $wpaList = $queryModel->orderBy('name', 'ASC')->findAll();

        $filename = 'data_wpa_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama WPA', 'NIK WPA', 'No. Izin WPA', 'Tgl Pemberian Izin', 'No. Sertifikat ASPEBTINDO', 'No. Sertifikat BI', 'No. Sertifikat Aspebtindo/BNSP', 'Masa Berlaku', 'Status', 'Keterangan']);

        $no = 1;
        foreach ($wpaList as $row) {
            fputcsv($output, [
                $no++,
                $row['name'],
                $row['nik_wpa'],
                $row['nomor_izin_wpa'],
                $row['tanggal_izin_wpa'],
                $row['no_sertifikat_aspebtindo'],
                $row['no_sertifikat_bi'],
                $row['no_sertifikat_bnsp'],
                $row['masa_berlaku'],
                $row['status'] === 'active' ? 'Aktif' : 'Tidak Aktif',
                $row['keterangan']
            ]);
        }
        fclose($output);
        exit();
    }
}
