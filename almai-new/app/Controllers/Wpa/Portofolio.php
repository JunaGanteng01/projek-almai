<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\ProfirmEaAccountModel;

class Portofolio extends BaseController
{
    public function index()
    {
        $wpaId = session()->get('wpaId');
        if (!$wpaId) return redirect()->to('/login');

        $userId = session()->get('userId');
        
        if (!$userId) {
            $wpaModel = new \App\Models\WpaModel();
            $wpa = $wpaModel->find($wpaId);
            $userId = $wpa['user_id'] ?? null;
        }

        if (!$userId) return redirect()->to('/login');

        $model = new ProfirmEaAccountModel();
        
        $accounts = $model->where('user_id', $userId)->findAll();
        
        // Loop untuk cek status online
        foreach ($accounts as &$acc) {
            $lastUpdate = strtotime($acc['updated_at']);
            $diff = time() - $lastUpdate;
            $acc['is_online'] = ($diff < 300); // Online jika update < 5 menit
        }

        $data = [
            'title' => 'Manajemen Portofolio - ALMAI',
            'activeMenu' => 'portofolio',
            'accounts' => $accounts,
            'count' => count($accounts)
        ];

        return view('wpa/portofolio/index', $data);
    }

    public function create()
    {
        $userId = session()->get('userId');
        $model = new ProfirmEaAccountModel();
        $count = $model->where('user_id', $userId)->countAllResults();

        if ($count >= 3) {
            return redirect()->to('/wpa/dashboard/portofolio')->with('error', 'Batas maksimal portofolio adalah 3 akun. Hapus salah satu jika ingin menambah baru.');
        }

        $data = [
            'title' => 'Buat Portofolio Baru',
            'activeMenu' => 'portofolio'
        ];
        return view('wpa/portofolio/create', $data);
    }

    public function store()
    {
        $wpaId = session()->get('wpaId');
        $userId = session()->get('userId');

        if (!$userId && $wpaId) {
            $wpaModel = new \App\Models\WpaModel();
            $wpa = $wpaModel->find($wpaId);
            $userId = $wpa['user_id'] ?? null;
        }

        if (!$userId) return redirect()->to('/login');

        $rules = [
            'account_name'  => 'required|min_length[3]|max_length[100]',
            'account_login' => 'required|numeric',
            'broker'        => 'required',
            'server'        => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi semua field dengan benar.');
        }

        $model = new ProfirmEaAccountModel();

        // Limit Check: Masih batas 3
        $count = $model->where('user_id', $userId)->countAllResults();
        $loginCheck = $this->request->getPost('account_login');
        $isExistingOwn = $model->where('account_login', $loginCheck)->where('user_id', $userId)->first();
        
        if ($count >= 3 && !$isExistingOwn) {
            return redirect()->back()->withInput()->with('error', 'Penyimpanan gagal. Anda sudah mencapai batas maksimal 3 portofolio.');
        }
        
        $login = $this->request->getPost('account_login');
        $existing = $model->where('account_login', $login)->first();

        $saveData = [
            'user_id'       => $userId,
            'account_name'  => $this->request->getPost('account_name'),
            'community_name'=> $this->request->getPost('community_name'),
            'account_login' => $login,
            'broker'        => $this->request->getPost('broker'),
            'server'        => $this->request->getPost('server'),
            'balance'       => 0,
            'equity'        => 0,
            'total_profit'  => 0,
            'total_deposits'=> 0,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            // Jika akun sudah ada tapi belum ada user_id, klaim akun ini mjd milik user
            if (empty($existing['user_id'])) {
                $model->update($existing['id'], $saveData);
            } else if ($existing['user_id'] != $userId) {
                return redirect()->back()->withInput()->with('error', 'Nomor akun ini sudah terdaftar oleh pengguna lain.');
            } else {
                $model->update($existing['id'], $saveData);
            }
        } else {
            $model->insert($saveData);
        }

        // --- Logic: Direct Download .ex5 EA ---
        try {
            $fileName = "portofolio-almai.ex5";
            session()->setFlashdata('download_ea', base_url('portofolio_eas/' . $fileName));
        } catch (\Exception $e) {
            // Silently fail
        }

        return redirect()->to('/wpa/dashboard/portofolio')->with('success', 'Portofolio berhasil dibuat! Silakan download EA di bawah dan pasang di MT5.');
    }

    public function delete($id)
    {
        $userId = session()->get('userId');
        $model = new ProfirmEaAccountModel();
        
        $acc = $model->where('id', $id)->where('user_id', $userId)->first();
        if ($acc) {
            $model->delete($id);
            return redirect()->to('/wpa/dashboard/portofolio')->with('success', 'Portofolio berhasil dihapus.');
        }
        
        return redirect()->to('/wpa/dashboard/portofolio')->with('error', 'Akses ditolak.');
    }
}
