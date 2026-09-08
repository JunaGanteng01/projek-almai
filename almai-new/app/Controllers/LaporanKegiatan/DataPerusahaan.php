<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\DataPerusahaanModel;

class DataPerusahaan extends BaseController
{
    public function index()
    {
        $model = new DataPerusahaanModel();
        
        $data = [
            'title' => 'Data Perusahaan',
            'activeMenu' => 'data-perusahaan',
            'perusahaans' => $model->orderBy('id', 'DESC')->findAll()
        ];
        
        return view('laporan-kegiatan/data_perusahaan/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Tambah Data Perusahaan',
            'activeMenu' => 'data-perusahaan'
        ];
        
        return view('laporan-kegiatan/data_perusahaan/create', $data);
    }
    
    public function store()
    {
        $model = new DataPerusahaanModel();
        
        $data = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'nomor_izin' => $this->request->getPost('nomor_izin'),
            'alamat' => $this->request->getPost('alamat'),
            'website' => $this->request->getPost('website'),
            'direktur_utama' => $this->request->getPost('direktur_utama'),
            'status' => $this->request->getPost('status') ?? 'active',
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        
        $model->insert($data);
        
        return redirect()->to(base_url('laporan-kegiatan/data-perusahaan'))->with('success', 'Data Perusahaan berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $model = new DataPerusahaanModel();
        
        $perusahaan = $model->find($id);
        if (!$perusahaan) {
            return redirect()->to(base_url('laporan-kegiatan/data-perusahaan'))->with('error', 'Data tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Data Perusahaan',
            'activeMenu' => 'data-perusahaan',
            'perusahaan' => $perusahaan
        ];
        
        return view('laporan-kegiatan/data_perusahaan/edit', $data);
    }
    
    public function update($id)
    {
        $model = new DataPerusahaanModel();
        
        $data = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'nomor_izin' => $this->request->getPost('nomor_izin'),
            'alamat' => $this->request->getPost('alamat'),
            'website' => $this->request->getPost('website'),
            'direktur_utama' => $this->request->getPost('direktur_utama'),
            'status' => $this->request->getPost('status') ?? 'active',
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        
        $model->update($id, $data);
        
        return redirect()->to(base_url('laporan-kegiatan/data-perusahaan'))->with('success', 'Data Perusahaan berhasil diperbarui');
    }
    
    public function delete($id)
    {
        $model = new DataPerusahaanModel();
        $model->delete($id);
        
        return redirect()->to(base_url('laporan-kegiatan/data-perusahaan'))->with('success', 'Data Perusahaan berhasil dihapus');
    }
}
