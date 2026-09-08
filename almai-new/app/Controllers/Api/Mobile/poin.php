<?php

namespace App\Controllers\Api\Mobile;

use App\Models\PoinModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * API Endpoint untuk Almai Poin
 * Digunakan oleh AIWE-X untuk:
 * 1. Cek saldo poin user
 * 2. Redeem poin menjadi kredit di AIWE-X
 */
class Poin extends ResourceController
{
    protected $format = 'json';

    /**
     * GET api/mobile/poin/balance
     * Cek saldo poin user yang sedang login
     */
    public function balance()
    {
        // Ambil user dari JWT (sudah dihandle filter jwtfilter)
        $userId = $this->request->user->id ?? null;

        if (!$userId) {
            return $this->respond([
                'status'  => false,
                'message' => 'User tidak ditemukan',
            ], 401);
        }

        $poinModel = new PoinModel();

        // Gunakan getUserBalance sesuai method yang ada di PoinModel
        $totalPoin = $poinModel->getUserBalance($userId);

        return $this->respond([
            'status'  => true,
            'message' => 'Saldo poin berhasil diambil',
            'data'    => [
                'user_id'    => $userId,
                'total_poin' => (int) $totalPoin,
            ],
        ]);
    }

    /**
     * POST api/mobile/poin/redeem-to-aiwe
     * Tukar poin almai menjadi kredit di AIWE-X
     *
     * Body (JSON):
     * {
     *   "jumlah_poin": 100,
     *   "aiwe_user_id": "xxx",
     *   "aiwe_api_key": "xxx"
     * }
     */
    public function redeemToAiwe()
    {
        $userId = $this->request->user->id ?? null;

        if (!$userId) {
            return $this->respond([
                'status'  => false,
                'message' => 'User tidak ditemukan',
            ], 401);
        }

        $input       = $this->request->getJSON(true);
        $jumlahPoin  = (int) ($input['jumlah_poin']  ?? 0);
        $aiweUserId  = $input['aiwe_user_id']         ?? null;
        $aiweApiKey  = $input['aiwe_api_key']         ?? null;

        // ── Validasi input ──────────────────────────────────────
        if ($jumlahPoin < 100) {
            return $this->respond([
                'status'  => false,
                'message' => 'Minimum redeem adalah 100 poin',
            ], 400);
        }

        if (!$aiweUserId || !$aiweApiKey) {
            return $this->respond([
                'status'  => false,
                'message' => 'aiwe_user_id dan aiwe_api_key wajib diisi',
            ], 400);
        }

        // ── Verifikasi API key AIWE-X ───────────────────────────
        $validKey = getenv('AIWE_API_KEY') ?: 'AIWE2025-ALMAI-SECRET-KEY';
        if ($aiweApiKey !== $validKey) {
            return $this->respond([
                'status'  => false,
                'message' => 'API key AIWE-X tidak valid',
            ], 403);
        }

        // ── Cek saldo poin user ─────────────────────────────────
        $poinModel = new PoinModel();
        $totalPoin = (int) $poinModel->getUserBalance($userId);

        if ($totalPoin < $jumlahPoin) {
            return $this->respond([
                'status'  => false,
                'message' => 'Saldo poin tidak cukup',
                'data'    => [
                    'saldo_anda' => $totalPoin,
                    'diminta'    => $jumlahPoin,
                ],
            ], 400);
        }

        // ── Konversi: 1 poin = Rp 10 ───────────────────────────
        $ratePerPoin = 10;
        $nilaiRupiah = $jumlahPoin * $ratePerPoin;
        $kodeTransaksi = 'AIWE-' . strtoupper(bin2hex(random_bytes(4)));

        // ── Kurangi saldo poin (insert baris negatif di tabel points) ──
        $db = \Config\Database::connect();
        $berhasil = $db->table('points')->insert([
            'user_id'         => $userId,
            'point'           => -$jumlahPoin,   // negatif = pengurangan
            'type'            => 'redeem_aiwe',
            'description'     => 'Redeem ke AIWE-X | Kode: ' . $kodeTransaksi,
            'pointable_type'  => 'aiwe_recharge',
            'pointable_id'    => 0,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        if (!$berhasil) {
            return $this->respond([
                'status'  => false,
                'message' => 'Gagal memproses redeem poin. Coba lagi.',
            ], 500);
        }

        // ── Response sukses ─────────────────────────────────────
        return $this->respond([
            'status'  => true,
            'message' => 'Redeem poin berhasil',
            'data'    => [
                'kode_transaksi'  => $kodeTransaksi,
                'almai_user_id'   => $userId,
                'aiwe_user_id'    => $aiweUserId,
                'jumlah_poin'     => $jumlahPoin,
                'nilai_rupiah'    => $nilaiRupiah,
                'saldo_poin_sisa' => $totalPoin - $jumlahPoin,
                'rate'            => $ratePerPoin . ' IDR per poin',
                'timestamp'       => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
