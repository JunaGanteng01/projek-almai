<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\KategoriLayananModel;
use App\Models\LayananArtikelModel;
use App\Models\LayananEventModel;
use App\Models\LayananToolsModel;
use App\Models\LayananSubscriptionModel;
use App\Models\LayananModel;
use App\Models\LayananPriceModel;
use App\Models\WpaModel;
use App\Models\TransaksiModel;

class Layanan extends \App\Controllers\Layanan
{
    protected $kategoriModel;
    protected $artikelModel;
    protected $eventModel;
    protected $toolsModel;
    protected $subscriptionModel;
    protected $layananModel;
    protected $priceModel;
    protected $wpaModel;
    protected $resourceModel;

    public function __construct()
    {
        parent::__construct();
        $this->kategoriModel = new KategoriLayananModel();
        // Models already initialized in parent
        $this->layananModel = new LayananModel();
        $this->wpaModel = new WpaModel();
        $this->resourceModel = new \App\Models\LayananResourceModel();
    }

    private function getWpaId()
    {
        $wpaId = $this->session->get('wpaId');
        if (!$wpaId) {
            $userId = $this->session->get('userId');
            $wpa = $this->wpaModel->where('user_id', $userId)->first();
            if ($wpa) {
                $wpaId = $wpa['id'];
                $this->session->set('wpaId', $wpaId);
            }
        }
        return $wpaId;
    }

    public function index($categorySlug = null)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/login');

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $allLayanan = $this->getWpaLayanan($wpaId);

        if ($search) {
            $allLayanan = array_filter($allLayanan, function ($item) use ($search) {
                return stripos($item['name'] ?? $item['title'] ?? '', $search) !== false || stripos($item['category'] ?? '', $search) !== false;
            });
        }

        if ($status && $status !== 'all') {
            $allLayanan = array_filter($allLayanan, function ($item) use ($status) {
                if ($status === 'active') return in_array($item['status'] ?? '', ['active', 'aktif', 'published', 'upcoming']);
                return ($item['status'] ?? '') === $status;
            });
        }

        $totalLayanan = count($allLayanan);
        $countAdvokasi = count(array_filter($allLayanan, fn($item) => ($item['category'] ?? '') === 'Advokasi'));
        $countEA = count(array_filter($allLayanan, fn($item) => ($item['category'] ?? '') === 'Expert Advisor'));
        $countUltimate = count(array_filter($allLayanan, fn($item) => ($item['category'] ?? '') === 'Almai Ultimate'));

        $totalStudents = 0;
        $totalRevenue = 0;

        if (!empty($allLayanan)) {
            $layananIds = array_filter(array_column($allLayanan, 'id'));
            $layananNames = array_unique(array_filter(array_map(fn($item) => $item['name'] ?? $item['title'] ?? null, $allLayanan)));

            $stModel = new TransaksiModel();
            $stModel->select('layanan_id, product_name, COUNT(id) as total_sales, SUM(total) as total_revenue');
            $stModel->where('status', 'confirmed');
            $stModel->groupStart();
            if (!empty($layananIds)) $stModel->whereIn('layanan_id', $layananIds);
            if (!empty($layananNames)) {
                $stModel->orWhereIn('product_name', $layananNames);
                if (count($layananNames) < 50) {
                    foreach ($layananNames as $name) $stModel->orLike('product_name', $name . ' - ', 'after');
                }
            }
            $stModel->groupEnd();
            $stModel->groupBy('layanan_id, product_name');
            $allStats = $stModel->findAll();

            foreach ($allLayanan as &$item) {
                $item['total_sales'] = 0; $item['total_revenue'] = 0;
                $name = $item['name'] ?? $item['title'] ?? '';
                foreach ($allStats as $stat) {
                    if ($stat['layanan_id'] == $item['id'] || $stat['product_name'] === $name || (isset($stat['product_name']) && stripos($stat['product_name'], $name . ' - ') === 0)) {
                        $item['total_sales'] += $stat['total_sales'];
                        $item['total_revenue'] += $stat['total_revenue'];
                    }
                }
                $totalStudents += $item['total_sales']; $totalRevenue += $item['total_revenue'];
            }
        }

        return view('wpa/layanan/index', [
            'title' => 'Layanan Saya - WPA Dashboard',
            'activeMenu' => 'layanan',
            'layananList' => $allLayanan,
            'search' => $search,
            'currentStatus' => $status,
            'totalLayanan' => $totalLayanan,
            'countAdvokasi' => $countAdvokasi,
            'countEA' => $countEA,
            'countUltimate' => $countUltimate,
            'totalStudents' => $totalStudents,
            'totalRevenue' => $totalRevenue
        ]);
    }

    public function daftarLayanan()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/login');

        $currentCategory = $this->request->getGet('category') ?: 'all';
        $currentSubcategory = $this->request->getGet('subcategory') ?: 'all';
        $searchQuery = $this->request->getGet('search');

        // Fetch all services using unified logic
        $allLayanan = $this->getLayananData();

        // Filter by Search
        if ($searchQuery) {
            $allLayanan = array_filter($allLayanan, function ($item) use ($searchQuery) {
                return stripos($item['name'] ?? '', $searchQuery) !== false || 
                       stripos($item['description'] ?? '', $searchQuery) !== false;
            });
        }

        // Filter by Category
        if ($currentCategory !== 'all') {
            $allLayanan = array_filter($allLayanan, function($item) use ($currentCategory) {
                if ($currentCategory === 'Ultimate' || $currentCategory === 'Almai Ultimate') {
                    return $item['category'] === 'Ultimate' || $item['category'] === 'Almai Ultimate';
                }
                return $item['category'] === $currentCategory;
            });
        }

        // Filter by Subcategory
        if ($currentSubcategory !== 'all') {
            $allLayanan = array_filter($allLayanan, fn($item) => $item['subcategory'] === $currentSubcategory);
        }

        // Get Subcategories for current category (for filter UI)
        $subcategories = [];
        if ($currentCategory !== 'all') {
            $catFilter = $currentCategory;
            if ($catFilter === 'Ultimate') $catFilter = 'Almai Ultimate';
            
            // Get from predefined subcategories in model
            $allSubcategories = \App\Models\LayananModel::getSubcategories();
            $subcategories = $allSubcategories[$catFilter] ?? $allSubcategories['Ultimate'] ?? [];
        }

        return view('wpa/layanan/daftar_layanan', [
            'title' => 'Daftar Layanan - WPA Dashboard',
            'activeMenu' => 'daftar-layanan',
            'layananList' => array_values($allLayanan),
            'currentCategory' => $currentCategory,
            'currentSubcategory' => $currentSubcategory,
            'searchQuery' => $searchQuery,
            'subcategories' => $subcategories
        ]);
    }

    private function getWpaLayanan($wpaId)
    {
        $artikel = $this->artikelModel->where('wpa_id', $wpaId)->findAll();
        foreach ($artikel as &$item) { $item['category'] = 'Advokasi'; $item['subcategory'] = 'Artikel'; $item['type'] = 'artikel'; }

        $event = $this->eventModel->where('wpa_id', $wpaId)->findAll();
        foreach ($event as &$item) {
            $item['category'] = 'Advokasi';
            $item['subcategory'] = ucwords(str_replace('_', ' ', $item['type']));
        }

        $tools = $this->toolsModel->where('wpa_id', $wpaId)->findAll();
        foreach ($tools as &$item) {
            if ($item['type'] === 'ea') { $item['category'] = 'Expert Advisor'; $item['subcategory'] = 'Expert Advisor (EA)'; }
            else { $item['category'] = 'Almai Ultimate'; $item['subcategory'] = 'Almai Toolkits'; }
        }

        $sub = $this->subscriptionModel->where('wpa_id', $wpaId)->findAll();
        foreach ($sub as &$item) {
            $item['category'] = 'Almai Ultimate';
            $item['subcategory'] = ucwords(str_replace('_', ' ', $item['type']));
        }

        return array_merge($artikel, $event, $tools, $sub);
    }

    public function create()
    {
        return view('wpa/layanan/create', [
            'title' => 'Buat Layanan Baru - WPA Dashboard',
            'activeMenu' => 'layanan',
            'kategoriList' => $this->kategoriModel->getActive(),
        ]);
    }

    public function store()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/login');
        $subcategory = $this->request->getPost('subcategory');
        switch ($subcategory) {
            case 'artikel': return $this->storeArtikel($wpaId);
            case 'webinar': case 'workshop': case 'live_trade': return $this->storeEvent($subcategory, $wpaId);
            case 'toolkit': case 'ea': return $this->storeTool($subcategory, $wpaId);
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': return $this->storeSubscription($subcategory, $wpaId);
            default: return redirect()->back()->with('error', 'Subcategory tidak valid');
        }
    }

    private function storeArtikel($wpaId)
    {
        $data = [
            'wpa_id' => $wpaId, 'title' => $this->request->getPost('name'), 'specialist' => $this->request->getPost('specialist') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null, 'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'excerpt' => $this->request->getPost('excerpt'), 'content' => $this->request->getPost('content'),
            'poin_price' => $this->request->getPost('poin_price') ?? 0, 'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0, 'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
        ];
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/artikel', $newName);
                $data['thumbnail'] = 'uploads/layanan/artikel/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Artikel thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $newName = 'artikel_' . time() . '.' . $file->getExtension();
                $file->move(WRITEPATH . 'uploads/layanan/artikel/files', $newName);
                $data['file_path'] = 'uploads/layanan/artikel/files/' . $newName;
                $data['file_type'] = $file->getExtension();
            } catch (\Exception $e) {
                log_message('error', 'WPA Artikel file upload failed: ' . $e->getMessage());
            }
        }
        $id = $this->artikelModel->insert($data);
        if ($id && $data['status'] === 'menunggu verifikasi') {
            (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', 'Artikel baru menunggu verifikasi: ' . $data['title'], 'info', '/admin/layanan/edit/artikel/' . $id);
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', 'Artikel berhasil ditambahkan');
    }

    private function storeEvent($type, $wpaId)
    {
        $data = [
            'type' => $type, 'wpa_id' => $wpaId, 'title' => $this->request->getPost('name'),
            'specialist' => $this->request->getPost('specialist') ?: null, 'description' => $this->request->getPost('description'),
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'price' => $this->request->getPost('price') ?? 0, 'poin_price' => $this->request->getPost('poin_price') ?? 0,
            'original_price' => $this->request->getPost('original_price') ?: null, 'event_date' => $this->request->getPost('event_date'),
            'event_end_date' => $this->request->getPost('event_end_date') ?: null, 'max_participants' => $this->request->getPost('max_participants') ?? 0,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0, 'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
            'is_recurring' => $this->request->getPost('is_recurring') ? 1 : 0, 'recurring_frequency' => $this->request->getPost('recurring_frequency') ?: null,
            'recurring_day' => $this->request->getPost('recurring_day') ?: null, 'recurring_time' => $this->request->getPost('recurring_time') ?: null,
        ];
        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link'); $data['meeting_id'] = $this->request->getPost('meeting_id'); $data['meeting_password'] = $this->request->getPost('meeting_password');
        } else {
            $data['location'] = $this->request->getPost('location'); $data['requires_agreement'] = $this->request->getPost('requires_agreement') ? 1 : 0; $data['agreement_text'] = $this->request->getPost('agreement_text');
        }
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
                $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Event thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $id = $this->eventModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            if ($data['status'] === 'menunggu verifikasi') {
                (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', ucfirst($type) . ' baru menunggu verifikasi: ' . $data['title'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
            }
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', ucfirst($type) . ' berhasil ditambahkan');
    }

    private function storeTool($type, $wpaId)
    {
        $data = [
            'type' => $type, 'wpa_id' => $wpaId, 'name' => $this->request->getPost('name'),
            'specialist' => $this->request->getPost('specialist') ?: null, 'description' => $this->request->getPost('description'),
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'version' => $this->request->getPost('version'), 'changelog' => $this->request->getPost('changelog'),
            'price' => $this->request->getPost('price') ?? 0, 'poin_price' => $this->request->getPost('poin_price') ?? 0,
            'original_price' => $this->request->getPost('original_price') ?: null, 'documentation_url' => $this->request->getPost('documentation_url'),
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0, 'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
        ];
        $compatibility = $this->request->getPost('compatibility'); if ($compatibility) $data['compatibility'] = json_encode($compatibility);
        $features = $this->request->getPost('features'); if ($features) $data['features'] = json_encode(array_filter(array_map('trim', explode("\n", $features))));
        $requirements = $this->request->getPost('requirements'); if ($requirements) $data['requirements'] = json_encode(array_filter(array_map('trim', explode("\n", $requirements))));
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/tools', $newName);
                $data['thumbnail'] = 'uploads/layanan/tools/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Tool thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $newName = $type . '_' . time() . '.' . $file->getExtension();
                $file->move(WRITEPATH . 'uploads/layanan/tools/files', $newName);
                $data['file_path'] = 'uploads/layanan/tools/files/' . $newName;
                $data['file_type'] = $file->getExtension();
            } catch (\Exception $e) {
                log_message('error', 'WPA Tool file upload failed: ' . $e->getMessage());
            }
        }
        if ($type === 'ea') {
            $data['has_license'] = $this->request->getPost('has_license') ? 1 : 0; $data['license_duration'] = $this->request->getPost('license_duration') ?: 0;
            $eaFile = $this->request->getFile('ea_file');
            if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
                try {
                    $newName = $type . '_ea_' . time() . '.' . $eaFile->getExtension();
                    $eaFile->move(WRITEPATH . 'uploads/layanan/tools/files', $newName);
                    $data['ea_file_path'] = 'uploads/layanan/tools/files/' . $newName;
                } catch (\Exception $e) {
                    log_message('error', 'WPA EA file upload failed: ' . $e->getMessage());
                }
            }
        }
        $id = $this->toolsModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            if ($data['status'] === 'menunggu verifikasi') {
                (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' baru menunggu verifikasi: ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
            }
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil ditambahkan');
    }

    private function storeSubscription($type, $wpaId)
    {
        $data = [
            'type' => $type, 'wpa_id' => $wpaId, 'name' => $this->request->getPost('name'),
            'specialist' => $this->request->getPost('specialist') ?: null, 'description' => $this->request->getPost('description'),
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'price' => $this->request->getPost('price') ?: 0, 'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null, 'duration_days' => $this->request->getPost('duration_days') ?: 30,
            'max_slots' => $this->request->getPost('max_slots') ?: 0, 'schedule_info' => $this->request->getPost('schedule_info'),
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0, 'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
        ];
        $benefits = $this->request->getPost('benefits'); if ($benefits) $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
        $includes = $this->request->getPost('includes'); if ($includes) $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/subscription', $newName);
                $data['thumbnail'] = 'uploads/layanan/subscription/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Subscription thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $id = $this->subscriptionModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            if ($data['status'] === 'menunggu verifikasi') {
                (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', 'Subscription baru menunggu verifikasi: ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
            }
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', 'Subscription berhasil ditambahkan');
    }

    private function savePackages($type, $id, $clearExisting = false)
    {
        if ($this->request->getPost('has_packages')) {
            if ($clearExisting) $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
            $packages = $this->request->getPost('packages');
            if ($packages && is_array($packages)) {
                $sort = 0;
                foreach ($packages as $pkg) {
                    if (empty($pkg['name']) || (empty($pkg['price']) && empty($pkg['poin_price']))) continue;
                    $this->priceModel->insert([
                        'layanan_type' => $type, 'layanan_id' => $id, 'name' => $pkg['name'],
                        'price' => $pkg['price'] ?? 0, 'poin_price' => $pkg['poin_price'] ?? 0,
                        'original_price' => !empty($pkg['original_price']) ? $pkg['original_price'] : null,
                        'description' => $pkg['description'] ?? '', 'sort_order' => $sort++
                    ]);
                }
            }
        } elseif ($clearExisting) $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
    }

    public function edit($type, $id)
    {
        $wpaId = $this->getWpaId(); if (!$wpaId) return redirect()->to('/wpa/login');
        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan || $layanan['wpa_id'] != $wpaId) return redirect()->to('/wpa/dashboard/layanan')->with('error', 'Akses ditolak.');
        return view('wpa/layanan/edit', [
            'title' => 'Edit Layanan - WPA Dashboard', 'activeMenu' => 'layanan', 'layanan' => $layanan, 'layananType' => $type,
            'kategoriList' => $this->kategoriModel->getActive(),
            'packages' => $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->orderBy('sort_order', 'ASC')->findAll(),
            'resources' => $this->resourceModel->where('layanan_type', $type)->where('layanan_id', $id)->findAll(),
        ]);
    }

    private function getLayananByType($type, $id)
    {
        switch ($type) {
            case 'artikel': $data = $this->artikelModel->find($id); if ($data) $data['name'] = $data['title']; return $data;
            case 'webinar': case 'workshop': case 'live_trade': $data = $this->eventModel->find($id); if ($data) $data['name'] = $data['title']; return $data;
            case 'toolkit': case 'ea': return $this->toolsModel->find($id);
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': return $this->subscriptionModel->find($id);
            default: return null;
        }
    }

    public function update($type, $id)
    {
        $wpaId = $this->getWpaId(); if (!$wpaId) return redirect()->to('/wpa/login');
        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan || $layanan['wpa_id'] != $wpaId) return redirect()->to('/wpa/dashboard/layanan')->with('error', 'Akses ditolak.');
        switch ($type) {
            case 'artikel': return $this->updateArtikel($id, $wpaId);
            case 'webinar': case 'workshop': case 'live_trade': return $this->updateEvent($type, $id, $wpaId);
            case 'toolkit': case 'ea': return $this->updateTool($type, $id, $wpaId);
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': return $this->updateSubscription($type, $id, $wpaId);
            default: return redirect()->back()->with('error', 'Type tidak valid');
        }
    }

    private function updateArtikel($id, $wpaId)
    {
        $artikel = $this->artikelModel->find($id);
        $data = [
            'title' => $this->request->getPost('name'), 'specialist' => $this->request->getPost('specialist') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'excerpt' => $this->request->getPost('excerpt'), 'content' => $this->request->getPost('content'),
            'poin_price' => $this->request->getPost('poin_price') ?? 0, 'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0, 'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
        ];
        if ($data['status'] === 'published' && !$artikel['published_at']) $data['published_at'] = date('Y-m-d H:i:s');
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/artikel', $newName);
                $data['thumbnail'] = 'uploads/layanan/artikel/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Update artikel thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $newName = 'artikel_' . time() . '.' . $file->getExtension();
                $file->move(WRITEPATH . 'uploads/layanan/artikel/files', $newName);
                $data['file_path'] = 'uploads/layanan/artikel/files/' . $newName;
                $data['file_type'] = $file->getExtension();
            } catch (\Exception $e) {
                log_message('error', 'WPA Update artikel file upload failed: ' . $e->getMessage());
            }
        }
        $this->artikelModel->update($id, $data);
        if ($data['status'] === 'menunggu verifikasi') {
            (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', 'Artikel menunggu verifikasi (Update): ' . $data['title'], 'info', '/admin/layanan/edit/artikel/' . $id);
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', 'Artikel berhasil diupdate');
    }

    private function updateEvent($type, $id, $wpaId)
    {
        $data = [
            'title' => $this->request->getPost('name'), 'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'), 'price' => $this->request->getPost('price') ?? 0,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'poin_price' => $this->request->getPost('poin_price') ?? 0, 'original_price' => $this->request->getPost('original_price') ?: null,
            'event_date' => $this->request->getPost('event_date'), 'event_end_date' => $this->request->getPost('event_end_date') ?: null,
            'max_participants' => $this->request->getPost('max_participants') ?? 0, 'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0, 'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
            'is_recurring' => $this->request->getPost('is_recurring') ? 1 : 0, 'recurring_frequency' => $this->request->getPost('recurring_frequency') ?: null,
            'recurring_day' => $this->request->getPost('recurring_day') ?: null, 'recurring_time' => $this->request->getPost('recurring_time') ?: null,
        ];
        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link'); $data['meeting_id'] = $this->request->getPost('meeting_id'); $data['meeting_password'] = $this->request->getPost('meeting_password');
        } else {
            $data['location'] = $this->request->getPost('location'); $data['requires_agreement'] = $this->request->getPost('requires_agreement') ? 1 : 0; $data['agreement_text'] = $this->request->getPost('agreement_text');
        }
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
                $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Update event thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $this->eventModel->update($id, $data); $this->savePackages($type, $id, true);
        if ($data['status'] === 'menunggu verifikasi') {
            (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', ucfirst($type) . ' menunggu verifikasi (Update): ' . $data['title'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', ucfirst($type) . ' berhasil diupdate');
    }

    private function updateTool($type, $id, $wpaId)
    {
        $data = [
            'name' => $this->request->getPost('name'), 'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'), 'price' => $this->request->getPost('price') ?? 0,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'poin_price' => $this->request->getPost('poin_price') ?? 0, 'original_price' => $this->request->getPost('original_price') ?: null,
            'version' => $this->request->getPost('version'), 'changelog' => $this->request->getPost('changelog'),
            'documentation_url' => $this->request->getPost('documentation_url'), 'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0, 'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
        ];
        $compatibility = $this->request->getPost('compatibility'); if ($compatibility) $data['compatibility'] = json_encode($compatibility);
        $features = $this->request->getPost('features'); if ($features) $data['features'] = json_encode(array_filter(array_map('trim', explode("\n", $features))));
        $requirements = $this->request->getPost('requirements'); if ($requirements) $data['requirements'] = json_encode(array_filter(array_map('trim', explode("\n", $requirements))));
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/tools', $newName);
                $data['thumbnail'] = 'uploads/layanan/tools/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Update tool thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $newName = $type . '_' . time() . '.' . $file->getExtension();
                $file->move(WRITEPATH . 'uploads/layanan/tools/files', $newName);
                $data['file_path'] = 'uploads/layanan/tools/files/' . $newName;
                $data['file_type'] = $file->getExtension();
            } catch (\Exception $e) {
                log_message('error', 'WPA Update tool file upload failed: ' . $e->getMessage());
            }
        }
        if ($type === 'ea') {
            $data['has_license'] = $this->request->getPost('has_license') ? 1 : 0; $data['license_duration'] = $this->request->getPost('license_duration') ?: 0;
            $eaFile = $this->request->getFile('ea_file');
            if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
                try {
                    $newName = $type . '_ea_' . time() . '.' . $eaFile->getExtension();
                    $eaFile->move(WRITEPATH . 'uploads/layanan/tools/files', $newName);
                    $data['ea_file_path'] = 'uploads/layanan/tools/files/' . $newName;
                } catch (\Exception $e) {
                    log_message('error', 'WPA Update EA file upload failed: ' . $e->getMessage());
                }
            }
        }
        $this->toolsModel->update($id, $data); $this->savePackages($type, $id, true);
        if ($data['status'] === 'menunggu verifikasi') {
            (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' menunggu verifikasi (Update): ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil diupdate');
    }

    private function updateSubscription($type, $id, $wpaId)
    {
        $data = [
            'name' => $this->request->getPost('name'), 'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'), 'price' => $this->request->getPost('price') ?? 0,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'poin_price' => $this->request->getPost('poin_price') ?? 0, 'original_price' => $this->request->getPost('original_price') ?: null,
            'duration_days' => $this->request->getPost('duration_days') ?: 30, 'max_slots' => $this->request->getPost('max_slots') ?? 0,
            'schedule_info' => $this->request->getPost('schedule_info'), 'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0, 'status' => ($this->request->getPost('status') === 'tidak aktif') ? 'tidak aktif' : 'menunggu verifikasi',
        ];
        $benefits = $this->request->getPost('benefits'); if ($benefits) $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
        $includes = $this->request->getPost('includes'); if ($includes) $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/subscription', $newName);
                $data['thumbnail'] = 'uploads/layanan/subscription/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'WPA Update subscription thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $this->subscriptionModel->update($id, $data); $this->savePackages($type, $id, true);
        if ($data['status'] === 'menunggu verifikasi') {
            (new \App\Models\NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru', 'Subscription menunggu verifikasi (Update): ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', 'Subscription berhasil diupdate');
    }

    public function delete($type, $id)
    {
        $wpaId = $this->getWpaId(); if (!$wpaId) return redirect()->to('/wpa/login');
        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan || $layanan['wpa_id'] != $wpaId) return redirect()->to('/wpa/dashboard/layanan')->with('error', 'Akses ditolak.');
        switch ($type) {
            case 'artikel': $this->artikelModel->delete($id); break;
            case 'webinar': case 'workshop': case 'live_trade': $this->eventModel->delete($id); break;
            case 'toolkit': case 'ea': $this->toolsModel->delete($id); break;
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': $this->subscriptionModel->delete($id); break;
        }
        return redirect()->to('/wpa/dashboard/layanan')->with('success', 'Layanan berhasil dihapus');
    }

    public function uploadImage()
    {
        $file = $this->request->getFile('upload');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/content', $newName);
                return $this->response->setJSON(['url' => base_url('file/uploads/content/' . $newName)]);
            } catch (\Exception $e) {
                log_message('error', 'WPA Content image upload failed: ' . $e->getMessage());
                return $this->response->setJSON(['error' => 'Upload failed'], 500);
            }
        }
        return $this->response->setJSON(['error' => ['message' => 'Upload failed']]);
    }
}
