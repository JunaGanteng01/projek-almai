<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\AkunModel;

class Akun extends BaseController
{
    public function index()
    {
        $akunModel = new AkunModel();

        $search = $this->request->getGet('search');
        $kategori = $this->request->getGet('kategori');

        // Sorting logic
        $sortBy = $this->request->getGet('sort') ?? 'kode_akun';
        $sortOrder = $this->request->getGet('order') ?? 'ASC';

        // Validate sort column
        $allowedSorts = ['kode_akun', 'nama_akun', 'kategori', 'kode_sub_akun'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'kode_akun';
        }
        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $builder = $akunModel;

        if ($search) {
            $builder->groupStart()
                ->like('nama_akun', $search)
                ->orLike('kode_akun', $search)
                ->orLike('nama_sub_akun', $search)
                ->groupEnd();
        }

        if ($kategori && $kategori !== 'all') {
            $builder->where('kategori', $kategori);
        }

        $builder->orderBy($sortBy, $sortOrder);

        $perPage = 20;
        $akunList = $builder->paginate($perPage);

        $data = [
            'title' => 'Daftar Akun (Chart of Accounts)',
            'activeMenu' => 'akun',
            'akunList' => $akunList,
            'pager' => $akunModel->pager,
            'kategoriList' => AkunModel::KATEGORI_AKUN,
            'currentSearch' => $search,
            'currentKategori' => $kategori,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'currentPage' => $akunModel->pager->getCurrentPage() ?? 1,
            'perPage' => $perPage
        ];

        return view('keuangan/akun/index', $data);
    }

    public function save()
    {
        $akunModel = new AkunModel();
        $id = $this->request->getPost('id');

        $data = [
            'nama_akun' => $this->request->getPost('nama_akun'),
            'kode_akun' => $this->request->getPost('kode_akun'),
            'kategori' => $this->request->getPost('kategori'),
            'kode_sub_akun' => $this->request->getPost('kode_sub_akun'),
            'nama_sub_akun' => $this->request->getPost('nama_sub_akun'),
        ];

        if ($id) {
            $akunModel->update($id, $data);
            $msg = 'Akun berhasil diperbarui';
        } else {
            $akunModel->insert($data);
            $msg = 'Akun berhasil ditambahkan';
        }

        return redirect()->to('/keuangan/akun')->with('success', $msg);
    }

    public function delete($id)
    {
        if (empty($id) || $id == 0) {
            return redirect()->to('/keuangan/akun')->with('error', 'ID Akun tidak valid.');
        }

        $akunModel = new AkunModel();
        $akunModel->delete($id);
        return redirect()->to('/keuangan/akun')->with('success', 'Akun berhasil dihapus');
    }
}
