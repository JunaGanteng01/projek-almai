<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\LayananModel;
use App\Models\UserModel;
use App\Models\LayananToolsModel;
use App\Models\LayananUlasanModel;
use App\Models\LayananPriceModel;
use App\Models\TeamModel;

class DaftarWpa extends BaseController
{
    public function index()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login')->with('error', 'Profil WPA tidak ditemukan');

        $wpaModel = new WpaModel();
        $search = $this->request->getGet('search');
        
        $builder = $wpaModel->where('status', 'active');
        
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('specialty', $search)
                ->orLike('bio', $search)
                ->groupEnd();
        }

        $allWpas = $builder->findAll();
        
        $layananModel = new LayananModel();
        $userModel = new UserModel();
        if (is_array($allWpas)) {
            foreach ($allWpas as &$wpa) {
                if (is_array($wpa)) {
                    $wpa['total_layanan'] = $layananModel->countActiveByWpaId($wpa['id']);
                    $wpa['total_users'] = !empty($wpa['user_id']) ? $userModel->getNetworkCount($wpa['user_id']) : 0;
                } else if (is_object($wpa)) {
                    $wpa->total_layanan = $layananModel->countActiveByWpaId($wpa->id);
                    $wpa->total_users = !empty($wpa->user_id) ? $userModel->getNetworkCount($wpa->user_id) : 0;
                }
            }
            
            // SORT BY total_users DESC, then name ASC
            usort($allWpas, function($a, $b) {
                $aUsers = is_array($a) ? ($a['total_users'] ?? 0) : ($a->total_users ?? 0);
                $bUsers = is_array($b) ? ($b['total_users'] ?? 0) : ($b->total_users ?? 0);
                
                if ($aUsers === $bUsers) {
                    $aName = is_array($a) ? ($a['name'] ?? '') : ($a->name ?? '');
                    $bName = is_array($b) ? ($b['name'] ?? '') : ($b->name ?? '');
                    return strcmp($aName, $bName);
                }
                return $bUsers - $aUsers;
            });
        }

        // Paginasi Manual
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $total = count($allWpas);
        $wpas = array_slice($allWpas, ($page - 1) * $perPage, $perPage);
        
        $pager = \Config\Services::pager();
        $pager_links = $pager->makeLinks($page, $perPage, $total, 'admin_pagination');
        
        // Fetch Team Members
        $teamModel = new TeamModel();
        $teams = $teamModel->where('is_active', 1)
            ->orderBy('order_number', 'ASC')
            ->limit(5)
            ->findAll();

        return view('wpa/daftar-wpa/index', [
            'title' => 'Daftar WPA - WPA Dashboard',
            'activeMenu' => 'daftar-wpa',
            'wpas' => $wpas,
            'pager_links' => $pager_links,
            'search' => $search,
            'teams' => $teams
        ]);
    }

    public function detail($slug)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login')->with('error', 'Profil WPA tidak ditemukan');

        $wpaModel = new WpaModel();
        $layananModel = new LayananModel();

        // Try to find by slug first
        $wpa = $wpaModel->findBySlug($slug);

        // Fallback to ID
        if (!$wpa && is_numeric($slug)) {
            $wpa = $wpaModel->find($slug);
        }

        if (!$wpa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $layananByWpa = $layananModel->getByWpaId($wpa['id']);
        
        // Filter out inactive services
        $layananByWpa = array_filter($layananByWpa, function($l) {
            $status = strtolower($l['status'] ?? '');
            return in_array($status, ['active', 'aktif', 'published']);
        });

        // Get network stats
        $referralCount = 0;
        $referralCode = null;
        if (!empty($wpa['user_id'])) {
            $userModel = new UserModel();
            $user = $userModel->find($wpa['user_id']);
            if ($user) {
                $referralCount = $userModel->getNetworkCount($user['id']);
                $referralCode = $user['code_referral'] ?? ($user['referral_code'] ?? '');
            }
        }

        // Follower Logic
        $db = \Config\Database::connect();
        $followerCount = 0;
        $isFollowing = false;
        if ($db->tableExists('followers')) {
            $followerCount = $db->table('followers')->where('wpa_id', $wpa['id'])->countAllResults();
            $userId = session()->get('userId');
            $isFollowing = $db->table('followers')
                ->where('user_id', $userId)
                ->where('wpa_id', $wpa['id'])
                ->countAllResults() > 0;
        }

        // Fetch Instagram data
        $instagramData = null;
        $instagramMedia = [];
        if (!empty($wpa['instagram'])) {
            $instagramService = new \App\Libraries\InstagramService();
            $instagramData = $instagramService->getProfile($wpa['instagram']);
            if ($instagramData) {
                $instagramMedia = $instagramService->getMedia($wpa['instagram'], 9);
            }
        }

        // Fetch YouTube data
        $youtubeData = null;
        if (!empty($wpa['youtube'])) {
            $youtubeService = new \App\Libraries\YoutubeService();
            $youtubeData = $youtubeService->getChannelById($wpa['youtube']);
        }

        $tiktokData = null;
        if (!empty($wpa['tiktok_secuid']) || !empty($wpa['tiktok'])) {
            $tiktokService = new \App\Libraries\TikTokService();
            $tiktokData = !empty($wpa['tiktok_secuid'])
                ? $tiktokService->getProfileBySecUid($wpa['tiktok_secuid'])
                : $tiktokService->getProfileByUsername($wpa['tiktok']);
        }

        return view('wpa/daftar-wpa/detail', [
            'title' => $wpa['name'] . ' - WPA Dashboard',
            'activeMenu' => 'daftar-wpa',
            'wpa' => $wpa,
            'layananByWpa' => $layananByWpa,
            'referralCount' => $referralCount,
            'referralCode' => $referralCode,
            'followerCount' => $followerCount,
            'isFollowing' => $isFollowing,
            'instagramData' => $instagramData,
            'instagramMedia' => $instagramMedia,
            'youtubeData' => $youtubeData,
            'tiktokData' => $tiktokData
        ]);
    }

    public function layananDetail($slug)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login');

        // 1. Get Service Data (Broad search across all tables)
        $db = \Config\Database::connect();
        $fixThumbnail = function ($path) {
            if (empty($path)) return null;
            if (strpos($path, 'http') === 0) return $path;
            $path = preg_replace('/^writable\//', '', $path);
            return base_url('file/' . ltrim($path, '/'));
        };
        
        // Search logic replicated from Wpa\Layanan::detail
        $layananModel = new LayananModel();
        $layanan = $layananModel->where('slug', $slug)->first();
        if ($layanan) {
            $layanan['type'] = 'layanan';
            $layanan['thumbnail'] = $fixThumbnail($layanan['thumbnail']);
        }

        if (!$layanan) {
            $tables = [
                'layanan_tools' => ['Expert Advisor', 'Expert Advisor'],
                'layanan_event' => ['Advokasi', 'Event'],
                'layanan_artikel' => ['Advokasi', 'Artikel'],
                'layanan_subscription' => ['Advokasi', 'Subscription']
            ];
            
            foreach ($tables as $table => $meta) {
                $item = $db->table($table)->where('slug', $slug)->get()->getRowArray();
                if ($item) {
                    $layanan = $item;
                    $layanan['category'] = $meta[0];
                    $layanan['subcategory'] = $meta[1];
                    
                    if ($table === 'layanan_tools') {
                        $layanan['category'] = ($item['type'] === 'ea') ? 'Expert Advisor' : 'Ultimate';
                        $layanan['subcategory'] = ($item['type'] === 'ea') ? 'Expert Advisor' : 'Almai Toolkits';
                        $layanan['students'] = $item['download_count'] ?? 0;
                    } elseif ($table === 'layanan_subscription') {
                        $layanan['category'] = in_array($item['type'], ['pendampingan', 'profirm']) ? 'Advokasi' : 'Ultimate';
                        $labels = ['pendampingan' => 'Pendampingan Wpa', 'profirm' => 'Profirm', 'vip_member' => 'VIP Member', 'private_konsultan' => 'Private Konsultan'];
                        $layanan['subcategory'] = $labels[$item['type']] ?? ucfirst($item['type']);
                        $layanan['students'] = 0;
                    } elseif ($table === 'layanan_event') {
                        $layanan['subcategory'] = ucfirst($item['type']);
                        $layanan['name'] = $item['title'];
                        $layanan['students'] = $item['current_participants'] ?? 0;
                    } elseif ($table === 'layanan_artikel') {
                        $layanan['name'] = $item['title'];
                        $layanan['students'] = $item['views'] ?? 0;
                    }
                    
                    if (!isset($layanan['students'])) $layanan['students'] = 0;
                    if (!isset($layanan['type'])) $layanan['type'] = ($table === 'layanan_artikel') ? 'artikel' : ($item['type'] ?? 'layanan');
                    $layanan['thumbnail'] = $fixThumbnail($layanan['thumbnail'] ?? null);
                    break;
                }
            }
        }

        if ($layanan && !isset($layanan['students'])) {
            $layanan['students'] = 0;
        }

        if (!$layanan) {
            return redirect()->to('/wpa/dashboard')->with('error', 'Layanan tidak ditemukan');
        }

        $requiresAdvokasi = $this->requiresAdvokasiMembership($layanan);

        // NOTE: We DO NOT check for existing purchase and redirect here, 
        // to satisfy user request "jangan arahkan ke layanan saya".

        // 3. Get Additional Info
        $priceModel = new LayananPriceModel();
        $ulasanModel = new LayananUlasanModel();
        
        $packages = $priceModel->where('layanan_id', $layanan['id'])->orderBy('price', 'ASC')->findAll();
        $reviews = $ulasanModel->getReviews($layanan['id'], $layanan['type'] ?? 'layanan');
        $avgRatingQuery = $ulasanModel->where('layanan_id', $layanan['id'])->selectAvg('rating')->first();
        $averageRating = $avgRatingQuery['rating'] ?? 5.0;

        // 4. Get WPA Info
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $db->table('wpa')->where('id', $layanan['wpa_id'])->get()->getRowArray();
        }

        return view('wpa/daftar-wpa/lihat-layanan', [
            'title' => $layanan['name'] . ' - WPA Dashboard',
            'layanan' => $layanan,
            'packages' => $packages,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'wpa' => $wpa,
            'referralCode' => session()->get('referralCode') ?? '',
            'activeMenu' => 'daftar-wpa', // Use daftar-wpa to keep menu highlight
            'requiresAdvokasi' => $requiresAdvokasi,
            'hasAdvokasi' => (function() {
                $userId = session()->get('userId');
                if (!$userId) return false;
                $policy = new \Config\LayananPolicy();
                $transaksiModel = new \App\Models\TransaksiModel();
                return $transaksiModel->where('user_id', $userId)
                                      ->where('layanan_id', $policy->advokasiMembershipLayananId)
                                      ->where('status', 'confirmed')
                                      ->countAllResults() > 0;
            })()
        ]);
    }

    protected function requiresAdvokasiMembership(array $layanan): bool
    {
        $policy = new \Config\LayananPolicy();
        $slug = strtolower((string) ($layanan['slug'] ?? ''));

        if (in_array($slug, $policy->advokasiExemptSlugs, true)) {
            return false;
        }

        if (($layanan['id'] ?? null) == $policy->advokasiMembershipLayananId) {
            return false;
        }

        return true;
    }

    private function getWpaId()
    {
        $wpaId = $this->session->get('wpaId');
        if (!$wpaId) {
            $userId = $this->session->get('userId');
            $wpaModel = new WpaModel();
            $wpa = $wpaModel->where('user_id', $userId)->first();
            if ($wpa) {
                $wpaId = $wpa['id'];
                $this->session->set('wpaId', $wpaId);
            }
        }
        return $wpaId;
    }
}
