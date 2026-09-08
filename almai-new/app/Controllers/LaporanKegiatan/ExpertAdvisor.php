<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\ExpertAdvisorModel;
use App\Models\WpaModel;

class ExpertAdvisor extends BaseController
{
    public function index()
    {
        $layananModel = new \App\Models\LayananModel();
        
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        
        $result = $layananModel->getLaporanByModul('Expert Advisor', $search, $page, 20);

        $data = [
            'title' => 'Expert Advisor (EA) - Almai',
            'activeMenu' => 'expert_advisor',
            'expertAdvisors' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search
        ];

        return view('laporan-kegiatan/expert-advisor/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();

        $data = [
            'title' => 'Tambah Expert Advisor - Almai',
            'activeMenu' => 'expert-advisor',
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/expert-advisor/form', $data);
    }

    public function store()
    {
        $expertAdvisorModel = new ExpertAdvisorModel();
        
        $data = [
            'nama_layanan' => $this->request->getPost('nama_layanan'),
            'penjelasan_layanan' => $this->request->getPost('penjelasan_layanan'),
            'jml_klien' => $this->request->getPost('jml_klien') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'winning_rate' => $this->request->getPost('winning_rate'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $expertAdvisorModel->insert($data);

        return redirect()->to('/laporan-kegiatan/expert-advisor')->with('success', 'Data Expert Advisor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $expertAdvisorModel = new ExpertAdvisorModel();
        $wpaModel = new WpaModel();
        
        $expertAdvisor = $expertAdvisorModel->find($id);

        if (!$expertAdvisor) {
            return redirect()->to('/laporan-kegiatan/expert-advisor')->with('error', 'Data Expert Advisor tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Expert Advisor - Almai',
            'activeMenu' => 'expert-advisor',
            'expertAdvisor' => $expertAdvisor,
            'wpas' => $wpaModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('laporan-kegiatan/expert-advisor/form', $data);
    }

    public function update($id)
    {
        $expertAdvisorModel = new ExpertAdvisorModel();
        $expertAdvisor = $expertAdvisorModel->find($id);

        if (!$expertAdvisor) {
            return redirect()->to('/laporan-kegiatan/expert-advisor')->with('error', 'Data Expert Advisor tidak ditemukan.');
        }

        $data = [
            'nama_layanan' => $this->request->getPost('nama_layanan'),
            'penjelasan_layanan' => $this->request->getPost('penjelasan_layanan'),
            'jml_klien' => $this->request->getPost('jml_klien') ?: 0,
            'produk' => $this->request->getPost('produk'),
            'wpa_id' => $this->request->getPost('wpa_id'),
            'winning_rate' => $this->request->getPost('winning_rate'),
            'tanggal' => $this->request->getPost('tanggal') ?: null,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        $expertAdvisorModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/expert-advisor')->with('success', 'Data Expert Advisor berhasil diperbarui.');
    }

    public function delete($id)
    {
        $expertAdvisorModel = new ExpertAdvisorModel();
        $expertAdvisorModel->delete($id);

        return redirect()->to('/laporan-kegiatan/expert-advisor')->with('success', 'Data Expert Advisor berhasil dihapus.');
    }
}
