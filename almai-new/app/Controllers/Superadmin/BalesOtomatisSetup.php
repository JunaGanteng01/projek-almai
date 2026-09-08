<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Libraries\Balesotomatis;

class BalesOtomatisSetup extends BaseController
{
    public function index()
    {
        $secretKey = env('BALESOTOMATIS_SECRET_KEY');
        $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');

        if (empty($secretKey) || empty($licensesKey)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Konfigurasi BALESOTOMATIS_SECRET_KEY dan BALESOTOMATIS_LICENSES_KEY belum diisi di .env'
            ]);
        }

        Balesotomatis::configure($secretKey, $licensesKey);

        $results = [];

        // 1. Template OTP
        $otpPayload = [
            'name' => 'almai_otp_registrasi',
            'language' => 'id',
            'category' => 'UTILITY',
            'submitToFacebook' => true, // Kirim ke Facebook/Meta untuk diapprove
            'body' => "Kode Rahasia (OTP) Anda untuk ALMAI adalah: *{{1}}*\n\nBerlaku selama 10 menit. Jangan bagikan ke siapapun.",
            'variables' => [
                [
                    'placeholder' => '{{1}}',
                    'label' => 'OTP',
                    'example' => '123456'
                ]
            ]
        ];

        $resOtp = Balesotomatis::createTemplate($otpPayload);
        $results['otp_template'] = $resOtp;

        // 2. Template Registrasi Sukses
        // (Sesuai dengan line 738 di Auth.php)
        $successPayload = [
            'name' => 'almai_registrasi_sukses',
            'language' => 'id',
            'category' => 'UTILITY',
            'submitToFacebook' => true,
            'body' => "✅ Registrasi Berhasil!\n\nTanggal   : {{1}}\nNama      : {{2}}\nNo. HP    : {{3}}\nEmail     : {{4}}\nPassword  : {{5}}\n{{6}} : {{7}}\nBonus Registrasi : {{8}} poin\n\nSilahkan login ke dashboard Anda, untuk melanjutkan kelayanan berikutnya.\n\nLogin : {{9}}\n\nSelamat bergabung di Almai | Platform Resmi Penasihat Perdagangan Derivatif & Aset Keuangan Digital 🇮🇩",
            'variables' => [
                ['placeholder' => '{{1}}', 'label' => 'Tanggal', 'example' => '12 Okt 2026 12:00'],
                ['placeholder' => '{{2}}', 'label' => 'Nama', 'example' => 'Budi'],
                ['placeholder' => '{{3}}', 'label' => 'No HP', 'example' => '628123456789'],
                ['placeholder' => '{{4}}', 'label' => 'Email', 'example' => 'budi@gmail.com'],
                ['placeholder' => '{{5}}', 'label' => 'Password', 'example' => 'ALMA-1234'],
                ['placeholder' => '{{6}}', 'label' => 'Role Affiliator', 'example' => 'WPA'],
                ['placeholder' => '{{7}}', 'label' => 'Nama Affiliator', 'example' => 'Sari'],
                ['placeholder' => '{{8}}', 'label' => 'Bonus', 'example' => '100'],
                ['placeholder' => '{{9}}', 'label' => 'Link Login', 'example' => 'https://almai.id/login']
            ]
        ];

        $resSuccess = Balesotomatis::createTemplate($successPayload);
        $results['success_template'] = $resSuccess;

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Proses pembuatan template selesai. Silakan cek hasilnya.',
            'data' => $results,
            'instruction' => 'Jika berhasil, catat nama template ("almai_otp_registrasi" dan "almai_registrasi_sukses") lalu masukkan ke .env Anda.'
        ]);
    }
}
