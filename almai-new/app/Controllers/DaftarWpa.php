<?php

namespace App\Controllers;

class DaftarWpa extends BaseController
{
    protected $cwpaSubmissionModel;
    protected $userModel;

    public function __construct()
    {
        $this->cwpaSubmissionModel = new \App\Models\CwpaSubmissionModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar CWPA - Almai WPA Platform',
            'meta_title' => 'Pendaftaran Calon Wakil Penasihat Berjangka - Almai',
            'meta_description' => 'Daftarkan diri Anda sebagai Calon Wakil Penasihat Berjangka (CWPA) dan dapatkan sertifikasi profesional.',
            'validation' => \Config\Services::validation(),
        ];
        return view('pages/daftar_wpa', $data);
    }

    public function store()
    {
        $validationRules = [
            'name' => 'required',
            'email' => 'required|valid_email',
            'whatsapp' => 'required',
            'trading_experience' => 'required',
            'specialties' => 'required', // Array
            'has_ijazah_s1' => 'required',
            'has_skck_card' => 'required',
            'has_clean_record' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        // If user doesn't exist, create one (simplistic approach for now)
        if (!$user) {
            // Generate temporary password or ask for one? 
            // For now, let's assume we create a basic user. 
            // In a real scenario, we'd send an email to set password.
            $password = bin2hex(random_bytes(8));

            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user',
                'active' => 1
            ];
            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();

            // Auto-login?
            session()->set('userId', $userId);
            session()->set('role', 'user');
        } else {
            $userId = $user['id'];
            // If not logged in, force login? Or assume the current session is valid?
            if (!session()->get('userId')) {
                // For security, usually we require login to link data.
                // But for "Daftar Sekarang" flow, maybe allow update if email matches?
                // Let's assume the user is either new or logged in.
                // If existing user but not logged in, we should probably redirect to login.
                return redirect()->to('/login')->with('error', 'Email sudah terdaftar. Silakan login terlebih dahulu.');
            }
        }

        // Process Specialties
        $specialties = $this->request->getPost('specialties');
        if (is_array($specialties)) {
            $specialties = json_encode($specialties);
        }

        // Process Social Media
        $socialMedia = [
            'ig' => $this->request->getPost('ig'),
            'fb' => $this->request->getPost('fb'),
            'youtube' => $this->request->getPost('youtube'),
            'tiktok' => $this->request->getPost('tiktok'),
            'linkedin' => $this->request->getPost('linkedin'),
        ];

        $submissionData = [
            'user_id' => $userId,
            'whatsapp' => $this->request->getPost('whatsapp'),
            'trading_experience' => $this->request->getPost('trading_experience'),
            'specialties' => $specialties,
            'social_media' => json_encode($socialMedia),
            'has_ijazah_s1' => $this->request->getPost('has_ijazah_s1') === 'yes' ? 1 : 0,
            'has_skck_card' => $this->request->getPost('has_skck_card') === 'yes' ? 1 : 0,
            'has_clean_record' => $this->request->getPost('has_clean_record') === 'yes' ? 1 : 0, // 'yes' from checkbox/radio
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        // Check if submission exists
        $existing = $this->cwpaSubmissionModel->where('user_id', $userId)->first();
        if ($existing) {
            $this->cwpaSubmissionModel->update($existing['id'], $submissionData);
        } else {
            $this->cwpaSubmissionModel->insert($submissionData);
        }

        return redirect()->to('/daftar-wpa/pricing');
    }

    public function pricing()
    {
        if (!session()->get('userId')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Paket Layanan CWPA - Almai',
        ];
        return view('pages/daftar_wpa_pricing', $data);
    }
}
