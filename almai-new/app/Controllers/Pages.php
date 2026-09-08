<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function presentasi()
    {
        $data = [
            'title' => 'Presentasi - Almai ID',
            'meta_title' => 'Presentasi - Almai ID',
            'meta_description' => 'Materi Presentasi ALMAI',
            'hideFooter' => true
        ];
        
        return view('pages/presentasi', $data);
    }

public function aiwe()
{
    $data = [
        'title' => 'AIWE - Almai ID',
        'meta_title' => 'AIWE - Almai ID',
        'meta_description' => 'Derivatif Keuangan & Aset Digital bersama Wakil Penasihat Berjangka (WPA) bersertifikat.',
        'hideFooter' => true
    ];

    return view('pages/aiwe', $data);
}

public function bidbox()
{
    $data = [
        'title' => 'BIDBOX - Almai ID',
        'meta_title' => 'BIDBOX - Almai ID',
        'meta_description' => 'Platform AI untuk analisis aset digital dan crypto.',
        'hideFooter' => true
    ];

    return view('pages/bidbox', $data);
}

    public function edukasi()
    {
        // Daftar ID Shorts (Unique)
        $allShortIds = [
            '02nlPHYRJqo', '11cyxBEvZBU', '2T-BANXL1Gw', '5ecjRScNhDk', '6WdAMFTWvHI',
            '6rzGpG-sZmI', '76gGODp0lwM', '8pGErLOEySg', '9zF-Ny0CyR8', 'E9-fHvFBaG4',
            'I4BEi2GEK6k', 'I8mC2bUgt80', 'KGKfal7oHIc', 'KpKfxscdeGY', 'PjKDH4VzPuA',
            'aJiFLHjKEnQ', 'eDQOKgzI0jA', 'eUCpIzNf8yg', 'f1ja6mg-qWk', 'f2YOVn1y72w',
            'fHoMvNi9isc', 'gsDN7Y_7IFA', 'iG3FU-hzNck', 'igzPmuTYGNw', 'jYcWYq-AT-A',
            'jdE8nuRdN3Q', 'mJPqOWF-BNc', 'oDPkv7AI2bg', 'p7itNahbhRU', 'r-W_IJ3EkFg',
            'rVDkIvks1C8', 'rsm5hWmQxmQ', 'sRR_vI7_DOk', 'tAkLPodCyp8', 'vFoqtiJLLVw',
            'wVOWoyBBfqQ', 'yv8artkoyFM', 'z19UhmV41d8'
        ];

        // Balik urutan agar list terakhir muncul di page 1 (terbaru di atas)
        $allShortIds = array_reverse($allShortIds);

        // Pagination setup
        $perPage = 8;
        $page = (int) ($this->request->getVar('page') ?? 1);
        if ($page < 1) $page = 1;

        $totalItems = count($allShortIds);
        $totalPages = ceil($totalItems / $perPage);
        
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;
        $shortIds = array_slice($allShortIds, $offset, $perPage);

        $shortsData = [];

        foreach ($shortIds as $id) {
            $url = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$id}&format=json";
            
            // Ambil metadata dari YouTube oEmbed API
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $data = json_decode($response, true);
                $shortsData[] = [
                    'id' => $id,
                    'title' => $data['title'] ?? 'ALMAI Edukasi',
                    'author' => $data['author_name'] ?? 'ALMAI',
                    'thumbnail' => $data['thumbnail_url'] ?? "https://img.youtube.com/vi/{$id}/hqdefault.jpg"
                ];
            } else {
                // Fallback jika API gagal
                $shortsData[] = [
                    'id' => $id,
                    'title' => 'Video Edukasi ALMAI',
                    'author' => 'Tim ALMAI',
                    'thumbnail' => "https://img.youtube.com/vi/{$id}/hqdefault.jpg"
                ];
            }
        }

        return view('pages/edukasi', [
            'title' => 'Edukasi Trading - ALMAI',
            'meta_title' => 'Edukasi Trading Singkat - ALMAI Shorts',
            'meta_description' => 'Pelajari trading dengan cepat melalui video singkat pilihan dari ALMAI.',
            'shorts' => $shortsData,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function about()
    {
        $teamModel = new \App\Models\TeamModel();
        $team = $teamModel->getActiveTeam();

        // Add WPA to team (Wakil Penasihat Berjangka)
        $wpaModel = new \App\Models\WpaModel();
        $wpas = $wpaModel->where('status', 'active')->findAll();
        foreach ($wpas as $wpa) {
            $team[] = [
                'name' => $wpa['name'],
                'role' => 'Wakil Penasihat Berjangka',
                'photo' => $wpa['photo'] ?? '',
                'is_active' => 1
            ];
        }

        // Add CWPA to team (Calon Wakil Penasihat Berjangka)
        $cwpaModel = new \App\Models\CwpaModel();
        $cwpas = $cwpaModel->getActiveCwpa();
        foreach ($cwpas as $cwpa) {
            $team[] = [
                'name' => $cwpa['name'],
                'role' => 'Calon Wakil Penasihat Berjangka',
                'photo' => $cwpa['photo'] ?? '',
                'is_active' => 1
            ];
        }


        // Get data for sections moved from home
        $wpaModel = new \App\Models\WpaModel();
        $layananModel = new \App\Models\LayananModel();
        $userModel = new \App\Models\UserModel();

        // Featured WPA (using similar logic to Home)
        $featuredWpa = $this->getTopWpa();

        // Upcoming Services (Events)
        $upcomingServices = $layananModel->getUpcomingEvents(5);

        // Latest Articles (Dummy or real)
        $latestArtikel = $this->getDummyArtikel();

        return view('pages/about', [
            'title' => 'About - Almai E-Learning',
            'team' => $team,
            'featuredWpa' => $featuredWpa,
            'upcomingServices' => $upcomingServices,
            'latestArtikel' => $latestArtikel,
            'totalUsers' => $userModel->countAllResults()
        ]);
    }

    private function getTopWpa()
    {
        $wpaModel = new \App\Models\WpaModel();
        $layananModel = new \App\Models\LayananModel();
        $userModel = new \App\Models\UserModel();

        // Get active WPAs
        $wpaList = $wpaModel->where('status', 'active')->findAll();

        foreach ($wpaList as &$wpa) {
            // total_classes and total_students are already using optimized sum/count in LayananModel
            $wpa['total_classes'] = $layananModel->countByWpaId($wpa['id']);
            $wpa['total_students'] = $layananModel->getTotalStudentsByWpa($wpa['id']);

            $uid = $wpa['user_id'] ?? 0;
            if ($uid) {
                // RESTORED: Calculate full 10-level network count (MLM Style)
                // Uses optimized method to prevent crashes
                $wpa['referral_count'] = $userModel->getNetworkCount($uid);
            } else {
                $wpa['referral_count'] = 0;
            }

            // Add students to network count for better representation
            $wpa['total_network'] = ($wpa['total_students'] ?? 0) + ($wpa['referral_count'] ?? 0);
        }

        // Sort by Performance (total students + referrals)
        usort($wpaList, function ($a, $b) {
            $scoreA = ($a['total_network'] ?? 0);
            $scoreB = ($b['total_network'] ?? 0);

            if ($scoreA === $scoreB) {
                return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
            }
            return $scoreB <=> $scoreA;
        });

        return array_slice($wpaList, 0, 5);
    }

    private function getDummyArtikel()
    {
        $baseUrl = 'https://almai.id/demo-wpa/';
        return [
            [
                'id' => 1,
                'title' => '5 Kesalahan Fatal Trader Pemula yang Harus Dihindari',
                'thumbnail' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
                'excerpt' => 'Banyak trader pemula kehilangan modal karena kesalahan yang sebenarnya bisa dihindari.',
                'category' => 'Tips Trading',
                'read_time' => '5 min',
                'created_at' => '2025-01-15 10:00:00',
                'wpa_name' => 'Alit Widiastika, S.E, M.H.',
                'wpa_photo' => $baseUrl . 'images/wpa/Alit Widiastika, S.E, M.H.png',
            ],
            [
                'id' => 2,
                'title' => 'Cara Membaca Berita Ekonomi untuk Trading Forex',
                'thumbnail' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=600',
                'excerpt' => 'Berita ekonomi sangat mempengaruhi pergerakan forex. Pelajari cara membaca dan memanfaatkannya.',
                'category' => 'Fundamental',
                'read_time' => '7 min',
                'created_at' => '2025-01-10 10:00:00',
                'wpa_name' => 'Aries Yuangga, S.Si.',
                'wpa_photo' => $baseUrl . 'images/wpa/Aries Yuangga, S.Si.png',
            ],
            [
                'id' => 3,
                'title' => 'Bitcoin Halving 2024: Dampak dan Peluang Trading',
                'thumbnail' => 'https://images.unsplash.com/photo-1621761191319-c6fb62004040?w=600',
                'excerpt' => 'Bitcoin halving selalu membawa volatilitas tinggi. Bagaimana memanfaatkan momentum ini?',
                'category' => 'Crypto',
                'read_time' => '10 min',
                'created_at' => '2025-01-05 10:00:00',
                'wpa_name' => 'I Ketut Gede Baskara Tirtha, S.Hum.',
                'wpa_photo' => $baseUrl . 'images/wpa/I Ketut Gede Baskara Tirtha, S.Hum.png',
            ],
        ];
    }

    public function kontak()
    {
        return view('pages/kontak', [
            'title' => 'Kontak - Almai E-Learning'
        ]);
    }

    public function aboutRole()
    {
        return view('pages/about_role', [
            'title' => 'Peran Penasihat Berjangka - Almai E-Learning'
        ]);
    }

    public function terms()
    {
        $settingModel = new \App\Models\SettingModel();
        $terms = $settingModel->get('terms_and_conditions');

        return view('pages/terms', [
            'title' => 'Syarat dan Ketentuan - Almai E-Learning',
            'terms' => $terms
        ]);
    }

    public function privacy()
    {
        $settingModel = new \App\Models\SettingModel();
        // Assuming there might be a setting key same as terms but for privacy, 
        // if not, the view handles empty state just like terms.
        $privacy = $settingModel->get('privacy_policy');

        return view('pages/privacy', [
            'title' => 'Kebijakan Privasi - Almai E-Learning',
            'privacy' => $privacy
        ]);
    }

    public function almaiPoin()
    {
        return view('pages/almai_poin', [
            'title' => 'Almai Poin - Reward & Loyalty Program'
        ]);
    }

    public function faq()
    {
        $faqModel = new \App\Models\FaqModel();
        
        // Fetch FAQs ordered by ID
        $faqs = $faqModel->orderBy('id', 'ASC')->findAll();
        
        return view('pages/faq', [
            'title' => 'FAQ - Pertanyaan Umum',
            'faqs' => $faqs
        ]);
    }

    public function rekomendasi()
    {
        // Scan images from rekomendasi folder
        $imagePath = FCPATH . 'images/rekomendasi';
        $images = [];

        if (is_dir($imagePath)) {
            $files = scandir($imagePath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = [
                        'filename' => $file,
                        'url' => base_url('images/rekomendasi/' . $file)
                    ];
                }
            }
        }

        return view('pages/rekomendasi', [
            'title' => 'Rekomendasi & Testimoni - Almai',
            'images' => $images
        ]);
    }

    public function advokasi($slug = null)
    {
        $userModel = new \App\Models\UserModel();
        $referralData = null;
        $referralType = 'WPA';
        
        if ($slug) {
            $wpaModel = new \App\Models\WpaModel();
            $cwpaModel = new \App\Models\CwpaModel();
            
            // 1. Cek tabel WPA
            $foundWpa = $wpaModel->where('slug', $slug)
                ->where('status', 'active')
                ->first();
                
            if ($foundWpa) {
                $referralData = $foundWpa;
                $referralType = 'WPA';
            } else {
                // 2. Cek tabel CWPA jika tidak ditemukan di WPA
                $foundCwpa = $cwpaModel->where('slug', $slug)
                    ->where('status', 'active')
                    ->first();
                    
                if ($foundCwpa) {
                    $referralData = $foundCwpa;
                    $referralType = 'CWPA';
                }
            }
                
            // Jika ditemukan salah satu, proses referral code
            if ($referralData && isset($referralData['user_id'])) {
                $user = $userModel->find($referralData['user_id']);
                
                if ($user) {
                    $refCode = $user['code_referral'] ?? null;
                    
                    if (empty($refCode)) {
                        $refCode = $userModel->createUniqueReferralCode();
                        $userModel->update($user['id'], ['code_referral' => $refCode]);
                    }
                    
                    $referralData['code_referral'] = $refCode;
                    session()->set('checkout_ref', $refCode);
                    session()->set('affiliate_code', $refCode);
                }
            }
        }
        
        $totalUsers = $userModel->countAllResults();
        $recentUsers = $userModel->select('name, avatar')->orderBy('created_at', 'DESC')->limit(20)->find();

        return view('pages/advokasi', [
            'title' => 'Advokasi Perdagangan Berjangka - Almai E-Learning',
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'referralWpa' => $referralData,
            'referralType' => $referralType
        ]);
    }

    public function advokasi2()
    {
        return view('pages/advokasi2', [
            'title' => 'Satuan Tugas Pelindungan Trader - ALMAI'
        ]);
    }

    public function profirm()
    {
        return view('pages/profirm', [
            'title' => 'Almai Prop Firm - Solusi Perdagangan Eksklusif'
        ]);
    }

    public function profirmTraderLogin()
    {
        return view('pages/profirm_trader_login', [
            'title' => 'Trader Login - Almai Prop Firm'
        ]);
    }

    public function profirmAdminLogin()
    {
        return view('pages/profirm_admin_login', [
            'title' => 'Admin Login - Almai Prop Firm'
        ]);
    }

    public function pengaduan()
    {
        return view('pages/pengaduan/index', [
            'title' => 'Daftar Advokasi - Almai Platform'
        ]);
    }

    public function submitPengaduan()
    {
        // Currently just a mock submission that redirects back with success message
        return redirect()->to(base_url('pengaduan'))->with('success', 'Laporan Anda telah berhasil kami terima. Tim Advokasi ALMAI akan segera mempelajari masalah Anda dan menghubungi Anda melalui WhatsApp atau Email secepatnya.');
    }

    public function roadmaps()
    {
        return view('pages/roadmaps', [
            'title' => 'Almai Roadmaps - Rencana Masa Depan'
        ]);
    }

    public function sinyal()
    {
        $userModel = new \App\Models\UserModel();
        $totalUsers = $userModel->countAllResults();
        $recentUsers = $userModel->select('name, avatar')->orderBy('created_at', 'DESC')->limit(20)->find();

        $activeSignals = [];
        try {
            $client = \Config\Services::curlrequest();
            $apiUrl = env('SIGNAL_API_URL', 'https://z.almai.id');
            $response = $client->get($apiUrl . '/api/signals?status=ACTIVE', [
                'timeout' => 5,
                'http_errors' => false
            ]);
            
            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                if (isset($body['success']) && $body['success'] === true && isset($body['data'])) {
                    // Limit to maximum of 3 signals
                    $apiData = array_slice($body['data'], 0, 3);
                    foreach ($apiData as $apiSignal) {
                        $order = strtoupper($apiSignal['order'] ?? '');
                        if ($order === 'LONG') $order = 'BUY';
                        else if ($order === 'SHORT') $order = 'SELL';
                        
                        $activeSignals[] = [
                            'pair' => $apiSignal['pair'] ?? '',
                            'order' => $order,
                            'entry' => $apiSignal['entry'] ?? 0,
                            'sl' => $apiSignal['sl'] ?? 0,
                            'tp1' => $apiSignal['tp1'] ?? 0,
                            'tp2' => $apiSignal['tp2'] ?? 0,
                            'tp3' => $apiSignal['tp3'] ?? 0,
                            'confidence' => $apiSignal['confidence'] ?? 0,
                            'timeframe' => $apiSignal['timeframe'] ?? '',
                            'created_at' => $apiSignal['createdAt'] ?? date('Y-m-d H:i:s'),
                            'pattern' => $apiSignal['analysis']['h1']['pattern'] ?? 'Auto Generated'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch AI signals: ' . $e->getMessage());
        }

        return view('pages/sinyal', [
            'title' => 'AI Signal Analyst & Partners - ALMAI',
            'meta_title' => 'Plus500 | Almai Trading Signal',
            'meta_description' => 'Almai Signal adalah ekosistem perdagangan berbasis AI yang bekerja secara real-time mendeteksi peluang pasar.',
            'meta_image' => base_url('images/plus.jpg'),
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'activeSignals' => $activeSignals
        ]);
    }
    public function signalplus500()
    {
        $embeddedSignals = [];
        $embeddedPortfolio = (object)[];
        $embeddedPrices = (object)[];

        $apiUrl = env('SIGNAL_API_URL', 'https://z.almai.id');
        try {
            $client = \Config\Services::curlrequest();
            
            // Fetch signals
            $resSignals = $client->get($apiUrl . '/api/signals?status=ACTIVE', ['timeout' => 5, 'http_errors' => false]);
            if ($resSignals->getStatusCode() === 200) {
                $body = json_decode($resSignals->getBody(), true);
                if (isset($body['success']) && $body['success'] === true && isset($body['data'])) {
                    $embeddedSignals = $body['data'];
                }
            }
            
            // Fetch portfolio
            $resPort = $client->get($apiUrl . '/api/portfolio?startDate=2026-07-01', ['timeout' => 5, 'http_errors' => false]);
            if ($resPort->getStatusCode() === 200) {
                $body = json_decode($resPort->getBody(), true);
                if (isset($body['success']) && $body['success'] === true && isset($body['data'])) {
                    $embeddedPortfolio = $body['data'];
                }
            }

            // Fetch prices
            $resPrices = $client->get($apiUrl . '/api/prices', ['timeout' => 5, 'http_errors' => false]);
            if ($resPrices->getStatusCode() === 200) {
                $body = json_decode($resPrices->getBody(), true);
                if (isset($body['success']) && $body['success'] === true && isset($body['data'])) {
                    $embeddedPrices = $body['data'];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch signalplus500 data: ' . $e->getMessage());
        }

        $bannerModel = new \App\Models\EventBannerModel();
        $eventBanners = $bannerModel->where('is_active', 1)->orderBy('order', 'ASC')->findAll();

        return view('pages/signalplus500', [
            'title' => 'Signal Plus 500 — AI Trading Signals | Almai',
            'meta_title' => 'Signal Plus 500 — AI Trading Signals',
            'meta_description' => 'Sinyal trading AI real-time untuk Plus500. Sinyal XAUUSD, USOIL, EURUSD, JP225 dengan analisis mendalam, money management ketat, dan notifikasi WhatsApp otomatis.',
            'embeddedSignals' => $embeddedSignals,
            'embeddedPortfolio' => $embeddedPortfolio,
            'embeddedPrices' => $embeddedPrices,
            'eventBanners' => $eventBanners
        ]);
    }

    public function republicTrader()
    {
        return view('pages/republic-trader', [
            'title' => 'REPUBLIC TRADER - Project $200 to $100,000'
        ]);
    }
}
