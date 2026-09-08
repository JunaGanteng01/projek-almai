<?php

namespace App\Controllers\AdminWpa;

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
use App\Models\AdminWpaAssignmentModel;

class Layanan extends BaseController
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
    protected $assignmentModel;

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
        $this->assignmentModel = new AdminWpaAssignmentModel();
    }

    private function getAssignedIds()
    {
        $adminId = session()->get('userId');
        $assignments = $this->assignmentModel->where('admin_id', $adminId)->findAll();
        return array_column($assignments, 'wpa_id') ?: [0];
    }

    public function uploadImage()
    {
        $file = $this->request->getFile('upload');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
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
        $wpaIds = $this->getAssignedIds();

        $layananList = $this->getLayananForWpa($wpaIds, $kategori, $subcategory);

        $stats = [
            'total' => count($layananList),
            'artikel' => $this->artikelModel->whereIn('wpa_id', $wpaIds)->countAllResults(),
            'event' => $this->eventModel->whereIn('wpa_id', $wpaIds)->countAllResults(),
            'tools' => $this->toolsModel->whereIn('wpa_id', $wpaIds)->countAllResults(),
            'subscription' => $this->subscriptionModel->whereIn('wpa_id', $wpaIds)->countAllResults(),
        ];

        return view('admin_wpa/layanan/index', [
            'title' => 'Kelola Layanan WPA',
            'activeMenu' => 'layanan',
            'layananList' => $layananList,
            'kategoriList' => $this->kategoriModel->getActive(),
            'currentKategori' => $kategori,
            'currentSubcategory' => $subcategory,
            'stats' => $stats
        ]);
    }

    private function getLayananForWpa($wpaIds, $kategori = null, $subcategory = null)
    {
        $result = [];

        // Artikel
        if (!$kategori || $kategori === 'advokasi') {
            if (!$subcategory || $subcategory === 'artikel') {
                $artikels = $this->artikelModel->whereIn('wpa_id', $wpaIds)->findAll();
                foreach ($artikels as $item) {
                    $wpa = $this->wpaModel->find($item['wpa_id']);
                    $item['wpa_name'] = $wpa ? $wpa['name'] : '-';
                    $item = $this->addPackageInfo($item, 'artikel');
                    $result[] = array_merge($item, [
                        'layanan_type' => 'artikel',
                        'kategori' => 'Advokasi',
                        'subcategory' => 'Artikel'
                    ]);
                }
            }
        }

        // Events
        if (!$kategori || $kategori === 'advokasi') {
            $events = $this->eventModel->whereIn('wpa_id', $wpaIds)->findAll();
            foreach ($events as $item) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $wpa = $this->wpaModel->find($item['wpa_id']);
                    $item['wpa_name'] = $wpa ? $wpa['name'] : '-';
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => 'Advokasi',
                        'subcategory' => ucfirst($item['type']),
                        'name' => $item['title']
                    ]);
                }
            }
        }

        // Tools
        $tools = $this->toolsModel->whereIn('wpa_id', $wpaIds)->findAll();
        foreach ($tools as $item) {
            $kat = $item['type'] === 'ea' ? 'expert-advisor' : 'ultimate';
            $katLabel = $item['type'] === 'ea' ? 'Expert Advisor' : 'Ultimate';
            if (!$kategori || $kategori === $kat) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $wpa = $this->wpaModel->find($item['wpa_id']);
                    $item['wpa_name'] = $wpa ? $wpa['name'] : '-';
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => $katLabel,
                        'subcategory' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Toolkits'
                    ]);
                }
            }
        }

        // Subscriptions
        $subs = $this->subscriptionModel->whereIn('wpa_id', $wpaIds)->findAll();
        foreach ($subs as $item) {
            $kat = in_array($item['type'], ['pendampingan', 'profirm']) ? 'advokasi' : 'ultimate';
            $katLabel = in_array($item['type'], ['pendampingan', 'profirm']) ? 'Advokasi' : 'Ultimate';
            if (!$kategori || $kategori === $kat) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $wpa = $this->wpaModel->find($item['wpa_id']);
                    $item['wpa_name'] = $wpa ? $wpa['name'] : '-';
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => $katLabel,
                        'subcategory' => $this->getSubcategoryLabel($item['type'])
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
        $wpaIds = $this->getAssignedIds();
        return view('admin_wpa/layanan/create', [
            'title' => 'Tambah Layanan',
            'activeMenu' => 'layanan',
            'kategoriList' => $this->kategoriModel->getActive(),
            'wpaList' => $this->wpaModel->whereIn('id', $wpaIds)->where('status', 'active')->findAll(),
            'cwpaList' => $this->cwpaModel->where('status', 'active')->findAll(),
        ]);
    }

    public function store()
    {
        $subcategory = $this->request->getPost('subcategory');
        $wpaId = $this->request->getPost('wpa_id');
        
        if (!in_array($wpaId, $this->getAssignedIds())) {
            return redirect()->back()->with('error', 'WPA tidak valid atau tidak ditugaskan kepada Anda.');
        }

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

    private function storeArtikel()
    {
        $data = $this->getCommonData('artikel');
        $data['excerpt'] = $this->request->getPost('excerpt');
        $data['content'] = $this->request->getPost('content');

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
        return redirect()->to('/admin-wpa/layanan')->with('success', 'Artikel berhasil ditambahkan');
    }

    private function storeEvent($type)
    {
        $data = $this->getCommonData($type);
        $data['event_date'] = $this->request->getPost('event_date');
        $data['event_end_date'] = $this->request->getPost('event_end_date');
        $data['max_participants'] = $this->request->getPost('max_participants') ?: 0;
        $data['total_sessions'] = $this->request->getPost('total_sessions') ?: 1;
        $data['location'] = $this->request->getPost('location');
        $data['is_recurring'] = $this->request->getPost('is_recurring') ? 1 : 0;
        $data['recurring_frequency'] = $this->request->getPost('recurring_frequency');
        $data['recurring_day'] = $this->request->getPost('recurring_day');
        $data['recurring_time'] = $this->request->getPost('recurring_time');
        
        if ($type === 'webinar' || $type === 'live_trade') {
            $data['zoom_link'] = $this->request->getPost('zoom_link');
            $data['meeting_id'] = $this->request->getPost('meeting_id');
            $data['meeting_password'] = $this->request->getPost('meeting_password');
        }

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/event', $newName);
            $data['thumbnail'] = 'uploads/layanan/event/' . $newName;
        }

        // Handle EA file if license
        if ($data['is_license_product']) {
            $eaFile = $this->request->getFile('ea_file');
            if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
                $newName = 'ea_' . time() . '_' . $eaFile->getClientName();
                $eaFile->move(WRITEPATH . 'uploads/layanan/ea_files', $newName);
                $data['ea_file_path'] = 'uploads/layanan/ea_files/' . $newName;
            }
        }

        $id = $this->eventModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
        }
        return redirect()->to('/admin-wpa/layanan')->with('success', ucfirst($type) . ' berhasil ditambahkan');
    }

    private function storeTool($type)
    {
        $data = $this->getCommonData($type);
        $data['version'] = $this->request->getPost('version');
        $data['changelog'] = $this->request->getPost('changelog');
        $data['documentation_url'] = $this->request->getPost('documentation_url');

        // Handle features & requirements from textareas (JSON)
        $features = $this->request->getPost('features');
        if ($features) {
            $data['features'] = json_encode(array_filter(array_map('trim', explode("\n", $features))));
        }
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

        // Handle file
        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $type . '_' . time() . '.' . $file->getExtension();
            $file->move(WRITEPATH . 'uploads/layanan/tools/files', $newName);
            $data['file_path'] = 'uploads/layanan/tools/files/' . $newName;
            $data['file_type'] = $file->getExtension();
        }

        // Handle EA file if license
        if ($data['is_license_product']) {
            $eaFile = $this->request->getFile('ea_file');
            if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
                $newName = 'ea_' . time() . '_' . $eaFile->getClientName();
                $eaFile->move(WRITEPATH . 'uploads/layanan/ea_files', $newName);
                $data['ea_file_path'] = 'uploads/layanan/ea_files/' . $newName;
            }
        }

        $id = $this->toolsModel->insert($data);
        if ($id) {
            $this->savePackages($type, $id);
            $this->saveResources($type, $id);
        }
        return redirect()->to('/admin-wpa/layanan')->with('success', ($type === 'ea' ? 'Expert Advisor' : 'Toolkit') . ' berhasil ditambahkan');
    }

    private function storeSubscription($type)
    {
        $data = $this->getCommonData($type);
        $data['duration_days'] = $this->request->getPost('duration_days') ?: 30;
        $data['max_slots'] = $this->request->getPost('max_slots') ?: 0;
        $data['schedule_info'] = $this->request->getPost('schedule_info');

        // Handle benefits & includes from textareas (JSON)
        $benefits = $this->request->getPost('benefits');
        if ($benefits) {
            $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
        }
        $includes = $this->request->getPost('includes');
        if ($includes) {
            $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));
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
        return redirect()->to('/admin-wpa/layanan')->with('success', $this->getSubcategoryLabel($type) . ' berhasil ditambahkan');
    }

    private function getCommonData($type)
    {
        return [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'cwpa_id' => $this->request->getPost('cwpa_id') ?: null,
            ($type === 'artikel' ? 'title' : 'name') => $this->request->getPost('name'),
            'type' => $type,
            'slug' => $this->request->getPost('slug') ?: null,
            'specialist' => $this->request->getPost('specialist') ?: null,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price') ?: 0,
            'poin_price' => $this->request->getPost('poin_price') ?: 0,
            'original_price' => $this->request->getPost('original_price') ?: null,
            'is_pro_only' => $this->request->getPost('is_pro_only') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'aktif',
            'referral_user_poin' => $this->request->getPost('referral_user_poin') ?: 0,
            'referral_user_cash' => $this->request->getPost('referral_user_cash') ?: 0,
            'referral_distribution_percentage' => $this->request->getPost('referral_distribution_percentage') ?: 50.00,
            'referral_max_depth' => $this->request->getPost('referral_max_depth') ?: 8,
            'layanan_utama' => $this->request->getPost('layanan_utama') ?: null,
            'fitur_unggulan' => $this->request->getPost('fitur_unggulan') ?: null,
            'youtube_tutorials' => $this->request->getPost('youtube_tutorials') ?: null,
            'is_license_product' => $this->request->getPost('is_license_product') ? 1 : 0,
            'license_duration' => $this->request->getPost('license_duration') ?: 0,
            'license_prefix' => $this->request->getPost('license_prefix') ?: 'ALMAI-{id_akun}-{random4}',
        ];
    }

    private function savePackages($type, $id, $clearExisting = false)
    {
        if ($this->request->getPost('has_packages')) {
            if ($clearExisting) {
                $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
            }

            $packages = $this->request->getPost('packages');
            if ($packages && is_array($packages)) {
                $sort = 0;
                foreach ($packages as $pkg) {
                    if (empty($pkg['name']) || empty($pkg['price'])) continue;
                    $this->priceModel->insert([
                        'layanan_type' => $type,
                        'layanan_id' => $id,
                        'name' => $pkg['name'],
                        'price' => $pkg['price'],
                        'original_price' => !empty($pkg['original_price']) ? $pkg['original_price'] : null,
                        'description' => $pkg['description'],
                        'sort_order' => $sort++
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
                    if (file_exists(WRITEPATH . $resource['file_path'])) unlink(WRITEPATH . $resource['file_path']);
                    $this->resourceModel->delete($resId);
                }
            }
        }

        // Uploads
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
        $wpaIds = $this->getAssignedIds();
        $model = $this->getModelByType($type);
        $item = $model->find($id);

        if (!$item || !in_array($item['wpa_id'], $wpaIds)) {
            return redirect()->to('/admin-wpa/layanan')->with('error', 'Layanan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        return view('admin_wpa/layanan/edit', [
            'title' => 'Edit Layanan',
            'activeMenu' => 'layanan',
            'layanan' => $item,
            'type' => $type,
            'kategoriList' => $this->kategoriModel->getActive(),
            'wpaList' => $this->wpaModel->whereIn('id', $wpaIds)->where('status', 'active')->findAll(),
            'cwpaList' => $this->cwpaModel->where('status', 'active')->findAll(),
            'packages' => $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->findAll(),
            'resources' => $this->resourceModel->where('layanan_type', $type)->where('layanan_id', $id)->findAll(),
        ]);
    }

    public function update($type, $id)
    {
        $wpaIds = $this->getAssignedIds();
        $model = $this->getModelByType($type);
        $item = $model->find($id);

        if (!$item || !in_array($item['wpa_id'], $wpaIds)) {
            return redirect()->to('/admin-wpa/layanan')->with('error', 'Akses ditolak.');
        }

        $wpaId = $this->request->getPost('wpa_id');
        if (!in_array($wpaId, $wpaIds)) {
            return redirect()->back()->with('error', 'WPA tidak valid.');
        }

        $data = $this->getCommonData($type);
        unset($data['type']); // Don't update type

        // Special handling for nested fields based on type
        if ($type === 'artikel') {
            $data['excerpt'] = $this->request->getPost('excerpt');
            $data['content'] = $this->request->getPost('content');
        } elseif (in_array($type, ['webinar', 'workshop', 'live_trade'])) {
            $data['event_date'] = $this->request->getPost('event_date');
            $data['event_end_date'] = $this->request->getPost('event_end_date');
            $data['max_participants'] = $this->request->getPost('max_participants');
            $data['total_sessions'] = $this->request->getPost('total_sessions');
            $data['location'] = $this->request->getPost('location');
            $data['is_recurring'] = $this->request->getPost('is_recurring') ? 1 : 0;
            $data['recurring_frequency'] = $this->request->getPost('recurring_frequency');
            $data['recurring_day'] = $this->request->getPost('recurring_day');
            $data['recurring_time'] = $this->request->getPost('recurring_time');
            if (in_array($type, ['webinar', 'live_trade'])) {
                $data['zoom_link'] = $this->request->getPost('zoom_link');
                $data['meeting_id'] = $this->request->getPost('meeting_id');
                $data['meeting_password'] = $this->request->getPost('meeting_password');
            }
        } elseif (in_array($type, ['toolkit', 'ea'])) {
            $data['version'] = $this->request->getPost('version');
            $data['changelog'] = $this->request->getPost('changelog');
            $data['documentation_url'] = $this->request->getPost('documentation_url');
            $features = $this->request->getPost('features');
            if ($features) $data['features'] = json_encode(array_filter(array_map('trim', explode("\n", $features))));
            $requirements = $this->request->getPost('requirements');
            if ($requirements) $data['requirements'] = json_encode(array_filter(array_map('trim', explode("\n", $requirements))));
        } elseif (in_array($type, ['pendampingan', 'profirm', 'private_konsultan', 'vip_member'])) {
            $data['duration_days'] = $this->request->getPost('duration_days');
            $data['max_slots'] = $this->request->getPost('max_slots');
            $data['schedule_info'] = $this->request->getPost('schedule_info');
            $benefits = $this->request->getPost('benefits');
            if ($benefits) $data['benefits'] = json_encode(array_filter(array_map('trim', explode("\n", $benefits))));
            $includes = $this->request->getPost('includes');
            if ($includes) $data['includes'] = json_encode(array_filter(array_map('trim', explode("\n", $includes))));
        }

        // File/Thumbnail handling...
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            if (!empty($item['thumbnail']) && file_exists(WRITEPATH . $item['thumbnail'])) unlink(WRITEPATH . $item['thumbnail']);
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/layanan/' . ($type === 'artikel' ? 'artikel' : 'other'), $newName);
            $data['thumbnail'] = 'uploads/layanan/' . ($type === 'artikel' ? 'artikel' : 'other') . '/' . $newName;
        }

        $file = $this->request->getFile('layanan_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!empty($item['file_path']) && file_exists(WRITEPATH . $item['file_path'])) unlink(WRITEPATH . $item['file_path']);
            $newName = $type . '_' . $id . '_' . time() . '.' . $file->getExtension();
            $file->move(WRITEPATH . 'uploads/layanan/' . $type . '/files', $newName);
            $data['file_path'] = 'uploads/layanan/' . $type . '/files/' . $newName;
            $data['file_type'] = $file->getExtension();
        }

        if ($data['is_license_product']) {
            $eaFile = $this->request->getFile('ea_file');
            if ($eaFile && $eaFile->isValid() && !$eaFile->hasMoved()) {
                if (!empty($item['ea_file_path']) && file_exists(WRITEPATH . $item['ea_file_path'])) unlink(WRITEPATH . $item['ea_file_path']);
                $newName = 'ea_' . $id . '_' . time() . '_' . $eaFile->getClientName();
                $eaFile->move(WRITEPATH . 'uploads/layanan/ea_files', $newName);
                $data['ea_file_path'] = 'uploads/layanan/ea_files/' . $newName;
            }
        }

        $model->update($id, $data);
        $this->savePackages($type, $id, true);
        $this->saveResources($type, $id);

        return redirect()->to('/admin-wpa/layanan')->with('success', 'Layanan berhasil diupdate');
    }

    public function delete($type, $id)
    {
        $wpaIds = $this->getAssignedIds();
        $model = $this->getModelByType($type);
        $item = $model->find($id);

        if (!$item || !in_array($item['wpa_id'], $wpaIds)) {
            return redirect()->to('/admin-wpa/layanan')->with('error', 'Akses ditolak.');
        }

        $model->delete($id);
        $this->priceModel->where('layanan_type', $type)->where('layanan_id', $id)->delete();
        return redirect()->to('/admin-wpa/layanan')->with('success', 'Layanan berhasil dihapus');
    }

    public function toggleStatus($type, $id)
    {
        $wpaIds = $this->getAssignedIds();
        $model = $this->getModelByType($type);
        $item = $model->find($id);

        if (!$item || !in_array($item['wpa_id'], $wpaIds)) {
            return redirect()->to('/admin-wpa/layanan')->with('error', 'Akses ditolak.');
        }

        $newStatus = ($item['status'] === 'aktif') ? 'tidak aktif' : 'aktif';
        $model->update($id, ['status' => $newStatus]);

        return redirect()->to('/admin-wpa/layanan')->with('success', 'Status layanan berhasil diupdate');
    }

    /**
     * Get model instance by service type
     * @param string $type
     * @return \CodeIgniter\Model
     */
    private function getModelByType($type)
    {
        switch ($type) {
            case 'artikel': return $this->artikelModel;
            case 'webinar':
            case 'workshop':
            case 'live_trade': return $this->eventModel;
            case 'toolkit':
            case 'ea': return $this->toolsModel;
            case 'pendampingan':
            case 'profirm':
            case 'private_konsultan':
            case 'vip_member':
            default:
                return $this->subscriptionModel;
        }
    }
}
