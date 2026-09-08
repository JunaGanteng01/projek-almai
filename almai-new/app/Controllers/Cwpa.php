<?php

namespace App\Controllers;

class Cwpa extends BaseController
{
    public function index()
    {
        $cwpaModel = new \App\Models\CwpaModel();
        $layananModel = new \App\Models\LayananModel();
        $userModel = new \App\Models\UserModel();

        // Fetch all active CWPAs
        $cwpaList = $cwpaModel->getActiveCwpa();

        // Calculate stats for each CWPA to match WPA card needs
        foreach ($cwpaList as &$client) {
            $client['total_classes'] = $layananModel->countByCwpaId($client['id']);
            
            $uid = $client['user_id'] ?? 0;
            if ($uid) {
                // Calculate full network count (Matches WPA Logic)
                // Added + 1 to include the person themselves as per requirement
                $client['referral_count'] = $userModel->getNetworkCount($uid) + 1;
            } else {
                $client['referral_count'] = 0;
            }
        }

        // Sort by referral_count descending
        usort($cwpaList, function($a, $b) {
            return $b['referral_count'] <=> $a['referral_count'];
        });

        $data = [
            'title' => 'CWPA - Calon Wakil Penasihat Berjangka | Almai',
            'meta_title' => 'Program CWPA - Sertifikasi Wakil Penasihat Berjangka',
            'meta_description' => 'Persiapkan diri Anda menjadi Wakil Penasihat Berjangka profesional dengan program bimbingan dan materi lengkap dari Almai.',
            'meta_image' => base_url('images/cwpa-og.jpg'),
            'cwpaList' => $cwpaList
        ];

        return view('pages/cwpa', $data);
    }

    public function detail($slug)
    {
        $cwpaModel = new \App\Models\CwpaModel();
        $cwpa = $cwpaModel->getBySlug($slug);

        if (!$cwpa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('CWPA tidak ditemukan');
        }

        // Get referral code from linked user account
        $referralCode = null;
        $referralCount = 0; // Initialize referral count

        if (!empty($cwpa['user_id'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($cwpa['user_id']);
            if ($user) {
                // Prioritize code_referral, fallback to referral_code if exists
                $referralCode = !empty($user['code_referral']) ? $user['code_referral'] : ($user['referral_code'] ?? null);

                // If still empty, do NOT fallback to name yet. Let view handle fallback if we really want to.
                // But user explicitly asked for code_referral. So let's stick to that.

                // Get total network count robustly (Matches Admin/WPA Logic)
                // We add 1 to include the user themselves as per requirement
                $referralCount = $userModel->getNetworkCount($user['id'], 10) + 1;

            }
        }

        // Follower Logic
        $followerModel = new \App\Models\FollowerModel();
        $followerCount = $followerModel->getFollowerCount(null, $cwpa['id']);
        $isFollowing = false;

        if (session()->get('isLoggedIn')) {
            $userId = session()->get('userId');
            $isFollowing = $followerModel->isFollowing($userId, null, $cwpa['id']);
        }

        // Fetch Instagram data if username is set
        $instagramData = null;
        $instagramMedia = [];
        $instagramUsername = \App\Models\CwpaModel::normalizeInstagram($cwpa['instagram'] ?? '');
        if ($instagramUsername !== '') {
            $instagramService = new \App\Libraries\InstagramService();
            $instagramData = $instagramService->getProfile($instagramUsername);
            if ($instagramData) {
                $instagramMedia = $instagramService->getMedia($instagramUsername, 9);
            }
        }

        // Fetch YouTube data if channel ID is set
        $youtubeData = null;
        if (!empty($cwpa['youtube'])) {
            $youtubeService = new \App\Libraries\YoutubeService();
            $youtubeData = $youtubeService->getChannelById($cwpa['youtube']);
        }

        // Fetch TikTok data if secUid or username is set
        $tiktokData = null;
        if (!empty($cwpa['tiktok_secuid']) || !empty($cwpa['tiktok'])) {
            $tiktokService = new \App\Libraries\TikTokService();
            if (!empty($cwpa['tiktok_secuid'])) {
                $tiktokData = $tiktokService->getProfileBySecUid($cwpa['tiktok_secuid']);
            } else {
                $tiktokData = $tiktokService->getProfileByUsername($cwpa['tiktok']);
            }
        }

        $layananModel = new \App\Models\LayananModel();
        $layanan = $layananModel->getByCwpaId($cwpa['id']);

        $data = [
            'title' => $cwpa['name'] . ' - CWPA | Almai',
            'meta_title' => $cwpa['name'] . ' - Calon Wakil Penasihat Berjangka',
            'meta_description' => 'Profil dan perjalanan sertifikasi ' . $cwpa['name'] . ' sebagai Calon Wakil Penasihat Berjangka bersama Almai.',
            'meta_image' => !empty($cwpa['photo']) ? base_url('file/' . preg_replace('/^writable\//', '', $cwpa['photo'])) : base_url('images/cwpa-og.jpg'),
            'cwpa' => $cwpa,
            'referralCode' => $referralCode,
            'referralCount' => $referralCount,
            'followerCount' => $followerCount,
            'isFollowing' => $isFollowing,
            'instagramData' => $instagramData,
            'instagramMedia' => $instagramMedia,
            'instagramUsername' => $instagramUsername,
            'instagramUrl' => \App\Models\CwpaModel::instagramUrl($cwpa['instagram'] ?? ''),
            'youtubeData' => $youtubeData,
            'tiktokData' => $tiktokData,
            'layanan' => $layanan
        ];

        return view('pages/cwpa/detail', $data);
    }

    public function toggleFollow($cwpaId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu'
            ])->setStatusCode(401);
        }

        $userId = session()->get('userId');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi login tidak valid. Silakan logout dan login kembali.'
            ]);
        }

        $followerModel = new \App\Models\FollowerModel();

        try {
            $isFollowing = $followerModel->toggleFollow($userId, null, $cwpaId);
            $newCount = $followerModel->getFollowerCount(null, $cwpaId);

            return $this->response->setJSON([
                'status' => 'success',
                'isFollowing' => $isFollowing,
                'newCount' => $newCount,
                'message' => $isFollowing ? 'Berhasil mengikuti CWPA' : 'Berhasil berhenti mengikuti CWPA'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
