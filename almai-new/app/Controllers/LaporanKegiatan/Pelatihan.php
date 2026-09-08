<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\PelatihanModel;
use App\Models\WpaModel;

class Pelatihan extends BaseController
{
    public function index()
    {
        $layananModel = new \App\Models\LayananModel();
        
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        
        $result = $layananModel->getLaporanByModul('Pelatihan Simulasi', $search, $page, 20);

        $data = [
            'title' => 'Pelatihan & Simulasi - Almai',
            'activeMenu' => 'pelatihan',
            'pelatihans' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search
        ];

        return view('laporan-kegiatan/pelatihan/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();

        $data = [
            'title' => 'Tambah Pelatihan Simulasi - Almai',
            'activeMenu' => 'pelatihan',
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/pelatihan/form', $data);
    }

    public function store()
    {
        $pelatihanModel = new PelatihanModel();
        
        $data = [
            'judul' => $this->request->getPost('judul'),
            'jml_peserta' => $this->request->getPost('jml_peserta') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'topik' => $this->request->getPost('topik')
        ];

        $pelatihanModel->insert($data);

        return redirect()->to('/laporan-kegiatan/pelatihan')->with('success', 'Data Pelatihan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelatihanModel = new PelatihanModel();
        $wpaModel = new WpaModel();
        
        $pelatihan = $pelatihanModel->find($id);

        if (!$pelatihan) {
            return redirect()->to('/laporan-kegiatan/pelatihan')->with('error', 'Data Pelatihan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Pelatihan Simulasi - Almai',
            'activeMenu' => 'pelatihan',
            'pelatihan' => $pelatihan,
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/pelatihan/form', $data);
    }

    public function update($id)
    {
        $pelatihanModel = new PelatihanModel();
        $pelatihan = $pelatihanModel->find($id);

        if (!$pelatihan) {
            return redirect()->to('/laporan-kegiatan/pelatihan')->with('error', 'Data Pelatihan tidak ditemukan.');
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

        $pelatihanModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/pelatihan')->with('success', 'Data Pelatihan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $pelatihanModel = new PelatihanModel();
        $pelatihanModel->delete($id);

        return redirect()->to('/laporan-kegiatan/pelatihan')->with('success', 'Data Pelatihan berhasil dihapus.');
    }
}
