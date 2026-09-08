<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\LayananUlasanModel;
use App\Models\PoinModel;
use App\Models\NotificationModel;

class LayananUlasan extends BaseController
{
    public function submit()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
        }

        $layananId = $this->request->getPost('layanan_id');
        $layananType = $this->request->getPost('layanan_type');
        $rating = $this->request->getPost('rating');
        $ulasan = $this->request->getPost('ulasan');

        if (!$layananId || !$rating || !$ulasan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rating dan ulasan wajib diisi']);
        }

        $ulasanModel = new LayananUlasanModel();
        $poinModel = new PoinModel();
        $notifModel = new NotificationModel();
        
        $db = \Config\Database::connect();

        // Verify if table exists
        if (!$db->tableExists('layanan_ulasan')) {
            // Fallback to old ulasan table if it's a course
            if ($layananType === 'layanan') {
                $oldUlasanModel = new \App\Models\UlasanModel();
                $oldUlasanData = [
                    'kelas_id' => $layananId,
                    'user_id' => $userId,
                    'rating' => $rating,
                    'ulasan' => $ulasan,
                    'status' => 'approved'
                ];
                if ($oldUlasanModel->insert($oldUlasanData)) {
                    $ulasanId = $oldUlasanModel->getInsertID();
                    $this->rewardPoints($userId, $ulasanId, 'ulasan');
                    return $this->response->setJSON(['status' => 'success', 'message' => "Ulasan berhasil dikirim ke tabel lama!", 'points' => 10]);
                }
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tabel ulasan tidak ditemukan di database.']);
        }

        // Check if user already reviewed
        $existing = $ulasanModel->where('user_id', $userId)
                               ->where('layanan_id', $layananId)
                               ->where('layanan_type', $layananType)
                               ->first();

        if ($existing) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memberikan ulasan untuk layanan ini']);
        }

        // Save ulasan
        $ulasanData = [
            'user_id' => $userId,
            'layanan_id' => $layananId,
            'layanan_type' => $layananType,
            'rating' => (float)$rating,
            'ulasan' => $ulasan,
            'status' => 'approved'
        ];

        try {
            if ($ulasanModel->insert($ulasanData)) {
                $ulasanId = $ulasanModel->getInsertID();
                $this->rewardPoints($userId, $ulasanId, 'layanan_ulasan');
                return $this->response->setJSON(['status' => 'success', 'message' => "Ulasan berhasil!", 'points' => 10, 'id' => $ulasanId]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal simpan: ' . json_encode($ulasanModel->errors())]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    private function rewardPoints($userId, $refId, $type)
    {
        $poinModel = new PoinModel();
        $notifModel = new NotificationModel();
        $pointsAwarded = 10;
        
        $poinModel->insert([
            'user_id' => $userId,
            'point' => $pointsAwarded,
            'type' => 'income',
            'description' => 'bonus ulasan layanan',
            'pointable_type' => $type,
            'pointable_id' => $refId
        ]);

        try {
            $notifModel->createNotification(
                $userId,
                'Terima Kasih!',
                "Anda mendapatkan $pointsAwarded poin karena ulasan.",
                'success',
                '/user/layanan-saya'
            );
        } catch (\Exception $e) {}
    }
}
