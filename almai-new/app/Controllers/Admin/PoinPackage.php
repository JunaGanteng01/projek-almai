<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PoinPackageModel;

class PoinPackage extends BaseController
{
    protected $poinPackageModel;

    public function __construct()
    {
        $this->poinPackageModel = new PoinPackageModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Harga Poin',
            'activeMenu' => 'poin_package',
            'packages' => $this->poinPackageModel->orderBy('amount', 'ASC')->findAll()
        ];

        return view('admin/poin-package/index', $data);
    }

    public function create()
    {
        $rules = [
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Semua field harus diisi dengan angka');
        }

        $this->poinPackageModel->save([
            'amount' => $this->request->getPost('amount'),
            'price' => $this->request->getPost('price'),
            'is_enabled' => $this->request->getPost('is_enabled') ?? 1
        ]);

        return redirect()->to('/admin/poin-package')->with('success', 'Paket poin berhasil ditambahkan');
    }

    public function update($id)
    {
        $rules = [
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Semua field harus diisi dengan angka');
        }

        $this->poinPackageModel->update($id, [
            'amount' => $this->request->getPost('amount'),
            'price' => $this->request->getPost('price'),
            'is_enabled' => $this->request->getPost('is_enabled') ?? 0
        ]);

        return redirect()->to('/admin/poin-package')->with('success', 'Paket poin berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->poinPackageModel->delete($id);
        return redirect()->to('/admin/poin-package')->with('success', 'Paket poin berhasil dihapus');
    }

    public function toggle($id)
    {
        $package = $this->poinPackageModel->find($id);
        if ($package) {
            $this->poinPackageModel->update($id, [
                'is_enabled' => $package['is_enabled'] == 1 ? 0 : 1
            ]);
        }
        return redirect()->to('/admin/poin-package')->with('success', 'Status paket berhasil diubah');
    }
}
