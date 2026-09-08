<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\KonsultasiModel;
use App\Models\WpaModel;

class Konsultasi extends BaseController
{
    public function index()
    {
        $layananModel = new \App\Models\LayananModel();
        
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        
        $result = $layananModel->getLaporanByModul('Konsultasi', $search, $page, 20);

        $data = [
            'title' => 'Konsultasi - Almai',
            'activeMenu' => 'konsultasi',
            'konsultasis' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search
        ];

        return view('laporan-kegiatan/konsultasi/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();

        $data = [
            'title' => 'Tambah Konsultasi - Almai',
            'activeMenu' => 'konsultasi',
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/konsultasi/form', $data);
    }

    public function store()
    {
        $konsultasiModel = new KonsultasiModel();
        
        $data = [
            'nama_klien' => $this->request->getPost('nama_klien'),
            'jml_nasihat' => $this->request->getPost('jml_nasihat') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'media' => $this->request->getPost('media'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $konsultasiModel->insert($data);

        return redirect()->to('/laporan-kegiatan/konsultasi')->with('success', 'Data Konsultasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $konsultasiModel = new KonsultasiModel();
        $wpaModel = new WpaModel();
        
        $konsultasi = $konsultasiModel->find($id);

        if (!$konsultasi) {
            return redirect()->to('/laporan-kegiatan/konsultasi')->with('error', 'Data Konsultasi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Konsultasi - Almai',
            'activeMenu' => 'konsultasi',
            'konsultasi' => $konsultasi,
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/konsultasi/form', $data);
    }

    public function update($id)
    {
        $konsultasiModel = new KonsultasiModel();
        $konsultasi = $konsultasiModel->find($id);

        if (!$konsultasi) {
            return redirect()->to('/laporan-kegiatan/konsultasi')->with('error', 'Data Konsultasi tidak ditemukan.');
        }

        $data = [
            'nama_klien' => $this->request->getPost('nama_klien'),
            'jml_nasihat' => $this->request->getPost('jml_nasihat') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'media' => $this->request->getPost('media'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $konsultasiModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/konsultasi')->with('success', 'Data Konsultasi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $konsultasiModel = new KonsultasiModel();
        $konsultasiModel->delete($id);

        return redirect()->to('/laporan-kegiatan/konsultasi')->with('success', 'Data Konsultasi berhasil dihapus.');
    }
}
