<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\AkunModel;
use App\Models\KontakModel;

class Kontak extends BaseController
{
    protected KontakModel $kontakModel;

    public function __construct()
    {
        $this->kontakModel = new KontakModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $tipe = $this->request->getGet('tipe') ?? 'all';

        $kontakList = $this->kontakModel
            ->getWithFilters(['search' => $search, 'tipe' => $tipe])
            ->paginate(20);

        return view('keuangan/kontak/index', [
            'title' => 'Buku Kontak',
            'activeMenu' => 'kontak',
            'kontakList' => $kontakList,
            'pager' => $this->kontakModel->pager,
            'search' => $search,
            'tipe' => $tipe,
            'akunList' => (new AkunModel())->orderBy('kode_akun', 'ASC')->findAll(),
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $data = [
            'tipe' => $this->request->getPost('tipe'),
            'nama' => $this->request->getPost('nama'),
            'perusahaan' => $this->request->getPost('perusahaan'),
            'email' => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'alamat' => $this->request->getPost('alamat'),
            'npwp' => $this->request->getPost('npwp'),
            'kode_akun_piutang' => $this->request->getPost('kode_akun_piutang') ?: null,
            'kode_akun_hutang' => $this->request->getPost('kode_akun_hutang') ?: null,
            'is_active' => 1,
        ];

        if ($id) {
            $this->kontakModel->update($id, $data);
            $msg = 'Kontak berhasil diperbarui';
        } else {
            $this->kontakModel->insert($data);
            $msg = 'Kontak berhasil ditambahkan';
        }

        return redirect()->to('/keuangan/kontak')->with('success', $msg);
    }

    public function delete($id)
    {
        $this->kontakModel->update($id, ['is_active' => 0]);
        return redirect()->to('/keuangan/kontak')->with('success', 'Kontak dinonaktifkan');
    }

    public function get($id)
    {
        $kontak = $this->kontakModel->find($id);
        if ($kontak) {
            return $this->response->setJSON(['status' => 'success', 'data' => $kontak]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }
}
