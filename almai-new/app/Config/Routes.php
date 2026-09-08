<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
// ALMAI REGISTRATION SYSTEM v2.0 ROUTES (FINAL RELEASE)
$routes->post('register/initiate', 'Auth::initiateRegisterV2');
$routes->post('auth/register-v2/initiate', 'Auth::initiateRegisterV2');
$routes->get('auth/register-v2/check-status', 'Auth::checkRegisterStatusV2');

$routes->get('/', 'Home::index');
$routes->get('market', 'Market::index');

$routes->get('invitation', 'Invitation::index');
$routes->get('invitation/stats', 'Invitation::getRealtimeStats');
$routes->get('about', 'Pages::about');
$routes->get('asosiasi', 'Pages::asosiasi');
$routes->get('advokasi', 'Pages::advokasi');
$routes->get('edukasi', 'Pages::edukasi');
$routes->get('advokasi/(:segment)', 'Pages::advokasi/$1');

// External broker redirects (Plus500 blocks direct links from unauthorized domains)
$routes->get('broker/(:segment)', 'BrokerRedirect::go/$1');

$routes->get('roadmaps', 'Pages::roadmaps');
$routes->get('advokasi2', 'Pages::advokasi2');
$routes->get('signal', 'Pages::sinyal');
$routes->get('signalplus500', 'Pages::signalplus500');
$routes->get('profirm', 'Pages::profirm');
$routes->get('profirm/login', 'Pages::profirmTraderLogin');
$routes->get('profirm/admin/login', 'Pages::profirmAdminLogin');
$routes->post('profirm/admin/auth', 'ProfirmAdmin::auth');
$routes->get('profirm/admin/dashboard', 'ProfirmAdmin::dashboard');
$routes->get('profirm/admin/users', 'ProfirmAdmin::users');
$routes->get('profirm/admin/bbook', 'ProfirmAdmin::bbook');
$routes->get('profirm/admin/rules', 'ProfirmAdmin::rules');
$routes->get('profirm/admin/logout', 'ProfirmAdmin::logout');
$routes->get('daftar-advokasi', 'Pengaduan::index');
$routes->post('daftar-advokasi/submit', 'Pengaduan::submit');
$routes->get('about/role', 'Pages::aboutRole');
$routes->get('syarat-ketentuan', 'Pages::terms');
$routes->get('kebijakan-privasi', 'Pages::privacy');
$routes->get('almai-poin', 'Pages::almaiPoin');
$routes->get('faq', 'Pages::faq');
$routes->get('sitemap.xml', 'Sitemap::index');
$routes->get('kontak', 'Pages::kontak');
$routes->get('roadmaps', 'Pages::roadmaps');
$routes->get('republictrader', 'Pages::republicTrader');
$routes->get('presentasi', 'Pages::presentasi');
$routes->get('aiwe', 'Pages::aiwe');
$routes->get('layanan', 'Layanan::index');
$routes->get('feedback', 'Feedback::index');
$routes->post('feedback/submit', 'Feedback::submit');
$routes->get('layanan/kategori/(:segment)', 'Layanan::index/$1'); // SEO Friendly Category Route
$routes->get('event', 'Event::index');
$routes->get('event/detail/(:segment)', 'Event::detail/$1');
$routes->post('layanan/submit-review', 'Layanan::submitReview');
$routes->get('layanan/(:segment)', 'Layanan::detail/$1');
$routes->get('glosarium', 'Glosarium::index');
$routes->get('glosarium/(:segment)', 'Glosarium::detail/$1');
$routes->get('kalender-ekonomi', 'KalenderEkonomi::index');
$routes->get('alokasi-keuangan', 'AlokasiKeuangan::index');
$routes->get('halaman', 'Halaman::index');
$routes->get('daftar-wpa', 'DaftarWpa::index');
$routes->get('invoice/c/(:num)/(:any)', 'Keuangan\Invoice::detail/$1/$2');

// Debug Routes
// SECURITY: Tutorial/debug routes hanya aktif di luar production
if (ENVIRONMENT !== 'production') {
    $routes->get('tutorial/aiwe', 'Tutorial::aiwe');
}

// Portofolio Routes
$routes->get('portofolio', 'Portofolio::index');
$routes->get('portofolio/(:segment)', 'Portofolio::detail/$1');

// Route untuk serve portfolio logos dari writable
$routes->get('uploads/portfolio_logos/(:any)', function(string $filename) {
    // SECURITY: Sanitasi path traversal
    $filename = basename($filename);
    if (!preg_match('/^[\w\-\.]+$/', $filename)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    // Whitelist ekstensi gambar saja
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $filepath = WRITEPATH . 'uploads/portfolio_logos/' . $filename;
    if (!file_exists($filepath) || !is_file($filepath)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $mime = mime_content_type($filepath);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($filepath));
    header('X-Content-Type-Options: nosniff');
    readfile($filepath);
    exit;
});

// Route untuk serve CALK images dari calk folder
$routes->get('calk/(:any)', function(string $filename) {
    // SECURITY: Sanitasi path traversal
    $filename = basename($filename);
    if (!preg_match('/^[\w\-\.]+$/', $filename)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    // Whitelist ekstensi dokumen/gambar saja
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $filepath = ROOTPATH . 'calk/' . $filename;
    if (!file_exists($filepath) || !is_file($filepath)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $mime = mime_content_type($filepath);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($filepath));
    header('X-Content-Type-Options: nosniff');
    readfile($filepath);
    exit;
});

$routes->get('legalitas', 'Legalitas::index');
$routes->get('cek-legalitas', 'CekLegalitas::index');
$routes->get('cek-legalitas/detail/(:segment)', 'CekLegalitas::detail/$1');

// CWPA Registration - Multi-step registration form
$routes->get('daftar-cwpa', 'DaftarCwpa::multiStepIndex');

// Multi-step CWPA Registration endpoints
$routes->post('daftar-cwpa/submit-multi-step', 'DaftarCwpa::submitMultiStep');
$routes->post('daftar-cwpa/check-user', 'DaftarCwpa::checkUser');
$routes->post('daftar-cwpa/send-otp', 'DaftarCwpa::sendOtp');
$routes->post('daftar-cwpa/verify-otp', 'DaftarCwpa::verifyOtp');
// Polling endpoint for WA-based registration verification (frontend polls this)
$routes->get('daftar-cwpa/check-wa-registration', 'DaftarCwpa::checkWaRegistration');
$routes->post('daftar-cwpa/check-wa-registration', 'DaftarCwpa::checkWaRegistration');

// Keep old endpoints for backward compatibility (will be shown as 404 or error)
$routes->post('daftar-cwpa/store', 'DaftarCwpa::store');
$routes->post('daftar-cwpa/submit-full', 'DaftarCwpa::submitConsolidated');
$routes->get('daftar-cwpa/pricing', 'DaftarCwpa::pricing');
$routes->get('daftar-cwpa/checkout', 'DaftarCwpa::checkout');
$routes->get('daftar-cwpa/success', 'DaftarCwpa::success');
$routes->get('daftar-cwpa/failure', 'DaftarCwpa::failure');
$routes->get('daftar-cwpa/documents', 'DaftarCwpa::documents');
$routes->post('daftar-cwpa/documents/store', 'DaftarCwpa::storeDocuments');
$routes->get('daftar-cwpa/agreement', 'DaftarCwpa::agreement');
$routes->post('daftar-cwpa/agreement/process', 'DaftarCwpa::processAgreement');
$routes->get('daftar-wpa/pricing', 'DaftarWpa::pricing');
$routes->get('legalitas', 'Legalitas::index');
$routes->get('legalitas/perjanjian-pemberian-jasa', 'Legalitas::perjanjianPemberianJasa');
$routes->get('legalitas/dokumen-pemberitahuan-risiko', 'Legalitas::dokumenPemberitahuanRisiko');
$routes->get('rekomendasi', 'Pages::rekomendasi');
$routes->get('referral/(:segment)', 'Auth::referral/$1'); // Referral Link
$routes->get('sesi-trading', 'SesiTrading::index');

// API Routes
$routes->get('api-docs', 'ApiDocs::index');
$routes->get('api/legal-document/(:segment)', 'Api::legalDocument/$1');

// Bot SSO & API Routes
$routes->group('webhook', function ($routes) {
    $routes->post('xendit', 'Webhook\XenditWebhook::incoming');
    $routes->post('balesotomatis', 'Webhook\BalesotomatisWebhook::incoming');
});

$routes->group('api/bot', function ($routes) {
    $routes->get('sso/login', 'Api\SsoBot::login');
    $routes->post('sso/verify', 'Api\SsoBot::verify');
    $routes->get('points/(:num)', 'Api\SsoBot::points/$1');
});


// RWA Dashboard Custom Routes
$routes->get('user/dashboard/rwa/market/summary', 'User\RwaBot::marketSummaryProxy');
$routes->get('user/dashboard/rwa/bots', 'User\RwaBot::bots');
$routes->post('user/dashboard/rwa/bots/start/(:segment)', 'User\RwaBot::startBot/$1');
$routes->post('user/dashboard/rwa/bots/stop/(:segment)', 'User\RwaBot::stopBot/$1');
$routes->post('user/dashboard/rwa/bots/settings/(:segment)', 'User\RwaBot::settingsBot/$1');
$routes->post('user/dashboard/rwa/market/insight', 'User\RwaBot::generateInsight');
$routes->get('user/dashboard/rwa/portfolio', 'User\Dashboard::rwaPortfolio');
$routes->get('user/dashboard/rwa/portoflio', 'User\Dashboard::rwaPortfolio');

// ============================================================
// MOBILE API ROUTES (Flutter Android)


// RWA Market Prices API (public, for frontend auto-refresh)
$routes->get('api/rwa/market-prices', 'Api\RwaMarket::prices');

// ============================================================

// Public: Auth (no JWT required)
$routes->group('api/mobile/auth', function ($routes) {
    $routes->options('(:any)', function() { return service('response')->setStatusCode(200); });
    $routes->post('login',    'Api\Mobile\Auth::login');
    $routes->post('register', 'Api\Mobile\Auth::register');
    $routes->post('refresh',  'Api\Mobile\Auth::refresh');
    $routes->post('logout',   'Api\Mobile\Auth::logout');
});

// Protected: JWT required
$routes->group('api/mobile', ['filter' => 'jwtfilter'], function ($routes) {
    $routes->options('(:any)', function() { return service('response')->setStatusCode(200); });

    // Dashboard & Poin
    $routes->get('dashboard',         'Api\Mobile\Dashboard::index');
    $routes->get('dashboard/poin',    'Api\Mobile\Dashboard::poin');
    $routes->post('dashboard/checkin','Api\Mobile\Dashboard::checkin');
    $routes->get('poin/balance',         'Api\Mobile\Poin::balance');
$routes->post('poin/redeem-to-aiwe', 'Api\Mobile\Poin::redeemToAiwe');


    // Advokasi
    $routes->get('advokasi',          'Api\Mobile\Advokasi::index');
    $routes->post('advokasi/activate','Api\Mobile\Advokasi::activate');

    // Signal (coming soon)
    $routes->get('signal', 'Api\Mobile\Signal::index');

    // Profile
    $routes->get('profile',                'Api\Mobile\Profile::index');
    $routes->put('profile',                'Api\Mobile\Profile::update');
    $routes->post('profile/avatar',        'Api\Mobile\Profile::avatar');
    $routes->post('profile/change-password','Api\Mobile\Profile::changePassword');

    // KYC
    $routes->get('kyc',         'Api\Mobile\Kyc::index');
    $routes->post('kyc/submit', 'Api\Mobile\Kyc::submit');
});



// ============================================================

// Checkout Routes
$routes->get('checkout/cwpa', 'Checkout::cwpa');


// Trading View Routes
$routes->get('trading-view', 'TradingView::index');
$routes->get('trading-view/market-data', 'TradingView::getMarketData');
$routes->get('trading-view/chart-data', 'TradingView::getChartData');

// File serve route (for writable folder files and public fallback)
$routes->get('uploads/avatars/(:any)', 'File::serveAvatar/$1');
$routes->get('uploads/kyc/(:any)', 'File::serveKyc/$1');
$routes->get('uploads/transfer_proofs/(:any)', 'File::serveTransferProof/$1');
$routes->get('file/(:any)', 'File::serve/$1');

// CWPA Auth Routes (Redirected to main login)
$routes->get('cwpa/login', function() { return redirect()->to('/login'); });
$routes->post('cwpa/login', function() { return redirect()->to('/login'); });
$routes->get('cwpa/logout', 'Auth::logout');

// Direct Service Detail inside CWPA context (Redirect to public layanan page)
$routes->get('cwpa/layanan/(:segment)', function(string $slug) { return redirect()->to('layanan/' . $slug); });

// CWPA Dashboard Routes (Protected)
$routes->group('cwpa/dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Cwpa\Dashboard::index');
    $routes->get('absen', 'Cwpa\Dashboard::absen');
    $routes->get('handbook', '\App\Controllers\Handbook::index');
    $routes->get('progress', 'Cwpa\Dashboard::index');
    $routes->get('sertifikat', 'Cwpa\Dashboard::index');
    $routes->get('jadwal', 'Cwpa\Dashboard::index');
    
    // Layanan CWPA Routes
    $routes->get('daftar-layanan', 'Cwpa\Layanan::daftarLayanan');
    $routes->get('layanan', 'Cwpa\Dashboard::layananSaya'); // Purchased
    $routes->get('kelola-layanan', 'Cwpa\Layanan::index'); // Manage
    $routes->get('layanan-saya', 'Cwpa\Dashboard::layananSaya'); // Alias or redirected
    $routes->get('layanan/create', 'Cwpa\Layanan::create');
    $routes->post('layanan/store', 'Cwpa\Layanan::store');
    $routes->get('layanan/edit/(:segment)/(:num)', 'Cwpa\Layanan::edit/$1/$2');
    $routes->post('layanan/update/(:segment)/(:num)', 'Cwpa\Layanan::update/$1/$2');
    $routes->post('layanan/delete/(:segment)/(:num)', 'Cwpa\Layanan::delete/$1/$2');
    $routes->post('layanan/upload-image', 'Cwpa\Layanan::uploadImage');
    
    // Absensi Management
    $routes->get('absensi', 'Cwpa\Absensi::index');
    $routes->get('absensi/create', 'Cwpa\Absensi::create');
    $routes->post('absensi/store', 'Cwpa\Absensi::store');
    $routes->get('absensi/delete/(:segment)/(:segment)', 'Cwpa\Absensi::delete/$1/$2');
    $routes->get('absensi/detail/(:segment)/(:num)', 'Cwpa\Absensi::detail/$1/$2');
    $routes->get('absensi/export-csv/(:segment)/(:num)', 'Cwpa\Absensi::exportCsv/$1/$2');

    // Profile Routes
    $routes->get('profile', 'Cwpa\Profile::index');
    $routes->get('profile/edit', 'Cwpa\Profile::edit');
    $routes->post('profile/update', 'Cwpa\Profile::update');
    $routes->post('profile/photo', 'Cwpa\Profile::updatePhoto');
    
    // Notification Routes
    $routes->get('notifications', 'Cwpa\Notifications::index');
    $routes->get('notifications/read/(:num)', 'Cwpa\Notifications::read/$1');
    $routes->get('notifications/read-all', 'Cwpa\Notifications::readAll');
    
    // Keuangan Routes
    $routes->get('earnings', 'Cwpa\Dashboard::earnings');
    $routes->get('withdraw', 'Cwpa\Dashboard::withdraw');
    $routes->post('withdraw/store', 'Cwpa\Dashboard::storeWithdraw');
    
    // Dedicated CWPA KYC
    $routes->get('kyc', 'Cwpa\Kyc::index');
    $routes->post('kyc/submit', 'Cwpa\Kyc::submit');
    $routes->get('layanan-saya', 'Cwpa\Dashboard::layananSaya');
    $routes->get('layanan-detail/(:num)', 'Cwpa\LayananDetail::index/$1');
    $routes->post('layanan-detail/(:num)/complete', 'Cwpa\LayananDetail::complete/$1');
    $routes->post('layanan-detail/(:num)/activate-license', 'Cwpa\LayananDetail::activateLicense/$1');
    $routes->post('layanan-detail/(:num)/renew-license', 'Cwpa\LayananDetail::renewLicense/$1');
    $routes->get('belajar/(:num)', 'User\Belajar::index/$1');
    
    // Portofolio CRUD Routes
    $routes->get('portofolio', 'Cwpa\Portofolio::index');
    $routes->get('portofolio/create', 'Cwpa\Portofolio::create');
    $routes->post('portofolio/store', 'Cwpa\Portofolio::store');
    $routes->get('portofolio/delete/(:num)', 'Cwpa\Portofolio::delete/$1');
    
    // User & Transaksi Routes
    $routes->get('user', 'Cwpa\User::index');
    $routes->get('user', 'Cwpa\User::index');
    $routes->get('daftar-wpa', 'Cwpa\DaftarWpa::index');
    $routes->get('daftar-wpa/(:segment)', 'Cwpa\DaftarWpa::detail/$1');
    $routes->get('daftar-wpa/layanan/(:segment)', 'Cwpa\DaftarWpa::layananDetail/$1');
    $routes->get('transaksi', 'Cwpa\Transaksi::index');
    $routes->get('transaksi/invoice/(:segment)', 'Cwpa\Transaksi::invoice/$1');
    $routes->get('transaksi/download-perjanjian/(:segment)', 'Cwpa\Transaksi::downloadPerjanjian/$1');
    $routes->get('transaksi/download-risiko/(:segment)', 'Cwpa\Transaksi::downloadRisiko/$1');
    $routes->get('transaksi/download-profil/(:segment)', 'Cwpa\Transaksi::downloadProfilPerusahaan/$1');
    $routes->get('transaksi/download-wpa/(:segment)', 'Cwpa\Transaksi::downloadPerjanjianWpa/$1');
    
    // Poin Routes
    $routes->get('poin', 'Cwpa\Poin::index');
    $routes->post('poin/checkin', 'Cwpa\Poin::checkin');
    $routes->get('poin/buy', 'Cwpa\Poin::buy');
    $routes->post('poin/purchase', 'Cwpa\Poin::purchase');
    $routes->get('poin/purchase-success', 'Cwpa\Poin::purchaseSuccess');
    $routes->get('poin/purchase-failed', 'Cwpa\Poin::purchaseFailed');
    $routes->get('poin/merchandise', 'Cwpa\Poin::merchandise');
    $routes->post('poin/redeem-merchandise', 'Cwpa\Poin::redeemMerchandise');

    // Poin Sharing Routes
    $routes->get('poin/share', 'Cwpa\PoinSharing::create');
    $routes->post('poin/share/store', 'Cwpa\PoinSharing::store');
    $routes->post('poin/share/delete/(:num)', 'Cwpa\PoinSharing::delete/$1');
    $routes->post('poin/share/verify/(:num)', 'Cwpa\PoinSharing::verify/$1');
    $routes->post('poin/share/reject/(:num)', 'Cwpa\PoinSharing::reject/$1');
    
    // Referral Routes
    $routes->get('referral', 'Cwpa\Referral::index');
    $routes->post('referral/update', 'Cwpa\Referral::update');

    // Advokasi
    $routes->get('advokasi', 'Cwpa\Advokasi::index');
    $routes->get('advokasi/belajar', 'Cwpa\Advokasi::belajar');
    $routes->get('advokasi/materi', 'Cwpa\Advokasi::materi');
    $routes->get('advokasi/buat-laporan', 'Cwpa\Advokasi::createReport');
    $routes->post('advokasi/submit-laporan', 'Cwpa\Advokasi::submitReport');
    $routes->get('advokasi/detail/(:num)', 'Cwpa\Advokasi::showDetail/$1');
    $routes->post('advokasi/activate-license', 'Cwpa\Advokasi::activateLicense');
    $routes->post('advokasi/complete', 'Cwpa\Advokasi::complete');
    $routes->post('advokasi/update/(:num)', 'Cwpa\Advokasi::update/$1');

    // Chat Leads
    $routes->get('chat-leads', 'Cwpa\ChatLeads::index');
    $routes->post('chat-leads/send-notification', 'Cwpa\ChatLeads::sendNotification');
});

// WPA Auth Routes (must be before wpa/(:segment))
$routes->get('wpa/login', 'Auth\WpaAuth::login');
$routes->post('wpa/login', 'Auth\WpaAuth::attemptLogin');
$routes->get('wpa/logout', 'Auth\WpaAuth::logout');

// WPA Dashboard Routes (Protected) - must be before wpa/(:segment)
$routes->group('wpa/dashboard', ['filter' => 'wpa'], function ($routes) {
    $routes->get('/', 'Wpa\Dashboard::index');
    $routes->get('absen', 'Wpa\Dashboard::absen');
    $routes->get('handbook', '\App\Controllers\Handbook::index');
    $routes->get('layanan-saya', 'Wpa\Dashboard::layananSaya');
    $routes->get('layanan-detail/(:num)', 'Wpa\LayananDetail::index/$1');
    $routes->post('layanan-detail/(:num)/complete', 'Wpa\LayananDetail::complete/$1');
    $routes->post('layanan-detail/(:num)/activate-license', 'Wpa\LayananDetail::activateLicense/$1');
    $routes->post('layanan-detail/(:num)/renew-license', 'Wpa\LayananDetail::renewLicense/$1');
    $routes->get('daftar-layanan', 'Wpa\Layanan::daftarLayanan');
    $routes->get('layanan', 'Wpa\Layanan::index');
    $routes->get('layanan/create', 'Wpa\Layanan::create');
    $routes->post('layanan/store', 'Wpa\Layanan::store');
    $routes->get('layanan/edit/(:segment)/(:num)', 'Wpa\Layanan::edit/$1/$2');
    $routes->post('layanan/update/(:segment)/(:num)', 'Wpa\Layanan::update/$1/$2');
    $routes->post('layanan/delete/(:segment)/(:num)', 'Wpa\Layanan::delete/$1/$2');
    $routes->post('layanan/upload-image', 'Wpa\Layanan::uploadImage');
    
    // Daftar WPA Routes
    $routes->get('daftar-wpa', 'Wpa\DaftarWpa::index');
    $routes->get('daftar-wpa/(:segment)', 'Wpa\DaftarWpa::detail/$1');
    $routes->get('daftar-wpa/layanan/(:segment)', 'Wpa\DaftarWpa::layananDetail/$1');

    $routes->get('artikel', 'Wpa\Artikel::index');
    $routes->get('artikel/create', 'Wpa\Artikel::create');
    $routes->post('artikel/store', 'Wpa\Artikel::store');
    $routes->get('artikel/edit/(:num)', 'Wpa\Artikel::edit/$1');
    $routes->post('artikel/update/(:num)', 'Wpa\Artikel::update/$1');
    $routes->post('artikel/delete/(:num)', 'Wpa\Artikel::delete/$1');
    $routes->get('withdraw', 'Wpa\Dashboard::withdraw');
    $routes->post('withdraw/store', 'Wpa\Dashboard::storeWithdraw');

    // Dedicated WPA KYC
    $routes->get('kyc', 'Wpa\Kyc::index');
    $routes->post('kyc/submit', 'Wpa\Kyc::submit');
    $routes->get('user', 'Wpa\User::index');
    $routes->get('transaksi', 'Wpa\Transaksi::index');
    $routes->get('transaksi/invoice/(:segment)', 'Wpa\Transaksi::invoice/$1');
    $routes->get('transaksi/download-perjanjian/(:segment)', 'Wpa\Transaksi::downloadPerjanjian/$1');
    $routes->get('transaksi/download-risiko/(:segment)', 'Wpa\Transaksi::downloadRisiko/$1');
    $routes->get('poin', 'Wpa\Poin::index');
    $routes->post('poin/checkin', 'Wpa\Poin::checkin');
    $routes->get('poin/buy', 'Wpa\Poin::buy');
    $routes->post('poin/purchase', 'Wpa\Poin::purchase');
    $routes->get('poin/purchase-success', 'Wpa\Poin::purchaseSuccess');
    $routes->get('poin/purchase-failed', 'Wpa\Poin::purchaseFailed');
    $routes->get('referral', 'Wpa\Referral::index');
    $routes->post('referral/update', 'Wpa\Referral::update');
    $routes->get('poin/merchandise', 'Wpa\Poin::merchandise');
    $routes->post('poin/redeem-merchandise', 'Wpa\Poin::redeemMerchandise');

    // Signal Center Routes
    $routes->get('signal-center', 'Wpa\SignalCenter::index');
    $routes->get('signal-center/create', 'Wpa\SignalCenter::create');
    $routes->post('signal-center/store', 'Wpa\SignalCenter::store');
    $routes->get('signal-center/edit/(:num)', 'Wpa\SignalCenter::edit/$1');
    $routes->post('signal-center/update/(:num)', 'Wpa\SignalCenter::update/$1');
    $routes->post('signal-center/delete/(:num)', 'Wpa\SignalCenter::delete/$1');
    $routes->post('signal-center/generate-ai', 'Wpa\SignalCenter::generateAiReview');

    // Poin Sharing Routes
    $routes->get('poin/share', 'Wpa\PoinSharing::create');
    $routes->post('poin/share/store', 'Wpa\PoinSharing::store');
    $routes->post('poin/share/delete/(:num)', 'Wpa\PoinSharing::delete/$1');
    $routes->post('poin/share/verify/(:num)', 'Wpa\PoinSharing::verify/$1');
    $routes->post('poin/share/reject/(:num)', 'Wpa\PoinSharing::reject/$1');
    // Absensi Management
    $routes->get('absensi', 'Wpa\Absensi::index');
    $routes->get('absensi/create', 'Wpa\Absensi::create');
    $routes->post('absensi/store', 'Wpa\Absensi::store');
    $routes->get('absensi/delete/(:segment)/(:segment)', 'Wpa\Absensi::delete/$1/$2');
    $routes->get('absensi/detail/(:segment)/(:num)', 'Wpa\Absensi::detail/$1/$2');
    $routes->get('absensi/export-csv/(:segment)/(:num)', 'Wpa\Absensi::exportCsv/$1/$2');



    $routes->get('profile', 'Wpa\Profile::index');
    $routes->post('profile/update', 'Wpa\Profile::update');
    $routes->post('profile/photo', 'Wpa\Profile::updatePhoto');
    $routes->get('notifications', 'Wpa\Notifications::index');
    $routes->get('notifications/read/(:num)', 'Wpa\Notifications::read/$1');
    $routes->get('notifications/read-all', 'Wpa\Notifications::readAll');

    // Portofolio CRUD Routes
    $routes->get('portofolio', 'Wpa\Portofolio::index');
    $routes->get('portofolio/create', 'Wpa\Portofolio::create');
    $routes->post('portofolio/store', 'Wpa\Portofolio::store');
    $routes->get('portofolio/delete/(:num)', 'Wpa\Portofolio::delete/$1');

    // Advokasi
    $routes->get('advokasi', 'Wpa\Advokasi::index');
    $routes->get('advokasi/belajar', 'Wpa\Advokasi::belajar');
    $routes->get('advokasi/materi', 'Wpa\Advokasi::materi');
    $routes->get('advokasi/buat-laporan', 'Wpa\Advokasi::createReport');
    $routes->post('advokasi/submit-laporan', 'Wpa\Advokasi::submitReport');
    $routes->get('advokasi/detail/(:num)', 'Wpa\Advokasi::showDetail/$1');
    $routes->post('advokasi/activate-license', 'Wpa\Advokasi::activateLicense');
    $routes->post('advokasi/complete', 'Wpa\Advokasi::complete');
    $routes->get('chat-leads', 'Wpa\ChatLeads::index');
    $routes->post('chat-leads/send-notification', 'Wpa\ChatLeads::sendNotification');
});

// WPA Public Routes (detail page with slug - must be last)
$routes->get('wpa', 'Wpa::index');
$routes->post('wpa/follow/(:num)', 'Wpa::toggleFollow/$1');
$routes->get('wpa/(:segment)', 'Wpa::detail/$1');

// Kelas Routes
$routes->get('kelas', 'Kelas::index');
$routes->get('kelas/(:num)', 'Kelas::detail/$1');
$routes->post('kelas/(:num)/ulasan', 'Kelas::submitUlasan/$1');

// Tools Routes
$routes->get('tools', 'Tools::index');
$routes->get('tools/(:num)', 'Tools::detail/$1');

// Artikel Routes
$routes->get('artikel', 'Artikel::index');
$routes->get('artikel/(:num)', 'Artikel::detail/$1');
$routes->post('artikel/purchase/(:num)', 'Artikel::purchase/$1');

// Event Routes
$routes->get('event', 'Event::index');
$routes->get('event/all', 'Event::list');
$routes->get('event/(:segment)', 'Event::detail/$1');

// User Auth Routes
// SECURITY: Debug routes dihapus dari production. Hanya aktif di environment development.
if (ENVIRONMENT !== 'production') {
    $routes->get('check-price', 'CheckPrice::index');
    $routes->get('check-trx', 'CheckTrx::index');
    $routes->get('check-poin', 'CheckPoin::index');
    $routes->get('check-settings', 'CheckSettings::index');
}
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->get('daftar', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->post('register/send-otp', 'Auth::sendOtp');
$routes->post('register/verify-otp', 'Auth::verifyOtp');
$routes->post('register/check-email', 'Auth::checkEmail');
$routes->get('register/check-wa', 'Auth::checkWaVerification');

// ALMAI REGISTRATION SYSTEM v2.0 ROUTES
$routes->post('auth/register-v2/initiate', 'Auth::initiateRegisterV2');
$routes->get('auth/register-v2/check-status', 'Auth::checkRegisterStatusV2');
$routes->get('welcome', 'Auth::welcomePage');


$routes->get('logout', 'Auth::logout');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');
$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:segment)', 'Auth::processResetPassword/$1');

// Google OAuth Routes
$routes->get('auth/google', 'Auth\GoogleAuth::login');
$routes->get('auth/google/callback', 'Auth\GoogleAuth::callback');

// Admin Auth Routes
$routes->get('admin/login', 'Auth\AdminAuth::login');
$routes->post('admin/login', 'Auth\AdminAuth::attemptLogin');
$routes->get('admin/logout', 'Auth\AdminAuth::logout');

// Keuangan Dashboard Routes (Protected by Accounting Filter)
// Finance Module Routes
$routes->group('keuangan', ['filter' => 'accounting'], function ($routes) {
    // Dashboard
    $routes->get('/', 'Keuangan\Dashboard::index');
    $routes->get('dashboard', 'Keuangan\Dashboard::index');
    $routes->get('audit', 'Keuangan\Audit::index');

    // Transaksi & Penjualan
    $routes->get('transaksi', 'Keuangan\Transaksi::index');
    $routes->get('invoice/(:segment)', 'Keuangan\Transaksi::invoice/$1');


    // Manual Customer Invoices (Separate Module)
    $routes->get('invoices', 'Keuangan\Invoice::index');
    $routes->get('invoices/create', 'Keuangan\Invoice::create');
    $routes->get('invoices/edit/(:num)', 'Keuangan\Invoice::edit/$1');
    $routes->get('invoices/generate-faktur', 'Keuangan\Invoice::generateFaktur');
    $routes->post('invoices/save', 'Keuangan\Invoice::save');
    $routes->get('invoices/get/(:num)', 'Keuangan\Invoice::get/$1');
    $routes->get('invoices/delete/(:num)', 'Keuangan\Invoice::delete/$1'); // TODO: ubah ke POST setelah update view
    $routes->get('invoices/detail/(:num)', 'Keuangan\Invoice::detail/$1');

    // Withdrawals
    $routes->get('withdrawals', 'Keuangan\Transaksi::withdrawals');
    $routes->post('withdrawals/update/(:num)', 'Keuangan\Transaksi::updateWithdrawalStatus/$1');

    // Chart of Accounts
    $routes->get('akun', 'Keuangan\Akun::index');
    $routes->post('akun/save', 'Keuangan\Akun::save');
    $routes->post('akun/delete/(:num)', 'Keuangan\Akun::delete/$1');

    // Jurnal & Reports
    $routes->get('jurnal-umum', 'Keuangan\Laporan::jurnalUmum');

    // Xendit
    $routes->get('xendit', 'Keuangan\Xendit::index');
    $routes->post('xendit/report', 'Keuangan\Xendit::createReport');
    $routes->post('jurnal-umum/save', 'Keuangan\Laporan::saveJurnal');

    // Report Routes
    $routes->get('jurnal-umum', 'Keuangan\Laporan::jurnalUmum');
    $routes->get('jurnal-umum/delete-all', 'Keuangan\Laporan::deleteAllJurnal');
    $routes->post('jurnal-umum/delete-by-year', 'Keuangan\Laporan::deleteJurnalByYear');
    $routes->get('jurnal-umum/duplikat', 'Keuangan\Laporan::duplikatJurnal');
    $routes->get('jurnal-umum/export-pdf', 'Keuangan\Laporan::exportPdfJurnalUmum');
    $routes->post('jurnal-umum/update', 'Keuangan\Laporan::updateJurnal');
    $routes->post('jurnal-umum/hapus-duplikat', 'Keuangan\Laporan::hapusDuplikatJurnal');
    $routes->post('jurnal-umum/hapus-duplikat-item', 'Keuangan\Laporan::hapusDuplikatJurnalItem');
    
    $routes->get('laba-rugi', 'Keuangan\Laporan::labaRugi');
    $routes->get('laba-rugi/export-pdf', 'Keuangan\Laporan::exportPdfLabaRugi');
    $routes->get('laba-rugi/export-excel', 'Keuangan\Laporan::exportExcelLabaRugi');
    
    $routes->get('neraca', 'Keuangan\Laporan::neraca');
    $routes->get('neraca/export-pdf', 'Keuangan\Laporan::exportPdfNeraca');
    $routes->get('neraca/export-excel', 'Keuangan\Laporan::exportExcelNeraca');
    
    $routes->get('arus-kas', 'Keuangan\Laporan::arusKas');
    $routes->get('arus-kas/export-pdf', 'Keuangan\Laporan::exportPdfArusKas');
    $routes->get('arus-kas/export-excel', 'Keuangan\Laporan::exportExcelArusKas');
    
    $routes->get('perubahan-modal', 'Keuangan\Laporan::perubahanModal');
    $routes->get('perubahan-modal/export-pdf', 'Keuangan\Laporan::exportPdfPerubahanModal');
    $routes->get('perubahan-modal/export-excel', 'Keuangan\Laporan::exportExcelPerubahanModal');
    
    $routes->get('neraca-saldo', 'Keuangan\Laporan::neracaSaldo');
    $routes->get('neraca-saldo/export-pdf', 'Keuangan\Laporan::exportPdfNeracaSaldo');
    $routes->get('buku-besar', 'Keuangan\Laporan::bukuBesarDetail');
    $routes->get('calk', 'Keuangan\Calk::index');
    $routes->post('calk/save', 'Keuangan\Calk::save');
    $routes->post('calk/sync', 'Keuangan\Calk::sync');
    $routes->get('calk/data', 'Keuangan\Calk::getData');
    $routes->get('calk/import-from-file', 'Keuangan\CalkImport::importFromFile');
    $routes->get('sak-etap', 'Keuangan\Laporan::sakEtap');
    $routes->get('sak-etap/export-pdf', 'Keuangan\Laporan::exportPdfSakEtap');
    $routes->get('bappebti/generate', 'Keuangan\BappebtiReport::generatePdf');
    $routes->get('bank-indonesia/generate', 'Keuangan\BankIndonesiaReport::generatePdf');
    $routes->get('laporan', 'Keuangan\Laporan::index'); // Redirect or Landing

    // Audit Parser
    $routes->get('audit-parser', 'Keuangan\AuditParser::index');
    $routes->post('audit-parser/parse', 'Keuangan\AuditParser::parse');
    $routes->post('audit-parser/save', 'Keuangan\AuditParser::save');

    // Operasional & Aset
    $routes->get('pengeluaran', 'Keuangan\Pengeluaran::index');
    $routes->get('pengeluaran/generate-faktur', 'Keuangan\Pengeluaran::generateFaktur');
    $routes->post('pengeluaran/save', 'Keuangan\Pengeluaran::save');
    $routes->get('pengeluaran/delete/(:num)', 'Keuangan\Pengeluaran::delete/$1');
    $routes->get('pengeluaran/get/(:num)', 'Keuangan\Pengeluaran::get/$1');
    $routes->get('pengeluaran/detail/(:num)', 'Keuangan\Pengeluaran::detail/$1');
    
    // Kas Masuk & Kas Keluar - Separate Views
    $routes->get('kas-masuk', 'Keuangan\KasMasukKeluar::kasMasuk');
    $routes->get('kas-masuk/duplikat', 'Keuangan\KasMasukKeluar::duplikat/masuk');
    $routes->post('kas-masuk/hapus-duplikat', 'Keuangan\KasMasukKeluar::hapusDuplikat/masuk');
    $routes->post('kas-masuk/hapus-duplikat-item', 'Keuangan\KasMasukKeluar::hapusDuplikatItem/masuk');
    $routes->get('kas-masuk/delete-all', 'Keuangan\KasMasukKeluar::deleteAll/masuk');
    
    $routes->get('kas-keluar', 'Keuangan\KasMasukKeluar::kasKeluar');
    $routes->get('kas-keluar/duplikat', 'Keuangan\KasMasukKeluar::duplikat/keluar');
    $routes->post('kas-keluar/hapus-duplikat', 'Keuangan\KasMasukKeluar::hapusDuplikat/keluar');
    $routes->post('kas-keluar/hapus-duplikat-item', 'Keuangan\KasMasukKeluar::hapusDuplikatItem/keluar');
    $routes->get('kas-keluar/delete-all', 'Keuangan\KasMasukKeluar::deleteAll/keluar');
    
    // Kas Masuk & Kas Keluar - Combined (deprecated, kept for backward compatibility)
    $routes->get('kas-masuk-keluar', 'Keuangan\KasMasukKeluar::index');
    $routes->post('kas-masuk-keluar/save', 'Keuangan\KasMasukKeluar::save');
    $routes->get('kas-masuk-keluar/get/(:segment)', 'Keuangan\KasMasukKeluar::get/$1');
    $routes->get('kas-masuk-keluar/delete/(:segment)', 'Keuangan\KasMasukKeluar::delete/$1');
    $routes->get('kas-masuk-keluar/download-template', 'Keuangan\KasMasukKeluar::downloadTemplate');
    $routes->get('kas-masuk-keluar/export-excel', 'Keuangan\KasMasukKeluar::exportExcel');
    $routes->post('kas-masuk-keluar/import-excel', 'Keuangan\KasMasukKeluar::importExcel');

    $routes->get('kas-bank', 'Keuangan\KasBank::index');
    $routes->get('biaya', 'Keuangan\Biaya::index');
    $routes->post('kas-bank/transfer', 'Keuangan\KasBank::transfer');
    $routes->get('kas-bank/transfer/detail/(:segment)', 'Keuangan\KasBank::detailTransfer/$1');
    $routes->post('kas-bank/transfer/update', 'Keuangan\KasBank::updateTransfer');
    $routes->post('kas-bank/import', 'Keuangan\KasBank::import');
    $routes->get('kas-bank/download-template', 'Keuangan\KasBank::downloadTemplate');
    $routes->get('kas-bank/mutasi/delete/(:segment)', 'Keuangan\KasBank::deleteMutasi/$1');
    $routes->get('kas-bank/mutasi/(:num)', 'Keuangan\KasBank::mutasi/$1');
    $routes->get('kas-bank/resetData', 'Keuangan\KasBank::resetData');
    $routes->post('kas-bank/set-saldo-awal', 'Keuangan\KasBank::setSaldoAwal');
    $routes->get('aset', 'Keuangan\Aset::index');
    $routes->get('aset/create', 'Keuangan\Aset::create');
    $routes->get('aset/edit/(:segment)', 'Keuangan\Aset::edit/$1');
    $routes->get('aset/get/(:segment)', 'Keuangan\Aset::get/$1');
    $routes->get('aset/delete/(:segment)', 'Keuangan\Aset::delete/$1');
    $routes->post('aset/save', 'Keuangan\Aset::save');
    $routes->post('aset/penyusutan/(:num)', 'Keuangan\Aset::postPenyusutan/$1');

    $routes->get('produk', 'Keuangan\Dashboard::produk');

    // Roadmap Pengembangan Sistem
    $routes->get('roadmap', 'Keuangan\Dashboard::roadmap');

    // AI Susani Accounting Assistant
    $routes->post('ai-assistant/chat', 'Keuangan\AiAssistant::chat');

    // Periode Akuntansi & Tutup Buku (Sprint A)
    $routes->get('periode-akuntansi',  'Keuangan\Laporan::periodeAkuntansi');
    $routes->post('tutup-buku',        'Keuangan\Laporan::tutupBuku', ['filter' => 'closingPeriod']);
    $routes->post('buka-buku',         'Keuangan\Laporan::bukaBuku', ['filter' => 'closingPeriod']);

    // Piutang & Hutang Reports
    $routes->get('piutang',            'Keuangan\Laporan::piutang');
    $routes->get('hutang',             'Keuangan\Laporan::hutang');

    // Master & pengaturan
    $routes->get('kontak', 'Keuangan\Kontak::index');
    $routes->get('kontak/get/(:segment)', 'Keuangan\Kontak::get/$1');
    $routes->post('kontak/save', 'Keuangan\Kontak::save');
    $routes->get('kontak/delete/([0-9]+)', 'Keuangan\Kontak::delete/$1');

    $routes->get('entitas', 'Keuangan\Entitas::index');
    $routes->post('entitas/save', 'Keuangan\Entitas::save');

    $routes->get('rekonsiliasi', 'Keuangan\Rekonsiliasi::index');

});

// Admin Legal Documents Routes
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    // Absensi
    $routes->get('absensi/delete-peserta/(:num)', 'Admin\Absensi::deletePeserta/$1');
    
});

// Checkout
$routes->get('checkout/(:num)', 'Checkout::index/$1');
$routes->get('checkout/tools/(:num)', 'Checkout::tools/$1');
$routes->get('checkout/layanan/(:segment)', 'Checkout::layanan/$1');
$routes->get('checkout/event/(:segment)', 'Checkout::event/$1');
$routes->post('checkout/process', 'Checkout::process');
$routes->post('checkout/validate-voucher', 'Checkout::validateVoucher');
$routes->get('checkout/xendit-success', 'Checkout::xenditSuccess');
$routes->get('checkout/xendit-failed', 'Checkout::xenditFailed');
$routes->post('checkout/xendit-callback', 'Checkout::xenditCallback');
$routes->post('webhook/xendit', 'Checkout::xenditCallback'); // Alias for webhook
$routes->post('webhook/balesotomatis', 'Webhook\BalesotomatisWebhook::incoming'); // Webhook Balesotomatis
$routes->post('checkout/send-otp', 'Checkout::sendOtp');
$routes->post('checkout/verify-otp', 'Checkout::verifyOtp');
$routes->post('checkout/send-user-otp', 'Checkout::sendUserOtp');
$routes->post('checkout/verify-dual-otp', 'Checkout::verifyDualOtp');
$routes->post('checkout/verify-single-otp', 'Checkout::verifySingleOtp');
$routes->post('checkout/register-guest-account', 'Checkout::registerGuestAccount');

// User Dashboard Routes (Protected)
$routes->group('user', ['filter' => 'user'], function ($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
    $routes->get('dashboard/absen', 'User\Dashboard::absen');
    
    // Aktivasi Layanan
    $routes->get('dashboard/aktivasi', 'User\Aktivasi::index');
    $routes->post('dashboard/aktivasi/redeem', 'User\Aktivasi::redeem');

    $routes->get('dashboard/rwa', 'User\Dashboard::rwa');
    
    // User Signals Market
    $routes->get('dashboard/signals', 'User\SignalMarket::index');
    $routes->post('dashboard/signals/buy/(:num)', 'User\SignalMarket::buy/$1');

    // RWA Bot Routes
    $routes->post('dashboard/rwa/set-mode', 'User\Dashboard::setMode');
    $routes->get('dashboard/rwa/exchange-api', 'User\RwaBot::exchangeApi');
    $routes->post('dashboard/rwa/exchange-api/save', 'User\RwaBot::saveExchangeApi');
    $routes->post('dashboard/rwa/exchange-api/delete', 'User\RwaBot::deleteExchangeApi');
    $routes->post('dashboard/rwa/exchange-api/test', 'User\RwaBot::testExchangeApi');
    $routes->get('dashboard/rwa/bots', 'User\RwaBot::bots');
    $routes->get('dashboard/rwa/orders', 'User\RwaBot::orders');
    $routes->get('dashboard/rwa/market', 'User\RwaBot::market');
    $routes->post('dashboard/rwa/market/order', 'User\RwaBot::placeOrder');
    $routes->get('dashboard/rwa/market/candles/(:segment)', 'User\RwaBot::chartCandles/$1');
    $routes->get('dashboard/rwa/market/price/(:segment)', 'User\RwaBot::chartPrice/$1');
    $routes->get('dashboard/rwa/backtest', 'User\RwaBot::backtest');
    $routes->post('dashboard/rwa/backtest', 'User\RwaBot::runBacktest');
    $routes->get('handbook', '\App\Controllers\Handbook::index');
    $routes->get('file/(:any)', 'File::serve/$1'); // Generic file server route
    $routes->get('layanan', 'User\Layanan::index');
    $routes->get('layanan-saya', 'User\Dashboard::layananSaya');
    $routes->get('transaksi', 'User\Dashboard::transaksi');
    $routes->get('dashboard/daftar-wpa', 'User\DaftarWpa::index');
    $routes->get('dashboard/daftar-wpa/(:segment)', 'User\DaftarWpa::detail/$1');
    $routes->get('dashboard/daftar-wpa/layanan/(:segment)', 'User\DaftarWpa::layananDetail/$1');
    $routes->get('sertifikat', 'User\Dashboard::sertifikat');
    $routes->get('sertifikat-test', function () {
        return 'Sertifikat route is working! User ID: ' . session()->get('userId');
    });
    $routes->get('poin', 'User\Dashboard::poin');
    $routes->get('transaksi/download-perjanjian/(:segment)', 'User\Dashboard::downloadPerjanjian/$1');
    $routes->get('transaksi/download-risiko/(:segment)', 'User\Dashboard::downloadRisiko/$1');
    $routes->get('poin/merchandise', 'User\Poin::merchandise');
    $routes->get('poin/buy', 'User\Poin::buy');
    $routes->post('poin/checkin', 'User\Poin::checkin');
    $routes->post('poin/redeem-merchandise', 'User\Poin::redeemMerchandise');
    $routes->post('poin/purchase', 'User\Poin::purchase');
    $routes->get('poin/purchase-success', 'User\Poin::purchaseSuccess');
    $routes->get('poin/purchase-failed', 'User\Poin::purchaseFailed');
    $routes->get('poin/share', 'User\PoinSharing::create');
    $routes->post('poin/share/store', 'User\PoinSharing::store');
    $routes->post('poin/share/delete/(:num)', 'User\PoinSharing::delete/$1');
    $routes->post('poin/share/verify/(:num)', 'User\PoinSharing::verify/$1');
    $routes->post('poin/share/reject/(:num)', 'User\PoinSharing::reject/$1');
    $routes->get('user', 'User\Dashboard::user');
    $routes->get('profile', 'User\Dashboard::profile');
    $routes->get('profile/edit', 'User\Dashboard::profileEdit');
    $routes->get('referral', 'User\Dashboard::referral');
    $routes->post('referral/update', 'User\Dashboard::updateReferralCode');
    $routes->get('kyc', 'User\Kyc::index');
    $routes->get('kyc/wilayah', 'User\Kyc::wilayahProxy');
    $routes->get('kyc/wilayah/(:any)', 'User\Kyc::wilayahProxy/$1');
    $routes->get('belajar', 'User\Belajar::index');
    $routes->get('belajar/cwpa', 'User\Belajar::cwpa'); // Point to dedicated method
    $routes->get('belajar/(:num)', 'User\Belajar::kelas/$1');
    $routes->get('layanan-detail/pendampingan-cwpa', 'User\Layanan::cwpaDetail');
    $routes->get('layanan-detail/cwpa', 'User\Layanan::cwpaDetail');
    $routes->get('layanan-detail/(:num)', 'User\LayananDetail::index/$1');
    $routes->get('layanan-artikel-detail/(:num)', 'User\LayananDetail::index/$1');
    $routes->post('layanan-detail/(:num)/complete', 'User\LayananDetail::complete/$1');
    $routes->post('layanan-detail/(:num)/activate-license', 'User\LayananDetail::activateLicense/$1');
    $routes->post('layanan-detail/(:num)/renew-license', 'User\LayananDetail::renewLicense/$1');
    $routes->get('event-detail/(:num)', 'User\LayananDetail::index/$1'); // Event detail (same as layanan)
    $routes->post('event-detail/(:num)/complete', 'User\LayananDetail::complete/$1'); // Event complete
    $routes->get('kelas-detail/(:num)', 'User\LayananDetail::index/$1'); // Backward compatibility
    $routes->get('kelas-live/(:num)', 'User\LayananDetail::index/$1'); // Backward compatibility
    $routes->get('invoice', 'User\Invoice::index');
    $routes->get('invoice/(:segment)', 'User\Invoice::detail/$1');
    $routes->get('ticket/(:segment)', 'User\Invoice::ticket/$1');
    $routes->post('profile/update', 'User\Profile::update');
    $routes->post('profile/password', 'User\Profile::changePassword');
    $routes->post('profile/send-otp', 'User\Profile::sendOtp');
    $routes->post('profile/verify-otp', 'User\Profile::verifyOtp');
    
    // Advokasi
    $routes->get('advokasi', 'User\Advokasi::index');
    $routes->get('advokasi/materi', 'User\Advokasi::materi');
    $routes->get('advokasi/buat-laporan', 'User\Advokasi::createReport');
    $routes->post('advokasi/submit-laporan', 'User\Advokasi::submitReport');
    $routes->get('advokasi/detail/(:num)', 'User\Advokasi::showDetail/$1');
    $routes->post('advokasi/activate-license', 'User\Advokasi::activateLicense');
    $routes->post('advokasi/complete', 'User\Advokasi::complete');
    
    // AI Signal Routes
    $routes->get('signal', 'User\SignalController::index');
    $routes->post('api/ai/analyze', 'User\SignalController::analyze');
    $routes->post('api/ai/technical', 'User\SignalController::technical');
    $routes->post('api/ai/sentiment', 'User\SignalController::sentiment');
    $routes->post('api/ai/news', 'User\SignalController::news');
    $routes->get('api/chart/price/(:any)', 'User\SignalController::price/$1');
    $routes->get('api/chart/candles/(:any)', 'User\SignalController::candles/$1');
    $routes->post('profile/avatar', 'User\Profile::avatar');
    $routes->post('kyc/submit', 'User\Kyc::submit');
    $routes->post('kyc/ocr-proxy', 'User\Kyc::ocrProxy');
    $routes->get('notifications', 'User\Notifications::index');
    $routes->get('notifications/read/(:num)', 'User\Notifications::read/$1');
    $routes->get('notifications/read-all', 'User\Notifications::readAll');
    $routes->post('push/subscribe', 'User\PushSubscription::subscribe');
    $routes->post('layanan/submit-ulasan', 'User\LayananUlasan::submit');
    $routes->get('layanan/(:segment)', 'User\Layanan::detail/$1');

    // User Upgrade CWPA
    $routes->get('upgrade-cwpa', 'User\UpgradeCwpa::index');
    $routes->post('upgrade-cwpa/submit', 'User\UpgradeCwpa::submit');

    // User Checkout Routes
    $routes->get('checkout/layanan/(:segment)', 'User\Checkout::layanan/$1');
    $routes->get('checkout/tools/(:num)', 'User\Checkout::tools/$1');
    $routes->get('checkout/(:num)', 'User\Checkout::index/$1');

    // Portofolio CRUD Routes
    $routes->get('dashboard/portofolio', 'User\Portofolio::index');
    $routes->get('dashboard/portofolio/create', 'User\Portofolio::create');
    $routes->post('dashboard/portofolio/store', 'User\Portofolio::store');
    $routes->get('dashboard/portofolio/delete/(:num)', 'User\Portofolio::delete/$1');

    // Withdraw Routes (PRO only)
    $routes->get('withdraw', 'User\Withdraw::index');
    $routes->post('withdraw/store', 'User\Withdraw::store');
});

// Certificate Routes (Public)
$routes->get('certificate/(:segment)', 'Certificate::view/$1');
$routes->get('certificate/(:segment)/download', 'Certificate::download/$1');
$routes->get('verify/(:segment)', 'Certificate::verify/$1');

// Receive Poin (Public but needs login to claim)
$routes->get('receive-poin/(:segment)', 'ReceivePoin::index/$1');
$routes->post('receive-poin/(:segment)/claim', 'ReceivePoin::claim/$1');
$routes->post('receive-poin/(:segment)/upload-proof', 'ReceivePoin::uploadProof/$1');

// Absensi (Public but needs login to claim points)
$routes->get('absensi/checkin/(:segment)', 'Absensi::checkin/$1');
$routes->post('absensi/checkin/(:segment)', 'Absensi::checkin/$1');

    // Admin Routes (Protected)
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('handbook', '\App\Controllers\Handbook::index');

    // Absensi Management
    $routes->get('absensi', 'Admin\Absensi::index');
    $routes->get('absensi/create', 'Admin\Absensi::create');
    $routes->post('absensi/store', 'Admin\Absensi::store');
    $routes->get('absensi/edit/(:segment)/(:num)', 'Admin\Absensi::edit/$1/$2');
    $routes->post('absensi/update/(:segment)/(:num)', 'Admin\Absensi::update/$1/$2');
    $routes->get('absensi/delete/(:segment)/(:segment)', 'Admin\Absensi::delete/$1/$2');
    $routes->get('absensi/detail/(:segment)/(:num)', 'Admin\Absensi::detail/$1/$2');
    $routes->get('absensi/export-pdf/(:segment)/(:num)', 'Admin\Absensi::exportPdf/$1/$2');
    $routes->get('absensi/export-csv/(:segment)/(:num)', 'Admin\Absensi::exportCsv/$1/$2');

    // WPA Management
    $routes->get('wpa', 'Admin\WpaManagement::index');
    $routes->get('wpa/create', 'Admin\WpaManagement::create');
    $routes->post('wpa/store', 'Admin\WpaManagement::store');
    $routes->get('wpa/edit/(:num)', 'Admin\WpaManagement::edit/$1');
    $routes->post('wpa/update/(:num)', 'Admin\WpaManagement::update/$1');
    $routes->post('wpa/delete/(:num)', 'Admin\WpaManagement::delete/$1');
    // CWPA Management
    $routes->get('cwpa', 'Admin\CwpaManagement::index');
    $routes->get('cwpa/create', 'Admin\CwpaManagement::create');
    $routes->post('cwpa/store', 'Admin\CwpaManagement::store');
    $routes->get('cwpa/edit/(:num)', 'Admin\CwpaManagement::edit/$1');
    $routes->post('cwpa/update/(:num)', 'Admin\CwpaManagement::update/$1');
    $routes->post('cwpa/delete/(:num)', 'Admin\CwpaManagement::delete/$1');
    $routes->get('cwpa/verification', 'Admin\CwpaManagement::verification');
    $routes->get('cwpa/verification/(:num)', 'Admin\CwpaManagement::getDetail/$1');
    $routes->get('cwpa/(:num)', 'Admin\CwpaManagement::getDetail/$1'); // Keep for backward compatibility or direct access if used elsewhere
    $routes->post('cwpa/verify/(:num)', 'Admin\CwpaManagement::verify/$1');
    $routes->post('cwpa/unverify/(:num)', 'Admin\CwpaManagement::unverify/$1');
    $routes->post('cwpa/promote/(:num)', 'Admin\CwpaManagement::promote/$1');

    // Users Management
    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/merge-referrals', 'Admin\Users::mergeReferrals');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/view/(:num)', 'Admin\Users::view/$1');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->get('users/batch-affiliator', 'Admin\Users::batchAffiliator');
    $routes->post('users/process-batch-affiliator', 'Admin\Users::processBatchAffiliator');
    $routes->get('users/toggle-status/(:num)', 'Admin\Users::toggleStatus/$1');
    $routes->get('users/download-agreement/(:num)/(:segment)', 'Admin\Users::downloadAgreement/$1/$2');

    // Transaksi Management
    $routes->get('transaksi', 'Admin\Transaksi::index');
    $routes->get('transaksi/create-manual', 'Admin\Transaksi::createManual');
    $routes->post('transaksi/store-manual', 'Admin\Transaksi::storeManual');
    $routes->get('transaksi/detail/(:num)', 'Admin\Transaksi::detail/$1');
    $routes->get('transaksi/export-excel', 'Admin\Transaksi::exportExcel');
    $routes->get('transaksi/export-pdf', 'Admin\Transaksi::exportPdf');
    $routes->post('transaksi/update-status/(:num)', 'Admin\Transaksi::updateStatus/$1');
    $routes->post('transaksi/resend-email/(:num)', 'Admin\Transaksi::resendEmail/$1');
    $routes->post('transaksi/delete/(:num)', 'Admin\Transaksi::delete/$1');

    // Poin Management
    $routes->get('poin', 'Admin\Poin::index');
    $routes->get('poin/buy', 'Admin\Poin::buy');
    $routes->post('poin/purchase', 'Admin\Poin::purchase');
    $routes->get('poin/purchase-success', 'Admin\Poin::purchaseSuccess');
    $routes->get('poin/purchase-failed', 'Admin\Poin::purchaseFailed');

    // Voucher Management
    $routes->get('voucher', 'Admin\Voucher::index');
    $routes->get('voucher/create', 'Admin\Voucher::create');
    $routes->post('voucher/store', 'Admin\Voucher::store');
    $routes->get('voucher/edit/(:num)', 'Admin\Voucher::edit/$1');
    $routes->post('voucher/update/(:num)', 'Admin\Voucher::update/$1');
    $routes->post('voucher/delete/(:num)', 'Admin\Voucher::delete/$1');
    $routes->post('voucher/toggle-status/(:num)', 'Admin\Voucher::toggleStatus/$1');
    $routes->get('voucher/usages/(:num)', 'Admin\Voucher::usages/$1');
    // Layanan Management
    $routes->get('layanan', 'Admin\LayananManagement::index');
    $routes->get('layanan/create', 'Admin\LayananManagement::create');
    $routes->get('layanan/create/artikel', 'Admin\LayananManagement::createArtikel');
    $routes->get('layanan/create/event', 'Admin\LayananManagement::createEvent');
    $routes->get('layanan/create/tools', 'Admin\LayananManagement::createTools');
    $routes->get('layanan/create/subscription', 'Admin\LayananManagement::createSubscription');
    $routes->get('layanan/subcategories', 'Admin\LayananManagement::getSubcategories');
    $routes->post('layanan/store', 'Admin\LayananManagement::store');
    $routes->get('layanan/edit/(:segment)/(:num)', 'Admin\LayananManagement::edit/$1/$2');
    $routes->post('layanan/update/(:segment)/(:num)', 'Admin\LayananManagement::update/$1/$2');
    $routes->post('layanan/delete/(:segment)/(:num)', 'Admin\LayananManagement::delete/$1/$2');
    $routes->post('layanan/toggle-status/(:segment)/(:num)', 'Admin\LayananManagement::toggleStatus/$1/$2');
    $routes->post('layanan/upload-image', 'Admin\LayananManagement::uploadImage');


    // Advokasi / Pengaduan Management
    $routes->get('advokasi', 'Admin\Advokasi::index');
    $routes->get('advokasi/setting', 'Admin\Advokasi::setting');
    $routes->post('advokasi/save-setting', 'Admin\Advokasi::saveSetting');
    $routes->get('advokasi/view/(:num)', 'Admin\Advokasi::show/$1');
    $routes->post('advokasi/update-status/(:num)', 'Admin\Advokasi::updateStatus/$1');
    $routes->get('advokasi/delete/(:num)', 'Admin\Advokasi::delete/$1');

    // FAQ Management
    $routes->get('faq', 'Admin\FaqManagement::index');
    $routes->get('faq/create', 'Admin\FaqManagement::create');
    $routes->post('faq/store', 'Admin\FaqManagement::store');
    $routes->get('faq/edit/(:num)', 'Admin\FaqManagement::edit/$1');
    $routes->post('faq/update/(:num)', 'Admin\FaqManagement::update/$1');
    $routes->post('faq/delete/(:num)', 'Admin\FaqManagement::delete/$1');

});

// --- Superadmin Routes (Protected, superadmin-only, fully separate from /admin) ---
$routes->group('superadmin', ['filter' => 'superadmin', 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('logout', 'Auth\AdminAuth::logout');
    $routes->get('/', 'Superadmin\Dashboard::index');
    $routes->get('dashboard', 'Superadmin\Dashboard::index');
    $routes->get('dashboard/revenue-data', 'Superadmin\Dashboard::revenueData');
    $routes->get('dashboard/user-growth-data', 'Superadmin\Dashboard::userGrowthData');

    // Feedback Management
    $routes->get('feedback', 'Superadmin\Feedback::index');
    $routes->get('feedback/export', 'Superadmin\Feedback::export');
    $routes->post('feedback/delete/(:num)', 'Superadmin\Feedback::delete/$1');
    $routes->get('handbook', '\App\Controllers\Handbook::index');
    $routes->get('whatsapp-gateway', 'Superadmin\WhatsappGateway::index');
    $routes->get('whatsapp-gateway/status', 'Superadmin\WhatsappGateway::status');
    $routes->get('whatsapp-gateway/qr', 'Superadmin\WhatsappGateway::qr');
    $routes->post('whatsapp-gateway/start', 'Superadmin\WhatsappGateway::start');
    $routes->post('whatsapp-gateway/logout', 'Superadmin\WhatsappGateway::logout');
    $routes->post('whatsapp-gateway/send', 'Superadmin\WhatsappGateway::sendTest');

    // WhatsApp Templates CRUD
    $routes->get('whatsapp-gateway/templates', 'Superadmin\WhatsappGateway::templates');
    $routes->get('whatsapp-gateway/templates/create', 'Superadmin\WhatsappGateway::createTemplate');
    $routes->post('whatsapp-gateway/templates/store', 'Superadmin\WhatsappGateway::storeTemplate');
    $routes->get('whatsapp-gateway/templates/edit/(:num)', 'Superadmin\WhatsappGateway::editTemplate/$1');
    $routes->post('whatsapp-gateway/templates/update/(:num)', 'Superadmin\WhatsappGateway::updateTemplate/$1');
    $routes->get('whatsapp-gateway/templates/delete/(:num)', 'Superadmin\WhatsappGateway::deleteTemplate/$1');

    // Absensi Management
    $routes->get('absensi', 'Superadmin\Absensi::index');
    $routes->get('absensi/create', 'Superadmin\Absensi::create');
    $routes->post('absensi/store', 'Superadmin\Absensi::store');
    $routes->get('absensi/edit/(:segment)/(:num)', 'Superadmin\Absensi::edit/$1/$2');
    $routes->post('absensi/update/(:segment)/(:num)', 'Superadmin\Absensi::update/$1/$2');
    $routes->get('absensi/delete/(:segment)/(:segment)', 'Superadmin\Absensi::delete/$1/$2');
    $routes->get('absensi/delete-peserta/(:num)', 'Superadmin\Absensi::deletePeserta/$1');
    $routes->get('absensi/detail/(:segment)/(:num)', 'Superadmin\Absensi::detail/$1/$2');
    $routes->get('absensi/export-pdf/(:segment)/(:num)', 'Superadmin\Absensi::exportPdf/$1/$2');
    $routes->get('absensi/export-csv/(:segment)/(:num)', 'Superadmin\Absensi::exportCsv/$1/$2');
    $routes->get('absensi/export-xlsx/(:segment)/(:num)', 'Superadmin\Absensi::exportXlsx/$1/$2');

    // WPA Management
    $routes->get('wpa', 'Superadmin\WpaManagement::index');
    $routes->get('wpa/create', 'Superadmin\WpaManagement::create');
    $routes->post('wpa/store', 'Superadmin\WpaManagement::store');
    $routes->get('wpa/edit/(:num)', 'Superadmin\WpaManagement::edit/$1');
    $routes->post('wpa/update/(:num)', 'Superadmin\WpaManagement::update/$1');
    $routes->post('wpa/delete/(:num)', 'Superadmin\WpaManagement::delete/$1');
    $routes->get('wpa-assignment', 'Superadmin\AdminWpaAssignment::index');
    $routes->post('wpa-assignment/store', 'Superadmin\AdminWpaAssignment::store');

    // CWPA Management
    $routes->get('cwpa', 'Superadmin\CwpaManagement::index');
    $routes->get('cwpa/create', 'Superadmin\CwpaManagement::create');
    $routes->post('cwpa/store', 'Superadmin\CwpaManagement::store');
    $routes->get('cwpa/edit/(:num)', 'Superadmin\CwpaManagement::edit/$1');
    $routes->post('cwpa/update/(:num)', 'Superadmin\CwpaManagement::update/$1');
    $routes->post('cwpa/delete/(:num)', 'Superadmin\CwpaManagement::delete/$1');
    $routes->get('cwpa/verification', 'Superadmin\CwpaManagement::verification');
    $routes->get('cwpa/verification/(:num)', 'Superadmin\CwpaManagement::getDetail/$1');
    $routes->get('cwpa/(:num)', 'Superadmin\CwpaManagement::getDetail/$1');
    $routes->post('cwpa/verify/(:num)', 'Superadmin\CwpaManagement::verify/$1');
    $routes->post('cwpa/unverify/(:num)', 'Superadmin\CwpaManagement::unverify/$1');
    $routes->post('cwpa/promote/(:num)', 'Superadmin\CwpaManagement::promote/$1');

    // Kelas Management
    $routes->get('kelas', 'Superadmin\KelasManagement::index');
    $routes->get('kelas/create', 'Superadmin\KelasManagement::create');
    $routes->post('kelas/store', 'Superadmin\KelasManagement::store');
    $routes->get('kelas/edit/(:num)', 'Superadmin\KelasManagement::edit/$1');
    $routes->post('kelas/update/(:num)', 'Superadmin\KelasManagement::update/$1');
    $routes->post('kelas/delete/(:num)', 'Superadmin\KelasManagement::delete/$1');

    // Artikel Management
    $routes->get('artikel', 'Superadmin\ArtikelManagement::index');
    $routes->get('artikel/create', 'Superadmin\ArtikelManagement::create');
    $routes->post('artikel/store', 'Superadmin\ArtikelManagement::store');
    $routes->get('artikel/edit/(:num)', 'Superadmin\ArtikelManagement::edit/$1');
    $routes->post('artikel/update/(:num)', 'Superadmin\ArtikelManagement::update/$1');
    $routes->post('artikel/approve/(:num)', 'Superadmin\ArtikelManagement::approve/$1');
    $routes->post('artikel/reject/(:num)', 'Superadmin\ArtikelManagement::reject/$1');
    $routes->post('artikel/delete/(:num)', 'Superadmin\ArtikelManagement::delete/$1');

    // Tools Management
    $routes->get('tools', 'Superadmin\ToolsManagement::index');
    $routes->get('tools/create', 'Superadmin\ToolsManagement::create');
    $routes->post('tools/store', 'Superadmin\ToolsManagement::store');
    $routes->get('tools/edit/(:num)', 'Superadmin\ToolsManagement::edit/$1');
    $routes->post('tools/update/(:num)', 'Superadmin\ToolsManagement::update/$1');
    $routes->post('tools/delete/(:num)', 'Superadmin\ToolsManagement::delete/$1');
    $routes->post('tools/toggle-status/(:num)', 'Superadmin\ToolsManagement::toggleStatus/$1');

    // Users Management
    $routes->get('users', 'Superadmin\Users::index');
    $routes->get('users/export-excel', 'Superadmin\Users::exportExcel');
    $routes->post('users/merge-referrals', 'Superadmin\Users::mergeReferrals');
    $routes->get('users/create', 'Superadmin\Users::create');
    $routes->post('users/store', 'Superadmin\Users::store');
    $routes->get('users/view/(:num)', 'Superadmin\Users::view/$1');
    $routes->get('users/edit/(:num)', 'Superadmin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Superadmin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Superadmin\Users::delete/$1');
    $routes->get('users/batch-affiliator', 'Superadmin\Users::batchAffiliator');
    $routes->post('users/process-batch-affiliator', 'Superadmin\Users::processBatchAffiliator');
    $routes->get('users/toggle-status/(:num)', 'Superadmin\Users::toggleStatus/$1');
    $routes->get('users/download-agreement/(:num)/(:segment)', 'Superadmin\Users::downloadAgreement/$1/$2');

    // Bot Users Management
    $routes->get('bot-users', 'Superadmin\BotUsers::index');
    $routes->get('bot-users/export-pdf', 'Superadmin\BotUsers::exportPdf');

    // Referral Dashboard
    $routes->get('referral', 'Superadmin\Referral::index');

    // Transaksi Management
    $routes->get('transaksi', 'Superadmin\Transaksi::index');
    $routes->get('transaksi/create-manual', 'Superadmin\Transaksi::createManual');
    $routes->post('transaksi/store-manual', 'Superadmin\Transaksi::storeManual');
    $routes->get('transaksi/detail/(:num)', 'Superadmin\Transaksi::detail/$1');
    $routes->get('transaksi/export-excel', 'Superadmin\Transaksi::exportExcel');
    $routes->get('transaksi/export-pdf', 'Superadmin\Transaksi::exportPdf');
    $routes->post('transaksi/update-status/(:num)', 'Superadmin\Transaksi::updateStatus/$1');
    $routes->post('transaksi/resend-email/(:num)', 'Superadmin\Transaksi::resendEmail/$1');
    $routes->post('transaksi/delete/(:num)', 'Superadmin\Transaksi::delete/$1');

    // Poin Management
    $routes->get('poin', 'Superadmin\Poin::index');
    $routes->get('poin/history', 'Superadmin\Poin::history');
    $routes->post('poin/sync/init', 'Superadmin\Poin::syncInit');
    $routes->post('poin/sync/process', 'Superadmin\Poin::syncProcess');
    $routes->post('poin/sync/complete', 'Superadmin\Poin::syncComplete');
    $routes->post('poin/delete/(:num)', 'Superadmin\Poin::delete/$1');
    $routes->get('poin/user/(:num)', 'Superadmin\Poin::userBalance/$1');

    // Poin Package Management (Harga Poin)
    $routes->get('poin-package', 'Superadmin\PoinPackage::index');
    $routes->post('poin-package/create', 'Superadmin\PoinPackage::create');
    $routes->post('poin-package/update/(:num)', 'Superadmin\PoinPackage::update/$1');
    $routes->get('poin-package/delete/(:num)', 'Superadmin\PoinPackage::delete/$1');
    $routes->get('poin-package/toggle/(:num)', 'Superadmin\PoinPackage::toggle/$1');

    // Admin Poin Sharing
    $routes->get('poin/share', 'Superadmin\PoinSharing::create');
    $routes->post('poin/share/store', 'Superadmin\PoinSharing::store');
    $routes->post('poin/share/delete/(:num)', 'Superadmin\PoinSharing::delete/$1');
    $routes->post('poin/share/verify/(:num)', 'Superadmin\PoinSharing::verify/$1');
    $routes->post('poin/share/reject/(:num)', 'Superadmin\PoinSharing::reject/$1');

    // Audit Log
    $routes->get('audit-log', 'Superadmin\AuditLog::index');
    $routes->get('log', 'Superadmin\AuditLog::index');

    // KYC Management
    $routes->get('kyc', 'Superadmin\Kyc::index');
    $routes->get('kyc/detail/(:num)', 'Superadmin\Kyc::detail/$1');
    $routes->post('kyc/approve/(:num)', 'Superadmin\Kyc::approve/$1');
    $routes->post('kyc/reject/(:num)', 'Superadmin\Kyc::reject/$1');

    // Withdrawal Management
    $routes->get('withdrawals', 'Superadmin\Withdrawal::index');
    $routes->post('withdrawals/update/(:num)', 'Superadmin\Withdrawal::updateStatus/$1');

    // Merchandise Management
    $routes->get('merchandise', 'Superadmin\Merchandise::index');
    $routes->get('merchandise/create', 'Superadmin\Merchandise::create');
    $routes->post('merchandise/store', 'Superadmin\Merchandise::store');
    $routes->get('merchandise/edit/(:num)', 'Superadmin\Merchandise::edit/$1');
    $routes->post('merchandise/update/(:num)', 'Superadmin\Merchandise::update/$1');
    $routes->post('merchandise/delete/(:num)', 'Superadmin\Merchandise::delete/$1');

    // Voucher Management
    $routes->get('voucher', 'Superadmin\Voucher::index');
    $routes->get('voucher/create', 'Superadmin\Voucher::create');
    $routes->post('voucher/store', 'Superadmin\Voucher::store');
    $routes->get('voucher/edit/(:num)', 'Superadmin\Voucher::edit/$1');
    $routes->post('voucher/update/(:num)', 'Superadmin\Voucher::update/$1');
    $routes->post('voucher/delete/(:num)', 'Superadmin\Voucher::delete/$1');
    $routes->post('voucher/toggle-status/(:num)', 'Superadmin\Voucher::toggleStatus/$1');
    $routes->get('voucher/usages/(:num)', 'Superadmin\Voucher::usages/$1');
    $routes->get('merchandise/redemptions', 'Superadmin\Merchandise::redemptions');
    $routes->post('merchandise/redemptions/update/(:num)', 'Superadmin\Merchandise::updateRedemptionStatus/$1');

    // EA License Management
    $routes->get('ea-license', 'Superadmin\EaLicense::index');
    $routes->post('ea-license/update/(:num)', 'Superadmin\EaLicense::update/$1');
    $routes->post('ea-license/delete/(:num)', 'Superadmin\EaLicense::delete/$1');
    $routes->post('ea-license/extend/(:num)', 'Superadmin\EaLicense::extend/$1');
    $routes->get('migrate-forex-accounts', 'Superadmin\MigrateForexAccounts::index');
    $routes->get('clear-cache', 'Superadmin\CacheCleaner::index');
    $routes->get('cleanup-points', 'Superadmin\Cleanup::points');

    // Broadcast Notifications
    $routes->get('broadcast', 'Superadmin\Broadcast::index');
    $routes->get('broadcast/get-users-by-role', 'Superadmin\Broadcast::getUsersByRole');
    $routes->post('broadcast/prepare', 'Superadmin\Broadcast::prepare');
    $routes->post('broadcast/process-batch', 'Superadmin\Broadcast::processBatch');

    // Layanan Management
    $routes->get('layanan', 'Superadmin\LayananManagement::index');
    $routes->get('layanan/create', 'Superadmin\LayananManagement::create');
    $routes->get('layanan/create/artikel', 'Superadmin\LayananManagement::createArtikel');
    $routes->get('layanan/create/event', 'Superadmin\LayananManagement::createEvent');
    $routes->get('layanan/create/tools', 'Superadmin\LayananManagement::createTools');
    $routes->get('layanan/create/subscription', 'Superadmin\LayananManagement::createSubscription');
    $routes->get('layanan/subcategories', 'Superadmin\LayananManagement::getSubcategories');
    $routes->post('layanan/store', 'Superadmin\LayananManagement::store');
    $routes->get('layanan/edit/(:segment)/(:num)', 'Superadmin\LayananManagement::edit/$1/$2');
    $routes->post('layanan/update/(:segment)/(:num)', 'Superadmin\LayananManagement::update/$1/$2');
    $routes->post('layanan/delete/(:segment)/(:num)', 'Superadmin\LayananManagement::delete/$1/$2');
    $routes->post('layanan/toggle-status/(:segment)/(:num)', 'Superadmin\LayananManagement::toggleStatus/$1/$2');
    $routes->post('layanan/upload-image', 'Superadmin\LayananManagement::uploadImage');

    // Advokasi / Pengaduan Management
    $routes->get('advokasi', 'Superadmin\Advokasi::index');
    $routes->get('advokasi/setting', 'Superadmin\Advokasi::setting');
    $routes->post('advokasi/save-setting', 'Superadmin\Advokasi::saveSetting');
    $routes->get('advokasi/view/(:num)', 'Superadmin\Advokasi::show/$1');
    $routes->post('advokasi/update-status/(:num)', 'Superadmin\Advokasi::updateStatus/$1');
    $routes->get('advokasi/delete/(:num)', 'Superadmin\Advokasi::delete/$1');

    // Notifications
    $routes->get('notifications', 'Superadmin\Notifications::index');
    $routes->get('notifications/read/(:num)', 'Superadmin\Notifications::read/$1');
    $routes->get('notifications/read-all', 'Superadmin\Notifications::readAll');

    // Settings
    $routes->get('setting', 'Superadmin\Setting::index');
    $routes->post('setting/update', 'Superadmin\Setting::update');
    $routes->get('syarat-ketentuan', 'Superadmin\Setting::terms');
    $routes->post('syarat-ketentuan', 'Superadmin\Setting::updateTerms');
    $routes->get('setting/vouchers', 'Superadmin\Setting::vouchers');
    $routes->post('setting/vouchers/save', 'Superadmin\Setting::saveVoucher');
    $routes->post('setting/vouchers/delete/(:segment)', 'Superadmin\Setting::deleteVoucher/$1');

    // Telegram Bot
    $routes->get('telegram', 'Superadmin\Telegram::index');
    $routes->get('telegram/test', 'Superadmin\Telegram::test');
    $routes->get('telegram/getUpdates', 'Superadmin\Telegram::getUpdates');

    // Event Banner Management
    $routes->get('event-banner', 'Superadmin\EventBannerManagement::index');
    $routes->get('event-banner/create', 'Superadmin\EventBannerManagement::create');
    $routes->post('event-banner/store', 'Superadmin\EventBannerManagement::store');
    $routes->get('event-banner/edit/(:num)', 'Superadmin\EventBannerManagement::edit/$1');
    $routes->post('event-banner/update/(:num)', 'Superadmin\EventBannerManagement::update/$1');
    $routes->post('event-banner/delete/(:num)', 'Superadmin\EventBannerManagement::delete/$1');

    // Chat Leads
    $routes->get('chat-leads', 'Superadmin\ChatLeads::index');
    $routes->post('chat-leads/delete/(:num)', 'Superadmin\ChatLeads::delete/$1');

    // FAQ Management
    $routes->get('faq', 'Superadmin\FaqManagement::index');
    $routes->get('faq/create', 'Superadmin\FaqManagement::create');
    $routes->post('faq/store', 'Superadmin\FaqManagement::store');
    $routes->get('faq/edit/(:num)', 'Superadmin\FaqManagement::edit/$1');
    $routes->post('faq/update/(:num)', 'Superadmin\FaqManagement::update/$1');
    $routes->post('faq/delete/(:num)', 'Superadmin\FaqManagement::delete/$1');

    // Portfolio Management
    $routes->get('portfolio', 'Superadmin\Portfolio::index');
    $routes->post('portfolio/delete/(:num)', 'Superadmin\Portfolio::delete/$1');

    // Team Management (superadmin-only)
    $routes->get('tim', 'Superadmin\TeamManagement::index');
    $routes->get('tim/create', 'Superadmin\TeamManagement::create');
    $routes->post('tim/store', 'Superadmin\TeamManagement::store');
    $routes->get('tim/edit/(:num)', 'Superadmin\TeamManagement::edit/$1');
    $routes->post('tim/update/(:num)', 'Superadmin\TeamManagement::update/$1');
    $routes->post('tim/delete/(:num)', 'Superadmin\TeamManagement::delete/$1');

    // Legal Documents (superadmin-only)
    $routes->get('legal-documents', 'Superadmin\LegalDocuments::index');
    $routes->get('legal-documents/create', 'Superadmin\LegalDocuments::create');
    $routes->post('legal-documents/store', 'Superadmin\LegalDocuments::store');
    $routes->get('legal-documents/edit/(:num)', 'Superadmin\LegalDocuments::edit/$1');
    $routes->post('legal-documents/update/(:num)', 'Superadmin\LegalDocuments::update/$1');
    $routes->post('legal-documents/delete/(:num)', 'Superadmin\LegalDocuments::delete/$1');
    $routes->get('legal-documents/preview/(:num)', 'Superadmin\LegalDocuments::preview/$1');
    $routes->post('legal-documents/toggle/(:num)', 'Superadmin\LegalDocuments::toggleActive/$1');
});

// CRM Monitoring Chat (Admin dan Superadmin)
$registerCrmRoutes = static function ($routes) {
    $routes->get('crm', '\\App\\Controllers\\Crm\\CrmController::index');
    $routes->get('crm/api/dashboard/summary', '\\App\\Controllers\\Crm\\CrmController::summary');
    $routes->get('crm/api/sessions', '\\App\\Controllers\\Crm\\CrmController::sessions');
    $routes->get('crm/api/sessions/(:num)', '\\App\\Controllers\\Crm\\CrmController::show/$1');
    $routes->post('crm/api/sessions/(:num)/status', '\\App\\Controllers\\Crm\\CrmController::updateStatus/$1');
    $routes->post('crm/api/sessions/(:num)/assign', '\\App\\Controllers\\Crm\\CrmController::assign/$1');
    $routes->post('crm/api/sessions/(:num)/messages', '\\App\\Controllers\\Crm\\CrmController::send/$1');
    $routes->get('crm/api/pics', '\\App\\Controllers\\Crm\\CrmController::pics');
    $routes->get('crm/api/recap', '\\App\\Controllers\\Crm\\CrmController::recap');
    $routes->post('crm/api/automations', '\\App\\Controllers\\Crm\\CrmController::workflowStore');
    $routes->post('crm/api/automations/(:num)', '\\App\\Controllers\\Crm\\CrmController::workflowUpdate/$1');
    // Alias REST sesuai kontrak integrasi eksternal.
    $routes->get('crm/api/chat-sessions', '\\App\\Controllers\\Crm\\CrmController::sessions');
    $routes->get('crm/api/chat-sessions/(:num)', '\\App\\Controllers\\Crm\\CrmController::show/$1');
    $routes->patch('crm/api/chat-sessions/(:num)/status', '\\App\\Controllers\\Crm\\CrmController::updateStatus/$1');
    $routes->post('crm/api/chat-sessions/(:num)/assign', '\\App\\Controllers\\Crm\\CrmController::assign/$1');
    $routes->get('crm/api/automation', '\\App\\Controllers\\Crm\\CrmController::workflows');
    $routes->post('crm/api/automation', '\\App\\Controllers\\Crm\\CrmController::workflowStore');
    $routes->patch('crm/api/automation/(:num)', '\\App\\Controllers\\Crm\\CrmController::workflowUpdate/$1');
    $routes->post('crm/api/messages/send', '\\App\\Controllers\\Crm\\CrmController::messageSend');
    $routes->post('crm/api/messages/auto', '\\App\\Controllers\\Crm\\CrmController::messageAuto');
};
$routes->group('admin', ['filter'=>'admin'], $registerCrmRoutes);
$routes->group('superadmin', ['filter'=>'superadmin'], $registerCrmRoutes);


// --- Admin WPA (Muti-Management) ---
$routes->get('admin-wpa/login', 'Auth\AdminWpaAuth::login');
$routes->post('admin-wpa/login', 'Auth\AdminWpaAuth::attemptLogin');
$routes->get('admin-wpa/logout', 'Auth\AdminWpaAuth::logout');

$routes->group('admin-wpa', ['filter' => 'admin_wpa', 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'AdminWpa\Dashboard::index');
    $routes->get('handbook', '\App\Controllers\Handbook::index');
    $routes->get('dashboard', 'AdminWpa\Dashboard::index');
    $routes->get('dashboard/revenue-data', 'AdminWpa\Dashboard::revenueData');
    $routes->get('dashboard/user-growth-data', 'AdminWpa\Dashboard::userGrowthData');
    
    // Users Management
    $routes->get('users', 'AdminWpa\Users::index');
    $routes->get('users/export', 'AdminWpa\Users::export');
    $routes->get('users/create', 'AdminWpa\Users::create');
    $routes->post('users/store', 'AdminWpa\Users::store');
    $routes->get('users/view/(:num)', 'AdminWpa\Users::view/$1');
    
    // Poin & Profile
    $routes->get('poin', 'AdminWpa\Poin::index');
    $routes->post('poin/checkin', 'AdminWpa\Poin::checkin');
    $routes->get('poin/merchandise', 'AdminWpa\Poin::merchandise');
    $routes->get('poin/buy', 'AdminWpa\Poin::buy');
    $routes->post('poin/purchase', 'AdminWpa\Poin::purchase');
    $routes->post('poin/redeem', 'AdminWpa\Poin::redeemMerchandise');

    $routes->get('absensi', 'AdminWpa\Absensi::index');
    $routes->get('absensi/create', 'AdminWpa\Absensi::create');
    $routes->post('absensi/store', 'AdminWpa\Absensi::store');
    $routes->get('absensi/edit/(:segment)/(:num)', 'AdminWpa\Absensi::edit/$1/$2');
    $routes->post('absensi/update/(:segment)/(:num)', 'AdminWpa\Absensi::update/$1/$2');
    $routes->get('absensi/delete/(:segment)/(:num)', 'AdminWpa\Absensi::delete/$1/$2');
    $routes->get('absensi/detail/(:segment)/(:num)', 'AdminWpa\Absensi::detail/$1/$2');
    $routes->get('absensi/export-pdf/(:segment)/(:num)', 'AdminWpa\Absensi::exportPdf/$1/$2');

    
    $routes->get('profile', 'AdminWpa\Profile::index');
    $routes->get('profile/edit', 'AdminWpa\Profile::edit');
    $routes->post('profile/update', 'AdminWpa\Profile::update');
    $routes->post('profile/update-photo', 'AdminWpa\Profile::updatePhoto');
    
    // Layanan Management
    $routes->get('layanan', 'AdminWpa\Layanan::index');
    $routes->get('layanan/create', 'AdminWpa\Layanan::create');
    $routes->post('layanan/store', 'AdminWpa\Layanan::store');
    $routes->get('layanan/edit/(:segment)/(:num)', 'AdminWpa\Layanan::edit/$1/$2');
    $routes->post('layanan/update/(:segment)/(:num)', 'AdminWpa\Layanan::update/$1/$2');
    $routes->post('layanan/delete/(:segment)/(:num)', 'AdminWpa\Layanan::delete/$1/$2');
    $routes->post('layanan/toggle-status/(:segment)/(:num)', 'AdminWpa\Layanan::toggleStatus/$1/$2');
    $routes->post('layanan/upload-image', 'AdminWpa\Layanan::uploadImage');
    
    // Transaksi Management
    $routes->get('transaksi', 'AdminWpa\Transaksi::index');
    $routes->get('transaksi/export', 'AdminWpa\Transaksi::export');
    $routes->get('transaksi/create-manual', 'AdminWpa\Transaksi::createManual');
    $routes->post('transaksi/store-manual', 'AdminWpa\Transaksi::storeManual');
    $routes->get('transaksi/view/(:num)', 'AdminWpa\Transaksi::view/$1');
    $routes->get('transaksi/confirm/(:num)', 'AdminWpa\Transaksi::confirm/$1');
});

// --- Admin Partnership (Read Only) ---
$routes->get('laporan-kegiatan/login', 'Auth\LaporanKegiatanAuth::login');
$routes->post('laporan-kegiatan/login', 'Auth\LaporanKegiatanAuth::attemptLogin');
$routes->get('laporan-kegiatan/logout', 'Auth\LaporanKegiatanAuth::logout');

$routes->group('laporan-kegiatan', ['filter' => 'partnership_admin', 'namespace' => 'App\Controllers\LaporanKegiatan'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('handbook', '\App\Controllers\Handbook::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('laporan-bank-indonesia', 'LaporanBankIndonesia::index');
    $routes->get('laporan-bank-indonesia/export', 'LaporanBankIndonesia::exportPdf');
    $routes->get('laporan-bank-indonesia/export-excel', 'LaporanBankIndonesia::exportExcel');
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/create', 'Profile::create');
    $routes->post('profile/updateAll', 'Profile::updateAll');

    // Data Perusahaan
    $routes->get('data-perusahaan', 'DataPerusahaan::index');
    $routes->get('data-perusahaan/create', 'DataPerusahaan::create');
    $routes->post('data-perusahaan/store', 'DataPerusahaan::store');
    $routes->get('data-perusahaan/edit/(:num)', 'DataPerusahaan::edit/$1');
    $routes->post('data-perusahaan/update/(:num)', 'DataPerusahaan::update/$1');
    $routes->get('data-perusahaan/delete/(:num)', 'DataPerusahaan::delete/$1');

    // Notifications
    $routes->get('notifications', 'Notifications::index');
    $routes->get('notifications/read-all', 'Notifications::readAll');
    $routes->get('notifications/read/(:num)', 'Notifications::read/$1');

    // WPA CRUD
    $routes->get('wpa', 'Wpa::index');
    $routes->get('wpa/create', 'Wpa::create');
    $routes->post('wpa/store', 'Wpa::store');
    $routes->get('wpa/edit/(:num)', 'Wpa::edit/$1');
    $routes->post('wpa/update/(:num)', 'Wpa::update/$1');
    $routes->get('wpa/delete/(:num)', 'Wpa::delete/$1');
    $routes->get('wpa/view/(:num)', 'Wpa::view/$1');
    $routes->get('wpa/export-csv', 'Wpa::exportCsv');

    // CWPA CRUD
    $routes->get('cwpa', 'Cwpa::index');
    $routes->get('cwpa/create', 'Cwpa::create');
    $routes->post('cwpa/store', 'Cwpa::store');
    $routes->get('cwpa/edit/(:num)', 'Cwpa::edit/$1');
    $routes->post('cwpa/update/(:num)', 'Cwpa::update/$1');
    $routes->get('cwpa/delete/(:num)', 'Cwpa::delete/$1');
    $routes->get('cwpa/view/(:num)', 'Cwpa::view/$1');
    $routes->get('cwpa/export-csv', 'Cwpa::exportCsv');

    // Seminar CRUD
    $routes->get('seminar', 'Seminar::index');
    $routes->get('seminar/create', 'Seminar::create');
    $routes->post('seminar/store', 'Seminar::store');
    $routes->get('seminar/edit/(:num)', 'Seminar::edit/$1');
    $routes->post('seminar/update/(:num)', 'Seminar::update/$1');
    $routes->get('seminar/delete/(:num)', 'Seminar::delete/$1');

    // Pelatihan Simulasi CRUD
    $routes->get('pelatihan', 'Pelatihan::index');
    $routes->get('pelatihan/create', 'Pelatihan::create');
    $routes->post('pelatihan/store', 'Pelatihan::store');
    $routes->get('pelatihan/edit/(:num)', 'Pelatihan::edit/$1');
    $routes->post('pelatihan/update/(:num)', 'Pelatihan::update/$1');
    $routes->get('pelatihan/delete/(:num)', 'Pelatihan::delete/$1');

    // Signal CRUD
    $routes->get('signal', 'Signal::index');
    $routes->get('signal/create', 'Signal::create');
    $routes->post('signal/store', 'Signal::store');
    $routes->get('signal/edit/(:num)', 'Signal::edit/$1');
    $routes->post('signal/update/(:num)', 'Signal::update/$1');
    $routes->get('signal/delete/(:num)', 'Signal::delete/$1');

    // Konsultasi CRUD
    $routes->get('konsultasi', 'Konsultasi::index');
    $routes->get('konsultasi/create', 'Konsultasi::create');
    $routes->post('konsultasi/store', 'Konsultasi::store');
    $routes->get('konsultasi/edit/(:num)', 'Konsultasi::edit/$1');
    $routes->post('konsultasi/update/(:num)', 'Konsultasi::update/$1');
    $routes->get('konsultasi/delete/(:num)', 'Konsultasi::delete/$1');

    // Expert Advisor CRUD
    $routes->get('expert-advisor', 'ExpertAdvisor::index');
    $routes->get('expert-advisor/create', 'ExpertAdvisor::create');
    $routes->post('expert-advisor/store', 'ExpertAdvisor::store');
    $routes->get('expert-advisor/edit/(:num)', 'ExpertAdvisor::edit/$1');
    $routes->post('expert-advisor/update/(:num)', 'ExpertAdvisor::update/$1');
    $routes->get('expert-advisor/delete/(:num)', 'ExpertAdvisor::delete/$1');

    // Kegiatan Lainnya CRUD
    $routes->get('kegiatan-lainnya', 'KegiatanLainnya::index');
    $routes->get('kegiatan-lainnya/create', 'KegiatanLainnya::create');
    $routes->post('kegiatan-lainnya/store', 'KegiatanLainnya::store');
    $routes->get('kegiatan-lainnya/edit/(:num)', 'KegiatanLainnya::edit/$1');
    $routes->post('kegiatan-lainnya/update/(:num)', 'KegiatanLainnya::update/$1');
    $routes->get('kegiatan-lainnya/delete/(:num)', 'KegiatanLainnya::delete/$1');

    // Laporan Regulasi
    $routes->get('laporan-regulasi', 'LaporanRegulasi::index');
    $routes->get('laporan-regulasi/exportPdf', 'LaporanRegulasi::exportPdf');

    // Pedoman Perilaku
    $routes->get('pedoman-perilaku', 'PedomanPerilaku::index');
    $routes->get('pedoman-perilaku/edit/(:num)', 'PedomanPerilaku::edit/$1');
    $routes->post('pedoman-perilaku/update/(:num)', 'PedomanPerilaku::update/$1');
    $routes->get('pedoman-perilaku/download-perjanjian/(:segment)', 'PedomanPerilaku::downloadPerjanjian/$1');
    $routes->get('pedoman-perilaku/download-profil-risiko/(:segment)', 'PedomanPerilaku::downloadProfilRisiko/$1');
    $routes->get('pedoman-perilaku/download-pernyataan-risiko/(:segment)', 'PedomanPerilaku::downloadPernyataanRisiko/$1');

    $routes->get('dokumen-arsip', 'DokumenArsip::index');
    
    $routes->get('dokumen-arsip/create-legalitas', 'DokumenArsip::createLegalitas');
    $routes->get('dokumen-arsip/edit-legalitas/(:num)', 'DokumenArsip::editLegalitas/$1');
    $routes->post('dokumen-arsip/store-legalitas', 'DokumenArsip::storeLegalitas');
    $routes->post('dokumen-arsip/update-legalitas/(:num)', 'DokumenArsip::updateLegalitas/$1');
    $routes->get('dokumen-arsip/delete-legalitas/(:num)', 'DokumenArsip::deleteLegalitas/$1');
    
    $routes->get('dokumen-arsip/create-izin-wpa', 'DokumenArsip::createIzinWpa');
    $routes->get('dokumen-arsip/edit-izin-wpa/(:num)', 'DokumenArsip::editIzinWpa/$1');
    $routes->post('dokumen-arsip/store-izin-wpa', 'DokumenArsip::storeIzinWpa');
    $routes->post('dokumen-arsip/update-izin-wpa/(:num)', 'DokumenArsip::updateIzinWpa/$1');
    $routes->get('dokumen-arsip/delete-izin-wpa/(:num)', 'DokumenArsip::deleteIzinWpa/$1');
    
    $routes->get('dokumen-arsip/create-bahan-kegiatan', 'DokumenArsip::createBahanKegiatan');
    $routes->get('dokumen-arsip/edit-bahan-kegiatan/(:num)', 'DokumenArsip::editBahanKegiatan/$1');
    $routes->post('dokumen-arsip/store-bahan-kegiatan', 'DokumenArsip::storeBahanKegiatan');
    $routes->post('dokumen-arsip/update-bahan-kegiatan/(:num)', 'DokumenArsip::updateBahanKegiatan/$1');
    $routes->get('dokumen-arsip/delete-bahan-kegiatan/(:num)', 'DokumenArsip::deleteBahanKegiatan/$1');

      // Absensi CRUD
      $routes->get('absensi', 'Absensi::index');
      $routes->get('absensi/create', 'Absensi::create');
      $routes->post('absensi/store', 'Absensi::store');
      $routes->get('absensi/edit/(:segment)/(:num)', 'Absensi::edit/$1/$2');
      $routes->post('absensi/update/(:segment)/(:num)', 'Absensi::update/$1/$2');
      $routes->get('absensi/delete/(:segment)/(:num)', 'Absensi::delete/$1/$2');
      $routes->get('absensi/detail/(:segment)/(:num)', 'Absensi::detail/$1/$2');
      $routes->get('absensi/export-pdf/(:segment)/(:num)', 'Absensi::exportPdf/$1/$2');
    
    $routes->get('dokumen-arsip/create-regulasi', 'DokumenArsip::createRegulasi');
    $routes->get('dokumen-arsip/edit-regulasi/(:num)', 'DokumenArsip::editRegulasi/$1');
    $routes->post('dokumen-arsip/store-regulasi', 'DokumenArsip::storeRegulasi');
    $routes->post('dokumen-arsip/update-regulasi/(:num)', 'DokumenArsip::updateRegulasi/$1');
    $routes->get('dokumen-arsip/delete-regulasi/(:num)', 'DokumenArsip::deleteRegulasi/$1');

    $routes->get('dashboard/revenue-data', 'Dashboard::revenueData');
    $routes->get('dashboard/user-growth-data', 'Dashboard::userGrowthData');

    // Read-only modules
    $routes->get('klien', 'Users::index');
    $routes->get('klien/view/(:num)', 'Users::view/$1');
    $routes->get('klien/export-csv', 'Users::exportCsv');
    $routes->get('klien/export-pdf', 'Users::exportPdf');
    $routes->get('layanan', 'Layanan::index');
    $routes->get('transaksi', 'Transaksi::index');
    $routes->get('transaksi/export-csv', 'Transaksi::exportCsv');
    $routes->get('wpa', 'Wpa::index');
    $routes->get('cwpa', 'Cwpa::index');
    $routes->get('cwpa/export-csv', 'Cwpa::exportCsv');

    // Broadcast Notifikasi
    $routes->get('broadcast', 'Broadcast::index');
    $routes->get('broadcast/get-users-by-role', 'Broadcast::getUsersByRole');
    $routes->post('broadcast/prepare', 'Broadcast::prepare');
    $routes->post('broadcast/process-batch', 'Broadcast::processBatch');

    // Poin Read-only Module
    $routes->get('poin', 'Poin::index');
    $routes->get('poin/history', 'Poin::history');
    $routes->get('poin/user/(:num)', 'Poin::userBalance/$1');

    // New Read-only modules
    $routes->get('chat-leads', 'ChatLeads::index');
    $routes->get('withdrawals', 'Withdrawal::index');
    $routes->get('advokasi', 'Cwpa\Advokasi::index');
    $routes->get('advokasi/belajar', 'Cwpa\Advokasi::belajar');
    $routes->get('advokasi/materi', 'Cwpa\Advokasi::materi');
    $routes->get('advokasi/buat-laporan', 'Cwpa\Advokasi::createReport');
    $routes->post('advokasi/submit-laporan', 'Cwpa\Advokasi::submitReport');
    $routes->get('advokasi/detail/(:num)', 'Cwpa\Advokasi::showDetail/$1');
    $routes->post('advokasi/activate-license', 'Cwpa\Advokasi::activateLicense');
    $routes->post('advokasi/complete', 'Cwpa\Advokasi::complete');
    $routes->post('advokasi/update/(:num)', 'Cwpa\Advokasi::update/$1');
    $routes->get('kpi', 'Kpi::index');
    $routes->get('laporan-regulasi', 'LaporanRegulasi::index');
    $routes->get('merchandise', 'Merchandise::index');
    $routes->get('merchandise/redemptions', 'Merchandise::redemptions');
    $routes->get('voucher', 'Voucher::index');
    $routes->get('portfolio', 'Portfolio::index');
    $routes->get('kyc', 'Kyc::index');
    $routes->get('kyc/detail/(:num)', 'Kyc::detail/$1');
    $routes->get('tim', 'Team::index');

    // Absensi Module
    $routes->get('absensi', 'Absensi::index');
    $routes->get('absensi/create', 'Absensi::create');
    $routes->post('absensi/store', 'Absensi::store');
    $routes->get('absensi/delete/(:segment)/(:segment)', 'Absensi::delete/$1/$2');
});

// Unified Home Stats and Recent Users
$routes->get('home/stats', 'Home::getRealtimeStats');
$routes->get('home/recent-users', 'Home::getRecentUsers');

// Webinar Registration Route
$routes->get('daftar-webinar', 'WebinarRegistration::index');
$routes->post('daftar-webinar/process', 'WebinarRegistration::process');


// CWPA Public Routes (must be after auth/dashboard routes)
$routes->get('cwpa', 'Cwpa::index');
$routes->post('cwpa/toggleFollow/(:num)', 'Cwpa::toggleFollow/$1');
$routes->get('cwpa/(:segment)', 'Cwpa::detail/$1');

// API Routes
$routes->group('api', function ($routes) {
    $routes->post('profirm', 'Api\Profirm::update');
    $routes->get('wpa', 'Api\Wpa::index');
    $routes->get('wpa/(:num)', 'Api\Wpa::show/$1');
    $routes->get('kelas', 'Api\Kelas::index');
    $routes->get('kelas/(:num)', 'Api\Kelas::show/$1');
    $routes->get('artikel', 'Api\Artikel::index');
    $routes->get('tools', 'Api\Tools::index');
    $routes->post('license/validate', 'Api\License::verify');
    $routes->post('chat/save-lead', 'ChatBot::saveLead');
    $routes->post('chat/process', 'ChatBot::processChat');
    $routes->get('users/stats', 'Api\Users::stats');
    $routes->get('users/recent', 'Api\Users::recent');
});

// ==============================================================================
// CEO EXECUTIVE SUITE
// Canonical URL is /ceo. Legacy /ea URLs remain available and use the same
// controllers/data source so the application has no duplicate dashboard logic.
// ==============================================================================
$routes->get('ceo', 'Ea\Auth::index');
$routes->post('ceo/login', 'Ea\Auth::attemptLogin');
$routes->get('ceo/logout', 'Ea\Auth::logout');
$routes->get('ea', 'Ea\Auth::index');
$routes->post('ea/login', 'Ea\Auth::attemptLogin');
$routes->get('ea/logout', 'Ea\Auth::logout');

$registerExecutiveRoutes = static function ($routes) {
    $routes->get('dashboard', 'Ceo\Dashboard::index');
    $routes->get('api/summary', 'Ceo\Dashboard::summary');
    $routes->get('api/calendar', 'Ceo\Calendar::events');
    $routes->get('reports/export-xlsx', 'Ceo\Dashboard::exportXlsx');
    $routes->get('reports/export-csv', 'Ceo\Dashboard::exportCsv'); // kompatibilitas tautan lama; kini menghasilkan XLSX
    $routes->post('approvals/(:num)/decision', 'Ceo\Approvals::decide/$1');
    $routes->post('approvals/batch', 'Ceo\Approvals::batch');
    $routes->post('bills', 'Ceo\Operations::storeBill');
    $routes->post('goals', 'Ceo\Operations::storeGoal');

    $routes->get('briefing', 'Ea\Briefing::index');
    $routes->get('calendar', 'Ceo\Calendar::index');
    $routes->get('calenders', 'Ceo\Calendar::index');
    $routes->get('today-schedule', 'Ceo\Calendar::index');
    $routes->get('approval-waiting', 'Ceo\Dashboard::index');
    $routes->get('ai-summary', 'Ea\Briefing::index');
    $routes->get('notifications', 'Ea\Notifications::index');
    $routes->post('notifications/(:num)/read', 'Ea\Notifications::read/$1');
    $routes->post('notifications/read-all', 'Ea\Notifications::readAll');
    $routes->get('activity', 'Ea\ActivityLog::index');
    $routes->get('quick-actions', 'Ea\QuickActions::index');

    $routes->group('quick-actions', static function ($routes) {
        $routes->get('/', 'Ea\QuickActions::index');
        $routes->get('create-task', 'Ea\QuickActions::createTask');
        $routes->post('store-task', 'Ea\QuickActions::storeTask');
        $routes->get('create-meeting', 'Ea\QuickActions::createMeeting');
        $routes->post('store-meeting', 'Ea\QuickActions::storeMeeting');
        $routes->get('create-reminder', 'Ea\QuickActions::createReminder');
        $routes->post('store-reminder', 'Ea\QuickActions::storeReminder');
    });

    $routes->group('tasks', static function ($routes) {
        $routes->get('/', 'Ea\Tasks::index');
        $routes->get('create', 'Ea\Tasks::create');
        $routes->post('store', 'Ea\Tasks::store');
        $routes->get('show/(:num)', 'Ea\Tasks::show/$1');
        $routes->post('add-comment/(:num)', 'Ea\Tasks::addComment/$1');
        $routes->get('edit/(:num)', 'Ea\Tasks::edit/$1');
        $routes->post('update/(:num)', 'Ea\Tasks::update/$1');
        $routes->post('quick-update/(:num)', 'Ea\Tasks::quickUpdate/$1');
        $routes->post('delete/(:num)', 'Ea\Tasks::delete/$1');
    });

    $routes->group('meetings', static function ($routes) {
        $routes->get('/', 'Ea\Meetings::index');
        $routes->get('create', 'Ea\Meetings::create');
        $routes->post('store', 'Ea\Meetings::store');
        $routes->get('edit/(:num)', 'Ea\Meetings::edit/$1');
        $routes->post('update/(:num)', 'Ea\Meetings::update/$1');
        $routes->post('delete/(:num)', 'Ea\Meetings::delete/$1');
    });

    $routes->group('reminders', static function ($routes) {
        $routes->get('/', 'Ea\Reminders::index');
        $routes->get('edit/(:num)', 'Ea\Reminders::edit/$1');
        $routes->post('update/(:num)', 'Ea\Reminders::update/$1');
        $routes->post('delete/(:num)', 'Ea\Reminders::delete/$1');
    });
};

$routes->group('ceo', ['filter' => 'ceo'], $registerExecutiveRoutes);
$routes->group('ea', ['filter' => 'ceo'], $registerExecutiveRoutes);

// ALMAI REGISTRATION SYSTEM v2.0
$routes->post('register/initiate', 'Auth::initiateRegisterV2');
$routes->post('auth/register-v2/initiate', 'Auth::initiateRegisterV2');
$routes->get('auth/register-v2/check-status', 'Auth::checkRegisterStatusV2');
