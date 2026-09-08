<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\SignalModel;
use App\Models\WpaModel;

class Signal extends BaseController
{
    public function index()
    {
        $layananModel = new \App\Models\LayananModel();
        
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        
        $result = $layananModel->getLaporanByModul('Signal', $search, $page, 20);

        $data = [
            'title' => 'Signal & Rekomendasi - Almai',
            'activeMenu' => 'signal',
            'signals' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search
        ];

        return view('laporan-kegiatan/signal/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();

        $data = [
            'title' => 'Tambah Signal - Almai',
            'activeMenu' => 'signal',
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/signal/form', $data);
    }

    public function store()
    {
        $signalModel = new SignalModel();
        
        $data = [
            'jml_peserta' => $this->request->getPost('jml_peserta') ?: 0,
            'jml_nasihat' => $this->request->getPost('jml_nasihat') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'media' => $this->request->getPost('media'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $signalModel->insert($data);

        return redirect()->to('/laporan-kegiatan/signal')->with('success', 'Data Signal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $signalModel = new SignalModel();
        $wpaModel = new WpaModel();
        
        $signal = $signalModel->find($id);

        if (!$signal) {
            return redirect()->to('/laporan-kegiatan/signal')->with('error', 'Data Signal tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Signal - Almai',
            'activeMenu' => 'signal',
            'signal' => $signal,
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/signal/form', $data);
    }

    public function update($id)
    {
        $signalModel = new SignalModel();
        $signal = $signalModel->find($id);

        if (!$signal) {
            return redirect()->to('/laporan-kegiatan/signal')->with('error', 'Data Signal tidak ditemukan.');
        }

        $data = [
            'jml_peserta' => $this->request->getPost('jml_peserta') ?: 0,
            'jml_nasihat' => $this->request->getPost('jml_nasihat') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'media' => $this->request->getPost('media'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $signalModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/signal')->with('success', 'Data Signal berhasil diperbarui.');
    }

    public function delete($id)
    {
        $signalModel = new SignalModel();
        $signalModel->delete($id);

        return redirect()->to('/laporan-kegiatan/signal')->with('success', 'Data Signal berhasil dihapus.');
    }
}
