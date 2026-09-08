<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\ToolsModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;
use App\Models\WpaModel;
use App\Models\PoinModel;
use App\Models\VoucherModel;
use App\Models\LayananPriceModel;
use App\Models\LegalDocumentModel;
use App\Libraries\XenditService;

class Checkout extends BaseController
{
    protected $viewPath = 'pages/checkout/index';

    public function index($kelasId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->set('redirectAfterLogin', '/checkout/' . $kelasId);
            return redirect()->to('/login?redirect=checkout/' . $kelasId);
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/kelas')->with('error', 'Kelas tidak ditemukan');
        }

        // Get WPA info
        $wpaModel = new WpaModel();
        $wpa = $wpaModel->find($kelas['wpa_id']);
        $kelas['wpa_name'] = $wpa ? $wpa['name'] : 'Unknown';
        $kelas['wpa_photo'] = $wpa ? $wpa['photo'] : '';
        $kelas['layanan_utama'] = implode("\n", json_decode($kelas['highlights'] ?? '[]', true) ?? []);

        // Get user info
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('userId'));

        // Get user poin balance
        $poinModel = new PoinModel();
        $poinBalance = $poinModel->getUserBalance(session()->get('userId'));

        // Get referral code from URL or session
        $refCode = $this->request->getGet('ref') ?? session()->get('checkout_ref');
        $referrer = null;

        if ($refCode) {
            $referrer = $userModel->findByReferralCode($refCode);

            // Don't allow self-referral
            if ($referrer && $referrer['id'] == session()->get('userId')) {
                $referrer = null;
                $refCode = null;
            } else {
                session()->set('checkout_ref', $refCode);
            }
        }
        // Load legal documents by type (active documents only - latest)
        $legalDocumentModel = new LegalDocumentModel();

        $perjanjianDocs = $legalDocumentModel->getByType('perjanjian');
        $legalPerjanjian = !empty($perjanjianDocs) ? $perjanjianDocs[0] : null;

        $risikoDocs = $legalDocumentModel->getByType('risiko');
        $legalRisiko = !empty($risikoDocs) ? $risikoDocs[0] : null;

        $legalProfil = $legalDocumentModel->getBySlug('profil-perusahaan');
        $legalAdvokasi = $legalDocumentModel->getBySlug('surat-advokasi');



        // Advocacy Bundle Logic - Check final price
        $userId = session()->get('userId');
        $hasAdvocacy = $this->checkUserHasAdvocacy($userId);
        $showAdvocacyBundle = !$hasAdvocacy && ($kelas['price'] >= 88000);

        // Calculate dynamic prices for display
        $bundlePrice = 0;
        $mainItemPrice = $kelas['price'];
        
        if ($showAdvocacyBundle) {
            $bundlePrice = 88000;
            $mainItemPrice = $kelas['price'] - $bundlePrice;
        }

        return view($this->viewPath, [
            'title' => 'Checkout - ' . $kelas['title'],
            'kelas' => $kelas,
            'user' => $user,
            'poinBalance' => $poinBalance,
            'referrer' => $referrer,
            'refCode' => $refCode,
            'productType' => 'kelas',
            'voucher' => $this->request->getGet('voucher'),
            'legalPerjanjian' => $legalPerjanjian,
            'legalRisiko' => $legalRisiko,
            'legalProfil' => $legalProfil,
            'legalAdvokasi' => $legalAdvokasi,
            'showAdvocacyBundle' => $showAdvocacyBundle,
            'bundlePrice' => $bundlePrice,
            'mainItemPrice' => $mainItemPrice,
        ]);
    }

    /**
     * Checkout for tools
     */
    public function tools($toolId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            $refCode = $this->request->getGet('ref');
            if ($refCode) {
                session()->set('checkout_ref', $refCode);
            }
            return redirect()->to('/login?redirect=checkout/tools/' . $toolId);
        }


        // Get user info
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('userId'));

        // Check if user can buy tools - PRO users, WPA, or Admin can buy
        $levelId = \App\Models\LevelModel::resolveLevelId(session());
        $canBuy = \App\Models\LevelModel::isStaffLevel($levelId) || ($user && $user['is_pro']);

        if (!$canBuy) {
            return redirect()->to('/tools')->with('error', 'Hanya user PRO yang dapat membeli tools. Silakan verifikasi KYC untuk upgrade ke PRO.');
        }

        $toolsModel = new ToolsModel();
        $tool = $toolsModel->find($toolId);

        if (!$tool) {
            return redirect()->to('/tools')->with('error', 'Tools tidak ditemukan');
        }

        // Get user poin balance
        $poinModel = new PoinModel();
        $poinBalance = $poinModel->getUserBalance(session()->get('userId'));

        // Get referral code from URL or session
        $refCode = $this->request->getGet('ref') ?? session()->get('checkout_ref');
        $referrer = null;

        if ($refCode) {
            $referrer = $userModel->findByReferralCode($refCode);

            // Don't allow self-referral
            if ($referrer && $referrer['id'] == session()->get('userId')) {
                $referrer = null;
                $refCode = null;
            } else {
                session()->set('checkout_ref', $refCode);
            }
        }

        // Convert tool to kelas-like format for view compatibility
        $kelas = [
            'id' => $tool['id'],
            'title' => $tool['name'],
            'thumbnail' => $tool['thumbnail'],
            'price' => $tool['price'],
            'original_price' => $tool['original_price'],
            'wpa_name' => 'Almai',
            'wpa_photo' => '',
            'category' => $tool['category'],
            'layanan_utama' => $tool['layanan_utama'] ?? null,
        ];

        // Load legal documents by type (active documents only - latest)
        $legalDocumentModel = new LegalDocumentModel();

        $perjanjianDocs = $legalDocumentModel->getByType('perjanjian');
        $legalPerjanjian = !empty($perjanjianDocs) ? $perjanjianDocs[0] : null;

        $risikoDocs = $legalDocumentModel->getByType('risiko');
        $legalRisiko = !empty($risikoDocs) ? $risikoDocs[0] : null;

        $legalProfil = $legalDocumentModel->getBySlug('profil-perusahaan');
        $legalAdvokasi = $legalDocumentModel->getBySlug('surat-advokasi');



        // Advocacy Bundle Logic - Check final price
        $userId = session()->get('userId');
        $hasAdvocacy = $this->checkUserHasAdvocacy($userId);
        $showAdvocacyBundle = !$hasAdvocacy && ($kelas['price'] >= 88000);

        // Calculate dynamic prices for display
        $bundlePrice = 0;
        $mainItemPrice = $kelas['price'];
        
        if ($showAdvocacyBundle) {
            $bundlePrice = 88000;
            $mainItemPrice = $kelas['price'] - $bundlePrice;
        }

        return view($this->viewPath, [
            'title' => 'Checkout - ' . $tool['name'],
            'kelas' => $kelas,
            'tool' => $tool,
            'user' => $user,
            'poinBalance' => $poinBalance,
            'referrer' => $referrer,
            'refCode' => $refCode,
            'productType' => 'tools',
            'voucher' => $this->request->getGet('voucher'),
            'legalPerjanjian' => $legalPerjanjian,
            'legalRisiko' => $legalRisiko,
            'legalProfil' => $legalProfil,
            'legalAdvokasi' => $legalAdvokasi,
            'showAdvocacyBundle' => $showAdvocacyBundle,
            'bundlePrice' => $bundlePrice,
            'mainItemPrice' => $mainItemPrice,
        ]);
    }

    /**
     * Checkout for layanan
     */
    public function layanan($slugOrId)
    {
        // Clear OTP verification state for fresh checkout
        session()->remove(['otp_email_verified', 'otp_wa_verified', 'checkout_verified', 'guest_verified']);

        $userModel = new UserModel();
        if (!session()->get('isLoggedIn')) {
            // Save the ref in session if available so it's not lost after login/register
            $refCode = $this->request->getGet('ref');
            if ($refCode) {
                session()->set('checkout_ref', $refCode);
            }
            return redirect()->to('/login?redirect=checkout/layanan/' . $slugOrId);
        }

        // Get user info
        $user = $userModel->find(session()->get('userId'));

        // Get user poin balance
        $poinModel = new PoinModel();
        $poinBalance = $poinModel->getUserBalance(session()->get('userId'));

        // Get layanan data
        $layananData = $this->getLayananData();
        $layanan = null;

        // Hardcoded Fallback for Advokasi Membership
        if ($slugOrId === 'advokasi' || $slugOrId == 9999) {
            $layanan = [
                'id' => 9999, // Virtual ID for Advokasi
                'name' => 'Membership Advokasi Almai',
                'slug' => 'advokasi',
                'description' => 'Akses penuh ke ekosistem perlindungan dan edukasi Almai selama 1 tahun.',
                'price' => $this->calculateAdvokasiPrice(session()->get('userId')),
                'poin_price' => 880,
                'original_price' => 88000,
                'thumbnail' => base_url('images/adv.png'), 
                'category' => 'Advokasi',
                'type' => 'subscription', // Treat as subscription
                'wpa_name' => 'Tim Almai',
                'wpa_photo' => '',
                'level' => 'All Level',
                'mode' => 'Online',
                'duration' => '1 Tahun',
                'layanan_utama' => "Membership tahunan\nModul E-learning Premium\nWebinar Rutin & Mentorship\nKonsultasi Bedah Kasus\nAkun Almai Portofolio\nAlmai Poin",
                'features' => ['Membership tahunan', 'Modul E-learning Premium', 'Webinar Rutin & Mentorship', 'Konsultasi Bedah Kasus', 'Akun Almai Portofolio', 'Almai Poin'],
                'includes' => ['E-Certificate', 'Akses Modul'],
                'requirements' => ['Wajib KYC setelah bergabung']
            ];
            // Adjust poin price if cash price is 0
            if ($layanan['price'] <= 0) {
                $layanan['poin_price'] = 0;
            }
        } else {
            foreach ($layananData as $item) {
                // Search by slug first (case-insensitive), then by ID
                if ((isset($item['slug']) && strcasecmp(trim($item['slug']), trim($slugOrId)) === 0) || (isset($item['id']) && $item['id'] == $slugOrId)) {
                    $layanan = $item;
                    break;
                }
            }
        }

        if (!$layanan) {
            log_message('error', 'Layanan not found for slug/id: ' . $slugOrId);
            return redirect()->to('/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        // Check if layanan has price or poin_price (Exempt ID 9999 as it can be 0 if user has spend)
        if ($layanan['id'] != 9999 && (!$layanan['price'] || $layanan['price'] == 0) && (!$layanan['poin_price'] || $layanan['poin_price'] == 0)) {
            log_message('warning', 'Layanan ' . $slugOrId . ' has no price or price is 0');
            return redirect()->to('/layanan/' . (isset($layanan['slug']) ? $layanan['slug'] : $slugOrId))->with('error', 'Layanan ini tidak tersedia untuk pembelian online. Silakan hubungi kami.');
        }

        // Load legal documents by type (active documents only - latest)
        $legalDocumentModel = new LegalDocumentModel();

        $perjanjianDocs = $legalDocumentModel->getByType('perjanjian');
        $legalPerjanjian = !empty($perjanjianDocs) ? $perjanjianDocs[0] : null;

        $risikoDocs = $legalDocumentModel->getByType('risiko');
        $legalRisiko = !empty($risikoDocs) ? $risikoDocs[0] : null;

        $legalProfil = $legalDocumentModel->getBySlug('profil-perusahaan');
        $legalAdvokasi = $legalDocumentModel->getBySlug('surat-advokasi');

        // Get referral code from URL or session
        $refCode = $this->request->getGet('ref') ?? session()->get('checkout_ref');
        $referralReadOnly = false;



        $referrer = null;

        if ($refCode) {
            $referrer = $userModel->findByReferralCode($refCode);

            // Don't allow self-referral
            if ($referrer && $referrer['id'] == session()->get('userId')) {
                $referrer = null;
                $refCode = null;
                $referralReadOnly = false;
            } else {
                session()->set('checkout_ref', $refCode);
            }
        }

        // Check for package selection
        $packageId = $this->request->getGet('package');
        $selectedPackage = null;

        if ($packageId) {
            $priceModel = new LayananPriceModel();
            $package = $priceModel->find($packageId);

            // Map product type to package table type (e.g. 'webinar' -> 'event')
            $packageSearchType = $layanan['type'];
            if (in_array($packageSearchType, ['webinar', 'workshop'])) $packageSearchType = 'event';
            if (in_array($packageSearchType, ['ea', 'toolkit'])) $packageSearchType = 'tool';
            if (in_array($packageSearchType, ['pendampingan', 'profirm', 'vip_member', 'private_konsultan'])) $packageSearchType = 'subscription';

            // Validate package belongs to this layanan
            if ($package && $package['layanan_id'] == $layanan['id'] && ($package['layanan_type'] == $layanan['type'] || $package['layanan_type'] == $packageSearchType)) {
                $selectedPackage = $package;
                // Override price and name
                $layanan['price'] = $package['price'];
                $layanan['original_price'] = $package['original_price'];
                $layanan['name'] .= ' - ' . $package['name'];
            }
        }


        // Advocacy Bundle Logic - MOVED HERE to check final price after package selection
        $userId = session()->get('userId');
        $hasAdvocacy = $this->checkUserHasAdvocacy($userId);
        $showAdvocacyBundle = !$hasAdvocacy && ($layanan['id'] != 9999) && ($layanan['price'] >= 88000);

        // Calculate dynamic prices for display
        $bundlePrice = 0;
        $mainItemPrice = $layanan['price'];
        
        if ($showAdvocacyBundle) {
            $bundlePrice = 88000;
            $mainItemPrice = $layanan['price'] - $bundlePrice;
        }

        // Convert layanan to kelas-like format for view compatibility
        $kelas = [
            'id' => $layanan['id'],
            'title' => $layanan['name'],
            'slug' => $layanan['slug'],
            'type' => $layanan['type'],
            'thumbnail' => $layanan['thumbnail'],
            'price' => $layanan['price'],
            'original_price' => $layanan['original_price'] ?? null,
            'poin_price' => $selectedPackage ? ($selectedPackage['poin_price'] ?? 0) : ($layanan['poin_price'] ?? 0),
            'wpa_name' => $layanan['wpa_name'] ?? 'Tim Almai',
            'wpa_photo' => $layanan['wpa_photo'] ?? '',
            'category' => $layanan['category'],
            'subcategory' => $layanan['subcategory'] ?? '',
            'level' => $layanan['level'],
            'mode' => $layanan['mode'],
            'duration' => $layanan['duration'],
            'layanan_utama' => $layanan['layanan_utama'] ?? null,
            'package_id' => $selectedPackage ? $selectedPackage['id'] : null
        ];

        return view($this->viewPath, [
            'title' => 'Checkout - ' . $layanan['name'],
            'kelas' => $kelas,
            'layanan' => $layanan,
            'user' => $user,
            'poinBalance' => $poinBalance,
            'referrer' => $referrer,
            'refCode' => $refCode,
            'referralReadOnly' => $referralReadOnly ?? false,
            'productType' => 'layanan',
            'voucher' => $this->request->getGet('voucher'),
            'legalPerjanjian' => $legalPerjanjian,
            'legalRisiko' => $legalRisiko,
            'legalProfil' => $legalProfil,
            'legalAdvokasi' => $legalAdvokasi,
            'showAdvocacyBundle' => $showAdvocacyBundle,
            'bundlePrice' => $bundlePrice,
            'mainItemPrice' => $mainItemPrice,
        ]);
    }

    /**
     * Checkout for events
     */
    public function event($slug)
    {
        // Event purchases require an authenticated account. Preserve the
        // complete checkout context so login returns to this exact product.
        if (!session()->get('isLoggedIn')) {
            $checkoutPath = '/checkout/event/' . $slug;
            $queryParams = array_filter([
                'package' => $this->request->getGet('package'),
                'ref' => $this->request->getGet('ref'),
                'voucher' => $this->request->getGet('voucher'),
            ], static fn ($value) => $value !== null && $value !== '');

            if ($queryParams !== []) {
                $checkoutPath .= '?' . http_build_query($queryParams);
            }

            if (!empty($queryParams['ref'])) {
                session()->set('checkout_ref', $queryParams['ref']);
            }

            session()->set('redirectAfterLogin', $checkoutPath);

            return redirect()->to('/login?redirect=' . rawurlencode(ltrim($checkoutPath, '/')))
                ->with('error', 'Silakan login terlebih dahulu untuk membeli event ini.');
        }

        // Clear OTP verification state for fresh checkout
        session()->remove(['otp_email_verified', 'otp_wa_verified', 'checkout_verified', 'guest_verified']);

        $userModel = new UserModel();
        // Get user info
        $user = $userModel->find(session()->get('userId'));

        // Get user poin balance
        $poinModel = new PoinModel();
        $poinBalance = $poinModel->getUserBalance(session()->get('userId'));

        // Get event data from Event controller first to check for exemptions
        $events = \App\Controllers\Event::getEvents();
        $event = null;
        foreach ($events as $e) {
            if (isset($e['slug']) && strcasecmp(trim($e['slug']), trim($slug)) === 0) {
                $event = $e;
                break;
            }
        }

        if (!$event) {
            return redirect()->to('/event')->with('error', 'Event tidak ditemukan');
        }


        // Check if event has price
        if (!$event['price']) {
            return redirect()->to('/event/' . $slug)->with('error', 'Event ini tidak tersedia untuk pembelian online. Silakan hubungi kami.');
        }

        // Load legal documents by type (active documents only - latest)
        $legalDocumentModel = new LegalDocumentModel();

        $perjanjianDocs = $legalDocumentModel->getByType('perjanjian');
        $legalPerjanjian = !empty($perjanjianDocs) ? $perjanjianDocs[0] : null;

        $risikoDocs = $legalDocumentModel->getByType('risiko');
        $legalRisiko = !empty($risikoDocs) ? $risikoDocs[0] : null;

        $legalProfil = $legalDocumentModel->getBySlug('profil-perusahaan');
        $legalAdvokasi = $legalDocumentModel->getBySlug('surat-advokasi');

        // Get referral code from URL or session
        $refCode = $this->request->getGet('ref') ?? session()->get('checkout_ref');
        $referralReadOnly = false;
        $referrer = null;

        if ($refCode) {
            $referrer = $userModel->findByReferralCode($refCode);

            // Don't allow self-referral
            if ($referrer && $referrer['id'] == session()->get('userId')) {
                $referrer = null;
                $refCode = null;
                $referralReadOnly = false;
            } else {
                session()->set('checkout_ref', $refCode);
            }
        }

        // Convert event to kelas-like format for view compatibility
        $kelas = [
            'id' => $event['id'],
            'title' => $event['title'],
            'slug' => $event['slug'],
            'type' => $event['category'],
            'thumbnail' => $event['image'],
            'price' => $event['price'],
            'original_price' => $event['original_price'] ?? null,
            'poin_price' => $event['poin_price'] ?? 0,
            'wpa_name' => $event['hosted_by'] ?? 'Tim Almai',
            'wpa_photo' => $event['wpa_photo'] ?? '',
            'category' => $event['category'],
            'level' => 'Intermediate',
            'mode' => 'Online',
            'duration' => $event['time'] ?? 'TBA',
            'layanan_utama' => $event['layanan_utama'] ?? null,
        ];

        // Advocacy Bundle Logic - Check final price
        $userId = session()->get('userId');
        $hasAdvocacy = $this->checkUserHasAdvocacy($userId);
        $showAdvocacyBundle = !$hasAdvocacy && ($kelas['price'] >= 88000);

        // Calculate dynamic prices for display
        $bundlePrice = 0;
        $mainItemPrice = $kelas['price'];
        
        if ($showAdvocacyBundle) {
            $bundlePrice = 88000;
            $mainItemPrice = $kelas['price'] - $bundlePrice;
        }

        return view($this->viewPath, [
            'title' => 'Checkout - ' . $event['title'],
            'kelas' => $kelas,
            'event' => $event,
            'user' => $user,
            'poinBalance' => $poinBalance,
            'referrer' => $referrer,
            'refCode' => $refCode,
            'referralReadOnly' => $referralReadOnly ?? false,
            'productType' => 'event',
            'voucher' => $this->request->getGet('voucher'),
            'legalPerjanjian' => $legalPerjanjian,
            'legalRisiko' => $legalRisiko,
            'legalProfil' => $legalProfil,
            'legalAdvokasi' => $legalAdvokasi,
            'showAdvocacyBundle' => $showAdvocacyBundle,
            'bundlePrice' => $bundlePrice,
            'mainItemPrice' => $mainItemPrice,
        ]);
    }

    /**
     * Checkout for CWPA (Hardcoded Service)
     */
    public function cwpa()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->set('redirectAfterLogin', '/checkout/cwpa');
            return redirect()->to('/login?redirect=checkout/cwpa');
        }

        // Get user info
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('userId'));

        // Get user poin balance
        $poinModel = new PoinModel();
        $poinBalance = $poinModel->getUserBalance(session()->get('userId'));


        // Hardcoded CWPA Service Data
        $cwpaService = [
            'id' => 0, // Virtual ID
            'name' => 'Pendampingan CWPA',
            'slug' => 'pendampingan-cwpa',
            'description' => 'Program pendampingan lengkap untuk menjadi Wakil Penasihat Berjangka (WPA) yang tersertifikasi',
            'price' => 23500000,
            'poin_price' => 235000, 
            'original_price' => null,
            'image' => '/uploads/cwpa.jpg',
            'category' => 'Pendampingan',
            'type' => 'cwpa',
            'wpa_name' => 'Tim Almai',
            'wpa_photo' => '',
            'features' => [
                'Pembekalan materi oleh WPA PT Alma Indonesia Raya yang telah berpengalaman',
                'Pendampingan selama proses memperoleh izin WPA berupa konsultasi hingga asistensi/koordinasi pendaftaran sertifikasi pada setiap tahap dengan instansi terkait',
                'Biaya-biaya sertifikasi pada tiap instansi',
                'Biaya-biaya keanggotaan',
                'Biaya administratif dokumen selama pengajuan izin CWPA (print, kirim dokumen, materai, dll)',
                'Kontrak kerja sebagai WPA pada PT Alma Indonesia Raya setelah memperoleh sertifikasi lengkap dan asistensi pendaftaran izin WPA ke instansi yang berwenang',
                'Event-event (offline dan online) yang didukung oleh PT Alma Indonesia Raya, dalam rangka meningkatkan pengetahuan mengenai PBK, WPA, dan legalitas industri'
            ]
        ];

        // Load legal documents
        $legalDocumentModel = new LegalDocumentModel();
        $perjanjianDocs = $legalDocumentModel->getByType('perjanjian');
        $legalPerjanjian = !empty($perjanjianDocs) ? $perjanjianDocs[0] : null;

        $risikoDocs = $legalDocumentModel->getByType('risiko');
        $legalRisiko = !empty($risikoDocs) ? $risikoDocs[0] : null;

        $legalProfil = $legalDocumentModel->getBySlug('profil-perusahaan');
        $legalAdvokasi = $legalDocumentModel->getBySlug('surat-advokasi');

        // Get referral code
        $refCode = $this->request->getGet('ref') ?? session()->get('checkout_ref');
        $referrer = null;

        if ($refCode) {
            $referrer = $userModel->findByReferralCode($refCode);

            // Don't allow self-referral
            if ($referrer && $referrer['id'] == session()->get('userId')) {
                $referrer = null;
                $refCode = null;
            } else {
                session()->set('checkout_ref', $refCode);
            }
        }

        // Convert to kelas-like format for view compatibility
        $kelas = [
            'id' => 0,
            'title' => $cwpaService['name'],
            'slug' => $cwpaService['slug'],
            'type' => 'cwpa',
            'thumbnail' => $cwpaService['image'],
            'price' => 23500000,
            'original_price' => $cwpaService['original_price'],
            'poin_price' => 235000,
            'wpa_name' => $cwpaService['wpa_name'],
            'wpa_photo' => $cwpaService['wpa_photo'],
            'category' => $cwpaService['category'],
            'level' => 'Professional',
            'mode' => 'Hybrid',
            'duration' => '12 Bulan',
            'layanan_utama' => implode("\n", $cwpaService['features'] ?? []),
        ];

        // Advocacy Bundle Logic - Check final price
        $userId = session()->get('userId');
        $hasAdvocacy = $this->checkUserHasAdvocacy($userId);
        $showAdvocacyBundle = !$hasAdvocacy && ($kelas['price'] >= 88000);

        // Calculate dynamic prices for display
        $bundlePrice = 0;
        $mainItemPrice = $kelas['price'];
        
        if ($showAdvocacyBundle) {
            $bundlePrice = 88000;
            $mainItemPrice = $kelas['price'] - $bundlePrice;
        }

        return view($this->viewPath, [
            'title' => 'Checkout - ' . $cwpaService['name'],
            'kelas' => $kelas,
            'cwpa' => $cwpaService,
            'user' => $user,
            'poinBalance' => $poinBalance,
            'referrer' => $referrer,
            'refCode' => $refCode,
            'productType' => 'cwpa',
            'voucher' => $this->request->getGet('voucher'),
            'legalPerjanjian' => $legalPerjanjian,
            'legalRisiko' => $legalRisiko,
            'legalProfil' => $legalProfil,
            'showAdvocacyBundle' => $showAdvocacyBundle,
            'bundlePrice' => $bundlePrice,
            'mainItemPrice' => $mainItemPrice,
        ]);
    }

    /**
     * Get layanan data (use same method as Layanan controller)
     */
    private function getLayananData()
    {
        $layananController = new \App\Controllers\Layanan();

        // We need to access the private method getLayananData from Layanan controller
        // Since it's private, we use Reflection to access it
        $method = new \ReflectionMethod(\App\Controllers\Layanan::class, 'getLayananData');
        $method->setAccessible(true);

        return $method->invoke($layananController);
    }

    /**
     * Validate voucher code via AJAX
     */
    public function validateVoucher()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['valid' => false, 'message' => 'Invalid request']);
        }

        $json = $this->request->getJSON(true);
        $code = $json['code'] ?? '';
        $productType = $json['product_type'] ?? '';
        $productId = $json['product_id'] ?? 0;
        $amount = $json['amount'] ?? 0;

        if (!$code) {
            return $this->response->setJSON(['valid' => false, 'message' => 'Kode voucher tidak boleh kosong']);
        }

        $userId = session()->get('userId');

        $voucherModel = new VoucherModel();
        $result = $voucherModel->validateVoucher($code, $userId, $productType, $productId, $amount);

        if ($result['valid']) {
            return $this->response->setJSON([
                'valid' => true,
                'voucher_id' => $result['voucher']['id'],
                'discount' => $result['discount'],
                'message' => $result['message']
            ]);
        }

        return $this->response->setJSON([
            'valid' => false,
            'message' => $result['message']
        ]);
    }

    public function process()
    {
        try {
            log_message('info', 'Checkout process started');
            // SECURITY: Jangan log seluruh POST data (berisi data sensitif)
            if (ENVIRONMENT === 'development') {
                log_message('debug', 'CHECKOUT POST DEBUG: ' . json_encode($this->request->getPost()));
            }

            $productType = $this->request->getPost('product_type') ?? 'kelas';
            $voucherProductType = $productType; // Save original for voucher validation
            
            // Prioritize ID based on product type, but fallback to others
            $productId = null;
            if ($productType === 'layanan') $productId = $this->request->getPost('layanan_id');
            elseif ($productType === 'tools') $productId = $this->request->getPost('tool_id');
            elseif ($productType === 'cwpa') $productId = $this->request->getPost('cwpa_id');
            else $productId = $this->request->getPost('kelas_id');

            // Robust fallback if still null or empty
            if (empty($productId)) {
                $productId = $this->request->getPost('kelas_id') ?: $this->request->getPost('tool_id') ?: $this->request->getPost('layanan_id') ?: $this->request->getPost('cwpa_id');
            }

            // Special Case: Advocacy Membership (ID 9999)
            // If ID 9999 is detected, ensure productType is 'layanan' for processing
            if ($productId == 9999) {
                $productType = 'layanan';
            }

            log_message('info', "Product: {$productType} ID: {$productId}");

            $rules = [
                'payment_method' => 'required',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->with('error', 'Data tidak valid');
            }

            // Advocacy Prerequisite Check for Process - Moved after product load

            // Check OTP verification for logged-in users - REMOVED: No OTP required for logged-in users
            $userId = session()->get('userId');
            // if ($userId && !session()->get('checkout_verified')) {
            //     return redirect()->back()->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
            // }

            // Check guest OTP verification
            // Check guest OTP verification - WhatsApp Only
            $guestWaVerified = session()->get('guest_wa_verified');
            log_message('debug', "Checkout Process - User ID: {$userId}, Guest WA Verified: {$guestWaVerified}");

            if (!$userId && !$guestWaVerified) {
                log_message('warning', "Guest checkout without WhatsApp verification");
                return redirect()->back()->with('error', 'Silakan verifikasi WhatsApp terlebih dahulu.');
            }

            // Clear checkout_verified after use
            session()->remove('checkout_verified');

            $paymentMethod = $this->request->getPost('payment_method');
            $usePoin = $this->request->getPost('use_poin') ? true : false;
            $poinUsed = (int) ($this->request->getPost('poin_amount') ?? 0);
            $voucherIdInput = $this->request->getPost('voucher_id');
            $voucherId = (!empty($voucherIdInput) && is_numeric($voucherIdInput)) ? (int) $voucherIdInput : null;

            // Get product based on type
            if ($productType === 'tools') {
                $toolsModel = new ToolsModel();
                $product = $toolsModel->find($productId);
                if (!$product) {
                    return redirect()->to('/tools')->with('error', 'Tools tidak ditemukan');
                }
                $productName = $product['name'];
                $productPrice = $product['price'];
                $productPoinPrice = $product['poin_price'] ?? 0;
            } elseif ($productType === 'layanan') {
                $layananType = $this->request->getPost('layanan_type');
                $layananData = $this->getLayananData();
                $product = null;

                // Hardcoded Fallback for Advokasi Membership (ID 9999)
                if ($productId == 9999) {
                    $product = [
                        'id' => 9999,
                        'name' => 'Membership Advokasi Almai',
                        'slug' => 'advokasi',
                        'price' => $this->calculateAdvokasiPrice($userId),
                        'poin_price' => 880,
                        'type' => 'subscription'
                    ];
                    if ($product['price'] <= 0) $product['poin_price'] = 0;
                } else {
                    foreach ($layananData as $item) {
                        if ($item['id'] == $productId && (empty($layananType) || $item['type'] == $layananType)) {
                            $product = $item;
                            break;
                        }
                    }
                }

                if (!$product) {
                    return redirect()->to('/layanan')->with('error', 'Layanan tidak ditemukan');
                }
                $productName = $product['name'];
                $productPrice = $product['price'];
                $productPoinPrice = $product['poin_price'] ?? 0;

                // Force CWPA type for registration logic compatibility if it's a CWPA service
                if (in_array($product['slug'], ['pendampingan-cwpa', 'cwpa'])) {
                    $productType = 'cwpa';
                } else {
                    $productType = $product['type'];
                }

                // Handle Package Price
                $packageId = $this->request->getPost('package_id');
                if ($packageId) {
                    $priceModel = new LayananPriceModel();
                    $package = $priceModel->find($packageId);

                    // Map product type to package table type (e.g. 'webinar' -> 'event')
                    $packageSearchType = $product['type'];
                    if (in_array($packageSearchType, ['webinar', 'workshop'])) $packageSearchType = 'event';
                    if (in_array($packageSearchType, ['ea', 'toolkit'])) $packageSearchType = 'tool';
                    if (in_array($packageSearchType, ['pendampingan', 'profirm', 'vip_member', 'private_konsultan'])) $packageSearchType = 'subscription';

                    // Validate package ownership
                    if ($package && $package['layanan_id'] == $productId && ($package['layanan_type'] == $product['type'] || $package['layanan_type'] == $packageSearchType)) {
                        $productPrice = $package['price'];
                        $productPoinPrice = $package['poin_price'] ?? 0;
                        $productName .= ' - ' . $package['name'];
                    }
                }
            } elseif ($productType === 'event') {
                $events = \App\Controllers\Event::getEvents();
                $product = null;
                foreach ($events as $e) {
                    if ($e['id'] == $productId) {
                        $product = $e;
                        break;
                    }
                }

                if (!$product) {
                    return redirect()->to('/event')->with('error', 'Event tidak ditemukan');
                }
                $productName = $product['title'];
                $productPrice = $product['price'];
                $productPoinPrice = $product['poin_price'] ?? 0;
            } elseif ($productType === 'cwpa') {
                // Hardcoded CWPA Service Data
                $product = [
                    'id' => 0,
                    'name' => 'Pendampingan CWPA',
                    'slug' => 'pendampingan-cwpa',
                    'price' => 23500000,
                    'poin_price' => 235000,
                    'type' => 'cwpa',
                    'cwpa_id' => 1 // Default platform/main CWPA ID
                ];
                $productName = $product['name'];
                $productPrice = $product['price'];
                $productPoinPrice = $product['poin_price'];
                $productId = 0; // Virtual ID
            } else {
                $kelasModel = new KelasModel();
                $product = $kelasModel->find($productId);
                if (!$product) {
                    return redirect()->to('/kelas')->with('error', 'Kelas tidak ditemukan');
                }
                $productName = $product['title'];
                $productPrice = $product['price'];
            }


            $userId = session()->get('userId');
            $userModel = new UserModel();

            // Handle guest registration - Create user NOW if guest checkout
            if (!$userId) {
                $guestName = $this->request->getPost('guest_name');
                $guestEmail = $this->request->getPost('guest_email');
                $guestPhone = $this->request->getPost('guest_phone');

                if (!$guestName || !$guestEmail || !$guestPhone) {
                    return redirect()->back()->with('error', 'Silakan lengkapi data diri Anda.');
                }

                // Verify if guest has completed WhatsApp verification
                if (!session()->get('guest_wa_verified')) {
                    return redirect()->back()->with('error', 'Silakan verifikasi WhatsApp terlebih dahulu.');
                }

                // Check if user already exists
                $existingUser = $userModel->where('email', $guestEmail)->first();
                if ($existingUser) {
                    return redirect()->back()->with('login_required_error', 'Email sudah terdaftar. Silakan login terlebih dahulu atau gunakan email lain.');
                }

                // Check if phone already exists (Handle 08, 628, +628)
                $cleanPhone = preg_replace('/[^0-9]/', '', $guestPhone);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }

                $phoneVariations = [
                    $guestPhone,
                    $cleanPhone,
                    '0' . substr($cleanPhone, 2),
                    '+' . $cleanPhone
                ];

                $existingPhone = $userModel->whereIn('phone', $phoneVariations)->first();
                if ($existingPhone) {
                    return redirect()->back()->with('login_required_error', 'Nomor WhatsApp sudah terdaftar. Silakan login terlebih dahulu.');
                }

                // SECURITY: Konsistensi minimum password 8 karakter (sama dengan registrasi normal)
                $password = $this->request->getPost('password');
                if (!$password || strlen($password) < 8) {
                    return redirect()->back()->with('error', 'Password harus minimal 8 karakter.');
                }

                $userData = [
                    'name' => $guestName,
                    'email' => $guestEmail,
                    'phone' => $guestPhone,
                    'password' => $password,
                    'status' => 'active',
                    'registration_types' => json_encode(['guest_checkout'])
                ];

                $newUserId = $userModel->insert($userData);
                if (!$newUserId) {
                    $errors = $userModel->errors();
                    log_message('error', 'User creation failed: ' . json_encode($errors));
                    return redirect()->back()->with('error', 'Gagal membuat akun: ' . implode(', ', $errors));
                }

                log_message('info', 'Guest user created successfully. ID: ' . $newUserId);

                // Auto-login the new user
                $user = $userModel->find($newUserId);
                session()->set([
                    'userId' => $newUserId,
                    'name' => $guestName,
                    'email' => $guestEmail,
                    'role' => 'user',
                    'isLoggedIn' => true
                ]);

                // Update userId
                $userId = $newUserId;

                // Check referral code to link affiliate
                $refCode = session()->get('checkout_ref');
                if (!$refCode) {
                    $manualRef = $this->request->getPost('referral_code');
                    if ($manualRef) $refCode = strtoupper(trim($manualRef));
                }

                if ($refCode) {
                    $userModel->update($userId, ['affiliator_code' => $refCode]);
                }

                // Send credentials via Email (async)
                // Send "Welcome" email (no password credential)
                try {
                    $emailService = new \App\Libraries\EmailService();
                    // Just send welcome email, user knows their password
                    $credentialResult = $emailService->sendGuestCredentials($guestEmail, $guestName, null);
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to send welcome email: ' . $e->getMessage());
                }

                // Clear guest verification from session
                session()->remove(['guest_verified', 'guest_email', 'guest_phone']);
            }

            $user = $userModel->find($userId);
            if (!$user) {
                log_message('error', 'Checkout process: User not found - ID ' . $userId);
                return redirect()->to('/')->with('error', 'Kesalahan sistem: User tidak ditemukan. Silakan hubungi support.');
            }

            // Memastikan user memiliki email sebelum checkout (Wajib untuk Xendit dan Invoice)
            if (empty($user['email'])) {
                return redirect()->back()->with('error', 'Untuk melakukan pembayaran, silakan lengkapi dan verifikasi email Anda di <a href="' . base_url('user/profile/edit') . '" style="text-decoration: underline; font-weight: bold; color: #3b82f6;">Menu Profil</a> terlebih dahulu.');
            }

            // Check if user already purchased this product - ALLOWED MULTIPLE PURCHASES
            /*
            $transaksiModel = new TransaksiModel();
            $existingTransaction = $transaksiModel->where('user_id', $userId)
                ->where('product_type', $productType)
                ->where('layanan_id', $productId)
                ->where('status', 'confirmed')
                ->first();

            if ($existingTransaction) {
                return redirect()->back()->with('error', 'Anda sudah membeli layanan ini. Silakan cek menu Layanan Saya.');
            }
            */

            // Prevent Double Transaction (Idempotency Check)
            $transaksiModel = new TransaksiModel();
            $duplicateCheck = $transaksiModel->where('user_id', $userId)
                ->where('product_type', $productType)
                ->where('layanan_id', $productId)
                ->where('status', 'pending')
                ->where('created_at >', date('Y-m-d H:i:s', strtotime('-2 minutes')))
                ->orderBy('id', 'DESC')
                ->first();

            if ($duplicateCheck) {
                log_message('info', "Duplicate transaction prevented for User ID {$userId}. Redirecting to existing invoice {$duplicateCheck['inv_number']}.");

                // If has payment URL, redirect there
                if (!empty($duplicateCheck['payment_url'])) {
                    return redirect()->to($duplicateCheck['payment_url']);
                }

                // Redirect to invoice page
                return redirect()->to('/user/invoice/' . $duplicateCheck['inv_number'])->with('warning', 'Transaksi tertunda ditemukan. Silakan selesaikan pembayaran ini sebelum membuat baru.');
            }

            $totalPrice = $productPrice;
            $poinDiscount = 0;
            $voucherDiscount = 0;

            // Validate and apply voucher
            if ($voucherId) {
                $voucherModel = new VoucherModel();
                $voucher = $voucherModel->find($voucherId);

                if ($voucher && $voucher['status'] === 'active') {
                    // Re-validate voucher
                    $validation = $voucherModel->validateVoucher($voucher['code'], $userId, $voucherProductType, $productId, $totalPrice);

                    if ($validation['valid']) {
                        $voucherDiscount = $validation['discount'];
                        $totalPrice -= $voucherDiscount;
                    }
                }
            }

            // Handle poin payment (full) or Point-only Service
            if ($paymentMethod === 'poin' || ($productPrice == 0 && isset($productPoinPrice) && $productPoinPrice > 0)) {
                $paymentMethod = 'poin';
                $poinModel = new PoinModel();
                $poinBalance = $poinModel->getUserBalance($userId);
                $poinNeeded = (isset($productPoinPrice) && $productPoinPrice > 0) ? $productPoinPrice : ceil($totalPrice / 100);

                if ($poinBalance < $poinNeeded) {
                    return redirect()->back()->with('error', 'Poin tidak mencukupi. Dibutuhkan ' . number_format($poinNeeded) . ' poin.');
                }

                // Deduct poin (negative value for redemption)
                $poinModel->insert([
                    'user_id' => $userId,
                    'type' => 'redeem',
                    'point' => -$poinNeeded, // Negative for deduction
                    'description' => 'Pembelian ' . (string) $productType . ': ' . $productName,
                    'pointable_type' => (string) $productType,
                    'pointable_id' => $productId,
                ]);

                $poinDiscount = 0; // Reset discount because we use points as the main value
                $totalPrice = $poinNeeded; 
                $productPrice = $poinNeeded; 
            } elseif ($usePoin && $poinUsed > 0) {
                // Partial poin usage
                $poinModel = new PoinModel();
                $poinBalance = $poinModel->getUserBalance($userId);

                if ($poinUsed > $poinBalance) {
                    $poinUsed = $poinBalance;
                }

                $poinDiscount = $poinUsed * 100; // 1 poin = Rp 100
                if ($poinDiscount > $totalPrice) {
                    $poinDiscount = $totalPrice;
                    $poinUsed = ceil($poinDiscount / 100);
                }

                $totalPrice -= $poinDiscount;

                if ($poinUsed > 0) {
                    // Deduct poin (negative value for redemption)
                    $poinModel->insert([
                        'user_id' => (int) $userId,
                        'type' => 'redeem',
                        'point' => (int) -$poinUsed, // Negative for deduction
                        'description' => 'Potongan pembelian ' . $productType . ': ' . $productName,
                        'pointable_type' => (string) $productType,
                        'pointable_id' => (int) $productId,
                    ]);
                }
            }

            // Check referral
            $refCode = session()->get('checkout_ref');

            // If not in session, check manual input
            if (!$refCode) {
                $manualRef = $this->request->getPost('referral_code');
                if ($manualRef) {
                    $refCode = strtoupper(trim($manualRef));
                }
            }

            $referrerId = null;

            if ($refCode) {
                $referrer = $userModel->findByReferralCode($refCode);
                if ($referrer && $referrer['id'] != $userId) {
                    $referrerId = $referrer['id'];
                }
            }

            // Create transaction
            $transaksiModel = new TransaksiModel();
            $invoiceNumber = $transaksiModel->generateInvoiceNumber();

            $status = (($paymentMethod === 'poin' || ($productPrice == 0 && $totalPrice == 0))) ? 'confirmed' : 'pending';

            $totalDiscount = $poinDiscount + $voucherDiscount;
            $notes = [];
            if (isset($poinNeeded) && $poinNeeded > 0 && $paymentMethod === 'poin') {
                $notes[] = 'Pembayaran poin: ' . number_format($poinNeeded, 0, ',', '.') . ' Poin';
            } elseif ($poinDiscount > 0) {
                $notes[] = 'Potongan poin: Rp ' . number_format($poinDiscount, 0, ',', '.');
            }
            if ($voucherDiscount > 0) {
                $notes[] = 'Diskon voucher: Rp ' . number_format($voucherDiscount, 0, ',', '.');
            }

            // Handle Advocacy Bundle flag
            $includeAdvocacy = ($this->request->getPost('include_advocacy') === '1');
            // Double check if user already has advocacy
            if ($includeAdvocacy && $this->checkUserHasAdvocacy($userId)) {
                $includeAdvocacy = false;
            }
            
            if ($includeAdvocacy) {
                $notes[] = 'Bundle Advokasi: Ya';
            }

            $transaksiData = [
                'invoice_number' => $invoiceNumber,
                'user_id' => (int) $userId,
                'layanan_id' => (int) $productId,
                'package_id' => $packageId ? (int) $packageId : null,
                'product_type' => (string) $productType,
                'product_name' => (string) $productName,
                'amount' => (float) ($includeAdvocacy ? $productPrice - 88000 : $productPrice),
                'discount' => (float) $totalDiscount,
                'total' => (float) ($includeAdvocacy ? $totalPrice - 88000 : $totalPrice),
                'payment_method' => (string) $paymentMethod,
                'status' => (string) $status,
                'referral_code' => $refCode ? (string) $refCode : null,
                'referrer_id' => $referrerId ? (int) $referrerId : null,
                'voucher_id' => $voucherId ? (int) $voucherId : null,
                'notes' => !empty($notes) ? implode(' | ', $notes) : null,
            ];

            // Debug: Check for array values in transaction data
            foreach ($transaksiData as $key => $value) {
                if (is_array($value)) {
                    log_message('error', "Field '{$key}' contains array: " . json_encode($value));
                    return redirect()->back()->with('error', "Error: Field '{$key}' tidak boleh berupa array.");
                }
            }

            // Debug: Log transaction data
            log_message('debug', 'Transaction data: ' . json_encode($transaksiData));

            try {
                $transaksiId = $transaksiModel->insert($transaksiData);
                if (!$transaksiId) {
                    $errors = $transaksiModel->errors();
                    log_message('error', 'Transaction insert failed: ' . json_encode($errors));
                    return redirect()->back()->with('error', 'Gagal membuat transaksi: ' . implode(', ', $errors));
                }
                $transaksiId = (int) $transaksiModel->getInsertID(); // Get proper integer ID

                // If Advocacy bundle, insert second record with same invoice number
                if ($includeAdvocacy) {
                    $advData = [
                        'invoice_number' => $invoiceNumber,
                        'user_id' => (int) $userId,
                        'layanan_id' => 9999,
                        'product_type' => 'layanan',
                        'product_name' => 'Membership Advokasi',
                        'amount' => 88000.00,
                        'discount' => 0.00,
                        'total' => 88000.00,
                        'payment_method' => (string) $paymentMethod,
                        'status' => (string) $status,
                        'notes' => 'Bundled with Transaksi #' . $transaksiId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $transaksiModel->insert($advData);
                }

                // Notify WPA of the new transaction
                if (!empty($product['wpa_id'])) {
                    try {
                        $wpaModel = new WpaModel();
                        $wpa = $wpaModel->find($product['wpa_id']);
                        if ($wpa && $wpa['user_id']) {
                            $notifModel = new \App\Models\NotificationModel();
                            $statusLabel = ($status === 'confirmed') ? 'berhasil dikonfirmasi' : 'sedang menunggu pembayaran';
                            $notifModel->createNotification(
                                $wpa['user_id'],
                                "Ada Pesanan Baru!",
                                "Layanan '{$productName}' Anda baru saja dipesan oleh " . session()->get('userName') . " ($statusLabel).",
                                'info',
                                base_url('wpa/dashboard/transaksi')
                            );
                        }
                    } catch (\Throwable $e) {
                        log_message('error', 'WPA New Order Notification Failed: ' . $e->getMessage());
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Transaction insert error: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                return redirect()->back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
            }

                // Record voucher usage
                if ($voucherId && $voucherDiscount > 0) {
                    $voucherModel = new VoucherModel();
                    $voucherModel->useVoucher($voucherId, $userId, $transaksiId, $voucherDiscount);
                }

                // Record voucher usage
                if ($voucherId && $voucherDiscount > 0) {
                    $voucherModel = new VoucherModel();
                    $voucherModel->useVoucher($voucherId, $userId, $transaksiId, $voucherDiscount);
                }

            // Clear referral from session
            session()->remove('checkout_ref');

            // If paid with poin (full) or free, process referral and send email immediately
            if ($status === 'confirmed') {
                log_message('info', 'Poin/Free payment confirmed for transaksi: ' . $transaksiId);
                
                // Process Commission & Licenses Immediately for Point/Confirmed payments
                $transaksiModel->processWpaCommission($transaksiId);
                $transaksiModel->processEaLicense($transaksiId);
                $transaksiModel->processReferralPoin($transaksiId);

                // Process Advocacy Bundle immediately for confirmed payments (Poin/Free)
                if ($includeAdvocacy) {
                    $this->grantAdvocacyMembership($userId, $transaksiId);
                }

                // Send Confirmation Email
                try {
                    $emailService = new \App\Libraries\EmailService();
                    $transaction = $transaksiModel->find($transaksiId);
                    $emailService->sendTransactionNotification($user['email'], $user['name'], $transaction);
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to send success email: ' . $e->getMessage());
                }
            }

            // Send WhatsApp Notification to CS
            try {
                $readableStatus = ($status === 'confirmed') ? 'Lunas (Otomatis)' : 'Menunggu Pembayaran';
                $formattedTotal = 'Rp ' . number_format($totalPrice, 0, ',', '.');
                $invoiceLink = base_url('user/invoice/' . $invoiceNumber);
                $userPhone = $user['phone'] ?? ($this->request->getPost('guest_phone') ?? '-');

                $ivosights = new \App\Libraries\IvosightsService();
                $ivosights->sendPurchaseNotificationToCS(
                    $user['name'],
                    $readableStatus,
                    $productName,
                    $formattedTotal,
                    $invoiceLink,
                    $userPhone
                );
            } catch (\Throwable $e) {
                log_message('error', 'Failed to send CS Purchase Notification: ' . $e->getMessage());
            }

            // Kledo Integration removed as per request


            // Handle Xendit payment (Specific Channels or Generic)
            $xenditMethods = ['BNI', 'BRI', 'BSI', 'MANDIRI', 'QRIS'];
            $cleanPaymentMethod = strtoupper(trim($paymentMethod));

            log_message('critical', "Checkout Process - Method: {$cleanPaymentMethod}, Total: {$totalPrice}");

            // Check if it's a Xendit method
            $isXendit = ($cleanPaymentMethod === 'XENDIT' || in_array($cleanPaymentMethod, $xenditMethods));

            if ($isXendit && $totalPrice > 0) {
                log_message('critical', "Entering Xendit Payment Flow");
                $xendit = new XenditService();

                // Ensure phone number is not empty for Xendit
                $phoneNumber = $user['phone'] ?? '';
                if (empty($phoneNumber)) {
                    // Try to get from POST data (for newly registered users)
                    $phoneNumber = $this->request->getPost('guest_phone') ?? $this->request->getPost('whatsapp') ?? '';
                }
                // If still empty, use a placeholder (Xendit requires it)
                if (empty($phoneNumber)) {
                    $phoneNumber = '+6281234567890'; // Placeholder
                }

                $invoiceData = [
                    'external_id' => $invoiceNumber,
                    'amount' => (int) $totalPrice,
                    'email' => $user['email'],
                    'customer_name' => $user['name'],
                    'phone' => $phoneNumber,
                    'description' => 'Pembelian ' . ucfirst($productType) . ': ' . $productName . ' - ' . $user['name'] . ' (' . $invoiceNumber . ')',
                    'item_name' => $productName,
                    'success_url' => base_url('checkout/xendit-success?invoice=' . $invoiceNumber),
                    'failure_url' => base_url('checkout/xendit-failed?invoice=' . $invoiceNumber),
                ];

                // If specific method selected, filter invoice payment methods
                if (in_array($cleanPaymentMethod, $xenditMethods)) {
                    $invoiceData['payment_methods'] = [$cleanPaymentMethod];
                }

                $result = $xendit->createInvoice($invoiceData);

                if ($result['success'] && isset($result['data']['invoice_url'])) {
                    // Update transaksi with xendit invoice id
                    $transaksiModel->update($transaksiId, [
                        'notes' => ($transaksiData['notes'] ? $transaksiData['notes'] . ' | ' : '') . 'Xendit ID: ' . $result['data']['id'] . ' | Method: ' . $cleanPaymentMethod,
                    ]);

                    // Send invoice pending email
                    $this->sendInvoicePendingEmail($transaksiData, $user, $result['data']['invoice_url'], $result['data']['expiry_date'] ?? null);

                    // Redirect to Xendit payment page
                    log_message('critical', "Redirecting to Xendit URL: " . $result['data']['invoice_url']);
                    return redirect()->to($result['data']['invoice_url']);
                } else {
                    // Xendit failed
                    log_message('error', "Xendit creation failed: " . json_encode($result));
                    $transaksiModel->update($transaksiId, [
                        'status' => 'cancelled', // Cancel failed transaction
                        'notes' => ($transaksiData['notes'] ? $transaksiData['notes'] . ' | ' : '') . 'Xendit error: ' . ($result['error'] ?? 'Unknown'),
                    ]);

                    return redirect()->to('/checkout/' . ($productType === 'kelas' ? $productId : $productType . '/' . $productId))->with('error', 'Gagal membuat pembayaran. Silakan coba lagi. (' . ($result['error'] ?? 'Server Error') . ')');
                }
            } else {
                log_message('critical', "Skipping Xendit Flow. IsXendit: " . ($isXendit ? 'YES' : 'NO') . ", Total > 0: " . ($totalPrice > 0 ? 'YES' : 'NO'));
            }

            // Redirect based on payment method
            if ($status === 'confirmed') {
                if ($productType === 'layanan') {
                    $layananType = $this->request->getPost('layanan_type') ?? ($product['type'] ?? 'layanan');
                    log_message('info', 'Checkout confirmed for layanan: ID=' . $productId . ' TrxID=' . $transaksiId);
                    // Redirect to layanan detail with transaction ID
                    return redirect()->to('/user/layanan-detail/' . $productId . '?trx=' . $transaksiId)->with('success', 'Pembayaran berhasil! Layanan sudah bisa diakses.');
                }
                return redirect()->to('/user/invoice/' . $invoiceNumber)->with('success', 'Pembayaran berhasil! ' . ($productType === 'tools' ? 'Tools' : 'Kelas') . ' sudah bisa diakses.');
            } else {
                log_message('info', 'Checkout process finished successfully - pending payment');
                return redirect()->to('/user/invoice/' . $invoiceNumber)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            }
        } catch (\Throwable $e) {
            log_message('critical', 'Checkout Process CRASH: ' . $e->getMessage());
            log_message('critical', 'Trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Sistem gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Send OTP for Guest Checkout
     */
    public function sendOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        // SECURITY: Rate Limiting anti-spam OTP (3 kali per 5 menit per IP)
        $throttler = \Config\Services::throttler();
        $ipAddress = $this->request->getIPAddress();
        if ($throttler->check(md5('checkout-otp-' . $ipAddress), 3, 300) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Terlalu banyak permintaan OTP. Tunggu 5 menit sebelum mencoba lagi.']);
        }

        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';
            $name = $json['name'] ?? 'Guest';
            $channel = $json['channel'] ?? 'whatsapp';

            if (empty($email) || empty($phone)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email dan nomor WhatsApp wajib diisi']);
            }

            // Check unique
            $userModel = new \App\Models\UserModel();
            if ($userModel->where('email', $email)->first()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email sudah terdaftar. Silakan login.',
                    'login_required' => true
                ]);
            }

            // Check if phone already exists (Handle 08, 628, +628)
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }

            $phoneVariations = [
                $phone,
                $cleanPhone, // 628...
                '0' . substr($cleanPhone, 2), // 08...
                '+' . $cleanPhone // +628...
            ];

            if ($userModel->whereIn('phone', $phoneVariations)->first()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Nomor WhatsApp sudah terdaftar. Silakan login.',
                    'login_required' => true
                ]);
            }

            $otpModel = new \App\Models\OtpModel();
            $identifier = ($channel === 'whatsapp') ? $phone : $email;
            $otp = $otpModel->generateOtp($identifier, 'registration', $channel);

            // Send OTP asynchronously (don't wait for response)
            if ($channel === 'whatsapp') {
                // WhatsApp Manual Verification: Cache registration data for webhook verification
                $password = $json['password'] ?? '';
                $affiliateCode = session()->get('checkout_ref') ?? ($json['affiliate_code'] ?? '');
                
                $regData = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $password,
                    'affiliate_code' => $affiliateCode,
                ];
                cache()->save('reg_' . preg_replace('/[^0-9]/', '', $phone), $regData, 3600); // 1 hour

                $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                $waApiKey = trim(env('WAGW_API_KEY', ''));
                $waAdminPhone = env('WA_ADMIN_PHONE', '6281234567890');
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $waUrl . '/status',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 3,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'x-api-key: ' . $waApiKey
                    ],
                ]);
                $response = curl_exec($curl);
                if (!curl_error($curl)) {
                    $resJson = json_decode($response, true);
                    if ($resJson && !empty($resJson['number'])) {
                        $waAdminPhone = $resJson['number'];
                    }
                }
                curl_close($curl);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Silakan kirim pesan ke Admin WhatsApp kami.',
                    'otp' => $otp,
                    'admin_phone' => $waAdminPhone
                ]);
            } else {
                // Email: Send asynchronously (return response immediately)
                // Store in session for quick response
                session()->set('otp_pending_email', [
                    'email' => $email,
                    'name' => $name,
                    'otp' => $otp,
                    'timestamp' => time()
                ]);

                // Send email in background (non-blocking)
                $this->sendEmailAsync($email, $name, $otp);

                $msg = 'OTP berhasil dikirim ke Email';
            }

            return $this->response->setJSON(['success' => true, 'message' => $msg, 'otp_dev' => (ENVIRONMENT === 'development' ? $otp : null)]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    /**
     * Send email asynchronously (non-blocking)
     */
    protected function sendEmailAsync(string $email, string $name, string $otp)
    {
        // Use ignore_user_abort to continue execution even if user closes connection
        ignore_user_abort(true);

        // Send email in background
        try {
            $emailService = new \App\Libraries\EmailService();
            $resp = $emailService->sendOtp($email, $name, $otp);

            if ($resp['success']) {
                log_message('info', 'OTP email sent successfully to: ' . $email);
            } else {
                log_message('error', 'Failed to send OTP email to ' . $email . ': ' . ($resp['error'] ?? $resp['message']));
            }
        } catch (\Exception $e) {
            log_message('error', 'OTP email exception: ' . $e->getMessage());
        }
    }

    /**
     * Verify OTP for Guest Checkout
     */
    public function verifyOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';
            $otp = $json['otp'] ?? '';
            $channel = $json['channel'] ?? 'whatsapp';

            $otpModel = new \App\Models\OtpModel();
            $identifier = ($channel === 'whatsapp') ? $phone : $email;

            if (!$otpModel->verifyOtp($identifier, $otp, 'registration')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa']);
            }

            // Mark this specific channel as verified in session
            $verifiedChannels = session()->get('guest_verified_channels') ?? [];
            $verifiedChannels[$channel] = true;
            session()->set('guest_verified_channels', $verifiedChannels);

            $allVerified = isset($verifiedChannels['whatsapp']) && isset($verifiedChannels['email']);

            if ($allVerified) {
                session()->set([
                    'guest_verified' => true,
                    'guest_email' => $email,
                    'guest_phone' => $phone
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi ' . ucfirst($channel) . ' berhasil',
                'all_verified' => $allVerified
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    /**
     * Verify Dual OTP (Email & WA) simultaneously
     */
    public function verifyDualOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';
            $emailOtp = $json['email_otp'] ?? '';
            $phoneOtp = $json['phone_otp'] ?? '';

            $otpModel = new \App\Models\OtpModel();

            // Determine OTP purpose based on login status
            $userId = session()->get('userId');
            $otpPurpose = $userId ? 'checkout' : 'registration';

            // Verify Email OTP
            if (!$otpModel->verifyOtp($email, $emailOtp, $otpPurpose)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kode OTP Email salah atau kadaluarsa',
                    'error_field' => 'email'
                ]);
            }

            // Verify WA OTP
            if (!$otpModel->verifyOtp($phone, $phoneOtp, $otpPurpose)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kode OTP WhatsApp salah atau kadaluarsa',
                    'error_field' => 'phone'
                ]);
            }

            // Both valid - Set Session
            if ($userId) {
                // For logged-in users, mark checkout as verified
                session()->set('checkout_verified', true);
            } else {
                // For guests, mark guest verification
                session()->set([
                    'guest_verified' => true,
                    'guest_email' => $email,
                    'guest_phone' => $phone
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi Berhasil!',
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    /**
     * Verify Single OTP (for per-channel verification)
     */
    public function verifySingleOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $json = $this->request->getJSON(true);
            $channel = $json['channel'] ?? '';
            $otp = $json['otp'] ?? '';
            $email = $json['email'] ?? '';
            $phone = $json['phone'] ?? '';

            $otpModel = new \App\Models\OtpModel();

            // Determine OTP purpose based on login status
            $userId = session()->get('userId');
            $otpPurpose = $userId ? 'checkout' : 'registration';

            if ($channel === 'email') {
                if (!$otpModel->verifyOtp($email, $otp, $otpPurpose)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Kode OTP Email salah atau kadaluarsa'
                    ]);
                }
                // Mark email as verified in session
                session()->set('otp_email_verified', true);
            } elseif ($channel === 'whatsapp') {
                if (!$otpModel->verifyOtp($phone, $otp, $otpPurpose)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Kode OTP WhatsApp salah atau kadaluarsa'
                    ]);
                }
                // Mark WhatsApp as verified in session
                session()->set('otp_wa_verified', true);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Channel tidak valid'
                ]);
            }

            // Check if both are verified
            $emailVerified = session()->get('otp_email_verified');
            $waVerified = session()->get('otp_wa_verified');
            $userId = session()->get('userId');
            $isGuestFlow = !$userId;

            // For guest: only need WhatsApp verification
            // For logged-in: need both WhatsApp and Email verification
            $isVerified = $isGuestFlow ? $waVerified : ($emailVerified && $waVerified);

            log_message('debug', "OTP Verification - Channel: {$channel}, Guest: {$isGuestFlow}, WA: {$waVerified}, Email: {$emailVerified}, Verified: {$isVerified}");

            if ($isVerified) {
                // Verification complete, set checkout as verified
                if ($userId) {
                    session()->set('checkout_verified', true);
                } else {
                    session()->set([
                        'guest_verified' => true,
                        'guest_email' => $email,
                        'guest_phone' => $phone,
                        'guest_wa_verified' => true  // Mark guest WhatsApp as verified
                    ]);
                    log_message('debug', "Guest session set: guest_wa_verified = true");
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi ' . ($channel === 'email' ? 'Email' : 'WhatsApp') . ' berhasil!'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    /**
     * Register Guest Account via AJAX (Background Process)
     */
    public function registerGuestAccount()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            // Check if user already logged in via session (prevent duplicate register)
            if (session()->get('userId')) {
                return $this->response->setJSON(['success' => true, 'message' => 'User already logged in']);
            }

            $json = $this->request->getJSON(true);
            $guestName = $json['name'] ?? '';
            $guestEmail = $json['email'] ?? '';
            $guestPhone = $json['phone'] ?? '';
            $guestPassword = $json['password'] ?? ''; // Get password from user input

            if (!$guestName || !$guestEmail || !$guestPhone || !$guestPassword) {
                return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
            }

            // Verify OTP Logic - Critical Security Check
            if (!session()->get('guest_verified') || session()->get('guest_email') !== $guestEmail) {
                return $this->response->setJSON(['success' => false, 'message' => 'Verifikasi OTP belum selesai atau email berubah']);
            }

            $userModel = new UserModel();

            // Check Existing Email (Prevent Error)
            $existingUser = $userModel->where('email', $guestEmail)->first();
            if ($existingUser) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email sudah terdaftar. Silakan login.']);
            }

            // Check Existing Phone
            $cleanPhone = preg_replace('/[^0-9]/', '', $guestPhone);
            if (str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
            $phoneVariations = [$guestPhone, $cleanPhone, '0' . substr($cleanPhone, 2), '+' . $cleanPhone];
            $existingPhone = $userModel->whereIn('phone', $phoneVariations)->first();
            if ($existingPhone) {
                return $this->response->setJSON(['success' => false, 'message' => 'Nomor WhatsApp sudah terdaftar.']);
            }

            // Create User with password from user input
            $userData = [
                'name' => $guestName,
                'email' => $guestEmail,
                'phone' => $guestPhone,
                'password' => $guestPassword, // Use password from user input
                'status' => 'active',
                'registration_types' => json_encode(['guest_checkout'])
            ];

            $newUserId = $userModel->insert($userData);
            if (!$newUserId) throw new \Exception('Gagal membuat user');

            // Set Referrer
            $refCode = session()->get('checkout_ref');
            if ($refCode) $userModel->update($newUserId, ['affiliator_code' => $refCode]);

            // Auto Login - Set Session
            session()->set([
                'userId' => $newUserId,
                'name' => $guestName,
                'email' => $guestEmail,
                'role' => 'user',
                'isLoggedIn' => true
            ]);

            // Clear guest verification session
            session()->remove(['guest_verified', 'guest_email', 'guest_phone']);

            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Send OTP for Logged-in User Checkout
     */
    public function sendUserOtp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        try {
            $userId = session()->get('userId');
            if (!$userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Anda belum login.']);
            }

            $json = $this->request->getJSON(true);
            $channel = $json['channel'] ?? 'whatsapp';

            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan.']);
            }
            
            $email = $user['email'];
            $phone = $user['phone'] ?? '';
            $name = $user['name'] ?? 'User';

            if ($channel === 'whatsapp' && empty($phone)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Nomor WhatsApp belum diatur. Silakan update profil Anda.']);
            }

            if ($channel === 'email' && empty($email)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email belum diatur. Silakan update profil Anda.']);
            }

            $otpModel = new \App\Models\OtpModel();
            $identifier = ($channel === 'whatsapp') ? $phone : $email;
            $otp = $otpModel->generateOtp($identifier, 'checkout', $channel);

            if ($channel === 'whatsapp') {
                $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                $waApiKey = trim(env('WAGW_API_KEY', ''));
                $waAdminPhone = env('WA_ADMIN_PHONE', '6281234567890');
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $waUrl . '/status',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 3,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'x-api-key: ' . $waApiKey
                    ],
                ]);
                $response = curl_exec($curl);
                if (!curl_error($curl)) {
                    $resJson = json_decode($response, true);
                    if ($resJson && !empty($resJson['number'])) {
                        $waAdminPhone = $resJson['number'];
                    }
                }
                curl_close($curl);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Silakan kirim pesan ke Admin WhatsApp kami.',
                    'otp' => $otp,
                    'admin_phone' => $waAdminPhone
                ]);
            } else {
                // Email: Send asynchronously (return response immediately)
                $this->sendEmailAsync($email, $name, $otp);
                $msg = 'OTP berhasil dikirim ke Email';
            }

            return $this->response->setJSON(['success' => true, 'message' => $msg, 'otp_dev' => (ENVIRONMENT === 'development' ? $otp : null)]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sistem error: ' . $e->getMessage()]);
        }
    }

    /**
     * Xendit success callback
     */
    public function xenditSuccess()
    {
        $invoiceNumber = $this->request->getGet('invoice');

        log_message('critical', '=== XENDIT SUCCESS CALLBACK TRIGGERED ===');
        log_message('critical', 'Invoice: ' . $invoiceNumber);
        log_message('critical', 'Session isLoggedIn: ' . (session()->get('isLoggedIn') ? 'YES' : 'NO'));
        log_message('critical', 'Session userId: ' . session()->get('userId'));

        // Find the transaction to redirect properly
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        log_message('critical', 'Transaction found: ' . ($transaksi ? 'YES' : 'NO'));
        if ($transaksi) {
            log_message('critical', 'Transaction ID: ' . $transaksi['id']);
            log_message('critical', 'Transaction Type: ' . $transaksi['product_type']);
            log_message('critical', 'Transaction User ID: ' . $transaksi['user_id']);
            log_message('critical', 'Transaction Layanan ID: ' . $transaksi['layanan_id']);
        }

        // Always restore user session from transaction (don't rely on session cookie from Xendit)
        if ($transaksi) {
            log_message('critical', 'Restoring session for user: ' . $transaksi['user_id']);
            $userModel = new UserModel();
            $user = $userModel->find($transaksi['user_id']);

            if ($user) {
                log_message('critical', 'User found: ' . $user['name']);
                // Force set session - this ensures user is logged in
                session()->set([
                    'userId' => (int)$user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'user',
                    'isLoggedIn' => true
                ]);
                log_message('critical', 'Session set successfully. isLoggedIn=' . session()->get('isLoggedIn'));
            } else {
                log_message('critical', 'User NOT found!');
            }
        } else {
            log_message('critical', 'Transaction NOT found! Cannot restore session.');
        }

        // Redirect to layanan detail if it's a layanan product
        if ($transaksi && $transaksi['product_type'] === 'layanan') {
            $redirectUrl = '/user/layanan-detail/' . $transaksi['layanan_id'] . '?trx=' . $transaksi['id'];
            log_message('critical', 'Redirecting to layanan detail: ' . $redirectUrl);
            log_message('critical', 'Session before redirect - isLoggedIn: ' . session()->get('isLoggedIn') . ', userId: ' . session()->get('userId'));
            return redirect()->to($redirectUrl)->with('success', 'Pembayaran berhasil! Layanan sudah bisa diakses.');
        }

        // Redirect to event detail if it's an event product
        if ($transaksi && $transaksi['product_type'] === 'event') {
            $redirectUrl = '/user/event-detail/' . $transaksi['layanan_id'] . '?trx=' . $transaksi['id'];
            log_message('critical', 'Redirecting to event detail: ' . $redirectUrl);
            log_message('critical', 'Session before redirect - isLoggedIn: ' . session()->get('isLoggedIn') . ', userId: ' . session()->get('userId'));
            return redirect()->to($redirectUrl)->with('success', 'Pembayaran berhasil! Event sudah bisa diakses.');
        }

        // Redirect to layanan saya if it's a cwpa product
        if ($transaksi && $transaksi['product_type'] === 'cwpa') {
            return redirect()->to('/user/layanan-saya')->with('success', 'Pembayaran berhasil! Silakan cek status pendaftaran Anda.');
        }

        $redirectUrl = '/user/invoice/' . $invoiceNumber;
        log_message('critical', 'Redirecting to invoice: ' . $redirectUrl);
        return redirect()->to($redirectUrl)->with('success', 'Pembayaran sedang diproses. Status akan diupdate otomatis.');
    }

    /**
     * Xendit failed callback
     */
    public function xenditFailed()
    {
        $invoiceNumber = $this->request->getGet('invoice');

        log_message('critical', '=== XENDIT FAILED CALLBACK TRIGGERED ===');
        log_message('critical', 'Invoice: ' . $invoiceNumber);

        // Restore user session from transaction
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if ($transaksi) {
            log_message('critical', 'Restoring session for user: ' . $transaksi['user_id']);
            $userModel = new UserModel();
            $user = $userModel->find($transaksi['user_id']);

            if ($user) {
                log_message('critical', 'User found: ' . $user['name']);
                session()->set([
                    'userId' => (int)$user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'user',
                    'isLoggedIn' => true
                ]);
                log_message('critical', 'Session restored successfully');
            }
        }

        return redirect()->to('/user/invoice/' . $invoiceNumber)->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
    }

    /**
     * Xendit webhook callback
     */
    public function xenditCallback()
    {
        $json = $this->request->getJSON(true);

        // Verify callback token
        $callbackToken = $this->request->getHeaderLine('x-callback-token');
        $xendit = new XenditService();

        if (!$xendit->verifyCallbackToken($callbackToken)) {
            log_message('error', 'Xendit callback: Invalid token');
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Invalid token']);
        }

        $externalId = $json['external_id'] ?? null;
        $status = $json['status'] ?? null;

        if (!$externalId || !$status) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid data']);
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $externalId)->first();

        if (!$transaksi) {
            log_message('error', 'Xendit callback: Transaction not found - ' . $externalId);
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Transaction not found']);
        }

        // Update status based on Xendit status - FAST OPERATION ONLY
        $newStatus = 'pending';
        if ($status === 'PAID' || $status === 'SETTLED') {
            $newStatus = 'confirmed';
        } elseif ($status === 'EXPIRED') {
            $newStatus = 'cancelled';
        }

        // Update transaction status ONLY (fast operation) - Update ALL records with same invoice number
        $transaksiModel->where('invoice_number', $externalId)->set([
            'status' => $newStatus,
            'paid_at' => ($newStatus === 'confirmed') ? date('Y-m-d H:i:s') : null,
            'confirmed_at' => ($newStatus === 'confirmed') ? date('Y-m-d H:i:s') : null,
        ])->update();

        log_message('info', 'Xendit callback: ' . $externalId . ' - ' . $status . ' -> ' . $newStatus);

        // Return success immediately to Xendit (don't wait for heavy operations)
        $response = $this->response->setJSON(['success' => true]);

        // Schedule heavy operations to run AFTER response is sent
        // These will be processed asynchronously
        if ($newStatus === 'confirmed') {
            log_message('info', 'Processing post-payment tasks for transaksi: ' . $transaksi['id']);
            $this->processPostPaymentTasks($transaksi['id']);
        }

        return $response;
    }

    /**
     * Process post-payment tasks (referral, commission, email, etc)
     * This should be called from a cron job or background queue
     * to avoid webhook timeout
     */
    public function processPostPaymentTasks($transaksiId = null)
    {
        try {
            $transaksiModel = new TransaksiModel();

            // If no specific transaksi ID, process all confirmed transactions that haven't been processed yet
            if (!$transaksiId) {
                // Get all confirmed transactions from last 1 hour that haven't been processed
                $transaksis = $transaksiModel
                    ->where('status', 'confirmed')
                    ->where('mail_invoice_success', 'not_sent') // Use mail_invoice_success as indicator
                    ->where('confirmed_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
                    ->findAll();
            } else {
                $transaksis = [$transaksiModel->find($transaksiId)];
            }

            if (empty($transaksis)) {
                log_message('info', 'No pending post-payment tasks');
                return;
            }

            foreach ($transaksis as $transaksi) {
                log_message('info', 'Processing post-payment tasks for transaksi: ' . $transaksi['id']);

                try {
                    // Process referral poin
                    if ($transaksi['referrer_id']) {
                        log_message('info', 'Processing referral poin for transaksi: ' . $transaksi['id']);
                        $transaksiModel->processReferralPoin($transaksi['id']);
                    }

                    // Process WPA Commission
                    log_message('info', 'Processing WPA commission for transaksi: ' . $transaksi['id']);
                    $transaksiModel->processWpaCommission($transaksi['id']);

                    // Process poin purchase
                    if ($transaksi['product_type'] === 'poin') {
                        log_message('info', 'Processing poin purchase for transaksi: ' . $transaksi['id']);
                        $this->processPoinPurchase($transaksi);
                    }

                    // Calculate and set expires_at
                    if (!empty($transaksi['package_id'])) {
                        $priceModel = new \App\Models\LayananPriceModel();
                        $package = $priceModel->find($transaksi['package_id']);
                        if ($package && !empty($package['duration_days'])) {
                            $expiresAt = date('Y-m-d H:i:s', strtotime('+' . $package['duration_days'] . ' days'));
                            $transaksiModel->update($transaksi['id'], ['expires_at' => $expiresAt]);
                            log_message('info', 'Set expires_at to ' . $expiresAt . ' for transaksi ' . $transaksi['id']);
                        }
                    } elseif ($transaksi['product_type'] === 'layanan' && !empty($transaksi['layanan_id'])) {
                        $db = \Config\Database::connect();
                        $sub = $db->table('layanan_subscription')->where('layanan_id', $transaksi['layanan_id'])->get()->getRowArray();
                        if ($sub && !empty($sub['duration_days'])) {
                            $expiresAt = date('Y-m-d H:i:s', strtotime('+' . $sub['duration_days'] . ' days'));
                            $transaksiModel->update($transaksi['id'], ['expires_at' => $expiresAt]);
                            log_message('info', 'Set expires_at to ' . $expiresAt . ' for transaksi ' . $transaksi['id']);
                        }
                    }

                    // Generate Activation Code for services
                    log_message('info', 'Generating activation code for transaksi: ' . $transaksi['id']);
                    $transaksiModel->generateActivationCode($transaksi['id']);

                    // Process CWPA registration
                    if ($transaksi['product_type'] === 'cwpa') {
                        log_message('info', 'Processing CWPA payment for transaksi: ' . $transaksi['id']);
                        $cwpaModel = new \App\Models\CwpaSubmissionModel();
                        $cwpaModel->where('user_id', $transaksi['user_id'])->update(null, ['payment_status' => 'paid']);

                        // Notify Admins
                        try {
                            $userModel = new \App\Models\UserModel();
                            $notifModel = new \App\Models\NotificationModel();

                            // Get user name who registered
                            $registrant = $userModel->find($transaksi['user_id']);
                            $registrantName = $registrant['name'] ?? 'User';

                            // Find all Admins and Super Admins
                            $admins = $userModel->groupStart()
                                ->where('level_id', \App\Models\LevelModel::LEVEL_ADMIN)
                                ->orWhere('level_id', \App\Models\LevelModel::LEVEL_SUPER_ADMIN)
                                ->groupEnd()
                                ->findAll();

                            foreach ($admins as $admin) {
                                $notifModel->createNotification(
                                    $admin['id'],
                                    "Pendaftaran CWPA Baru",
                                    "User $registrantName telah melakukan pembayaran pendaftaran CWPA via {$transaksi['payment_method']}. Segera lakukan verifikasi.",
                                    'info',
                                    base_url('admin/cwpa/verification')
                                );
                            }
                        } catch (\Throwable $e) {
                            log_message('error', 'CWPA Admin Notification Failed: ' . $e->getMessage());
                        }
                    }

                    // Send payment success email with PDF
                    log_message('info', 'Sending payment success email for transaksi: ' . $transaksi['id']);
                    $this->sendPaymentSuccessEmail($transaksi);

                    // Kledo integration removed


                    // Mark as processed - redundant as mail statuses are updated individually
                    // $transaksiModel->update($transaksi['id'], ['legal_sent' => 1]);

                    log_message('info', 'Post-payment tasks completed for transaksi: ' . $transaksi['id']);
                } catch (\Exception $e) {
                    log_message('error', 'Error processing post-payment tasks for transaksi ' . $transaksi['id'] . ': ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in processPostPaymentTasks: ' . $e->getMessage());
        }
    }

    /**
     * Process poin purchase after payment confirmed
     */
    protected function processPoinPurchase($transaksi)
    {
        $poinModel = new PoinModel();
        $notifModel = new \App\Models\NotificationModel();

        // Parse poin from notes (format: "Poin: 1000 + Bonus: 100 | Xendit ID: xxx")
        $notes = $transaksi['notes'] ?? '';
        preg_match('/Poin: (\d+)/', $notes, $poinMatch);
        preg_match('/Bonus: (\d+)/', $notes, $bonusMatch);

        $poinAmount = isset($poinMatch[1]) ? (int)$poinMatch[1] : 0;
        $bonusAmount = isset($bonusMatch[1]) ? (int)$bonusMatch[1] : 0;

        if ($poinAmount <= 0) {
            log_message('error', 'Poin purchase: Invalid poin amount for transaksi ' . $transaksi['id']);
            return;
        }

        // Add main poin (positive value for earning)
        $poinModel->insert([
            'user_id' => $transaksi['user_id'],
            'type' => 'earn',
            'point' => $poinAmount, // Positive for earning
            'description' => 'Pembelian Service Fee: ' . number_format($poinAmount) . ' Poin',
            'pointable_type' => 'purchase',
            'pointable_id' => $transaksi['id'],
        ]);

        // Add bonus poin if any
        if ($bonusAmount > 0) {
            $poinModel->insert([
                'user_id' => $transaksi['user_id'],
                'type' => 'bonus',
                'point' => $bonusAmount, // Positive for bonus
                'description' => 'Bonus pembelian Service Fee',
                'pointable_type' => 'purchase_bonus',
                'pointable_id' => $transaksi['id'],
            ]);
        }

        $totalPoin = $poinAmount + $bonusAmount;

        // Send notification to user
        $notifModel->createNotification(
            $transaksi['user_id'],
            'Poin Berhasil Ditambahkan',
            'Selamat! ' . number_format($totalPoin) . ' poin telah ditambahkan ke akun Anda.' . ($bonusAmount > 0 ? ' (termasuk bonus ' . number_format($bonusAmount) . ' poin)' : ''),
            'success',
            '/user/poin'
        );

        log_message('info', 'Poin purchase processed: User ' . $transaksi['user_id'] . ' received ' . $totalPoin . ' poin');
    }

    /**
     * Send payment success email with PDF invoice and legal documents
     */
    protected function sendPaymentSuccessEmail($transaksi)
    {
        try {
            $userModel = new UserModel();
            $user = $userModel->find($transaksi['user_id']);

            if (!$user) {
                log_message('error', 'sendPaymentSuccessEmail: User not found for transaksi ' . $transaksi['id']);
                return;
            }

            // Update transaksi with paid_at for email
            $transaksi['paid_at'] = date('Y-m-d H:i:s');

            // Fetch all items for this invoice to include in PDF
            $transaksiModel = new \App\Models\TransaksiModel();
            $items = $transaksiModel->where('invoice_number', $transaksi['invoice_number'])->findAll();

            // Generate PDF invoice
            $pdfService = new \App\Libraries\InvoicePdfService();
            $pdfPath = $pdfService->generate($transaksi, $user, $items);

            // Send email with PDF attachment
            $emailService = new \App\Libraries\EmailService();
            $result = $emailService->sendPaymentSuccess(
                $user['email'],
                $user['name'],
                $transaksi,
                $pdfPath
            );

            if ($result['success']) {
                log_message('info', 'Payment success email sent to ' . $user['email']);
                (new TransaksiModel())->where('invoice_number', $transaksi['invoice_number'])->set(['mail_invoice_success' => 'sent'])->update();
            } else {
                log_message('error', 'Failed to send payment success email: ' . ($result['error'] ?? $result['message']));
                (new TransaksiModel())->update($transaksi['id'], ['mail_invoice_success' => 'failed']);
            }

            // Generate and send legal documents (Perjanjian & Risiko)
            try {
                // Get layanan data for WPA name
                $layananData = $this->getLayananDataForEmail($transaksi);
                $wpaName = $layananData['wpa_name'] ?? 'Tim Almai';

                $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
                $tempDir = WRITEPATH . 'uploads/invoices/';
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                $attachments = [];
                $trackingData = [];

                // 1. Perjanjian Pemberian Jasa (Standard)
                $perjanjianPdf = $legalPdfService->generatePerjanjianPdf($transaksi, $user, $layananData, $wpaName);
                if ($perjanjianPdf) {
                    $path = $tempDir . 'legal_perjanjian_' . $transaksi['invoice_number'] . '.pdf';
                    file_put_contents($path, $perjanjianPdf);
                    $attachments['Perjanjian_Pemberian_Jasa_' . $transaksi['invoice_number']] = $path;
                }

                // 2. Dokumen Pemberitahuan Risiko (Standard)
                $risikoPdf = $legalPdfService->generateRisikoPdf($transaksi, $user, $layananData);
                if ($risikoPdf) {
                    $path = $tempDir . 'legal_risiko_' . $transaksi['invoice_number'] . '.pdf';
                    file_put_contents($path, $risikoPdf);
                    $attachments['Dokumen_Pemberitahuan_Risiko_' . $transaksi['invoice_number']] = $path;
                }

                // CWPA Specific Documents
                if ($transaksi['product_type'] === 'cwpa') {
                    // 3. Profil Perusahaan
                    $profilPdf = $legalPdfService->generateProfilPerusahaanPdf($transaksi, $user);
                    if ($profilPdf) {
                        $path = $tempDir . 'profil_perusahaan_' . $transaksi['invoice_number'] . '.pdf';
                        file_put_contents($path, $profilPdf);
                        $attachments['Profil_Perusahaan_' . $transaksi['invoice_number']] = $path;
                    }

                    // 4. Perjanjian WPA
                    $wpaPdf = $legalPdfService->generatePerjanjianWpaPdf($transaksi, $user, $layananData);
                    if ($wpaPdf) {
                        $path = $tempDir . 'perjanjian_wpa_' . $transaksi['invoice_number'] . '.pdf';
                        file_put_contents($path, $wpaPdf);
                        $attachments['Perjanjian_WPA_' . $transaksi['invoice_number']] = $path;
                    }
                }

                if (empty($attachments)) {
                    log_message('error', 'No legal PDFs generated for ' . $transaksi['invoice_number']);
                    return;
                }

                // Send legal documents email
                $legalResult = $emailService->sendLegalDocuments(
                    $user['email'],
                    $user['name'],
                    $attachments,
                    $transaksi['invoice_number']
                );

                if ($legalResult['success']) {
                    log_message('info', 'Legal documents sent to ' . $user['email']);
                    $trackingData['mail_legal_pemberian_jasa'] = isset($attachments['Perjanjian_Pemberian_Jasa_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                    $trackingData['mail_legal_risiko'] = isset($attachments['Dokumen_Pemberitahuan_Risiko_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';

                    if ($transaksi['product_type'] === 'cwpa') {
                        $trackingData['mail_legal_profil'] = isset($attachments['Profil_Perusahaan_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                        $trackingData['mail_legal_wpa'] = isset($attachments['Perjanjian_WPA_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                    }
                } else {
                    log_message('error', 'Failed to send legal documents: ' . ($legalResult['error'] ?? $legalResult['message']));
                    $trackingData['mail_legal_pemberian_jasa'] = 'failed';
                    $trackingData['mail_legal_risiko'] = 'failed';
                    if ($transaksi['product_type'] === 'cwpa') {
                        $trackingData['mail_legal_profil'] = 'failed';
                        $trackingData['mail_legal_wpa'] = 'failed';
                    }
                }

                (new TransaksiModel())->update($transaksi['id'], $trackingData);

                // Clean up legal PDFs immediately
                foreach ($attachments as $path) {
                    if (file_exists($path)) @unlink($path);
                }
            } catch (\Exception $e) {
                log_message('error', 'Legal documents generation/send error: ' . $e->getMessage());
            }

            // Clean up invoice PDF file after sending (optional)
            // if (file_exists($pdfPath)) unlink($pdfPath);

        } catch (\Exception $e) {
            log_message('error', 'sendPaymentSuccessEmail error: ' . $e->getMessage());
        }
    }

    /**
     * Get layanan data for email (simplified version)
     */
    private function getLayananDataForEmail($transaksi)
    {
        $layananData = [
            'id' => $transaksi['layanan_id'],
            'name' => $transaksi['product_name'],
            'slug' => strtolower(str_replace(' ', '-', $transaksi['product_name'])),
            'wpa_name' => 'Tim Almai'
        ];

        // Try to get actual layanan data if it's a layanan product
        if ($transaksi['product_type'] === 'layanan') {
            try {
                $allLayanan = $this->getLayananData();
                foreach ($allLayanan as $item) {
                    if ($item['id'] == $transaksi['layanan_id']) {
                        $layananData = array_merge($layananData, $item);
                        break;
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to get layanan data: ' . $e->getMessage());
            }
        }

        return $layananData;
    }

    /**
     * Send invoice pending payment email
     */
    protected function sendInvoicePendingEmail($transaksi, $user, $paymentUrl, $expiryDate = null)
    {
        try {
            if ($expiryDate) {
                $transaksi['expired_at'] = $expiryDate;
            }

            $emailService = new \App\Libraries\EmailService();
            $result = $emailService->sendInvoicePending(
                $user['email'],
                $user['name'],
                $transaksi,
                $paymentUrl
            );

            if ($result['success']) {
                log_message('info', 'Invoice pending email sent to ' . $user['email']);
                (new TransaksiModel())->update($transaksi['id'], ['mail_invoice_pending' => 'sent']);
            } else {
                log_message('error', 'Failed to send invoice pending email: ' . ($result['error'] ?? $result['message']));
                (new TransaksiModel())->update($transaksi['id'], ['mail_invoice_pending' => 'failed']);
            }
        } catch (\Exception $e) {
            log_message('error', 'sendInvoicePendingEmail error: ' . $e->getMessage());
        }
    }

    private function checkUserHasAdvocacy($userId)
    {
        if (!$userId) return false;
        $transaksiModel = new TransaksiModel();
        return $transaksiModel->where('user_id', $userId)
                             ->where('layanan_id', 9999)
                             ->where('status', 'confirmed')
                             ->countAllResults() > 0;
    }


    private function calculateAdvokasiPrice($userId)
    {
        $basePrice = 88000;
        if (!$userId) return $basePrice;

        $transaksiModel = new TransaksiModel();
        // Sum up confirmed service transaction amounts (excluding advocacy and points)
        // This acts as a cumulative discount for advocacy membership
        $spend = $transaksiModel->where('user_id', $userId)
                                ->where('status', 'confirmed')
                                ->where('layanan_id !=', 9999)
                                ->where('product_type !=', 'poin')
                                ->selectSum('amount')
                                ->first();
        
        $totalSpend = $spend['amount'] ?? 0;
        $finalPrice = $basePrice - $totalSpend;
        
        return max(0, $finalPrice);
    }

    private function checkAdvocacyPrerequisite()
    {
        // Prerequisite removed as per new logic
        return true;
    }

    private function requiresAdvokasiForLayanan(array $layanan): bool
    {
        // All services can now be purchased without advocacy first
        return false;
    }

    private function grantAdvocacyMembership($userId, $transaksiId)
    {
        $transaksiModel = new TransaksiModel();
        $advocacyTx = $transaksiModel->where('user_id', $userId)
            ->where('layanan_id', 9999)
            ->where('status', 'confirmed')
            ->where('notes', 'Bundled with Transaksi #' . $transaksiId)
            ->first();
            
        if ($advocacyTx) {
            $transaksiModel->processReferralPoin($advocacyTx['id']);
        }
    }
}
