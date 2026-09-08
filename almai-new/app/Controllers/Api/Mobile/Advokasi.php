<?php

namespace App\Controllers\Api\Mobile;

use App\Models\UserModel;
use App\Models\EaLicenseModel;
use App\Models\AdvokasiSettingModel;
use CodeIgniter\RESTful\ResourceController;

class Advokasi extends ResourceController
{
    protected $format = 'json';

    /**
     * GET /api/mobile/advokasi
     * Info program advokasi + status user
     */
    public function index()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $userModel    = new UserModel();
        $licenseModel = new EaLicenseModel();

        $user     = $userModel->find($userId);
        $licenses = $licenseModel->where('user_id', $userId)->findAll();

        // Format licenses
        $licensesFormatted = array_map(function ($lic) {
            return [
                'license_key'    => $lic['license_key'],
                'broker_name'    => str_replace(['[MT4] ', '[MT5] '], '', $lic['broker_name']),
                'account_number' => $lic['account_trading_number'] ?? '',
                'platform'       => strpos($lic['broker_name'], '[MT4]') !== false ? 'MT4' : 'MT5',
                'expires_at'     => $lic['expires_at'] ?? null,
                'is_lifetime'    => empty($lic['expires_at']),
                'status'         => 'active',
            ];
        }, $licenses);

        // Jadwal webinar
        $schedule = [
            ['day' => 'Senin',   'title' => 'MELINDUNGI MARGIN',    'topic' => 'Legalitas, Keamanan Margin, dan Perlindungan Akun'],
            ['day' => 'Selasa',  'title' => 'MEMPERTAHANKAN',       'topic' => 'Advokasi, Hak & Perlindungan Trader'],
            ['day' => 'Rabu',    'title' => 'PAHAM DERIVATIF',      'topic' => 'Struktur Market, Platform Trading, dan EA AIWE'],
            ['day' => 'Kamis',   'title' => 'PAHAM ASET DIGITAL',   'topic' => 'Crypto, Blockchain, dan Market Digital'],
            ['day' => 'Jumat',   'title' => 'TERUKUR',              'topic' => 'Risk Management, Trading Plan, dan Eksekusi Trading'],
            ['day' => 'Sabtu',   'title' => 'BERTUMBUH',            'topic' => 'Evaluasi, Konsistensi, & Pengembangan Trader'],
            ['day' => 'Minggu',  'title' => 'SIAP MENJADI TRADER',  'topic' => 'Mindset, Disiplin, dan Kebiasaan Dasar Trader'],
        ];

        return $this->respond([
            'success' => true,
            'data'    => [
                'program' => [
                    'title'       => 'PROGRAM ADVOKASI TRADER BASIC (7 HARI)',
                    'description' => 'Program pendampingan trader untuk perlindungan dan edukasi.',
                    'schedule'    => $schedule,
                ],
                'user_status' => [
                    'has_license' => count($licenses) > 0,
                    'licenses'    => $licensesFormatted,
                    'kyc_status'  => $user['kyc_status'] ?? 'not_submitted',
                    'is_pro'      => (bool)($user['is_pro'] ?? false),
                ],
            ],
        ]);
    }

    /**
     * POST /api/mobile/advokasi/activate
     * Activate EA license
     * Body: { broker_name_mt4?, account_number_mt4?, broker_name_mt5?, account_number_mt5? }
     */
    public function activate()
    {
        $jwtUser = $this->request->jwtUser;
        $userId  = $jwtUser['user_id'];

        $json = $this->request->getJSON(true);

        $licenseModel = new EaLicenseModel();

        // Check existing license
        $existing = $licenseModel->where('user_id', $userId)->first();
        if ($existing) {
            return $this->fail(['message' => 'Anda sudah memiliki lisensi aktif.'], 400);
        }

        $activated = [];

        foreach (['mt4', 'mt5'] as $platform) {
            $accountNumber = $json["account_number_{$platform}"] ?? '';
            $brokerName    = $json["broker_name_{$platform}"] ?? '';

            if (empty($accountNumber)) continue;

            $licenseKey = strtoupper(substr(bin2hex(random_bytes(8)), 0, 16));

            $licenseModel->insert([
                'user_id'               => $userId,
                'license_key'           => $licenseKey,
                'broker_name'           => "[" . strtoupper($platform) . "] " . $brokerName,
                'account_trading_number'=> $accountNumber,
                'status'                => 'active',
                'expires_at'            => null, // Lifetime
            ]);

            $activated[] = [
                'platform'    => strtoupper($platform),
                'license_key' => $licenseKey,
            ];
        }

        if (empty($activated)) {
            return $this->fail(['message' => 'Masukkan minimal satu nomor akun.'], 422);
        }

        return $this->respond([
            'success'   => true,
            'message'   => 'Lisensi berhasil diaktivasi!',
            'activated' => $activated,
        ]);
    }
}
