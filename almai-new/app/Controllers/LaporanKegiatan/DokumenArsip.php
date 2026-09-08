<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\DokLegalitasModel;
use App\Models\DokIzinWpaModel;
use App\Models\DokBahanKegiatanModel;
use App\Models\ReferensiRegulasiModel;
use App\Models\WpaModel;

class DokumenArsip extends BaseController
{
    protected $legalitasModel;
    protected $izinWpaModel;
    protected $bahanKegiatanModel;
    protected $regulasiModel;
    protected $wpaModel;

    public function __construct()
    {
        $this->legalitasModel = new DokLegalitasModel();
        $this->izinWpaModel = new DokIzinWpaModel();
        $this->bahanKegiatanModel = new DokBahanKegiatanModel();
        $this->regulasiModel = new ReferensiRegulasiModel();
        $this->wpaModel = new WpaModel();
    }

    public function index()
    {
        // Get active tab from URL query, default to tab1
        $activeTab = $this->request->getGet('tab') ?? 'legalitas';

        // Fetch data
        $legalitasData = $this->legalitasModel->orderBy('tanggal_terbit', 'DESC')->findAll();
        
        // For Izin WPA, join with wpa table
        $db = \Config\Database::connect();
        $izinWpaData = $db->table('dok_izin_wpa')
            ->select('dok_izin_wpa.*, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = dok_izin_wpa.wpa_id', 'left')
            ->orderBy('dok_izin_wpa.tanggal_izin', 'DESC')
            ->get()->getResultArray();

        $bahanKegiatanData = $this->bahanKegiatanModel->orderBy('tgl_pengajuan', 'DESC')->findAll();
        $regulasiData = $this->regulasiModel->orderBy('id', 'ASC')->findAll();
        $wpaList = $this->wpaModel->findAll();

        return view('laporan-kegiatan/dokumen-arsip/index', [
            'title' => 'Dokumen Arsip & Referensi - Almai',
            'activeMenu' => 'dokumen-arsip',
            'activeTab' => $activeTab,
            'legalitasData' => $legalitasData,
            'izinWpaData' => $izinWpaData,
            'bahanKegiatanData' => $bahanKegiatanData,
            'regulasiData' => $regulasiData,
            'wpaList' => $wpaList
        ]);
    }

    // --- CRUD LEGALITAS ---
    public function createLegalitas()
    {
        return view('laporan-kegiatan/dokumen-arsip/form_legalitas', [
            'title' => 'Tambah Dokumen Legalitas',
            'activeMenu' => 'dokumen-arsip',
            'row' => null
        ]);
    }

    public function editLegalitas($id)
    {
        $row = $this->legalitasModel->find($id);
        if (!$row) return redirect()->to('/laporan-kegiatan/dokumen-arsip');
        
        return view('laporan-kegiatan/dokumen-arsip/form_legalitas', [
            'title' => 'Edit Dokumen Legalitas',
            'activeMenu' => 'dokumen-arsip',
            'row' => $row
        ]);
    }

    public function storeLegalitas()
    {
        $data = $this->request->getPost();
        
        $file = $this->request->getFile('file_path');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/dokumen_arsip', $newName);
            $data['file_path'] = 'uploads/dokumen_arsip/' . $newName;
        }

        $this->legalitasModel->insert($data);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip?tab=legalitas')->with('success', 'Dokumen Legalitas berhasil ditambahkan.');
    }

    public function updateLegalitas($id)
    {
        $data = $this->request->getPost();
        
        $file = $this->request->getFile('file_path');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/dokumen_arsip', $newName);
            $data['file_path'] = 'uploads/dokumen_arsip/' . $newName;
        }

        $this->legalitasModel->update($id, $data);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip?tab=legalitas')->with('success', 'Dokumen Legalitas berhasil diupdate.');
    }

    public function deleteLegalitas($id)
    {
        $this->legalitasModel->delete($id);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip?tab=legalitas')->with('success', 'Dokumen Legalitas berhasil dihapus.');
    }

    // --- CRUD IZIN WPA ---
    public function createIzinWpa()
    {
        return view('laporan-kegiatan/dokumen-arsip/form_izin_wpa', [
            'title' => 'Tambah Dokumen Izin WPA',
            'activeMenu' => 'dokumen-arsip',
            'wpaList' => $this->wpaModel->findAll(),
            'row' => null
        ]);
    }

    public function editIzinWpa($id)
    {
        $row = $this->izinWpaModel->find($id);
        if (!$row) return redirect()->to('/laporan-kegiatan/dokumen-arsip');
        
        return view('laporan-kegiatan/dokumen-arsip/form_izin_wpa', [
            'title' => 'Edit Dokumen Izin WPA',
            'activeMenu' => 'dokumen-arsip',
            'wpaList' => $this->wpaModel->findAll(),
            'row' => $row
        ]);
    }

    public function storeIzinWpa()
    {
        $this->izinWpaModel->insert($this->request->getPost());
        return redirect()->to('/laporan-kegiatan/dokumen-arsip?tab=izin_wpa')->with('success', 'Dokumen Izin WPA berhasil ditambahkan.');
    }

    public function updateIzinWpa($id)
    {
        $this->izinWpaModel->update($id, $this->request->getPost());
        return redirect()->to('/laporan-kegiatan/dokumen-arsip?tab=izin_wpa')->with('success', 'Dokumen Izin WPA berhasil diupdate.');
    }

    public function deleteIzinWpa($id)
    {
        $this->izinWpaModel->delete($id);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip?tab=izin_wpa')->with('success', 'Dokumen Izin WPA berhasil dihapus.');
    }

    // --- CRUD BAHAN KEGIATAN ---
    public function createBahanKegiatan()
    {
        return view('laporan-kegiatan/dokumen-arsip/form_bahan_kegiatan', [
            'title' => 'Tambah Bahan Kegiatan',
            'activeMenu' => 'dokumen-arsip',
            'row' => null
        ]);
    }

    public function editBahanKegiatan($id)
    {
        $row = $this->bahanKegiatanModel->find($id);
        if (!$row) return redirect()->to('/laporan-kegiatan/dokumen-arsip');
        
        return view('laporan-kegiatan/dokumen-arsip/form_bahan_kegiatan', [
            'title' => 'Edit Bahan Kegiatan',
            'activeMenu' => 'dokumen-arsip',
            'row' => $row
        ]);
    }

    public function storeBahanKegiatan()
    {
        $data = $this->request->getPost();
        
        $file = $this->request->getFile('file_path');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/dokumen_arsip', $newName);
            $data['file_path'] = 'uploads/dokumen_arsip/' . $newName;
        }

        $this->bahanKegiatanModel->insert($data);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip')->with('success', 'Dokumen Bahan Kegiatan berhasil ditambahkan.');
    }

    public function updateBahanKegiatan($id)
    {
        $data = $this->request->getPost();
        
        $file = $this->request->getFile('file_path');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/dokumen_arsip', $newName);
            $data['file_path'] = 'uploads/dokumen_arsip/' . $newName;
        }

        $this->bahanKegiatanModel->update($id, $data);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip')->with('success', 'Dokumen Bahan Kegiatan berhasil diupdate.');
    }

    public function deleteBahanKegiatan($id)
    {
        $this->bahanKegiatanModel->delete($id);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip')->with('success', 'Dokumen Bahan Kegiatan berhasil dihapus.');
    }

    // --- CRUD REFERENSI REGULASI ---
    public function createRegulasi()
    {
        return view('laporan-kegiatan/dokumen-arsip/form_regulasi', [
            'title' => 'Tambah Referensi Regulasi',
            'activeMenu' => 'dokumen-arsip',
            'row' => null
        ]);
    }

    public function editRegulasi($id)
    {
        $row = $this->regulasiModel->find($id);
        if (!$row) return redirect()->to('/laporan-kegiatan/dokumen-arsip');
        
        return view('laporan-kegiatan/dokumen-arsip/form_regulasi', [
            'title' => 'Edit Referensi Regulasi',
            'activeMenu' => 'dokumen-arsip',
            'row' => $row
        ]);
    }

    public function storeRegulasi()
    {
        $this->regulasiModel->insert($this->request->getPost());
        return redirect()->to('/laporan-kegiatan/dokumen-arsip')->with('success', 'Referensi Regulasi berhasil ditambahkan.');
    }

    public function updateRegulasi($id)
    {
        $this->regulasiModel->update($id, $this->request->getPost());
        return redirect()->to('/laporan-kegiatan/dokumen-arsip')->with('success', 'Referensi Regulasi berhasil diupdate.');
    }

    public function deleteRegulasi($id)
    {
        $this->regulasiModel->delete($id);
        return redirect()->to('/laporan-kegiatan/dokumen-arsip')->with('success', 'Referensi Regulasi berhasil dihapus.');
    }
}
