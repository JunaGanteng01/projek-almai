<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\SeminarModel;
use App\Models\WpaModel;

class Seminar extends BaseController
{
    public function index()
    {
        $layananModel = new \App\Models\LayananModel();
        
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        
        $result = $layananModel->getLaporanByModul('Seminar FGD', $search, $page, 20);

        $data = [
            'title' => 'Seminar & FGD - Almai',
            'activeMenu' => 'seminar',
            'seminars' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search
        ];

        return view('laporan-kegiatan/seminar/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();

        $data = [
            'title' => 'Tambah Seminar/FGD - Almai',
            'activeMenu' => 'seminar',
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/seminar/form', $data);
    }

    public function store()
    {
        $seminarModel = new SeminarModel();
        
        $data = [
            'judul' => $this->request->getPost('judul'),
            'jml_peserta' => $this->request->getPost('jml_peserta') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'topik' => $this->request->getPost('topik')
        ];

        $seminarModel->insert($data);

        return redirect()->to('/laporan-kegiatan/seminar')->with('success', 'Data Seminar/FGD berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $seminarModel = new SeminarModel();
        $wpaModel = new WpaModel();
        
        $seminar = $seminarModel->find($id);

        if (!$seminar) {
            return redirect()->to('/laporan-kegiatan/seminar')->with('error', 'Data Seminar tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Seminar/FGD - Almai',
            'activeMenu' => 'seminar',
            'seminar' => $seminar,
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/seminar/form', $data);
    }

    public function update($id)
    {
        $seminarModel = new SeminarModel();
        $seminar = $seminarModel->find($id);

        if (!$seminar) {
            return redirect()->to('/laporan-kegiatan/seminar')->with('error', 'Data Seminar tidak ditemukan.');
        }

        $data = [
            'judul' => $this->request->getPost('judul'),
            'jml_peserta' => $this->request->getPost('jml_peserta') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'topik' => $this->request->getPost('topik')
        ];

        $seminarModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/seminar')->with('success', 'Data Seminar/FGD berhasil diperbarui.');
    }

    public function delete($id)
    {
        $seminarModel = new SeminarModel();
        $seminarModel->delete($id);

        return redirect()->to('/laporan-kegiatan/seminar')->with('success', 'Data Seminar/FGD berhasil dihapus.');
    }
}
