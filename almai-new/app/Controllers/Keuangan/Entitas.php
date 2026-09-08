<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\EntitasAkuntansiModel;

class Entitas extends BaseController
{
    public function index()
    {
        $model = new EntitasAkuntansiModel();
        $entitas = $model->getProfile();

        return view('keuangan/entitas/index', [
            'title' => 'Profil Entitas',
            'activeMenu' => 'entitas',
            'entitas' => $entitas,
        ]);
    }

    public function save()
    {
        $model = new EntitasAkuntansiModel();
        $entitas = $model->getProfile();

        $data = [
            'nama_entitas' => $this->request->getPost('nama_entitas'),
            'nama_pendek' => $this->request->getPost('nama_pendek'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'npwp' => $this->request->getPost('npwp'),
            'telepon' => $this->request->getPost('telepon'),
            'email' => $this->request->getPost('email'),
            'mata_uang' => $this->request->getPost('mata_uang') ?: 'IDR',
            'bulan_awal_fiskal' => (int) $this->request->getPost('bulan_awal_fiskal'),
            'catatan_laporan' => $this->request->getPost('catatan_laporan'),
        ];

        if ($entitas) {
            $model->update($entitas['id'], $data);
        } else {
            $model->insert($data);
        }

        return redirect()->to('/keuangan/entitas')->with('success', 'Profil entitas berhasil disimpan');
    }
}
