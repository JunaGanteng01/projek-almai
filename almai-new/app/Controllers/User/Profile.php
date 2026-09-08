<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function update()
    {
        $userId = session()->get('userId');

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => [
                'rules'  => "permit_empty|valid_email|is_unique[users.email,id,{$userId}]",
                'errors' => [
                    'is_unique' => 'Email ini sudah digunakan oleh akun lain.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ],
            'phone' => 'permit_empty|min_length[10]',
            'address' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            $errors = $validation->getErrors();
            $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Data tidak valid';
            return redirect()->back()->with('error', $errorMessage);
        }

        $userModel = new UserModel();

        $dataToUpdate = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        // Jika email di-post, update (khususnya untuk user yang belum verify)
        if ($this->request->getPost('email')) {
            $dataToUpdate['email'] = $this->request->getPost('email');
        }

        $userModel->update($userId, $dataToUpdate);

        // Update session
        session()->set('userName', $this->request->getPost('name'));
        session()->set('address', $this->request->getPost('address'));

        return redirect()->to('/user/profile')->with('success', 'Profile berhasil diupdate!');
    }

    public function changePassword()
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Password tidak valid. Minimal 8 karakter.');
        }

        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!password_verify($this->request->getPost('old_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah');
        }

        $userModel->update($userId, [
            'password' => $this->request->getPost('new_password'),
        ]);

        return redirect()->to('/user/profile')->with('success', 'Password berhasil diubah!');
    }

    public function avatar()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        
        $avatar = $this->request->getFile('avatar');
        
        if (!$avatar || !$avatar->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }
        
        // Validate file size (max 2MB)
        if ($avatar->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB');
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($avatar->getMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Format file harus JPG, PNG, GIF, atau WEBP');
        }
        
        // Delete old avatar if exists
        $user = $userModel->find($userId);
        if ($user['avatar'] && file_exists(WRITEPATH . $user['avatar'])) {
            unlink(WRITEPATH . $user['avatar']);
        }
        
        // Upload new avatar
        $newName = 'avatar_' . $userId . '_' . time() . '.' . $avatar->getExtension();
        $avatar->move(WRITEPATH . 'uploads/avatars', $newName);
        $avatarPath = 'uploads/avatars/' . $newName;
        
        // Update database
        $userModel->update($userId, ['avatar' => $avatarPath]);
        
        return redirect()->to('/user/profile')->with('success', 'Foto profil berhasil diupdate!');
    }
    public function sendOtp()
    {
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        try {
            $json = $this->request->getJSON(true);
            $channel = $json['channel'] ?? 'whatsapp';
            
            $userId = session()->get('userId');
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
            }

            if ($channel === 'whatsapp') {
                $phone = !empty($json['value']) ? $json['value'] : $user['phone'];
                if (empty($phone) || strlen($phone) < 10) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Nomor WhatsApp tidak valid. Silakan isi dengan benar.']);
                }

                $otpModel = new \App\Models\OtpModel();
                $otp = $otpModel->generateOtp($phone, 'profile_verification', 'whatsapp');

                // Send via Custom WA Gateway
                $message = "Halo {$user['name']}!\n\nKode OTP Verifikasi ALMAI Anda adalah: *{$otp}*\n\nJangan berikan kode ini kepada siapapun.";
                
                $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                $waApiKey = trim(env('WAGW_API_KEY', ''));
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $waUrl . '/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'phone' => $phone,
                        'message' => $message
                    ]),
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'x-api-key: ' . $waApiKey
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal terhubung ke WhatsApp Gateway']);
                }

                $resJson = json_decode($response, true);
                if (!$resJson || !isset($resJson['success']) || !$resJson['success']) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim OTP WhatsApp. Pastikan Gateway terhubung.']);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'OTP berhasil dikirim ke WhatsApp',
                    'channel' => 'whatsapp'
                ]);

            } else {
                // Email
                $email = !empty($json['value']) ? trim($json['value']) : $user['email'];
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Email tidak valid: [' . $email . '] Debug JSON: ' . json_encode($json)]);
                }
                
                $otpModel = new \App\Models\OtpModel();
                $otp = $otpModel->generateOtp($email, 'profile_verification', 'email');

                $emailService = new \App\Libraries\EmailService();
                $resp = $emailService->sendOtp($email, $user['name'] ?: 'User', $otp);

                if ($resp['success']) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'OTP berhasil dikirim ke Email',
                        'channel' => 'email'
                    ]);
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim Email OTP']);
                }
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . (ENVIRONMENT === 'development' ? $e->getMessage() : 'Silakan coba lagi.')
            ]);
        }
    }

    public function verifyOtp()
    {
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $json = $this->request->getJSON(true);
            $otp = $json['otp'] ?? '';
            $channel = $json['channel'] ?? 'whatsapp';
            $inputValue = $json['value'] ?? '';
            
            $userId = session()->get('userId');
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
            }

            $otpModel = new \App\Models\OtpModel();
            $valid = false;

            if ($channel === 'whatsapp') {
                $phone = !empty($inputValue) ? $inputValue : $user['phone'];
                $valid = $otpModel->verifyOtp($phone, $otp, 'profile_verification');
                if ($valid) {
                    $userModel->update($userId, [
                        'phone' => $phone,
                        'otp_status' => 1
                    ]);
                }
            } else {
                $email = !empty($inputValue) ? $inputValue : $user['email'];
                $valid = $otpModel->verifyOtp($email, $otp, 'profile_verification');
                if ($valid) {
                    $userModel->update($userId, [
                        'email' => $email,
                        'email_verified_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            if (!$valid) {
                return $this->response->setJSON(['success' => false, 'message' => 'Verifikasi gagal. Kode tidak valid atau sudah kadaluarsa.']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi ' . ucfirst($channel) . ' berhasil!',
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . (ENVIRONMENT === 'development' ? $e->getMessage() : 'Silakan coba lagi.')
            ]);
        }
    }
}
