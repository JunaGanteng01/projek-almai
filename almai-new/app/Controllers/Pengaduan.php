<?php

namespace App\Controllers;

use App\Models\LayananPengaduanModel;
use App\Models\TransaksiModel;
use CodeIgniter\Controller;

class Pengaduan extends BaseController
{
    /**
     * Display the multi-step complaint form
     */
    public function index()
    {
        $userId = session()->get('userId');
        $userRole = session()->get('role');
        $isPro = false;
        $isMember = false;

        // Admins, WPA, CWPA, and other staff have automatic access
        if (\App\Models\LevelModel::isStaffLevel(\App\Models\LevelModel::resolveLevelId(session()))) {
            $isPro = true;
            $isMember = true;
        }

        $userData = null;
        if ($userId) {
            $userModel = model('UserModel');
            $userData = $userModel->find($userId);
            
            if ($userData && !$isPro) {
                $isPro = (bool)($userData['is_pro'] ?? false);
            }

            // Check if user has active Advocacy Membership (Layanan 9999)
            $transaksiModel = new TransaksiModel();
            $membership = $transaksiModel->where('user_id', $userId)
                                         ->where('layanan_id', 9999)
                                         ->where('status', 'confirmed')
                                         ->first();
            if ($membership) {
                $isMember = true;
                
                // If already purchased, redirect to their dashboard to prevent double purchase
                $target = '/user/advokasi';
                if ($userRole === 'cwpa') $target = '/cwpa/dashboard/advokasi';
                if ($userRole === 'wpa') $target = '/wpa/dashboard/advokasi';
                
                return redirect()->to($target)->with('info', 'Anda sudah membeli layanan advokasi');
            }
        }

        $data = [
            'title' => 'Daftar Advokasi',
            'isLoggedIn' => session()->get('isLoggedIn'),
            'isPro' => $isPro,
            'isMember' => $isMember,
            'user' => $userData
        ];

        return view('pages/pengaduan/index', $data);
    }

    public function submit()
    {
        $postData = $this->request->getPost();
        
        // Validate basic fields
        $rules = [
            'name'          => 'required|min_length[3]',
            'email'         => 'required|valid_email',
            'whatsapp'      => 'required|min_length[10]',
        ];

        // Manual validation
        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($postData)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Lengkapi data wajib (*).'
            ])->setStatusCode(400);
        }

        // Check if email already has active membership
        $transaksiModel = new TransaksiModel();
        $userModel = model('UserModel');
        $existingUser = $userModel->where('email', $postData['email'])->first();
        if ($existingUser) {
            $membership = $transaksiModel->where('user_id', $existingUser['id'])
                                         ->where('layanan_id', 9999)
                                         ->where('status', 'confirmed')
                                         ->first();
            if ($membership) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda sudah membeli layanan advokasi. Silakan login untuk mengakses materi.'
                ]);
            }
        }

        // Prepare data for model (Simple registration)
        $complaintData = [
            'user_id'           => session()->get('userId'),
            'name'              => $postData['name'],
            'email'             => $postData['email'],
            'whatsapp'          => $postData['whatsapp'],
            'status'            => 'pending_payment',
            'referrer_id'       => $this->getReferrerId(),
            'referral_code'     => session()->get('checkout_ref') ?? $this->request->getGet('ref'),
        ];

        // Save to Database
        try {
            $model = new LayananPengaduanModel();
            if (!$model->insert($complaintData)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan data.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan sistem.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pendaftaran berhasil. Melanjutkan ke pembayaran...',
            'redirect' => base_url('checkout/layanan/advokasi')
        ]);
    }

    private function getReferrerId()
    {
        $refCode = session()->get('checkout_ref') ?? $this->request->getGet('ref');
        if (!$refCode) return null;

        $userModel = model('UserModel');
        $referrer = $userModel->where('code_referral', $refCode)->first();
        return $referrer ? $referrer['id'] : null;
    }
}
