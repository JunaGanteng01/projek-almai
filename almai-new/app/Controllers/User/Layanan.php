<?php

namespace App\Controllers\User;

use App\Controllers\Layanan as PublicLayanan;

class Layanan extends PublicLayanan
{
    public function index($categorySlug = null)
    {
        $allLayanan = $this->getLayananData();

        // Handle SEO Friendly URL (reused logic)
        $category = $this->request->getGet('category');
        if ($categorySlug) {
            $category = ucwords(str_replace('-', ' ', $categorySlug));
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
        $subcategories = [];

        if ($category && $category !== 'all') {
            $filtered = array_filter($allLayanan, fn($item) => $item['category'] === $category);
            $subcategories = array_unique(array_column($filtered, 'subcategory'));
        }

        return view('user/layanan/index', [
            'title' => 'Daftar Layanan - Almai',
            'layananList' => array_values($layananList),
            'categories' => $categories,
            'subcategories' => $subcategories,
            'currentCategory' => $category,
            'currentSubcategory' => $subcategory,
            'searchQuery' => $search,
            'activeMenu' => 'layanan',
            'pageTitle' => 'Daftar Layanan'
        ]);
    }

    public function detail($slug)
    {
        // Capture referral code key if passed (though less likely in dashboard)
        $ref = $this->request->getGet('ref');
        if ($ref) {
            session()->set('checkout_ref', $ref);
        }

        $allLayanan = $this->getLayananData();



        // Find layanan by Slug (Normal flow)
        if (!isset($layanan)) {
            $layanan = null;
            foreach ($allLayanan as $item) {
                if (isset($item['slug']) && strcasecmp(trim($item['slug']), trim($slug)) === 0) {
                    $layanan = $item;
                    break;
                }
            }
        }

        if (!$layanan) {
            return redirect()->to('/user/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        // DEBUG: Log the service type
        log_message('info', '📋 Service found - Slug: ' . ($layanan['slug'] ?? 'N/A') . ', Type: ' . ($layanan['type'] ?? 'N/A') . ', Name: ' . ($layanan['name'] ?? 'N/A'));

        // Check if PRO/Premium and User KYC
        $needsKyc = false;
        if (!empty($layanan['is_premium'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find(session()->get('userId'));

            if (($user['kyc_status'] ?? '') !== 'approved') {
                $needsKyc = true;
            }
        }

        // Get Price Packages
        $packages = $this->priceModel->getPackages($layanan['type'], $layanan['id']);

        // Get related
        $relatedLayanan = array_filter(
            $allLayanan,
            fn($item) =>
            $item['category'] === $layanan['category'] && $item['id'] != $layanan['id']
        );
        $relatedLayanan = array_slice(array_values($relatedLayanan), 0, 3);

        $extendedInfo = [
            'features' => $layanan['features'] ?? [],
            'requirements' => $layanan['requirements'] ?? [],
            'includes' => $layanan['includes'] ?? [],
            'warning' => $layanan['warning'] ?? null
        ];

        // Get Reviews
        $ulasanModel = new \App\Models\LayananUlasanModel();
        $reviews = $ulasanModel->getReviews($layanan['id'], $layanan['type']);
        $averageRating = 0;
        if (!empty($reviews)) {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $averageRating = $totalRating / count($reviews);
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

        // Check if user has purchased
        $hasPurchased = false;
        $hasReviewed = false;
        if (session()->get('isLoggedIn')) {
            $transaksiModel = new \App\Models\TransaksiModel();
            // Check by product name since ID is unreliable in aggregated views
            if ($layanan['type'] === 'cwpa') {
                $purchase = $transaksiModel->where('user_id', session()->get('userId'))
                    ->where('product_type', 'cwpa')
                    ->groupStart()
                    ->where('status', 'confirmed')
                    ->orWhere('status', 'paid')
                    ->groupEnd()
                    ->first();
            } else {
                $purchase = $transaksiModel->where('user_id', session()->get('userId'))
                    ->where('product_type', 'layanan')
                    ->where('product_name', $layanan['name'])
                    ->where('status', 'confirmed')
                    ->first();
            }

            $hasPurchased = ($purchase !== null);

            if ($hasPurchased) {
                $hasReviewed = $ulasanModel->hasUserReviewed(session()->get('userId'), $layanan['id'], $layanan['type']);
            }
        }

        // Load CWPA legal agreement document for registration modal
        $legalDocument = null;
        // Fix: CWPA is stored as type 'pendampingan', not 'cwpa'
        if ($layanan['type'] === 'pendampingan') {
            log_message('info', '🔍 Loading legal document for CWPA/Pendampingan service');
            $legalDocModel = new \App\Models\LegalDocumentModel();

            // Method 1: Try to get directly by ID 5
            $legalDocument = $legalDocModel->where('id', 5)
                ->where('is_active', 1)
                ->first();

            if ($legalDocument) {
                log_message('info', '✅ Legal document found by ID 5: ' . $legalDocument['title']);
            }

            // Method 2: If not found, search by title containing keywords
            if (!$legalDocument) {
                log_message('info', '⚠️ ID 5 not found, trying title search...');
                $legalDocument = $legalDocModel->like('title', 'PENDAMPINGAN', 'both')
                    ->where('is_active', 1)
                    ->first();

                if ($legalDocument) {
                    log_message('info', '✅ Legal document found by title: ' . $legalDocument['title']);
                }
            }

            // Method 3: If still not found, get any active document with 'surat-perjanjian-pendampingan' in slug
            if (!$legalDocument) {
                log_message('info', '⚠️ Title search failed, trying slug search...');
                $legalDocument = $legalDocModel->like('slug', 'surat-perjanjian-pendampingan', 'both')
                    ->where('is_active', 1)
                    ->first();

                if ($legalDocument) {
                    log_message('info', '✅ Legal document found by slug: ' . $legalDocument['title']);
                } else {
                    log_message('error', '❌ No legal document found at all!');
                }
            }
        } else {
            log_message('info', '⚠️ Service type is not CWPA: ' . ($layanan['type'] ?? 'unknown'));
        }

        return view('user/layanan/detail', [
            'title' => $layanan['name'] . ' - Almai',
            'meta_title' => $layanan['name'] . ' - ' . $layanan['category'] . ' | Almai',
            'meta_description' => $metaDescription,
            'meta_image' => $metaImage,
            'layanan' => $layanan,
            'packages' => $packages,
            'relatedLayanan' => $relatedLayanan,
            'extendedInfo' => $extendedInfo,
            'reviews' => $reviews,
            'averageRating' => $averageRating ?: $layanan['rating'], // Fallback to initial rating
            'reviewCount' => count($reviews),
            'hasPurchased' => $hasPurchased,
            'hasReviewed' => $hasReviewed,
            'needsKyc' => $needsKyc ?? false,
            'legalDocument' => $legalDocument
        ]);
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

    public function cwpaDetail()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Verify Purchase
        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', session()->get('userId'))
            ->where('product_type', 'cwpa')
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->first();

        if (!$purchase) {
            return redirect()->to('/user/layanan')->with('error', 'Anda belum membeli layanan ini.');
        }

        $kelas = [
            'id' => 0,
            'title' => 'Pendampingan CWPA',
            'thumbnail' => base_url('uploads/cwpa.jpg'),
            'description' => 'Program intensif persiapan ujian Calon Wakil Penasihat Berjangka (CWPA). Silakan bergabung ke grup diskusi untuk informasi lebih lanjut.',
            'type' => 'cwpa',
            'duration' => 'Lifetime',
            'level' => 'Professional',
            'students' => 100,
            'rating' => 5.0,
            'modules' => 0,
            'mode' => 'Hybrid',
            'location' => 'Online',
            'schedule' => 'Setiap Saat',
            'next_session' => 'Sekarang',
            'zoom_link' => 'https://chat.whatsapp.com/D3cUOdFmguMCfJGsozTt9B',
            'zoom_meeting_id' => '-',
            'zoom_password' => '-',
        ];

        $wpa = [
            'name' => 'Tim Almai',
            'photo' => base_url('images/logo.png'),
            'specialty' => 'Official Mentor',
            'phone' => '6285183231800'
        ];

        return view('user/kelas-detail', [
            'title' => 'Pendampingan CWPA - Almai',
            'kelas' => $kelas,
            'wpa' => $wpa,
            'materials' => [],
            'isCompleted' => false,
            'certificate' => null,
            'isLive' => true // To show the link section
        ]);
    }
}
