<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserDataModel;
use App\Models\NotificationModel;

class Kyc extends BaseController
{
    protected $session;
    protected $userModel;
    protected $userDataModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        $this->userDataModel = new UserDataModel();
    }

    public function index()
    {
        $userId = $this->session->get('userId');
        $user = $this->userModel->find($userId);
        $userData = $this->userDataModel->where('user_id', $userId)->first();

        return view('wpa/kyc', [
            'title' => 'Verifikasi KYC - WPA',
            'user' => $user,
            'userData' => $userData,
            'activeMenu' => 'dashboard'
        ]);
    }

    public function submit()
    {
        $userId = $this->session->get('userId');
        
        $rules = [
            'full_name'      => 'required',
            'nik'            => 'required|numeric',
            'npwp'           => 'required',
            'birth_place'    => 'required',
            'birth_date'     => 'required',
            'gender'         => 'required',
            'phone'          => 'required',
            'address'        => 'required',
            'province'       => 'required',
            'city'           => 'required',
            'district'       => 'required',
            'village'        => 'required',
            'postal_code'    => 'required',
            'profession'     => 'required',
            'registration_purpose' => 'required',
            'bank_name'      => 'required',
            'account_number' => 'required',
            'account_name'   => 'required',
            'experience'     => 'required',
            'trading_goal'   => 'required',
            'monthly_income' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Semua bidang wajib diisi dengan benar.');
        }

        $ktpPhoto = $this->request->getFile('ktp_photo');
        $selfiePhoto = $this->request->getFile('selfie_photo');

        $data = [
            'user_id'        => $userId,
            'full_name'      => $this->request->getPost('full_name'),
            'nik'            => $this->request->getPost('nik'),
            'npwp'           => $this->request->getPost('npwp'),
            'birth_place'    => $this->request->getPost('birth_place'),
            'birth_date'     => $this->request->getPost('birth_date'),
            'gender'         => $this->request->getPost('gender'),
            'phone'          => $this->request->getPost('phone'),
            'address'        => $this->request->getPost('address'),
            'province'       => $this->request->getPost('province'),
            'city'           => $this->request->getPost('city'),
            'district'       => $this->request->getPost('district'),
            'village'        => $this->request->getPost('village'),
            'postal_code'    => $this->request->getPost('postal_code'),
            'profession'     => $this->request->getPost('profession'),
            'registration_purpose' => $this->request->getPost('registration_purpose'),
            'bank_name'      => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_name'   => $this->request->getPost('account_name'),
            'experience'     => $this->request->getPost('experience'),
            'trading_goal'   => $this->request->getPost('trading_goal'),
            'monthly_income' => $this->request->getPost('monthly_income'),
            'risk_profile'   => $this->request->getPost('risk_profile'),
        ];

        if ($ktpPhoto && $ktpPhoto->isValid() && !$ktpPhoto->hasMoved()) {
            $newName = $ktpPhoto->getRandomName();
            $ktpPhoto->move(FCPATH . 'uploads/kyc', $newName);
            $data['ktp_photo'] = 'uploads/kyc/' . $newName;
        }

        if ($selfiePhoto && $selfiePhoto->isValid() && !$selfiePhoto->hasMoved()) {
            $newName = $selfiePhoto->getRandomName();
            $selfiePhoto->move(FCPATH . 'uploads/kyc', $newName);
            $data['selfie_photo'] = 'uploads/kyc/' . $newName;
        }

        $existingData = $this->userDataModel->where('user_id', $userId)->first();
        if ($existingData) {
            $this->userDataModel->update($existingData['id'], $data);
        } else {
            $this->userDataModel->insert($data);
        }

        $this->userModel->update($userId, ['kyc_status' => 'pending']);

        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $userId,
            'Pengajuan KYC Terkirim 📄',
            'Data KYC WPA Anda telah kami terima dan sedang dalam antrean verifikasi.',
            'info',
            '/wpa/dashboard'
        );

        return redirect()->to('/wpa/dashboard')->with('success', 'Data KYC berhasil dikirim! Mohon tunggu verifikasi admin.');
    }
}
