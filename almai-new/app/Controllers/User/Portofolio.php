<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ProfirmEaAccountModel;
use App\Models\UserModel;

class Portofolio extends BaseController
{
    private function checkPro()
    {
        $userId = session()->get('userId');
        if (!$userId) return false;

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        // Allowed for Pro level (2) or higher (CWPA, WPA, etc.)
        return (isset($user['level_id']) && $user['level_id'] >= \App\Models\LevelModel::LEVEL_PRO) || (isset($user['is_pro']) && $user['is_pro'] == 1);
    }

    public function index()
    {
        if (!$this->checkPro()) {
            return view('user/portofolio/locked', ['title' => 'Portofolio Terkunci']);
        }

        $userId = session()->get('userId');
        $model = new ProfirmEaAccountModel();
        
        $accounts = $model->where('user_id', $userId)->findAll();
        
        foreach ($accounts as &$acc) {
            $lastUpdate = strtotime($acc['updated_at']);
            $diff = time() - $lastUpdate;
            $acc['is_online'] = ($diff < 300);
        }

        $data = [
            'title' => 'Manajemen Portofolio - ALMAI',
            'activeMenu' => 'portofolio',
            'accounts' => $accounts,
            'count' => count($accounts)
        ];

        return view('user/portofolio/index', $data);
    }

    public function create()
    {
        if (!$this->checkPro()) {
            return redirect()->to('/user/dashboard/portofolio');
        }

        $userId = session()->get('userId');
        $model = new ProfirmEaAccountModel();
        $count = $model->where('user_id', $userId)->countAllResults();

        if ($count >= 3) {
            return redirect()->to('/user/dashboard/portofolio')->with('error', 'Batas maksimal portofolio adalah 3 akun.');
        }

        $data = [
            'title' => 'Buat Portofolio Baru',
            'activeMenu' => 'portofolio'
        ];
        return view('user/portofolio/create', $data);
    }

    public function store()
    {
        if (!$this->checkPro()) return redirect()->to('/login');

        $userId = session()->get('userId');

        $rules = [
            'account_name'  => 'required|min_length[3]|max_length[100]',
            'account_login' => 'required|numeric',
            'logo'          => 'permit_empty|uploaded[logo]|max_size[logo,2048]|is_image[logo]|mime_in[logo,image/png,image/jpg,image/jpeg]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi semua field dengan benar.');
        }

        $model = new ProfirmEaAccountModel();

        $count = $model->where('user_id', $userId)->countAllResults();
        $loginCheck = $this->request->getPost('account_login');
        $isExistingOwn = $model->where('account_login', $loginCheck)->where('user_id', $userId)->first();
        
        if ($count >= 3 && !$isExistingOwn) {
            return redirect()->back()->withInput()->with('error', 'Penyimpanan gagal. Anda sudah mencapai batas maksimal 3 portofolio.');
        }
        
        $login = $this->request->getPost('account_login');
        $existing = $model->where('account_login', $login)->first();

        // Handle logo upload
        $logoPath = null;
        $logoFile = $this->request->getFile('logo');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $newName = 'portfolio_' . $login . '_' . time() . '.' . $logoFile->getExtension();
            $logoFile->move(WRITEPATH . 'uploads/portfolio_logos', $newName);
            $logoPath = 'writable/uploads/portfolio_logos/' . $newName;
        }

        $saveData = [
            'user_id'       => $userId,
            'account_name'  => $this->request->getPost('account_name'),
            'community_name'=> $this->request->getPost('community_name'),
            'logo'          => $logoPath,
            'account_login' => $login,
            'broker'        => $existing['broker'] ?? 'Unknown',
            'server'        => $existing['server'] ?? 'Unknown',
            'balance'       => 0,
            'equity'        => 0,
            'total_profit'  => 0,
            'total_deposits'=> 0,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            if (empty($existing['user_id'])) {
                $model->update($existing['id'], $saveData);
            } else if ($existing['user_id'] != $userId) {
                return redirect()->back()->withInput()->with('error', 'Nomor akun ini sudah terdaftar oleh pengguna lain.');
            } else {
                // Keep existing logo if no new upload
                if (!$logoPath && isset($existing['logo'])) {
                    $saveData['logo'] = $existing['logo'];
                }
                $model->update($existing['id'], $saveData);
            }
        } else {
            $model->insert($saveData);
        }

        try {
            $fileName = "portofolio-almai.ex5";
            session()->setFlashdata('download_ea', base_url('portofolio_eas/' . $fileName));
        } catch (\Exception $e) {}

        return redirect()->to('/user/dashboard/portofolio')->with('success', 'Portofolio berhasil dibuat!');
    }

    public function delete($id)
    {
        if (!$this->checkPro()) return redirect()->to('/login');

        $userId = session()->get('userId');
        $model = new ProfirmEaAccountModel();
        
        $acc = $model->where('id', $id)->where('user_id', $userId)->first();
        if ($acc) {
            $model->delete($id);
            return redirect()->to('/user/dashboard/portofolio')->with('success', 'Portofolio berhasil dihapus.');
        }
        
        return redirect()->to('/user/dashboard/portofolio')->with('error', 'Akses ditolak.');
    }
}
