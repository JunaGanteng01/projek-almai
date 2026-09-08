<?php

namespace App\Controllers;

use App\Models\WpaModel;
use App\Models\LayananModel;
use App\Models\TeamModel;

class Wpa extends BaseController
{
    protected $wpaModel;
    protected $layananModel;

    public function __construct()
    {
        $this->wpaModel = new WpaModel();
        $this->layananModel = new LayananModel();
    }

    public function index()
    {
        $specialty = $this->request->getGet('specialty');
        $search = $this->request->getGet('search');

        $wpaList = $this->wpaModel->getFiltered($specialty, $search);

        // Calculate total layanan and referral count for each WPA
        $userModel = new \App\Models\UserModel();
        foreach ($wpaList as &$wpa) {
            $wpa['total_classes'] = $this->layananModel->countByWpaId($wpa['id']);

            $uid = $wpa['user_id'] ?? 0;
            if ($uid) {
                // RESTORED: Calculate full 10-level network count (MLM Style)
                $wpa['referral_count'] = $userModel->getNetworkCount($uid);
            } else {
                $wpa['referral_count'] = 0;
            }
        }

        // Sort WPAs by referral_count descending (Total Users highest to lowest)
        usort($wpaList, function ($a, $b) {
            return ($b['referral_count'] ?? 0) <=> ($a['referral_count'] ?? 0);
        });

        $data = [
            'title' => 'WPA - Almai E-Learning',
            'wpaList' => $wpaList,
            'team' => (new TeamModel())->getActiveTeam(),
            'specialties' => $this->wpaModel->getSpecialties(),
            'currentSpecialty' => $specialty,
            'searchQuery' => $search,
            'meta_title' => 'WPA - Almai E-Learning',
            'meta_description' => 'Temukan mentor trading profesional dan terverifikasi di Almai WPA.',
            // User requested explicit image for the WPA main page
            'meta_image' => base_url('images/wpa/wpa.jpeg'),
        ];

        return view('pages/wpa/index', $data);
    }

    public function detail($slug)
    {
        // Try to find by slug first
        $wpa = $this->wpaModel->findBySlug($slug);

        // Fallback to ID for backward compatibility
        if (!$wpa && is_numeric($slug)) {
            $wpa = $this->wpaModel->find($slug);
        }

        if (!$wpa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $layananByWpa = $this->layananModel->getByWpaId($wpa['id']);
        
        // Filter out inactive services
        $layananByWpa = array_filter($layananByWpa, function($l) {
            $status = strtolower($l['status'] ?? '');
            return in_array($status, ['active', 'aktif', 'published']);
        });

        // Get referral count
        $referralCount = 0;
        $referralCode = null;
        if (!empty($wpa['user_id'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($wpa['user_id']);

            if ($user) {
                $referralCode = $user['code_referral'] ?? ($user['referral_code'] ?? '');

                // RESTORED: Use 10-level network counting (MLM Style)
                $referralCount = $userModel->getNetworkCount($user['id']);
            }
        }

        // Prepare Meta Image
        $metaImage = $wpa['photo'];
        if ($metaImage && strpos($metaImage, 'uploads/') === 0) {
            $metaImage = base_url('file/' . $metaImage);
        } elseif ($metaImage && strpos($metaImage, 'writable/') === 0) {
            $metaImage = base_url('file/' . preg_replace('/^writable\//', '', $metaImage));
        }

        // Follower Logic
        $db = \Config\Database::connect();
        $followerCount = 0;
        $isFollowing = false;

        // Check if table exists to avoid errors if SQL not imported yet
        if ($db->tableExists('followers')) {
            $followerCount = $db->table('followers')->where('wpa_id', $wpa['id'])->countAllResults();

            if (session()->get('isLoggedIn')) {
                $userId = session()->get('userId'); // Changed from 'id' to 'userId'
                $isFollowing = $db->table('followers')
                    ->where('user_id', $userId)
                    ->where('wpa_id', $wpa['id'])
                    ->countAllResults() > 0;
            }
        }

        // Fetch Instagram data if username is set
        $instagramData = null;
        $instagramMedia = [];
        if (!empty($wpa['instagram'])) {
            $instagramService = new \App\Libraries\InstagramService();
            $instagramData = $instagramService->getProfile($wpa['instagram']);
            if ($instagramData) {
                $instagramMedia = $instagramService->getMedia($wpa['instagram'], 9);
            }
        }

        // Fetch TikTok data if secUid or username is set
        $tiktokData = null;
        if (!empty($wpa['tiktok_secuid']) || !empty($wpa['tiktok'])) {
            $tiktokService = new \App\Libraries\TikTokService();
            if (!empty($wpa['tiktok_secuid'])) {
                $tiktokData = $tiktokService->getProfileBySecUid($wpa['tiktok_secuid']);
            } else {
                $tiktokData = $tiktokService->getProfileByUsername($wpa['tiktok']);
            }
        }

        // Fetch YouTube data if channel ID is set
        $youtubeData = null;
        if (!empty($wpa['youtube'])) {
            $youtubeService = new \App\Libraries\YoutubeService();
            $youtubeData = $youtubeService->getChannelById($wpa['youtube']);
        }

        $data = [
            'title' => $wpa['name'] . ' - Almai E-Learning',
            'wpa' => $wpa,
            'layananByWpa' => $layananByWpa,
            'referralCount' => $referralCount,
            'referralCode' => $referralCode,
            'followerCount' => $followerCount,
            'isFollowing' => $isFollowing,
            'instagramData' => $instagramData,
            'instagramMedia' => $instagramMedia,
            'youtubeData' => $youtubeData,
            'tiktokData' => $tiktokData,
            'meta_title' => $wpa['name'] . ' - ' . $wpa['specialty'],
            'meta_description' => substr(strip_tags($wpa['bio']), 0, 160),
            'meta_image' => $metaImage,
        ];

        return view('pages/wpa/detail', $data);
    }

    public function toggleFollow($wpaId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
        }

        $userId = session()->get('userId'); // Changed from 'id' to 'userId' matches Auth controller
        $db = \Config\Database::connect();

        if (!$db->tableExists('followers')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'System update required (followers table missing)']);
        }

        // check if following
        $exists = $db->table('followers')
            ->where('user_id', $userId)
            ->where('wpa_id', $wpaId)
            ->countAllResults();

        if ($exists) {
            $db->table('followers')
                ->where('user_id', $userId)
                ->where('wpa_id', $wpaId)
                ->delete();
            $action = 'unfollowed';
        } else {
            $db->table('followers')->insert([
                'user_id' => $userId,
                'wpa_id' => $wpaId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $action = 'followed';

            // Trigger Notification
            $wpa = $this->wpaModel->find($wpaId);
            if ($wpa && !empty($wpa['user_id'])) {
                $notifModel = new \App\Models\NotificationModel();
                $followerName = session()->get('userName') ?? 'Seseorang';
                $notifModel->createNotification(
                    $wpa['user_id'],
                    'Follower Baru',
                    "$followerName mulai mengikuti Anda.",
                    'success', // Type
                    null // Link (optional, maybe to profile?)
                );
            }
        }

        // Get new count
        $count = $db->table('followers')->where('wpa_id', $wpaId)->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'action' => $action,
            'count' => $count,
            'count_formatted' => number_format($count)
        ]);
    }
}
