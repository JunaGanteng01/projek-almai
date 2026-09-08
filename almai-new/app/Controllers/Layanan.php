<?php

namespace App\Controllers;

use App\Models\LayananArtikelModel;
use App\Models\LayananEventModel;
use App\Models\LayananToolsModel;
use App\Models\LayananSubscriptionModel;
use App\Models\WpaModel;
use App\Models\LayananPriceModel;

class Layanan extends BaseController
{
    protected $artikelModel;
    protected $eventModel;
    protected $toolsModel;
    protected $subscriptionModel;
    protected $wpaModel;
    protected $priceModel;

    public function __construct()
    {
        $this->artikelModel = new LayananArtikelModel();
        $this->eventModel = new LayananEventModel();
        $this->toolsModel = new LayananToolsModel();
        $this->subscriptionModel = new LayananSubscriptionModel();
        $this->wpaModel = new WpaModel();
        $this->priceModel = new LayananPriceModel();
    }

    protected function getLayananData()
    {
        $result = [];
        $db = \Config\Database::connect();
        log_message('info', '🔍 Starting getLayananData fetching...');

        // Fetch real confirmed participants count from transaksi table
        $pxCounts = $db->table('transaksi')
            ->select('product_name, COUNT(id) as total')
            ->where('status', 'confirmed')
            ->groupBy('product_name')
            ->get()
            ->getResultArray();

        $pxMap = [];
        foreach ($pxCounts as $row) {
            $pxMap[$row['product_name']] = $row['total'];
        }

        // Helper to fix image URL
        $fixImageUrl = function ($path = null, $fallback = 'https://almai.id/images/alma.gif') {
            if (empty($path)) return $fallback;
            $pathStr = (string) $path;
            if (strpos($pathStr, 'http') === 0) return $pathStr;

            // If it starts with images/, it's in public folder
            if (strpos($pathStr, 'images/') === 0) return base_url($pathStr);

            $pathStr = preg_replace('/^writable\//', '', $pathStr);
            return base_url('file/' . ltrim($pathStr, '/'));
        };

        // 0. Get from main layanan table
        $layananModel = new \App\Models\LayananModel();
        $courses = $layananModel->getAllWithWpa();
        log_message('info', '📚 Found ' . count($courses) . ' courses from main table');
        foreach ($courses as $item) {
            if (isset($item['status']) && !in_array($item['status'], ['active', 'aktif'])) continue;

            $result[] = [
                'id' => $item['id'],
                'slug' => trim($item['slug']),
                'type' => 'layanan',
                'name' => $item['name'],
                'category' => $item['category'] === 'Almai Ultimate' ? 'Ultimate' : $item['category'],
                'subcategory' => $item['subcategory'],
                'thumbnail' => $fixImageUrl($item['thumbnail'] ?? null, null),
                'wpa_name' => $item['wpa_name'] ?? null,
                'wpa_slug' => $item['wpa_slug'] ?? null,
                'wpa_id' => $item['wpa_id'] ?? null,
                'wpa_photo' => $fixImageUrl($item['wpa_photo'] ?? null),
                'cwpa_name' => $item['cwpa_name'] ?? ($item['wpa_name'] ?? 'Tim Almai'),
                'cwpa_slug' => $item['cwpa_slug'] ?? ($item['wpa_slug'] ?? null),
                'cwpa_id' => $item['cwpa_id'] ?? ($item['wpa_id'] ?? null),
                'cwpa_photo' => $fixImageUrl($item['cwpa_photo'] ?? ($item['wpa_photo'] ?? null)),
                'level' => $item['level'] ?? 'Beginner',
                'mode' => $item['mode'] ?? 'Online',
                'rating' => $item['rating'] ?? 5.0,
                'duration' => $item['duration'] ?? 'Lifetime',
                'modules' => $item['modules'] ?? 0,
                'students' => $item['students'] ?? 0,
                'price' => $item['price'] ?? 0,
                'poin_price' => $item['poin_price'] ?? 0,
                'original_price' => $item['original_price'] ?? null,
                'description' => $item['description'],
                'location' => $item['location'] ?? 'Online',
                'is_premium' => $item['is_premium'],
                'referral_distribution_percentage' => $item['referral_distribution_percentage'] ?? 0,
            ];
        }

        // 1. Get Artikel (Advokasi)
        $artikels = $this->artikelModel->getWithWpa();
        log_message('info', '📝 Found ' . count($artikels) . ' articles');
        foreach ($artikels as $item) {
            if (isset($item['status']) && !in_array($item['status'], ['published', 'aktif'])) continue;

            $result[] = [
                'id' => $item['id'],
                'slug' => trim($item['slug']),
                'type' => 'artikel',
                'name' => $item['title'],
                'category' => 'Advokasi',
                'subcategory' => 'Artikel',
                'thumbnail' => $fixImageUrl($item['thumbnail'], null),
                'wpa_name' => $item['wpa_name'] ?? null,
                'wpa_slug' => $item['wpa_slug'] ?? null,
                'wpa_id' => $item['wpa_id'] ?? null,
                'wpa_photo' => $fixImageUrl($item['wpa_photo'] ?? null),
                'cwpa_name' => $item['cwpa_name'] ?? ($item['wpa_name'] ?? 'Tim Almai'),
                'cwpa_slug' => $item['cwpa_slug'] ?? ($item['wpa_slug'] ?? null),
                'cwpa_id' => $item['cwpa_id'] ?? ($item['wpa_id'] ?? null),
                'cwpa_photo' => $fixImageUrl($item['cwpa_photo'] ?? ($item['wpa_photo'] ?? null)),
                'level' => 'Beginner',
                'mode' => 'Online',
                'rating' => 5.0,
                'duration' => 'Unlimited',
                'modules' => 1,
                'students' => $pxMap[$item['title']] ?? ($item['view_count'] ?? 0), // Use transaction count or fallback to views
                'price' => 0,
                'poin_price' => $item['poin_price'],
                'original_price' => null,
                'description' => $item['excerpt'],
                'location' => 'Online',
                'is_premium' => $item['is_pro_only'],
                'features' => ['Analisis Mendalam', 'Update Harian'],
                'requirements' => [],
                'includes' => ['Artikel PDF'],
                'warning' => null,
                'fitur_unggulan' => $item['fitur_unggulan'] ?? null,
                'layanan_utama' => $item['layanan_utama'] ?? null,
                'referral_distribution_percentage' => $item['referral_distribution_percentage'] ?? 0,
            ];
        }

        // 2. Get Events (Webinar & Workshop)
        $events = $this->eventModel->getWithWpa();
        log_message('info', '📅 Found ' . count($events) . ' events');
        foreach ($events as $item) {
            if (isset($item['status']) && !in_array($item['status'], ['upcoming', 'published', 'aktif'])) continue;

            $subcategory = ucfirst($item['type']);

            // Calculate Duration
            $duration = '1 Sesi';
            if (!empty($item['event_date']) && !empty($item['event_end_date'])) {
                $start = strtotime($item['event_date']);
                $end = strtotime($item['event_end_date']);
                $diffHours = ($end - $start) / 3600;
                if ($diffHours < 24) {
                    $duration = round($diffHours) . ' Jam';
                } else {
                    $duration = round($diffHours / 24) . ' Hari';
                }
            }

            // Check Packages
            $packages = $this->priceModel->getPackages($item['type'], $item['id']);
            $hasPackages = !empty($packages);
            $finalPrice = $item['price'];
            $minPrice = $item['price'];
            $maxPrice = $item['price'];
            if ($hasPackages) {
                // Find min and max price
                $prices = array_column($packages, 'price');
                if (!empty($prices)) {
                    $minPrice = min($prices);
                    $maxPrice = max($prices);
                    $finalPrice = $minPrice;
                }
            }

            $result[] = [
                'id' => $item['id'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'name' => $item['title'],
                'category' => 'Advokasi',
                'subcategory' => $subcategory,
                'thumbnail' => $fixImageUrl($item['thumbnail'] ?? null, null),
                'wpa_name' => $item['wpa_name'] ?? null,
                'wpa_slug' => $item['wpa_slug'] ?? null,
                'wpa_id' => $item['wpa_id'] ?? null,
                'wpa_photo' => $fixImageUrl($item['wpa_photo'] ?? null),
                'cwpa_name' => $item['cwpa_name'] ?? ($item['wpa_name'] ?? 'Tim Almai'),
                'cwpa_slug' => $item['cwpa_slug'] ?? ($item['wpa_slug'] ?? null),
                'cwpa_id' => $item['cwpa_id'] ?? ($item['wpa_id'] ?? null),
                'cwpa_photo' => $fixImageUrl($item['cwpa_photo'] ?? ($item['wpa_photo'] ?? null)),
                'level' => 'Intermediate',
                'mode' => $item['type'] === 'webinar' ? 'Online' : 'Offline',
                'rating' => 5.0,
                'duration' => $duration,
                'modules' => 0, // Hide modules for events
                'students' => $pxMap[$item['title']] ?? ($item['current_participants'] ?? 0), // Real transaction count
                'max_participants' => $item['max_participants'] ?? 0,
                'price' => $finalPrice,
                'poin_price' => $item['poin_price'] ?? 0,
                'has_packages' => $hasPackages,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'original_price' => $item['original_price'] ?? null,
                'description' => $item['description'],
                'location' => !empty($item['location']) ? $item['location'] : ($item['type'] === 'webinar' ? 'Online' : 'TBA'),
                'is_premium' => $item['is_pro_only'],
                'features' => ['Live Session', 'Q&A'],
                'requirements' => [],
                'includes' => ['E-Certificate'],
                'warning' => null,
                'is_recurring' => $item['is_recurring'] ?? 0,
                'recurring_frequency' => $item['recurring_frequency'] ?? null,
                'recurring_day' => $item['recurring_day'] ?? null,
                'recurring_time' => $item['recurring_time'] ?? null,
                'event_date' => $item['event_date'] ?? null,
                'event_end_date' => $item['event_end_date'] ?? null,
                'total_sessions' => $item['total_sessions'] ?? 0,
                'fitur_unggulan' => $item['fitur_unggulan'] ?? null,
                'layanan_utama' => $item['layanan_utama'] ?? null,
                'referral_distribution_percentage' => $item['referral_distribution_percentage'] ?? 0,
            ];
        }

        // 3. Get Tools (EA & Toolkit)
        $tools = $this->toolsModel->getPublished();
        log_message('info', '🛠️ Found ' . count($tools) . ' tools');
        foreach ($tools as $item) {
            $category = $item['type'] === 'ea' ? 'Expert Advisor' : 'Ultimate';
            $subcategory = $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Toolkits';

            $features = json_decode($item['features'] ?? '[]', true);
            $requirements = json_decode($item['requirements'] ?? '[]', true);

            // Check Packages
            $packages = $this->priceModel->getPackages($item['type'], $item['id']);
            $hasPackages = !empty($packages);
            $finalPrice = $item['price'];
            $minPrice = $item['price'];
            $maxPrice = $item['price'];
            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                if (!empty($prices)) {
                    $minPrice = min($prices);
                    $maxPrice = max($prices);
                    $finalPrice = $minPrice;
                }
            }

            $result[] = [
                'id' => $item['id'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'name' => $item['name'],
                'category' => $category,
                'subcategory' => $subcategory,
                'thumbnail' => $fixImageUrl($item['thumbnail'], null),
                'wpa_name' => $item['wpa_name'] ?? null,
                'wpa_slug' => $item['wpa_slug'] ?? null,
                'wpa_id' => $item['wpa_id'] ?? null,
                'wpa_photo' => $fixImageUrl($item['wpa_photo'] ?? null),
                'cwpa_name' => $item['cwpa_name'] ?? ($item['wpa_name'] ?? 'Tim Almai'),
                'cwpa_slug' => $item['cwpa_slug'] ?? ($item['wpa_slug'] ?? null),
                'cwpa_id' => $item['cwpa_id'] ?? ($item['wpa_id'] ?? null),
                'cwpa_photo' => $fixImageUrl($item['cwpa_photo'] ?? ($item['wpa_photo'] ?? null)),
                'level' => 'Advanced',
                'mode' => 'Online',
                'rating' => 5.0,
                'duration' => 'Lifetime',
                'modules' => 1,
                'students' => $pxMap[$item['name']] ?? ($item['download_count'] ?? 0), // Real transaction count
                'price' => $finalPrice,
                'poin_price' => $item['poin_price'] ?? 0,
                'has_packages' => $hasPackages,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'original_price' => $item['original_price'] ?? null,
                'description' => $item['description'],
                'location' => 'Online',
                'is_premium' => $item['is_pro_only'],
                'badge' => ($item['type'] === 'ea' ? 'JFX' : null),
                'features' => $features,
                'requirements' => $requirements,
                'includes' => ['Software/Files', 'Manual'],
                'warning' => ($item['type'] === 'ea' ? 'Trading menggunakan EA tetap memiliki risiko.' : null),
                'fitur_unggulan' => $item['fitur_unggulan'] ?? null,
                'layanan_utama' => $item['layanan_utama'] ?? null,
                'referral_distribution_percentage' => $item['referral_distribution_percentage'] ?? 0,
            ];
        }

        // 4. Get Subscriptions (Pendampingan, Profirm, VIP)
        $subs = $this->subscriptionModel->getWithWpa();
        log_message('info', '💳 Found ' . count($subs) . ' subscriptions');
        foreach ($subs as $item) {
            if (isset($item['status']) && !in_array($item['status'], ['published', 'aktif'])) continue;

            // Skip services with no price and no points
            if ((empty($item['price']) || $item['price'] == 0) && (empty($item['poin_price']) || $item['poin_price'] == 0)) continue;

            $category = in_array($item['type'], ['pendampingan', 'profirm']) ? 'Advokasi' : 'Ultimate';
            $subcategoryLabels = [
                'pendampingan' => 'Pendampingan CWPA',
                'profirm' => 'Profirm',
                'vip_member' => 'VIP Member',
                'private_konsultan' => 'Private Konsultan'
            ];
            $subcategory = $subcategoryLabels[$item['type']] ?? ucfirst($item['type']);

            $features = json_decode($item['benefits'] ?? '[]', true);
            $includes = json_decode($item['includes'] ?? '[]', true);
            $requirements = json_decode($item['requirements'] ?? '[]', true);

            // Check Packages
            $packages = $this->priceModel->getPackages($item['type'], $item['id']);
            $hasPackages = !empty($packages);
            $finalPrice = $item['price'];
            $minPrice = $item['price'];
            $maxPrice = $item['price'];
            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                if (!empty($prices)) {
                    $minPrice = min($prices);
                    $maxPrice = max($prices);
                    $finalPrice = $minPrice;
                }
            }

            $result[] = [
                'id' => $item['id'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'name' => $item['name'],
                'category' => $category,
                'subcategory' => $subcategory,
                'thumbnail' => $fixImageUrl($item['thumbnail'], null),
                'wpa_name' => $item['wpa_name'] ?? null,
                'wpa_slug' => $item['wpa_slug'] ?? null,
                'wpa_id' => $item['wpa_id'] ?? null,
                'wpa_photo' => $fixImageUrl($item['wpa_photo'] ?? null),
                'cwpa_name' => $item['cwpa_name'] ?? ($item['wpa_name'] ?? 'Tim Almai'),
                'cwpa_slug' => $item['cwpa_slug'] ?? ($item['wpa_slug'] ?? null),
                'cwpa_id' => $item['cwpa_id'] ?? ($item['wpa_id'] ?? null),
                'cwpa_photo' => $fixImageUrl($item['cwpa_photo'] ?? ($item['wpa_photo'] ?? null)),
                'level' => 'All Level',
                'mode' => 'Hybrid',
                'rating' => 5.0,
                'duration' => $item['duration_days'] . ' Hari',
                'modules' => 0,
                'students' => $pxMap[$item['name']] ?? 0, // Real transaction count
                'price' => $finalPrice,
                'poin_price' => $item['poin_price'] ?? 0,
                'has_packages' => $hasPackages,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'original_price' => $item['original_price'] ?? null,
                'description' => $item['description'],
                'location' => 'Online/Offline',
                'is_premium' => $item['is_pro_only'],
                'features' => $features,
                'requirements' => $requirements,
                'includes' => $includes,
                'warning' => null,
                'total_sessions' => $item['total_sessions'] ?? 0,
                'fitur_unggulan' => $item['fitur_unggulan'] ?? null,
                'layanan_utama' => $item['layanan_utama'] ?? null,
                'referral_distribution_percentage' => $item['referral_distribution_percentage'] ?? 0,
            ];
        }

        // 5. Hardcoded Fallback for Advokasi Membership (ID 9999)
        $advokasiPrice = 88000;
        if (session()->get('isLoggedIn')) {
            // Calculate price based on spend
            $userId = session()->get('userId');
            $totalSpend = $db->table('transaksi')
                             ->where('user_id', $userId)
                             ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                             ->selectSum('total')
                             ->get()
                             ->getRowArray()['total'] ?? 0;
            $advokasiPrice = max(0, 88000 - $totalSpend);
        }

        $result[] = [
            'id' => 9999, // Virtual ID for Advokasi
            'slug' => 'advokasi',
            'type' => 'layanan',
            'name' => 'Membership Advokasi Almai',
            'category' => 'Advokasi',
            'subcategory' => 'Pendampingan',
            'thumbnail' => base_url('images/adv.png'), 
            'wpa_name' => 'Tim Almai',
            'wpa_slug' => 'tim-almai',
            'wpa_id' => 0,
            'wpa_photo' => $fixImageUrl('images/logo.png'),
            'cwpa_name' => 'Tim Almai',
            'cwpa_slug' => 'tim-almai',
            'cwpa_id' => 0,
            'cwpa_photo' => $fixImageUrl('images/logo.png'),
            'level' => 'All Level',
            'mode' => 'Online',
            'rating' => 5.0,
            'duration' => '1 Tahun',
            'modules' => 0,
            'students' => $pxMap['Membership Advokasi Almai'] ?? 0,
            'price' => $advokasiPrice,
            'poin_price' => ($advokasiPrice <= 0) ? 0 : 880,
            'original_price' => 88000,
            'description' => 'Akses penuh ke ekosistem perlindungan dan edukasi Almai selama 1 tahun.',
            'location' => 'Online',
            'is_premium' => false,
            'referral_distribution_percentage' => 0,
            'features' => ['Membership tahunan', 'Modul E-learning Premium', 'Webinar Rutin & Mentorship', 'Konsultasi Bedah Kasus', 'Akun Almai Portofolio', 'Almai Poin'],
            'requirements' => ['Wajib KYC setelah bergabung'],
            'includes' => ['E-Certificate', 'Akses Modul'],
            'layanan_utama' => "Membership tahunan\nModul E-learning Premium\nWebinar Rutin & Mentorship\nKonsultasi Bedah Kasus\nAkun Almai Portofolio\nAlmai Poin",
        ];

        log_message('info', '✅ Finished getLayananData. Total result items: ' . count($result));
        return $result;
    }

    public function index($categorySlug = null)
    {
        $allLayanan = $this->getLayananData();

        // Handle SEO Friendly URL
        $category = $this->request->getGet('category');
        if ($categorySlug) {
            // Convert slug back to category name (e.g. expert-advisor -> Expert Advisor)
            // Or simple matching
            $category = ucwords(str_replace('-', ' ', $categorySlug));

            // Special cases
            if ($categorySlug === 'expert-advisor') $category = 'Expert Advisor';
            if ($categorySlug === 'almai-ultimate' || $categorySlug === 'ultimate') $category = 'Ultimate';
        }

        if ($category === 'Almai Ultimate') $category = 'Ultimate';
        $subcategory = $this->request->getGet('subcategory');
        $search = $this->request->getGet('search');

        $layananList = $allLayanan;

        if ($category && $category !== 'all') {
            $layananList = array_filter($layananList, fn($item) => $item['category'] === $category);
        }

        if ($subcategory && $subcategory !== 'all') {
            $layananList = array_filter($layananList, fn($item) => $item['subcategory'] === $subcategory);
        }

        if ($search) {
            $layananList = array_filter(
                $layananList,
                fn($item) =>
                stripos($item['name'], $search) !== false ||
                    stripos($item['description'], $search) !== false
            );
        }

        // Get unique categories/subcategories for filters
        $categories = array_unique(array_column($allLayanan, 'category'));
        // Get subcategories from predefined list (LayananModel)
        $allSubcategories = \App\Models\LayananModel::getSubcategories();
        $subcategories = [];

        if ($category && $category !== 'all') {
            // Use predefined subcategories for the selected category
            $subcategories = $allSubcategories[$category] ?? [];
        }

        return view('pages/layanan/index', [
            'title' => 'Layanan - Almai',
            'layananList' => array_values($layananList),
            'categories' => $categories,
            'subcategories' => $subcategories,
            'currentCategory' => $category,
            'currentSubcategory' => $subcategory,
            'searchQuery' => $search,
        ]);
    }

    public function detail($slug)
    {
        // Capture referral code from URL
        $ref = $this->request->getGet('ref');
        if ($ref) {
            session()->set('checkout_ref', $ref);
        }

        $allLayanan = $this->getLayananData();

        // Find layanan by Slug instead of ID to avoid collisions
        $layanan = null;
        foreach ($allLayanan as $item) {
            // Check if item has slug and matches
            // Since we construct the array manually in getLayananData, 
            // we need to make sure we included 'slug' in it.
            if (isset($item['slug']) && strcasecmp(trim($item['slug']), trim($slug)) === 0) {
                $layanan = $item;
                break;
            }
        }

        if (!$layanan) {
            return redirect()->to('/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        $requiresAdvokasi = $this->requiresAdvokasiMembership($layanan);

        // Do not redirect from detail page; keep page visible and enforce prerequisite on CTA + checkout backend.

        // Redirect CWPA to user dashboard if logged in (has registration modal)
        if (in_array($layanan['slug'], ['pendampingan-cwpa', 'cwpa']) && session()->get('isLoggedIn')) {
            // Preserve ref code from URL
            $refCode = $this->request->getGet('ref');
            $redirectUrl = base_url('user/layanan/' . $layanan['slug']);
            if ($refCode) {
                $redirectUrl .= '?ref=' . urlencode($refCode);
            }
            return redirect()->to($redirectUrl);
        }

        // Get Price Packages
        $packages = $this->priceModel->getPackages($layanan['type'], $layanan['id']);


        $extendedInfo = [
            'features' => $layanan['features'] ?? [],
            'requirements' => $layanan['requirements'] ?? [],
            'includes' => $layanan['includes'] ?? [],
            'warning' => $layanan['warning'] ?? null,
            'layanan_utama' => $layanan['layanan_utama'] ?? null
        ];

        // Get Reviews
        $ulasanModel = new \App\Models\LayananUlasanModel();
        $reviews = $ulasanModel->getReviews($layanan['id'], $layanan['type']);
        $averageRating = 0;
        if (!empty($reviews)) {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $averageRating = $totalRating / count($reviews);
        }

        // Check if user has purchased
        $hasPurchased = false;
        $hasReviewed = false;
        if (session()->get('isLoggedIn')) {
            $transaksiModel = new \App\Models\TransaksiModel();
            // Check by product name since ID is unreliable in aggregated views
            // Or ideally by product_type = 'layanan' AND some identifier
            // For now rely on name as it is unique enough in context
            $purchase = $transaksiModel->where('user_id', session()->get('userId'))
                // ->where('product_type', 'layanan') // Relax check to catch purchases from Tools module too
                ->where('product_name', $layanan['name'])
                ->where('status', 'confirmed')
                ->first();

            $hasPurchased = ($purchase !== null);

            if ($hasPurchased) {
                $hasReviewed = $ulasanModel->hasUserReviewed(session()->get('userId'), $layanan['id'], $layanan['type']);
            }
        }

        // Prepare meta description (strip HTML and limit to 160 chars)
        $metaDescription = strip_tags($layanan['description']);
        $metaDescription = mb_substr($metaDescription, 0, 160);
        if (mb_strlen(strip_tags($layanan['description'])) > 160) {
            $metaDescription .= '...';
        }

        // Prepare meta image (use thumbnail or fallback)
        $metaImage = $layanan['thumbnail'];
        if (empty($metaImage) || strpos($metaImage, 'unsplash.com') !== false) {
            // If no thumbnail or using placeholder, use logo
            $metaImage = base_url('images/logo.png?v=3');
        }

        return view('pages/layanan/detail', [
            'title' => $layanan['name'] . ' - Almai',
            'meta_title' => $layanan['name'] . ' - ' . $layanan['category'] . ' | Almai',
            'meta_description' => $metaDescription,
            'meta_image' => $metaImage,
            'layanan' => $layanan,
            'packages' => $packages,
            'extendedInfo' => $extendedInfo,
            'reviews' => $reviews,
            'averageRating' => $averageRating ?: $layanan['rating'], // Fallback to initial rating
            'reviewCount' => count($reviews),
            'hasPurchased' => $hasPurchased,
            'hasReviewed' => $hasReviewed,
            'requiresAdvokasi' => $requiresAdvokasi,
            'hasAdvokasi' => (function() {
                if (!session()->get('isLoggedIn')) return false; 
                
                // WPA Bypass: Level 4 (WPA) and above get automatic access
                if (\App\Models\LevelModel::isStaffLevel((int) session()->get('level_id'))) return true;

                $policy = new \Config\LayananPolicy();
                $transaksiModel = new \App\Models\TransaksiModel();
                return $transaksiModel->where('user_id', session()->get('userId'))
                                      ->where('layanan_id', $policy->advokasiMembershipLayananId)
                                      ->where('status', 'confirmed')
                                      ->countAllResults() > 0;
            })()
        ]);
    }

    protected function requiresAdvokasiMembership(array $layanan): bool
    {
        $policy = new \Config\LayananPolicy();
        $slug = strtolower(trim((string) ($layanan['slug'] ?? '')));
        $exemptSlugs = array_map('strtolower', $policy->advokasiExemptSlugs);

        if (in_array($slug, $exemptSlugs, true)) {
            return false;
        }

        if (($layanan['id'] ?? null) == $policy->advokasiMembershipLayananId) {
            return false;
        }

        return true;
    }

    public function submitReview()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu');
        }

        $layananId = $this->request->getPost('layanan_id');
        $layananType = $this->request->getPost('layanan_type');
        $rating = $this->request->getPost('rating');
        $ulasan = $this->request->getPost('ulasan');

        // Verify purchase again
        $transaksiModel = new \App\Models\TransaksiModel();
        $layananName = $this->request->getPost('layanan_name'); // Need to pass name as well for verification

        $purchase = $transaksiModel->where('user_id', session()->get('userId'))
            ->where('product_type', 'layanan')
            ->where('product_name', $layananName)
            ->where('status', 'confirmed')
            ->first();

        if (!$purchase) {
            return redirect()->back()->with('error', 'Anda belum membeli layanan ini.');
        }

        $ulasanModel = new \App\Models\LayananUlasanModel();

        // Check duplicate
        if ($ulasanModel->hasUserReviewed(session()->get('userId'), $layananId, $layananType)) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan.');
        }

        $ulasanId = $ulasanModel->insert([
            'user_id' => session()->get('userId'),
            'layanan_id' => $layananId, // The specific ID (1, 2, etc)
            'layanan_type' => $layananType, // 'artikel', 'event', etc.
            'rating' => $rating,
            'ulasan' => $ulasan,
            'status' => 'approved' // Auto approve for now
        ]);

        if ($ulasanId) {
            // Notify WPA
            try {
                $db = \Config\Database::connect();
                $wpaId = null;
                $productName = $layananName;

                if ($layananType === 'artikel') $tbl = 'layanan_artikel';
                elseif (in_array($layananType, ['webinar', 'workshop', 'event'])) $tbl = 'layanan_event';
                elseif (in_array($layananType, ['ea', 'toolkit', 'tool'])) $tbl = 'layanan_tools';
                elseif (in_array($layananType, ['subscription', 'pendampingan', 'profirm', 'vip_member', 'private_konsultan'])) $tbl = 'layanan_subscription';
                else $tbl = 'layanan';

                $item = $db->table($tbl)->select('wpa_id')->where('id', $layananId)->get()->getRowArray();
                if ($item && !empty($item['wpa_id'])) {
                    $wpa = $db->table('wpa')->select('user_id')->where('id', $item['wpa_id'])->get()->getRowArray();
                    if ($wpa && $wpa['user_id']) {
                        $notifModel = new \App\Models\NotificationModel();
                        $notifModel->createNotification(
                            $wpa['user_id'],
                            "Ulasan Baru Diterima!",
                            session()->get('userName') . " memberikan rating $rating bintang untuk layanan '$productName' Anda.",
                            'success',
                            base_url('wpa/dashboard/layanan')
                        );
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Review Notification Failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
