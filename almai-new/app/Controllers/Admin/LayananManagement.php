<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriLayananModel;
use App\Models\LayananArtikelModel;
use App\Models\LayananEventModel;
use App\Models\LayananToolsModel;
use App\Models\LayananSubscriptionModel;
use App\Models\LayananModel;
use App\Models\LayananPriceModel;
use App\Models\WpaModel;
use App\Models\NotificationModel;

class LayananManagement extends BaseController
{
    protected $kategoriModel;
    protected $artikelModel;
    protected $eventModel;
    protected $toolsModel;
    protected $subscriptionModel;
    protected $layananModel;
    protected $priceModel;
    protected $wpaModel;
    protected $cwpaModel;
    protected $resourceModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriLayananModel();
        $this->artikelModel = new LayananArtikelModel();
        $this->eventModel = new LayananEventModel();
        $this->toolsModel = new LayananToolsModel();
        $this->subscriptionModel = new LayananSubscriptionModel();
        $this->layananModel = new LayananModel();
        $this->priceModel = new LayananPriceModel();
        $this->wpaModel = new WpaModel();
        $this->cwpaModel = new \App\Models\CwpaModel();
        $this->resourceModel = new \App\Models\LayananResourceModel();
    }

    /**
     * Admin (Level 5) dan Super Admin (Level 7) boleh kelola layanan.
     * Accounting (Level 6) read-only.
     */
    private function canManageLayanan(): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        $levelId = (int) $this->session->get('level_id');
        return $levelId === \App\Models\LevelModel::LEVEL_ADMIN
            || $levelId === \App\Models\LevelModel::LEVEL_SUPER_ADMIN;
    }

    public function uploadImage()
    {
        $file = $this->request->getFile('upload');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Move to writable/uploads/content
            $file->move(WRITEPATH . 'uploads/content', $newName);

            return $this->response->setJSON([
                'url' => base_url('file/uploads/content/' . $newName)
            ]);
        }

        return $this->response->setJSON([
            'error' => [
                'message' => 'The image upload failed.'
            ]
        ]);
    }
    public function index()
    {
        $kategori = $this->request->getGet('kategori');
        $subcategory = $this->request->getGet('subcategory');

        $layananList = $this->getAllLayanan($kategori, $subcategory);

        $stats = [
            'total' => count($layananList),
            'artikel' => $this->artikelModel->countAllResults(),
            'event' => $this->eventModel->countAllResults(),
            'tools' => $this->toolsModel->countAllResults(),
            'subscription' => $this->subscriptionModel->countAllResults(),
        ];

        return view('admin/layanan/index', [
            'title' => 'Kelola Layanan',
            'activeMenu' => 'layanan',
            'layananList' => $layananList,
            'kategoriList' => $this->kategoriModel->getActive(),
            'currentKategori' => $kategori,
            'currentSubcategory' => $subcategory,
            'stats' => $stats,
            'canWrite' => $this->canManageLayanan()
        ]);
    }

    private function getAllLayanan($kategori = null, $subcategory = null)
    {
        $result = [];

        // Get from main layanan table (from seeder)
        $layananItems = $this->layananModel->getAllWithWpa($kategori);
        foreach ($layananItems as $item) {
            $item = $this->addPackageInfo($item, 'layanan');
            $result[] = array_merge($item, [
                'layanan_type' => 'layanan',
                'kategori' => $item['category'],
                'table_name' => 'layanan'
            ]);
        }

        // Get Artikel
        if (!$kategori || $kategori === 'advokasi') {
            if (!$subcategory || $subcategory === 'artikel') {
                $artikels = $this->artikelModel->getWithWpa();
                foreach ($artikels as $item) {
                    $item = $this->addPackageInfo($item, 'artikel');
                    $result[] = array_merge($item, [
                        'layanan_type' => 'artikel',
                        'kategori' => 'Advokasi',
                        'subcategory' => 'Artikel',
                        'table_name' => 'layanan_artikel'
                    ]);
                }
            }
        }

        // Get Events (Webinar & Workshop)
        if (!$kategori || $kategori === 'advokasi') {
            $events = $this->eventModel->getWithWpa();
            foreach ($events as $item) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => 'Advokasi',
                        'subcategory' => ucfirst($item['type']),
                        'table_name' => 'layanan_event',
                        'name' => $item['title']
                    ]);
                }
            }
        }

        // Get Tools (Toolkit & EA)
        $tools = $this->toolsModel->findAll();
        foreach ($tools as $item) {
            $kat = $item['type'] === 'ea' ? 'expert-advisor' : 'almai-ultimate';
            $katLabel = $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Ultimate';
            if (!$kategori || $kategori === $kat) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => $katLabel,
                        'subcategory' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Toolkits',
                        'table_name' => 'layanan_tools'
                    ]);
                }
            }
        }

        // Get Subscriptions
        $subs = $this->subscriptionModel->getWithWpa();
        foreach ($subs as $item) {
            $kat = in_array($item['type'], ['pendampingan', 'profirm']) ? 'advokasi' : 'almai-ultimate';
            $katLabel = in_array($item['type'], ['pendampingan', 'profirm']) ? 'Advokasi' : 'Almai Ultimate';
            if (!$kategori || $kategori === $kat) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => $katLabel,
                        'subcategory' => $this->getSubcategoryLabel($item['type']),
                        'table_name' => 'layanan_subscription'
                    ]);
                }
            }
        }

        return $result;
    }

    private function addPackageInfo($item, $type)
    {
        $packages = $this->priceModel->where('layanan_type', $type)
            ->where('layanan_id', $item['id'])
            ->orderBy('price', 'ASC')
            ->findAll();

        if (!empty($packages)) {
            $item['has_packages'] = true;
            $item['packages'] = $packages;
            $item['min_price'] = $packages[0]['price'];
            $item['max_price'] = $packages[count($packages) - 1]['price'];
        } else {
            $item['has_packages'] = false;
        }

        return $item;
    }

    private function getSubcategoryLabel($type)
    {
        $labels = [
            'artikel' => 'Artikel',
            'webinar' => 'Webinar',
            'workshop' => 'Workshop',
            'pendampingan' => 'Pendampingan CWPA',
            'profirm' => 'Profirm',
            'live_trade' => 'Live Trade',
            'ea' => 'Expert Advisor',
            'toolkit' => 'Almai Toolkits',
            'private_konsultan' => 'Private Konsultan',
            'vip_member' => 'VIP Member',
        ];
        return $labels[$type] ?? ucfirst($type);
    }

    public function create()
    {
        if (!$this->canManageLayanan()) {
            return redirect()->to('/admin/layanan')->with('error', 'Akses ditolak.');
        }

        return view('admin/layanan/create', [
            'title' => 'Tambah Layanan',
            'activeMenu' => 'layanan',
            'kategoriList' => $this->kategoriModel->getActive(),
            'wpaList' => $this->wpaModel->where('status', 'active')->findAll(),
            'cwpaList' => $this->cwpaModel->where('status', 'active')->findAll(),
        ]);
    }

    public function getSubcategories()
    {
        $kategoriSlug = $this->request->getGet('kategori');
        $subcategories = $this->kategoriModel->getSubcategories($kategoriSlug);
        return $this->response->setJSON($subcategories);
    }

    public function store()
    {
        if (!$this->canManageLayanan()) {
            return redirect()->to('/admin/layanan')->with('error', 'Akses ditolak.');
        }

        $subcategory = $this->request->getPost('subcategory');

        switch ($subcategory) {
            case 'artikel':
                return $this->storeArtikel();
            case 'webinar':
            case 'workshop':
            case 'live_trade':
                return $this->storeEvent($subcategory);
            case 'toolkit':
            case 'ea':
                return $this->storeTool($subcategory);
            case 'pendampingan':
            case 'profirm':
            case 'private_konsultan':
            case 'vip_member':
                return $this->storeSubscription($subcategory);
            default:
                return redirect()->back()->with('error', 'Subcategory tidak valid');
        }
    }

    /**
     * Calculate referral pool fields from price and pool percentage.
     * - pool_pct  = % dari harga yang jadi total pool (admin input, e.g. 25%)
     * - referral_user_cash = total pool penuh → didistribusikan ke chain (L1 dapat 50%, L2 25%, dst.)
     * - referral_wpa_cash  = komisi terpisah untuk WPA pemilik layanan (sama besar dengan total pool)
     * - poin = floor(cash / 1000) — 1 poin = Rp 1.000
     *
     * Contoh: harga Rp 2.000.000, pool_pct 25%
     *   total pool = Rp 500.000
     *   L1 dapat 50% × 500.000 = Rp 250.000
     *   L2 dapat 50% × 250.000 = Rp 125.000, dst.
     *   WPA dapat Rp 500.000 (terpisah, dari referral_wpa_cash)
     */
    private function calcReferralPool(float $price, float $pool_pct): array
    {
        $totalPool = (int) floor($price * $pool_pct / 100);

        $userCash = $totalPool;                          // full pool untuk referral chain
        $userPoin = (int) floor($userCash / 1000);

        $wpaCash  = $totalPool;                          // WPA dapat pool yang sama (terpisah)
        $wpaPoin  = (int) floor($wpaCash / 1000);

        return [
            'cash'     => $userCash,
            'poin'     => $userPoin,
            'wpa_cash' => $wpaCash,
            'wpa_poin' => $wpaPoin,
        ];
    }

    private function storeArtikel()
    {
        $data = [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'title' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Auto-calculate referral user pool (artikel has no cash price, pool = 0)
        $data['referral_user_cash'] = 0;
        $data['referral_user_poin'] = 0;

        if ($data['status'] === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/artikel', $newName);
            $data['thumbnail'] = 'uploads/layanan/artikel/' . $newName;
        }

        // Handle file upload (PDF)
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'artikel_' . time() . '.' . $file->getExtension();
            $file->move(WRITEPATH . 'uploads/layanan/artikel/files', $newName);
            $data['file_path'] = 'uploads/layanan/artikel/files/' . $newName;
            $data['file_type'] = $file->getExtension();
        }

        $id = $this->artikelModel->insert($data);
        if ($id) {
            $this->saveResources('artikel', $id);
        }
        return redirect()->to('/admin/layanan')->with('success', 'Artikel berhasil ditambahkan');
    }

    private function storeEvent($type)
    {
        $data = [
            'type' => $type,
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'title' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'event_date' => $this->request->getPost('event_date'),
            'event_end_date' => $this->request->getPost('event_end_date') ?: null,
            'max_participants' => $this->request->getPost('max_participants') ?: 0,
            'total_sessions' => $this->request->getPost('total_sessions') ?: 1,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'is_recurring' => $this->request->getPost('is_recurring') ? 1 : 0,
            'recurring_frequency' => $this->request->getPost('recurring_frequency') ?: null,
            'recurring_day' => $this->request->getPost('recurring_day') ?: null,
            'recurring_time' => $this->request->getPost('recurring_time') ?: null,
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'wpa_commission_percent' => $this->request->getPost('wpa_commission_percent') ?: 10.00,
            'is_license_product' => $this->request->getPost('is_license_product') ? 1 : 0,
            'license_duration' => $this->request->getPost('license_duration') ?: 0,
            'license_prefix' => $this->request->getPost('license_prefix') ?: 'ALMAI-{id_akun}-{random4}',
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Auto-calculate all referral pool fields from effective price × pool_pct%
        // If packages exist, use the highest package price as the reference
        $effectivePrice = (float)($data['price'] ?? 0);
        $packages = $this->request->getPost('packages');
        if ($this->request->getPost('has_packages') && is_array($packages)) {
            foreach ($packages as $pkg) {
                $pkgPrice = (float)($pkg['price'] ?? 0);
                if ($pkgPrice > $effectivePrice) $effectivePrice = $pkgPrice;
            }
        }
        $referralPool = $this->calcReferralPool($effectivePrice, (float)($data['referral_distribution_percentage'] ?? 25));
        $data['referral_user_cash'] = $referralPool['cash'];
        $data['referral_user_poin'] = $referralPool['poin'];
        $data['referral_wpa_cash']  = $referralPool['wpa_cash'];
        $data['referral_wpa_poin']  = $referralPool['wpa_poin'];

        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link');
            $data['meeting_id'] = $this->request->getPost('meeting_id');
            $data['meeting_password'] = $this->request->getPost('meeting_password');
        } else {
            $data['location'] = $this->request->getPost('location');
            $data['requires_agreement'] = $this->request->getPost('requires_agreement') ? 1 : 0;
            $data['agreement_text'] = $this->request->getPost('agreement_text');
        }

        // Handle thumbnail
        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
            $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
        }

        // Handle EA file upload (License)
        $eaFile = $this->request->getFile('ea_file');
        if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
            $newName = 'ea_' . time() . '_' . $eaFile->getClientName();
            $eaFile->move(WRITEPATH . 'uploads/layanan/ea_files', $newName);
            $data['ea_file_path'] = 'uploads/layanan/ea_files/' . $newName;
        }

        $id = $this->eventModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
        }
        return redirect()->to('/admin/layanan')->with('success', ucfirst($type) . ' berhasil ditambahkan');
    }

    private function storeTool($type)
    {
        $data = [
            'type' => $type,
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'version' => $this->request->getPost('version'),
            'changelog' => $this->request->getPost('changelog'),
            'documentation_url' => $this->request->getPost('documentation_url'),
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'wpa_commission_percent' => $this->request->getPost('wpa_commission_percent') ?: 10.00,
            'is_license_product' => $this->request->getPost('is_license_product') ? 1 : 0,
            'license_duration' => $this->request->getPost('license_duration') ?: 0,
            'license_prefix' => $this->request->getPost('license_prefix') ?: 'ALMAI-{id_akun}-{random4}',
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Auto-calculate all referral pool fields from effective price × pool_pct%
        // If packages exist, use the highest package price as the reference
        $effectivePrice = (float)($data['price'] ?? 0);
        $packages = $this->request->getPost('packages');
        if ($this->request->getPost('has_packages') && is_array($packages)) {
            foreach ($packages as $pkg) {
                $pkgPrice = (float)($pkg['price'] ?? 0);
                if ($pkgPrice > $effectivePrice) $effectivePrice = $pkgPrice;
            }
        }
        $referralPool = $this->calcReferralPool($effectivePrice, (float)($data['referral_distribution_percentage'] ?? 25));
        $data['referral_user_cash'] = $referralPool['cash'];
        $data['referral_user_poin'] = $referralPool['poin'];
        $data['referral_wpa_cash']  = $referralPool['wpa_cash'];
        $data['referral_wpa_poin']  = $referralPool['wpa_poin'];

        // Handle compatibility
        $compatibility = $this->request->getPost('compatibility');
        if ($compatibility) {
            $data['compatibility'] = json_encode($compatibility);
        }

        // Handle features
        $features = $this->request->getPost('features');
        if ($features) {
            $data['features'] = json_encode(array_filter(array_map('trim', explode("\n", $features))));
        }

        // Handle requirements
        $requirements = $this->request->getPost('requirements');
        if ($requirements) {
            $data['requirements'] = json_encode(array_filter(array_map('trim', explode("\n", $requirements))));
        }

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/tools', $newName);
            $data['thumbnail'] = 'uploads/layanan/tools/' . $newName;
        }

        // Handle file upload
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $type . '_' . time() . '.' . $file->getExtension();
            $file->move(WRITEPATH . 'uploads/layanan/tools/files', $newName);
            $data['file_path'] = 'uploads/layanan/tools/files/' . $newName;
            $data['file_type'] = $file->getExtension();
        }

        // Handle EA file upload (License)
        $eaFile = $this->request->getFile('ea_file');
        if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
            $newName = 'ea_' . time() . '_' . $eaFile->getClientName();
            $eaFile->move(WRITEPATH . 'uploads/layanan/ea_files', $newName);
            $data['ea_file_path'] = 'uploads/layanan/ea_files/' . $newName;
        }

        $id = $this->toolsModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
        }
        return redirect()->to('/admin/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil ditambahkan');
    }

    private function storeSubscription($type)
    {
        $data = [
            'type' => $type,
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'duration_days' => $this->request->getPost('duration_days') ?: 30,
            'total_sessions' => $this->request->getPost('total_sessions') ?: 0,
            'max_slots' => $this->request->getPost('max_slots') ?: 0,
            'schedule_info' => $this->request->getPost('schedule_info'),
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'wpa_commission_percent' => $this->request->getPost('wpa_commission_percent') ?: 10.00,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Auto-calculate all referral pool fields from effective price × pool_pct%
        // If packages exist, use the highest package price as the reference
        $effectivePrice = (float)($data['price'] ?? 0);
        $packages = $this->request->getPost('packages');
        if ($this->request->getPost('has_packages') && is_array($packages)) {
            foreach ($packages as $pkg) {
                $pkgPrice = (float)($pkg['price'] ?? 0);
                if ($pkgPrice > $effectivePrice) $effectivePrice = $pkgPrice;
            }
        }
        $referralPool = $this->calcReferralPool($effectivePrice, (float)($data['referral_distribution_percentage'] ?? 25));
        $data['referral_user_cash'] = $referralPool['cash'];
        $data['referral_user_poin'] = $referralPool['poin'];
        $data['referral_wpa_cash']  = $referralPool['wpa_cash'];
        $data['referral_wpa_poin']  = $referralPool['wpa_poin'];

        // Handle benefits
        $benefits = $this->request->getPost('benefits');
        if ($benefits) {
            $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
        }

        // Handle includes
        $includes = $this->request->getPost('includes');
        if ($includes) {
            $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));
        }

        // Handle requirements
        $requirements = $this->request->getPost('requirements');
        if ($requirements) {
            $data['requirements'] = json_encode(array_filter(array_map('trim', explode("\n", $requirements))));
        }

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/subscription', $newName);
            $data['thumbnail'] = 'uploads/layanan/subscription/' . $newName;
        }

        $id = $this->subscriptionModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
        }
        return redirect()->to('/admin/layanan')->with('success', $this->getSubcategoryLabel($type) . ' berhasil ditambahkan');
    }

    private function savePackages($type, $id, $clearExisting = false)
    {
        if ($this->request->getPost('has_packages')) {
            if ($clearExisting) {
                $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
            }

            $packages   = $this->request->getPost('packages');
            // Distribution % is the same for all packages — taken from the layanan-level input
            $distPct    = (float)($this->request->getPost('referral_distribution_percentage') ?? 0);

            if ($packages && is_array($packages)) {
                $sort = 0;
                foreach ($packages as $pkg) {
                    if (empty($pkg['name']) || empty($pkg['price'])) continue;

                    $pkgPrice = (float) $pkg['price'];
                    $pool     = $this->calcReferralPool($pkgPrice, $distPct);

                    $this->priceModel->insert([
                        'layanan_type'       => $type,
                        'layanan_id'         => $id,
                        'name'               => $pkg['name'],
                        'price'              => $pkgPrice,
                        'original_price'     => !empty($pkg['original_price']) ? $pkg['original_price'] : null,
                        'description'        => $pkg['description'] ?? null,
                        'sort_order'         => $sort++,
                        'duration_days'      => !empty($pkg['duration_days']) ? (int)$pkg['duration_days'] : null,
                        'referral_user_cash' => $pool['cash'],
                        'referral_user_poin' => $pool['poin'],
                        'referral_wpa_cash'  => $pool['wpa_cash'],
                        'referral_wpa_poin'  => $pool['wpa_poin'],
                    ]);
                }
            }
        } elseif ($clearExisting) {
            $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
        }
    }

    private function saveResources($type, $id)
    {
        // Handle deletions
        $deletedIds = $this->request->getPost('delete_resources');
        if ($deletedIds && is_array($deletedIds)) {
            foreach ($deletedIds as $resId) {
                $resource = $this->resourceModel->find($resId);
                if ($resource) {
                    if (file_exists(WRITEPATH . $resource['file_path'])) {
                        unlink(WRITEPATH . $resource['file_path']);
                    }
                    $this->resourceModel->delete($resId);
                }
            }
        }

        // Handle new uploads
        $files = $this->request->getFileMultiple('resources');
        $titles = $this->request->getPost('resource_titles');

        if ($files) {
            foreach ($files as $index => $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $dir = WRITEPATH . 'uploads/layanan/resources';
                    if (!is_dir($dir)) mkdir($dir, 0777, true);

                    $newName = 'res_' . time() . '_' . $file->getRandomName();
                    $file->move($dir, $newName);

                    $this->resourceModel->insert([
                        'layanan_id' => $id,
                        'layanan_type' => $type,
                        'title' => !empty($titles[$index]) ? $titles[$index] : $file->getClientName(),
                        'file_path' => 'uploads/layanan/resources/' . $newName,
                        'file_type' => $file->getClientExtension(),
                        'file_size' => $file->getSize(),
                        'sort_order' => $index
                    ]);
                }
            }
        }
    }

    public function edit($type, $id)
    {
        if (!$this->canManageLayanan()) {
            return redirect()->to('/admin/layanan')->with('error', 'Akses ditolak.');
        }

        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan) {
            return redirect()->to('/admin/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        $packages = $this->priceModel->getPackages($type, $id);

        $resources = $this->resourceModel->getByLayanan($type, $id);

        return view('admin/layanan/edit', [
            'title' => 'Edit Layanan',
            'activeMenu' => 'layanan',
            'layanan' => $layanan,
            'layananType' => $type,
            'kategoriList' => $this->kategoriModel->getActive(),
            'wpaList' => $this->wpaModel->where('status', 'active')->findAll(),
            'cwpaList' => $this->cwpaModel->where('status', 'active')->findAll(),
            'packages' => $packages,
            'resources' => $resources
        ]);
    }

    private function getLayananByType($type, $id)
    {
        switch ($type) {
            case 'artikel':
                $data = $this->artikelModel->find($id);
                if ($data) $data['name'] = $data['title'];
                return $data;
            case 'webinar':
            case 'workshop':
            case 'live_trade':
                $data = $this->eventModel->find($id);
                if ($data) $data['name'] = $data['title'];
                return $data;
            case 'toolkit':
            case 'ea':
                return $this->toolsModel->find($id);
            case 'pendampingan':
            case 'profirm':
            case 'private_konsultan':
            case 'vip_member':
                return $this->subscriptionModel->find($id);
            default:
                return null;
        }
    }

    public function update($type, $id)
    {
        if (!$this->canManageLayanan()) {
            return redirect()->to('/admin/layanan')->with('error', 'Akses ditolak.');
        }

        switch ($type) {
            case 'artikel':
                return $this->updateArtikel($id);
            case 'webinar':
            case 'workshop':
            case 'live_trade':
                return $this->updateEvent($type, $id);
            case 'toolkit':
            case 'ea':
                return $this->updateTool($type, $id);
            case 'pendampingan':
            case 'profirm':
            case 'private_konsultan':
            case 'vip_member':
                return $this->updateSubscription($type, $id);
            default:
                return redirect()->back()->with('error', 'Type tidak valid');
        }
    }

    private function updateArtikel($id)
    {
        $artikel = $this->artikelModel->find($id);
        if (!$artikel) {
            return redirect()->to('/admin/layanan')->with('error', 'Artikel tidak ditemukan');
        }
        $oldStatus = $artikel['status'];

        $data = [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'title' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'wpa_commission_percent' => $this->request->getPost('wpa_commission_percent') ?: 10.00,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Auto-calculate referral user pool (artikel has no cash price, pool = 0)
        $data['referral_user_cash'] = 0;
        $data['referral_user_poin'] = 0;

        if ($data['status'] === 'published' && !$artikel['published_at']) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            if ($artikel['thumbnail'] && file_exists(WRITEPATH . $artikel['thumbnail'])) {
                unlink(WRITEPATH . $artikel['thumbnail']);
            }
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/artikel', $newName);
            $data['thumbnail'] = 'uploads/layanan/artikel/' . $newName;
        }

        // Handle file upload
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($artikel['file_path'] && file_exists(WRITEPATH . $artikel['file_path'])) {
                unlink(WRITEPATH . $artikel['file_path']);
            }
            $newName = 'artikel_' . $id . '_' . time() . '.' . $file->getExtension();
            $file->move(WRITEPATH . 'uploads/layanan/artikel/files', $newName);
            $data['file_path'] = 'uploads/layanan/artikel/files/' . $newName;
            $data['file_type'] = $file->getExtension();
        }

        $this->artikelModel->update($id, $data);
        $this->saveResources('artikel', $id);

        // Notify WPA
        $newStatus = $data['status'];
        if ($oldStatus !== $newStatus) {
            $wpaId = $data['wpa_id'] ?? $artikel['wpa_id'];
            if ($wpaId) {
                $wpa = $this->wpaModel->find($wpaId);
                if ($wpa && $wpa['user_id']) {
                    $notif = new NotificationModel();
                    if (in_array($oldStatus, ['pending', 'menunggu verifikasi']) && in_array($newStatus, ['published', 'active', 'aktif', 'upcoming'])) {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Disetujui',
                            'Layanan Artikel "' . $data['title'] . '" telah disetujui and kini aktif.',
                            'success',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'rejected') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Ditolak',
                            'Layanan Artikel "' . $data['title'] . '" ditolak oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'tidak aktif' || $newStatus === 'inactive') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Dinonaktifkan',
                            'Layanan Artikel "' . $data['title'] . '" telah diubah menjadi tidak aktif oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    }
                }
            }
        }

        return redirect()->to('/admin/layanan')->with('success', 'Artikel berhasil diupdate');
    }

    private function updateEvent($type, $id)
    {
        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to('/admin/layanan')->with('error', 'Event tidak ditemukan');
        }
        $oldStatus = $event['status'];

        $data = [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'title' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'event_date' => $this->request->getPost('event_date'),
            'event_end_date' => $this->request->getPost('event_end_date') ?: null,
            'max_participants' => $this->request->getPost('max_participants') ?: 0,
            'total_sessions' => $this->request->getPost('total_sessions') ?: 1,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'is_recurring' => $this->request->getPost('is_recurring') ? 1 : 0,
            'recurring_frequency' => $this->request->getPost('recurring_frequency') ?: null,
            'recurring_day' => $this->request->getPost('recurring_day') ?: null,
            'recurring_time' => $this->request->getPost('recurring_time') ?: null,
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'is_license_product' => $this->request->getPost('is_license_product') ? 1 : 0,
            'license_duration' => $this->request->getPost('license_duration') ?: 0,
            'license_prefix' => $this->request->getPost('license_prefix') ?: 'ALMAI-{id_akun}-{random4}',
            'wpa_commission_percent' => $this->request->getPost('wpa_commission_percent') ?: 10.00,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Auto-calculate all referral pool fields from effective price × pool_pct%
        // If packages exist, use the highest package price as the reference
        $effectivePrice = (float)($data['price'] ?? 0);
        $packages = $this->request->getPost('packages');
        if ($this->request->getPost('has_packages') && is_array($packages)) {
            foreach ($packages as $pkg) {
                $pkgPrice = (float)($pkg['price'] ?? 0);
                if ($pkgPrice > $effectivePrice) $effectivePrice = $pkgPrice;
            }
        }
        $referralPool = $this->calcReferralPool($effectivePrice, (float)($data['referral_distribution_percentage'] ?? 25));
        $data['referral_user_cash'] = $referralPool['cash'];
        $data['referral_user_poin'] = $referralPool['poin'];
        $data['referral_wpa_cash']  = $referralPool['wpa_cash'];
        $data['referral_wpa_poin']  = $referralPool['wpa_poin'];

        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link');
            $data['meeting_id'] = $this->request->getPost('meeting_id');
            $data['meeting_password'] = $this->request->getPost('meeting_password');
        } else {
            $data['location'] = $this->request->getPost('location');
            $data['requires_agreement'] = $this->request->getPost('requires_agreement') ? 1 : 0;
            $data['agreement_text'] = $this->request->getPost('agreement_text');
        }

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            if ($event['thumbnail'] && file_exists(WRITEPATH . $event['thumbnail'])) {
                unlink(WRITEPATH . $event['thumbnail']);
            }
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
            $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
        }

        $this->eventModel->update($id, $data);
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);

        // Notify WPA
        $newStatus = $data['status'];
        if ($oldStatus !== $newStatus) {
            $wpaId = $data['wpa_id'] ?? $event['wpa_id'];
            if ($wpaId) {
                $wpa = $this->wpaModel->find($wpaId);
                if ($wpa && $wpa['user_id']) {
                    $notif = new NotificationModel();
                    if (in_array($oldStatus, ['pending', 'menunggu verifikasi']) && in_array($newStatus, ['published', 'active', 'aktif', 'upcoming', 'ongoing'])) {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Disetujui',
                            'Layanan Event "' . $data['title'] . '" telah disetujui and kini aktif.',
                            'success',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'rejected') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Ditolak',
                            'Layanan Event "' . $data['title'] . '" ditolak oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'tidak aktif' || $newStatus === 'inactive') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Dinonaktifkan',
                            'Layanan Event "' . $data['title'] . '" telah diubah menjadi tidak aktif oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    }
                }
            }
        }

        return redirect()->to('/admin/layanan')->with('success', ucfirst($type) . ' berhasil diupdate');
    }

    private function updateTool($type, $id)
    {
        $tool = $this->toolsModel->find($id);
        if (!$tool) {
            return redirect()->to('/admin/layanan')->with('error', 'Tool tidak ditemukan');
        }
        $oldStatus = $tool['status'];

        $data = [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'version' => $this->request->getPost('version'),
            'changelog' => $this->request->getPost('changelog'),
            'documentation_url' => $this->request->getPost('documentation_url'),
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'is_license_product' => $this->request->getPost('is_license_product') ? 1 : 0,
            'license_duration' => $this->request->getPost('license_duration') ?: 0,
            'license_prefix' => $this->request->getPost('license_prefix') ?: 'ALMAI-{id_akun}-{random4}',
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
        ];

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            if ($tool['thumbnail'] && file_exists(WRITEPATH . $tool['thumbnail'])) {
                unlink(WRITEPATH . $tool['thumbnail']);
            }
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/tools', $newName);
            $data['thumbnail'] = 'uploads/layanan/tools/' . $newName;
        }

        $this->toolsModel->update($id, $data);
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);

        // Notify WPA
        $newStatus = $data['status'];
        if ($oldStatus !== $newStatus) {
            $wpaId = $data['wpa_id'] ?? $tool['wpa_id'];
            if ($wpaId) {
                $wpa = $this->wpaModel->find($wpaId);
                if ($wpa && $wpa['user_id']) {
                    $notif = new NotificationModel();
                    if (in_array($oldStatus, ['pending', 'menunggu verifikasi']) && in_array($newStatus, ['published', 'active', 'aktif', 'upcoming'])) {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Disetujui',
                            'Layanan Tool "' . $data['name'] . '" telah disetujui and kini aktif.',
                            'success',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'rejected') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Ditolak',
                            'Layanan Tool "' . $data['name'] . '" ditolak oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'tidak aktif' || $newStatus === 'inactive') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Dinonaktifkan',
                            'Layanan Tool "' . $data['name'] . '" telah diubah menjadi tidak aktif oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    }
                }
            }
        }

        return redirect()->to('/admin/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil diupdate');
    }

    private function updateSubscription($type, $id)
    {
        $sub = $this->subscriptionModel->find($id);
        if (!$sub) {
            return redirect()->to('/admin/layanan')->with('error', 'Subscription tidak ditemukan');
        }
        $oldStatus = $sub['status'];

        $data = [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'duration_days' => $this->request->getPost('duration_days') ?: 30,
            'total_sessions' => $this->request->getPost('total_sessions') ?: 0,
            'max_slots' => $this->request->getPost('max_slots') ?: 0,
            'schedule_info' => $this->request->getPost('schedule_info'),
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_wpa_poin' => 0,
            'referral_wpa_cash' => 0,
            'referral_distribution_percentage' => (float)($this->request->getPost('referral_distribution_percentage') ?? 0),
            'referral_max_depth' => (int)($this->request->getPost('referral_max_depth') ?: 8),
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'rejection_reason' => $this->request->getPost('rejection_reason'),
        ];

        // Auto-calculate all referral pool fields from effective price × pool_pct%
        // If packages exist, use the highest package price as the reference
        $effectivePrice = (float)($data['price'] ?? 0);
        $packages = $this->request->getPost('packages');
        if ($this->request->getPost('has_packages') && is_array($packages)) {
            foreach ($packages as $pkg) {
                $pkgPrice = (float)($pkg['price'] ?? 0);
                if ($pkgPrice > $effectivePrice) $effectivePrice = $pkgPrice;
            }
        }
        $referralPool = $this->calcReferralPool($effectivePrice, (float)($data['referral_distribution_percentage'] ?? 25));
        $data['referral_user_cash'] = $referralPool['cash'];
        $data['referral_user_poin'] = $referralPool['poin'];
        $data['referral_wpa_cash']  = $referralPool['wpa_cash'];
        $data['referral_wpa_poin']  = $referralPool['wpa_poin'];

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            if ($sub['thumbnail'] && file_exists(WRITEPATH . $sub['thumbnail'])) {
                unlink(WRITEPATH . $sub['thumbnail']);
            }
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/subscription', $newName);
            $data['thumbnail'] = 'uploads/layanan/subscription/' . $newName;
        }

        $this->subscriptionModel->update($id, $data);
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);

        // Notify WPA
        $newStatus = $data['status'];
        if ($oldStatus !== $newStatus) {
            $wpaId = $data['wpa_id'] ?? $sub['wpa_id'];
            if ($wpaId) {
                $wpa = $this->wpaModel->find($wpaId);
                if ($wpa && $wpa['user_id']) {
                    $notif = new NotificationModel();
                    if (in_array($oldStatus, ['pending', 'menunggu verifikasi']) && in_array($newStatus, ['published', 'active', 'aktif', 'upcoming'])) {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Disetujui',
                            'Layanan Subscription "' . $data['name'] . '" telah disetujui and kini aktif.',
                            'success',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'rejected') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Ditolak',
                            'Layanan Subscription "' . $data['name'] . '" ditolak oleh admin. Alasan: ' . ($data['rejection_reason'] ?? '-'),
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    } elseif ($newStatus === 'tidak aktif' || $newStatus === 'inactive') {
                        $notif->createNotification(
                            $wpa['user_id'],
                            'Layanan Dinonaktifkan',
                            'Layanan Subscription "' . $data['name'] . '" telah diubah menjadi tidak aktif oleh admin.',
                            'error',
                            '/wpa/dashboard/layanan'
                        );
                    }
                }
            }
        }

        return redirect()->to('/admin/layanan')->with('success', $this->getSubcategoryLabel($type) . ' berhasil diupdate');
    }

    public function delete($type, $id)
    {
        if (!$this->canManageLayanan()) {
            return redirect()->to('/admin/layanan')->with('error', 'Akses ditolak.');
        }

        $model = null;
        $item = null;

        switch ($type) {
            case 'artikel':
                $model = $this->artikelModel;
                break;
            case 'webinar':
            case 'workshop':
            case 'live_trade':
                $model = $this->eventModel;
                break;
            case 'toolkit':
            case 'ea':
                $model = $this->toolsModel;
                break;
            case 'pendampingan':
            case 'profirm':
            case 'private_konsultan':
            case 'vip_member':
                $model = $this->subscriptionModel;
                break;
        }

        if (!$model) {
            return redirect()->to('/admin/layanan')->with('error', 'Type tidak valid');
        }

        $item = $model->find($id);
        if (!$item) {
            return redirect()->to('/admin/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        // Delete files
        if (!empty($item['thumbnail']) && file_exists(WRITEPATH . $item['thumbnail'])) {
            unlink(WRITEPATH . $item['thumbnail']);
        }
        if (!empty($item['file_path']) && file_exists(WRITEPATH . $item['file_path'])) {
            unlink(WRITEPATH . $item['file_path']);
        }

        $model->delete($id);
        return redirect()->to('/admin/layanan')->with('success', 'Layanan berhasil dihapus');
    }

    public function toggleStatus($type, $id)
    {
        if (!$this->canManageLayanan()) {
            return redirect()->to('/admin/layanan')->with('error', 'Akses ditolak.');
        }

        $model = null;
        $statusField = 'status';
        $activeValue = 'aktif';
        $inactiveValue = 'tidak aktif';

        switch ($type) {
            case 'artikel':
                $model = $this->artikelModel;
                break;
            case 'webinar':
            case 'workshop':
            case 'live_trade':
                $model = $this->eventModel;
                break;
            case 'toolkit':
            case 'ea':
                $model = $this->toolsModel;
                break;
            case 'pendampingan':
            case 'profirm':
            case 'private_konsultan':
            case 'vip_member':
                $model = $this->subscriptionModel;
                break;
        }

        if (!$model) {
            return redirect()->to('/admin/layanan')->with('error', 'Type tidak valid');
        }

        $item = $model->find($id);
        if (!$item) {
            return redirect()->to('/admin/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        $newStatus = $item['status'] === $activeValue ? $inactiveValue : $activeValue;
        $model->update($id, ['status' => $newStatus]);

        // Notify WPA
        $wpaId = $item['wpa_id'] ?? null;
        if ($wpaId) {
            $wpa = $this->wpaModel->find($wpaId);
            if ($wpa && $wpa['user_id']) {
                $notif = new NotificationModel();
                $title = $item['title'] ?? $item['name'] ?? 'Layanan';
                if ($newStatus === $activeValue) {
                    $notif->createNotification(
                        $wpa['user_id'],
                        'Layanan Diaktifkan',
                        'Layanan "' . $title . '" telah diaktifkan kembali oleh admin.',
                        'success',
                        '/wpa/dashboard/layanan'
                    );
                } else {
                    $notif->createNotification(
                        $wpa['user_id'],
                        'Layanan Dinonaktifkan',
                        'Layanan "' . $title . '" telah dinonaktifkan oleh admin.',
                        'error',
                        '/wpa/dashboard/layanan'
                    );
                }
            }
        }

        return redirect()->to('/admin/layanan')->with('success', 'Status layanan berhasil diubah');
    }
}

