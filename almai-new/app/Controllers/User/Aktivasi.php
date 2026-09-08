<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Aktivasi extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('userId');

        // Fetch active services
        $active_services = $db->table('user_active_services')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Aktivasi Layanan',
            'active_services' => $active_services
        ];

        return view('user/aktivasi/index', $data);
    }

    public function redeem()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('userId');
        
        // Simple rate limiting
        $attempts = session()->get('aktivasi_attempts') ?? 0;
        $lockUntil = session()->get('lock_until') ?? 0;

        if (time() < $lockUntil) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan. Silakan coba lagi nanti.');
        }

        $code = strtoupper(trim($this->request->getPost('activation_code')));
        
        $activationCode = $db->table('activation_codes')
                             ->where('code', $code)
                             ->get()
                             ->getRowArray();

        if (!$activationCode || $activationCode['status'] !== 'available') {
            $attempts++;
            session()->set('aktivasi_attempts', $attempts);
            
            if ($attempts >= 5) {
                // Lock for 10 minutes (600 seconds)
                session()->set('lock_until', time() + 600);
                session()->remove('aktivasi_attempts');
                return redirect()->back()->with('error', 'Terlalu banyak percobaan gagal. Akun Anda dikunci sementara dari fitur ini selama 10 menit.');
            }

            return redirect()->back()->with('error', 'Kode Aktivasi tidak valid atau sudah digunakan.');
        }

        // Code is valid! Process redemption
        $db->transStart();

        // 1. Mark code as used
        $db->table('activation_codes')
           ->where('id', $activationCode['id'])
           ->update([
               'status' => 'used',
               'used_by' => $userId,
               'used_at' => date('Y-m-d H:i:s')
           ]);

        // 2. Insert into user_active_services
        $expiresAt = null;
        if ($activationCode['duration_days'] > 0) {
            $expiresAt = date('Y-m-d H:i:s', strtotime('+' . $activationCode['duration_days'] . ' days'));
        }

        $db->table('user_active_services')->insert([
            'user_id' => $userId,
            'product_type' => $activationCode['product_type'],
            'product_name' => $activationCode['product_name'],
            'activation_code_id' => $activationCode['id'],
            'activated_at' => date('Y-m-d H:i:s'),
            'expires_at' => $expiresAt,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses kode Anda. Silakan hubungi admin.');
        }

        // Reset attempts
        session()->remove('aktivasi_attempts');

        return redirect()->to('/user/dashboard/aktivasi')->with('success', 'Berhasil! Akses Layanan ' . esc($activationCode['product_name']) . ' kini telah aktif di akun Anda.');
    }
}
