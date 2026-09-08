<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\KategoriLayananModel;
use App\Models\LayananArtikelModel;
use App\Models\LayananEventModel;
use App\Models\LayananToolsModel;
use App\Models\LayananSubscriptionModel;
use App\Models\LayananModel;
use App\Models\LayananPriceModel;
use App\Models\CwpaModel;
use App\Models\TransaksiModel;
use App\Models\LayananResourceModel;
use App\Models\NotificationModel;

class Layanan extends \App\Controllers\Layanan
{
    protected $kategoriModel;
    protected $artikelModel;
    protected $eventModel;
    protected $toolsModel;
    protected $subscriptionModel;
    protected $layananModel;
    protected $priceModel;
    protected $cwpaModel;
    protected $resourceModel;

    public function __construct()
    {
        parent::__construct();
        $this->kategoriModel = new KategoriLayananModel();
        // Models already initialized in parent
        $this->layananModel = new LayananModel();
        $this->cwpaModel = new CwpaModel();
        $this->resourceModel = new LayananResourceModel();
    }

    private function getCwpaId()
    {
        $cwpaId = $this->session->get('cwpaId');
        if (!$cwpaId) {
            $userId = $this->session->get('userId');
            $cwpa = $this->cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) {
                $cwpaId = $cwpa['id'];
                $this->session->set('cwpaId', $cwpaId);
            }
        }
        return $cwpaId;
    }

    public function index($categorySlug = null)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login');

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $allLayanan = $this->getCwpaLayanan($cwpaId);

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
        $advokasiName = \App\Models\KategoriLayananModel::getNameBySlug('advokasi', 'Advokasi');
        $countAdvokasi = count(array_filter($allLayanan, fn($item) => ($item['category'] ?? '') === $advokasiName));
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

        return view('cwpa/layanan/index', [
            'title' => 'Layanan Saya - CWPA Dashboard',
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
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login');

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
            
            $allSubcategories = LayananModel::getSubcategories();
            $subcategories = $allSubcategories[$catFilter] ?? $allSubcategories['Ultimate'] ?? [];
        }

        return view('cwpa/layanan/daftar_layanan', [
            'title' => 'Daftar Layanan - CWPA Dashboard',
            'activeMenu' => 'daftar-layanan',
            'layananList' => array_values($allLayanan),
            'currentCategory' => $currentCategory,
            'currentSubcategory' => $currentSubcategory,
            'searchQuery' => $searchQuery,
            'subcategories' => $subcategories
        ]);
    }

    public function detail($slug)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login');

        // 1. Get Service Data (Broad search across all tables, ignoring status for dashboard view)
        $db = \Config\Database::connect();
        $fixThumbnail = function ($path) {
            if (empty($path)) return null;
            if (strpos($path, 'http') === 0) return $path;
            $path = preg_replace('/^writable\//', '', $path);
            return base_url('file/' . ltrim($path, '/'));
        };
        
        // First try main table
        $layananModel = new LayananModel();
        $layanan = $layananModel->where('slug', $slug)->first();
        if ($layanan) {
            $layanan['type'] = 'layanan';
            $layanan['thumbnail'] = $fixThumbnail($layanan['thumbnail']);
        }

        // If not found, search in all other tables
        if (!$layanan) {
            $advokasiName = \App\Models\KategoriLayananModel::getNameBySlug('advokasi', 'Advokasi');
            $tables = [
                'layanan_tools' => ['Expert Advisor', 'Expert Advisor'],
                'layanan_event' => [$advokasiName, 'Event'],
                'layanan_artikel' => [$advokasiName, 'Artikel'],
                'layanan_subscription' => [$advokasiName, 'Subscription']
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
                    } elseif ($table === 'layanan_subscription') {
                        $layanan['category'] = in_array($item['type'], ['pendampingan', 'profirm']) ? $advokasiName : 'Ultimate';
                        $labels = ['pendampingan' => 'Pendampingan CWPA', 'profirm' => 'Profirm', 'vip_member' => 'VIP Member', 'private_konsultan' => 'Private Konsultan'];
                        $layanan['subcategory'] = $labels[$item['type']] ?? ucfirst($item['type']);
                    } elseif ($table === 'layanan_event') {
                        $layanan['subcategory'] = ucfirst($item['type']);
                        $layanan['name'] = $item['title'];
                    } elseif ($table === 'layanan_artikel') {
                        $layanan['name'] = $item['title'];
                    }
                    
                    if (!isset($layanan['type'])) $layanan['type'] = ($table === 'layanan_artikel') ? 'artikel' : ($item['type'] ?? 'layanan');
                    $layanan['thumbnail'] = $fixThumbnail($layanan['thumbnail'] ?? null);
                    break;
                }
            }
        }

        if (!$layanan) {
            // Redirect to dashboard home as requested ("daftar-layanan hapus aja")
            return redirect()->to('/cwpa/dashboard')->with('error', 'Layanan tidak ditemukan');
        }

        // 3. Fetch Additional Info for Detail Page
        $priceModel = new LayananPriceModel();
        $ulasanModel = new \App\Models\LayananUlasanModel();
        
        $packages = $priceModel->where('layanan_id', $layanan['id'])->orderBy('price', 'ASC')->findAll();
        $reviews = $ulasanModel->getReviews($layanan['id'], $layanan['type'] ?? 'layanan');
        $avgRatingQuery = $ulasanModel->where('layanan_id', $layanan['id'])->selectAvg('rating')->first();
        
        // Get Provider (WPA) Data
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $db->table('wpa')->where('id', $layanan['wpa_id'])->get()->getRowArray();
        }

        return view('cwpa/layanan/detail', [
            'title' => esc($layanan['name']) . ' - ALMAI',
            'activeMenu' => 'daftar-layanan',
            'kelas' => $layanan,
            'packages' => $packages,
            'reviews' => $reviews,
            'averageRating' => $avgRatingQuery['rating'] ?? 5.0,
            'reviewCount' => count($reviews),
            'wpa' => $wpa,
            'referralCode' => session()->get('referralCode') ?? ''
        ]);
    }

    public function detailPublic($slug)
    {
        // Public version - no login required
        // Same logic as detail() but without CWPA ID check
        
        // 1. Get Service Data (Broad search across all tables)
        $db = \Config\Database::connect();
        $fixThumbnail = function ($path) {
            if (empty($path)) return null;
            if (strpos($path, 'http') === 0) return $path;
            $path = preg_replace('/^writable\//', '', $path);
            return base_url('file/' . ltrim($path, '/'));
        };
        
        // First try main table
        $layananModel = new LayananModel();
        $layanan = $layananModel->where('slug', $slug)->first();
        if ($layanan) {
            $layanan['type'] = 'layanan';
            $layanan['thumbnail'] = $fixThumbnail($layanan['thumbnail']);
        }

        // If not found, search in all other tables
        if (!$layanan) {
            $advokasiName = \App\Models\KategoriLayananModel::getNameBySlug('advokasi', 'Advokasi');
            $tables = [
                'layanan_tools' => ['Expert Advisor', 'Expert Advisor'],
                'layanan_event' => [$advokasiName, 'Event'],
                'layanan_artikel' => [$advokasiName, 'Artikel'],
                'layanan_subscription' => [$advokasiName, 'Subscription']
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
                    } elseif ($table === 'layanan_subscription') {
                        $layanan['category'] = in_array($item['type'], ['pendampingan', 'profirm']) ? $advokasiName : 'Ultimate';
                        $labels = ['pendampingan' => 'Pendampingan CWPA', 'profirm' => 'Profirm', 'vip_member' => 'VIP Member', 'private_konsultan' => 'Private Konsultan'];
                        $layanan['subcategory'] = $labels[$item['type']] ?? ucfirst($item['type']);
                    } elseif ($table === 'layanan_event') {
                        $layanan['subcategory'] = ucfirst($item['type']);
                        $layanan['name'] = $item['title'];
                    } elseif ($table === 'layanan_artikel') {
                        $layanan['name'] = $item['title'];
                    }
                    
                    if (!isset($layanan['type'])) $layanan['type'] = ($table === 'layanan_artikel') ? 'artikel' : ($item['type'] ?? 'layanan');
                    $layanan['thumbnail'] = $fixThumbnail($layanan['thumbnail'] ?? null);
                    break;
                }
            }
        }

        if (!$layanan) {
            return redirect()->to('/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        // Fetch Additional Info
        $priceModel = new LayananPriceModel();
        $ulasanModel = new \App\Models\LayananUlasanModel();
        
        $packages = $priceModel->where('layanan_id', $layanan['id'])->orderBy('price', 'ASC')->findAll();
        $reviews = $ulasanModel->getReviews($layanan['id'], $layanan['type'] ?? 'layanan');
        $avgRatingQuery = $ulasanModel->where('layanan_id', $layanan['id'])->selectAvg('rating')->first();
        
        // Get Provider (WPA) Data
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $db->table('wpa')->where('id', $layanan['wpa_id'])->get()->getRowArray();
        }

        return view('cwpa/layanan/detail', [
            'title' => esc($layanan['name']) . ' - ALMAI',
            'activeMenu' => 'daftar-layanan',
            'kelas' => $layanan,
            'packages' => $packages,
            'reviews' => $reviews,
            'averageRating' => $avgRatingQuery['rating'] ?? 5.0,
            'reviewCount' => count($reviews),
            'wpa' => $wpa,
            'referralCode' => session()->get('referralCode') ?? '',
            'isPublic' => true
        ]);
    }

    private function getCwpaLayanan($cwpaId)
    {
        $fixThumbnail = function ($path) {
            if (empty($path)) return null;
            if (strpos($path, 'http') === 0) return $path;
            $path = preg_replace('/^writable\//', '', $path);
            return base_url('file/' . ltrim($path, '/'));
        };

        $advokasiName = \App\Models\KategoriLayananModel::getNameBySlug('advokasi', 'Advokasi');
        $artikel = $this->artikelModel->where('cwpa_id', $cwpaId)->findAll();
        foreach ($artikel as &$item) {
            $item['category'] = $advokasiName;
            $item['subcategory'] = 'Artikel';
            $item['type'] = 'artikel';
            $item['name'] = $item['title'];
            $item['thumbnail'] = $fixThumbnail($item['thumbnail'] ?? null);
        }

        $event = $this->eventModel->where('cwpa_id', $cwpaId)->findAll();
        foreach ($event as &$item) {
            $item['category'] = $advokasiName;
            $item['subcategory'] = ucwords(str_replace('_', ' ', $item['type']));
            $item['name'] = $item['title'];
            $item['thumbnail'] = $fixThumbnail($item['thumbnail'] ?? null);
        }

        $tools = $this->toolsModel->where('cwpa_id', $cwpaId)->findAll();
        foreach ($tools as &$item) {
            if ($item['type'] === 'ea') {
                $item['category'] = 'Expert Advisor';
                $item['subcategory'] = 'Expert Advisor (EA)';
            } else {
                $item['category'] = 'Almai Ultimate';
                $item['subcategory'] = 'Almai Toolkits';
            }
            $item['thumbnail'] = $fixThumbnail($item['thumbnail'] ?? null);
        }

        $sub = $this->subscriptionModel->where('cwpa_id', $cwpaId)->findAll();
        foreach ($sub as &$item) {
            $item['category'] = 'Almai Ultimate';
            $item['subcategory'] = ucwords(str_replace('_', ' ', $item['type']));
            $item['thumbnail'] = $fixThumbnail($item['thumbnail'] ?? null);
        }

        return array_merge($artikel, $event, $tools, $sub);
    }

    public function create()
    {
        return view('cwpa/layanan/create', [
            'title' => 'Buat Layanan Baru - CWPA Dashboard',
            'activeMenu' => 'layanan',
            'kategoriList' => $this->kategoriModel->getActive(),
        ]);
    }

    public function store()
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login');
        $subcategory = $this->request->getPost('subcategory');
        switch ($subcategory) {
            case 'artikel': return $this->storeArtikel($cwpaId);
            case 'webinar': case 'workshop': case 'live_trade': return $this->storeEvent($subcategory, $cwpaId);
            case 'toolkit': case 'ea': return $this->storeTool($subcategory, $cwpaId);
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': return $this->storeSubscription($subcategory, $cwpaId);
            default: return redirect()->back()->with('error', 'Subcategory tidak valid');
        }
    }

    private function storeArtikel($cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['title'] = $this->request->getPost('name');
        $data['excerpt'] = $this->request->getPost('excerpt');
        $data['content'] = $this->request->getPost('content');
        
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/artikel', $newName);
                $data['thumbnail'] = 'uploads/layanan/artikel/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Thumbnail upload failed: ' . $e->getMessage());
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
                log_message('error', 'File upload failed: ' . $e->getMessage());
            }
        }
        $data = $this->filterDataByTableFields('layanan_artikel', $data);
        $id = $this->artikelModel->insert($data);
        if ($id) {
            $this->saveResources('artikel', $id);
            (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', 'Artikel baru menunggu verifikasi: ' . $data['title'], 'info', '/admin/layanan/edit/artikel/' . $id);
        }
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', 'Artikel berhasil ditambahkan');
    }

    private function storeEvent($type, $cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['type'] = $type;
        $data['title'] = $this->request->getPost('name');
        $data['description'] = $this->request->getPost('description');
        $data['price'] = 0;
        $data['original_price'] = null;
        $data['event_date'] = $this->request->getPost('event_date');
        $data['event_end_date'] = $this->request->getPost('event_end_date') ?: null;
        $data['max_participants'] = $this->request->getPost('max_participants') ?? 0;
        $data['is_recurring'] = $this->request->getPost('is_recurring') ? 1 : 0;
        $data['recurring_frequency'] = $this->request->getPost('recurring_frequency') ?: null;
        $data['recurring_day'] = $this->request->getPost('recurring_day') ?: null;
        $data['recurring_time'] = $this->request->getPost('recurring_time') ?: null;
        $data['location'] = $this->request->getPost('location');

        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link');
            $data['meeting_id'] = $this->request->getPost('meeting_id');
            $data['meeting_password'] = $this->request->getPost('meeting_password');
        } else {
            $data['requires_agreement'] = $this->request->getPost('requires_agreement') ? 1 : 0;
            $data['agreement_text'] = $this->request->getPost('agreement_text');
        }

        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
                $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Event thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $data = $this->filterDataByTableFields('layanan_event', $data);
        $id = $this->eventModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
            (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', ucfirst($type) . ' baru menunggu verifikasi: ' . $data['title'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        }
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', ucfirst($type) . ' berhasil ditambahkan');
    }

    private function storeTool($type, $cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['type'] = $type;
        $data['name'] = $this->request->getPost('name');
        $data['description'] = $this->request->getPost('description');
        $data['price'] = 0;
        $data['original_price'] = null;
        $data['version'] = $this->request->getPost('version');
        $data['changelog'] = $this->request->getPost('changelog');
        $data['documentation_url'] = $this->request->getPost('documentation_url');
        
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
                log_message('error', 'Tool thumbnail upload failed: ' . $e->getMessage());
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
                log_message('error', 'Tool file upload failed: ' . $e->getMessage());
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
                    log_message('error', 'EA file upload failed: ' . $e->getMessage());
                }
            }
        }
        $data = $this->filterDataByTableFields('layanan_tools', $data);
        $id = $this->toolsModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
            (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' baru menunggu verifikasi: ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        }
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil ditambahkan');
    }

    private function storeSubscription($type, $cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['type'] = $type;
        $data['name'] = $this->request->getPost('name');
        $data['description'] = $this->request->getPost('description');
        $data['price'] = 0;
        $data['original_price'] = null;
        $data['duration_days'] = $this->request->getPost('duration_days') ?: 30;
        $data['max_slots'] = $this->request->getPost('max_slots') ?: 0;
        $data['schedule_info'] = $this->request->getPost('schedule_info');
        
        $benefits = $this->request->getPost('benefits'); if ($benefits) $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
        $includes = $this->request->getPost('includes'); if ($includes) $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));

        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/subscription', $newName);
                $data['thumbnail'] = 'uploads/layanan/subscription/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Subscription thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $data = $this->filterDataByTableFields('layanan_subscription', $data);
        $id = $this->subscriptionModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
            (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', 'Subscription baru menunggu verifikasi: ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        }
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', 'Subscription berhasil ditambahkan');
    }

    private function getPostData($cwpaId)
    {
        return [
            'cwpa_id' => $cwpaId,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'poin_price' => $this->request->getPost('poin_price') ?? 0,
            'referral_user_poin' => $this->request->getPost('referral_user_poin') ?? 0,
            'referral_distribution_percentage' => $this->request->getPost('referral_distribution_percentage') ?? 50.00,
            'referral_max_depth' => $this->request->getPost('referral_max_depth') ?? 8,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => 'menunggu verifikasi',
        ];
    }

    private function filterDataByTableFields(string $table, array $data): array
    {
        $db = \Config\Database::connect();

        foreach (array_keys($data) as $field) {
            if (!$db->fieldExists($field, $table)) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    private function savePackages($type, $id, $clearExisting = false)
    {
        if ($this->request->getPost('has_packages')) {
            if ($clearExisting) $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
            $packages = $this->request->getPost('packages');
            if ($packages && is_array($packages)) {
                $sort = 0;
                foreach ($packages as $pkg) {
                    if (empty($pkg['name']) || empty($pkg['poin_price'])) continue;
                    $this->priceModel->insert([
                        'layanan_type' => $type, 'layanan_id' => $id, 'name' => $pkg['name'],
                        'price' => 0, 'poin_price' => $pkg['poin_price'] ?? 0,
                        'original_price' => null,
                        'description' => $pkg['description'] ?? '', 'sort_order' => $sort++
                    ]);
                }
            }
        } elseif ($clearExisting) $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
    }

    private function saveResources($type, $id)
    {
        // Delete resources if checked
        $deleteResources = $this->request->getPost('delete_resources');
        if ($deleteResources && is_array($deleteResources)) {
            foreach ($deleteResources as $resId) {
                $res = $this->resourceModel->find($resId);
                if ($res && $res['layanan_type'] == $type && $res['layanan_id'] == $id) {
                    if (file_exists(WRITEPATH . $res['file_path'])) @unlink(WRITEPATH . $res['file_path']);
                    $this->resourceModel->delete($resId);
                }
            }
        }

        // Add new resources
        $resources = $this->request->getFileMultiple('resources');
        $titles = $this->request->getPost('resource_titles');
        if ($resources) {
            foreach ($resources as $i => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    try {
                        // Get file info before moving
                        $fileExtension = $file->getExtension();
                        $fileSize = $file->getSize();
                        $fileName = $file->getName();
                        
                        $newName = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/layanan/resources', $newName);
                        
                        $this->resourceModel->insert([
                            'layanan_type' => $type,
                            'layanan_id' => $id,
                            'title' => $titles[$i] ?? $fileName,
                            'file_path' => 'uploads/layanan/resources/' . $newName,
                            'file_type' => $fileExtension,
                            'file_size' => $fileSize,
                            'sort_order' => 0
                        ]);
                    } catch (\Exception $e) {
                        log_message('error', 'Resource upload failed: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    public function edit($type, $id)
    {
        $cwpaId = $this->getCwpaId(); if (!$cwpaId) return redirect()->to('/login');
        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan || $layanan['cwpa_id'] != $cwpaId) return redirect()->to('/cwpa/dashboard/layanan')->with('error', 'Akses ditolak.');
        return view('cwpa/layanan/edit', [
            'title' => 'Edit Layanan - CWPA Dashboard', 'activeMenu' => 'layanan', 'layanan' => $layanan, 'layananType' => $type,
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
        $cwpaId = $this->getCwpaId(); if (!$cwpaId) return redirect()->to('/login');
        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan || $layanan['cwpa_id'] != $cwpaId) return redirect()->to('/cwpa/dashboard/layanan')->with('error', 'Akses ditolak.');
        switch ($type) {
            case 'artikel': return $this->updateArtikel($id, $cwpaId);
            case 'webinar': case 'workshop': case 'live_trade': return $this->updateEvent($type, $id, $cwpaId);
            case 'toolkit': case 'ea': return $this->updateTool($type, $id, $cwpaId);
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': return $this->updateSubscription($type, $id, $cwpaId);
            default: return redirect()->back()->with('error', 'Type tidak valid');
        }
    }

    private function updateArtikel($id, $cwpaId)
    {
        $artikel = $this->artikelModel->find($id);
        $data = $this->getPostData($cwpaId);
        $data['title'] = $this->request->getPost('name');
        $data['excerpt'] = $this->request->getPost('excerpt');
        $data['content'] = $this->request->getPost('content');
        
        if ($data['status'] === 'published' && !$artikel['published_at']) $data['published_at'] = date('Y-m-d H:i:s');
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/artikel', $newName);
                $data['thumbnail'] = 'uploads/layanan/artikel/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Update artikel thumbnail upload failed: ' . $e->getMessage());
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
                log_message('error', 'Update artikel file upload failed: ' . $e->getMessage());
            }
        }
        $data = $this->filterDataByTableFields('layanan_artikel', $data);
        $this->artikelModel->update($id, $data);
        $this->saveResources('artikel', $id);
        (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', 'Artikel menunggu verifikasi (Update): ' . $data['title'], 'info', '/admin/layanan/edit/artikel/' . $id);
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', 'Artikel berhasil diupdate dan menunggu verifikasi');
    }

    private function updateEvent($type, $id, $cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['title'] = $this->request->getPost('name');
        $data['description'] = $this->request->getPost('description');
        $data['price'] = 0;
        $data['original_price'] = null;
        $data['event_date'] = $this->request->getPost('event_date');
        $data['event_end_date'] = $this->request->getPost('event_end_date') ?: null;
        $data['max_participants'] = $this->request->getPost('max_participants') ?? 0;
        $data['is_recurring'] = $this->request->getPost('is_recurring') ? 1 : 0;
        $data['recurring_frequency'] = $this->request->getPost('recurring_frequency') ?: null;
        $data['recurring_day'] = $this->request->getPost('recurring_day') ?: null;
        $data['recurring_time'] = $this->request->getPost('recurring_time') ?: null;
        $data['location'] = $this->request->getPost('location');

        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link');
            $data['meeting_id'] = $this->request->getPost('meeting_id');
            $data['meeting_password'] = $this->request->getPost('meeting_password');
        } else {
            $data['requires_agreement'] = $this->request->getPost('requires_agreement') ? 1 : 0;
            $data['agreement_text'] = $this->request->getPost('agreement_text');
        }

        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
                $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Update event thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $data = $this->filterDataByTableFields('layanan_event', $data);
        $this->eventModel->update($id, $data); 
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);
        (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', ucfirst($type) . ' menunggu verifikasi (Update): ' . $data['title'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', ucfirst($type) . ' berhasil diupdate dan menunggu verifikasi');
    }

    private function updateTool($type, $id, $cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['name'] = $this->request->getPost('name');
        $data['description'] = $this->request->getPost('description');
        $data['price'] = 0;
        $data['original_price'] = null;
        $data['version'] = $this->request->getPost('version');
        $data['changelog'] = $this->request->getPost('changelog');
        $data['documentation_url'] = $this->request->getPost('documentation_url');
        
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
                log_message('error', 'Update tool thumbnail upload failed: ' . $e->getMessage());
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
                log_message('error', 'Update tool file upload failed: ' . $e->getMessage());
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
                    log_message('error', 'Update EA file upload failed: ' . $e->getMessage());
                }
            }
        }
        $data = $this->filterDataByTableFields('layanan_tools', $data);
        $this->toolsModel->update($id, $data);
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);
        (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' menunggu verifikasi (Update): ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil diupdate dan menunggu verifikasi');
    }

    private function updateSubscription($type, $id, $cwpaId)
    {
        $data = $this->getPostData($cwpaId);
        $data['name'] = $this->request->getPost('name');
        $data['description'] = $this->request->getPost('description');
        $data['price'] = 0;
        $data['original_price'] = null;
        $data['duration_days'] = $this->request->getPost('duration_days') ?: 30;
        $data['max_slots'] = $this->request->getPost('max_slots') ?? 0;
        $data['schedule_info'] = $this->request->getPost('schedule_info');
        
        $benefits = $this->request->getPost('benefits'); if ($benefits) $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
        $includes = $this->request->getPost('includes'); if ($includes) $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));

        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            try {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/layanan/subscription', $newName);
                $data['thumbnail'] = 'uploads/layanan/subscription/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Update subscription thumbnail upload failed: ' . $e->getMessage());
            }
        }
        $data = $this->filterDataByTableFields('layanan_subscription', $data);
        $this->subscriptionModel->update($id, $data); 
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);
        (new NotificationModel())->createNotification(1, 'Verifikasi Layanan Baru (CWPA)', 'Subscription menunggu verifikasi (Update): ' . $data['name'], 'info', '/admin/layanan/edit/' . $type . '/' . $id);
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', 'Subscription berhasil diupdate dan menunggu verifikasi');
    }

    public function delete($type, $id)
    {
        $cwpaId = $this->getCwpaId(); if (!$cwpaId) return redirect()->to('/login');
        $layanan = $this->getLayananByType($type, $id);
        if (!$layanan || $layanan['cwpa_id'] != $cwpaId) return redirect()->to('/cwpa/dashboard/layanan')->with('error', 'Akses ditolak.');
        switch ($type) {
            case 'artikel': $this->artikelModel->delete($id); break;
            case 'webinar': case 'workshop': case 'live_trade': $this->eventModel->delete($id); break;
            case 'toolkit': case 'ea': $this->toolsModel->delete($id); break;
            case 'pendampingan': case 'profirm': case 'private_konsultan': case 'vip_member': $this->subscriptionModel->delete($id); break;
        }
        return redirect()->to('/cwpa/dashboard/layanan')->with('success', 'Layanan berhasil dihapus');
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
                log_message('error', 'Content image upload failed: ' . $e->getMessage());
                return $this->response->setJSON(['error' => 'Upload failed'], 500);
            }
        }
        return $this->response->setJSON(['error' => ['message' => 'Upload failed']]);
    }
}
