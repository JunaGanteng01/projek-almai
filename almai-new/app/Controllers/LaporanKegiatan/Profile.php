<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\CompanyProfileModel;

class Profile extends BaseController
{
    public function index()
    {
        $profileModel = new CompanyProfileModel();
        $profile = $profileModel->getProfile();

        if (!$profile) {
            // Failsafe if DB row somehow doesn't exist
            $profileModel->insert(['id' => 1]);
            $profile = $profileModel->getProfile();
        }

        $data = [
            'title' => 'Profil Perusahaan - Almai',
            'activeMenu' => 'profile',
            'profile' => $profile
        ];

        return view('laporan-kegiatan/profile/index', $data);
    }

    public function create()
    {
        $profileModel = new CompanyProfileModel();
        $profile = $profileModel->getProfile();

        $data = [
            'title' => 'Edit Profil Perusahaan - Almai',
            'activeMenu' => 'profile',
            'profile' => $profile
        ];

        return view('laporan-kegiatan/profile/create', $data);
    }

    public function updateAll()
    {
        $profileModel = new CompanyProfileModel();
        
        $identitas = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'no_izin_bappebti' => $this->request->getPost('no_izin_bappebti'),
            'tanggal_izin' => $this->request->getPost('tanggal_izin'),
            'masa_berlaku' => $this->request->getPost('masa_berlaku'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kode_pos' => $this->request->getPost('kode_pos'),
            'no_telp' => $this->request->getPost('no_telp'),
            'email' => $this->request->getPost('email'),
            'website' => $this->request->getPost('website'),
        ];

        $pejabat = [
            'nama_dirut' => $this->request->getPost('nama_dirut'),
            'telp_dirut' => $this->request->getPost('telp_dirut'),
            'email_dirut' => $this->request->getPost('email_dirut'),
            'nama_kontak' => $this->request->getPost('nama_kontak'),
            'hp_kontak' => $this->request->getPost('hp_kontak'),
            'email_kontak' => $this->request->getPost('email_kontak'),
        ];

        $produk_layanan = $this->request->getPost('produk_layanan') ?? '[]';
        $produkData = json_decode($produk_layanan, true) ?? [];

        $kualifikasi = $this->request->getPost('kualifikasi_pengguna') ?? '[]';
        $kualifikasiData = json_decode($kualifikasi, true) ?? [];

        $penjelasan = [
            'wpa' => $this->request->getPost('wpa_desc'),
            'cwpa' => $this->request->getPost('cwpa_desc')
        ];

        $profileModel->updateProfile([
            'identitas' => $identitas,
            'pejabat' => $pejabat,
            'produk_layanan' => $produkData,
            'kualifikasi_pengguna' => $kualifikasiData,
            'penjelasan_wpa_cwpa' => $penjelasan
        ]);

        return redirect()->to('/laporan-kegiatan/profile')->with('success', 'Profil Perusahaan berhasil diperbarui.');
    }
}
