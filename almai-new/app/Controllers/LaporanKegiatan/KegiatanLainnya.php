<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\KegiatanLainnyaModel;
use App\Models\WpaModel;

class KegiatanLainnya extends BaseController
{
    public function index()
    {
        $layananModel = new \App\Models\LayananModel();
        
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        
        $result = $layananModel->getLaporanByModul('Kegiatan Lainnya', $search, $page, 20);

        $data = [
            'title' => 'Kegiatan Lainnya - Almai',
            'activeMenu' => 'kegiatan_lainnya',
            'kegiatans' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search
        ];

        return view('laporan-kegiatan/kegiatan-lainnya/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();

        $data = [
            'title' => 'Tambah Kegiatan Lainnya - Almai',
            'activeMenu' => 'kegiatan-lainnya',
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/kegiatan-lainnya/form', $data);
    }

    public function store()
    {
        $kegiatanModel = new KegiatanLainnyaModel();
        
        $data = [
            'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
            'jml_klien' => $this->request->getPost('jml_klien') ?: 0,
            'jml_nasihat' => $this->request->getPost('jml_nasihat') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'media' => $this->request->getPost('media'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $kegiatanModel->insert($data);

        return redirect()->to('/laporan-kegiatan/kegiatan-lainnya')->with('success', 'Data Kegiatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatanModel = new KegiatanLainnyaModel();
        $wpaModel = new WpaModel();
        
        $kegiatan = $kegiatanModel->find($id);

        if (!$kegiatan) {
            return redirect()->to('/laporan-kegiatan/kegiatan-lainnya')->with('error', 'Data Kegiatan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Kegiatan Lainnya - Almai',
            'activeMenu' => 'kegiatan-lainnya',
            'kegiatan' => $kegiatan,
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/kegiatan-lainnya/form', $data);
    }

    public function update($id)
    {
        $kegiatanModel = new KegiatanLainnyaModel();
        $kegiatan = $kegiatanModel->find($id);

        if (!$kegiatan) {
            return redirect()->to('/laporan-kegiatan/kegiatan-lainnya')->with('error', 'Data Kegiatan tidak ditemukan.');
        }

        $data = [
            'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
            'jml_klien' => $this->request->getPost('jml_klien') ?: 0,
            'jml_nasihat' => $this->request->getPost('jml_nasihat') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'media' => $this->request->getPost('media'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $kegiatanModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/kegiatan-lainnya')->with('success', 'Data Kegiatan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kegiatanModel = new KegiatanLainnyaModel();
        $kegiatanModel->delete($id);

        return redirect()->to('/laporan-kegiatan/kegiatan-lainnya')->with('success', 'Data Kegiatan berhasil dihapus.');
    }
}
