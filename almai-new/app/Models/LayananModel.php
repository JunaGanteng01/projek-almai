<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'slug',
        'category',
        'subcategory',
        'description',
        'thumbnail',
        'file_path',
        'file_type',
        'event_date',
        'event_link',
        'download_count',
        'wpa_id',
        'cwpa_id',
        'level',
        'mode',
        'duration',
        'modules',
        'location',
        'price',
        'original_price',
        'badge',
        'is_premium',
        'features',
        'includes',
        'requirements',
        'warning',
        'rating',
        'students',
        'status',
        'referral_wpa_poin',
        'referral_wpa_cash',
        'referral_user_poin',
        'referral_user_cash',
        'referral_distribution_percentage',
        'referral_max_depth',
        'youtube_tutorials',
        'jenis_modul'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    public static function getCategories()
    {
        return ['Layanan WPA', 'Expert Advisor', 'Ultimate'];
    }

    public static function getSubcategories()
    {
        return [
            'Layanan WPA' => ['Artikel', 'Webinar', 'Workshop', 'Pendampingan CWPA', 'Profirm'],
            'Expert Advisor' => ['AIWE', 'BIDBOX'],
            'Ultimate' => ['Almai Toolkits', 'Private Konsultan', 'VIP Member'],
            'Almai Ultimate' => ['Almai Toolkits', 'Private Konsultan', 'VIP Member'],
        ];
    }

    public static function getLevels()
    {
        return ['Beginner', 'Intermediate', 'Advanced', 'All Level'];
    }

    public static function getModes()
    {
        return ['Online', 'Offline', 'Hybrid'];
    }

    public function getFiltered($category = null, $subcategory = null, $search = null)
    {
        $builder = $this->whereIn('status', ['active', 'aktif']);

        if ($category && $category !== 'all') {
            $builder->where('category', $category);
        }

        if ($subcategory && $subcategory !== 'all') {
            $builder->where('subcategory', $subcategory);
        }

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getWithWpa($id)
    {
        $select = 'layanan.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.specialty as wpa_specialty';
        $builder = $this->select($select)
            ->join('wpa', 'wpa.id = layanan.wpa_id', 'left');

        if ($this->db->fieldExists('cwpa_id', 'layanan')) {
            $builder->select('cwpa.name as cwpa_name, cwpa.photo as cwpa_photo')
                ->join('cwpa', 'cwpa.id = layanan.cwpa_id', 'left');
        }

        return $builder->find($id);
    }

    public function getAllWithWpa($category = null, $status = null)
    {
        $select = 'layanan.*, wpa.name as wpa_name, wpa.photo as wpa_photo';
        $builder = $this->select($select)
            ->join('wpa', 'wpa.id = layanan.wpa_id', 'left');

        if ($this->db->fieldExists('cwpa_id', 'layanan')) {
            $builder->select('cwpa.name as cwpa_name, cwpa.photo as cwpa_photo')
                ->join('cwpa', 'cwpa.id = layanan.cwpa_id', 'left');
        }

        if ($category && $category !== 'all') {
            $builder->where('layanan.category', $category);
        }

        if ($status && $status !== 'all') {
            $builder->where('layanan.status', $status);
        }

        $results = $builder->orderBy('layanan.created_at', 'DESC')->findAll();
        
        foreach ($results as &$resItem) {
            $buyers = $this->getBuyerCount($resItem['id'], 'layanan');
            if ($buyers > 0) {
                $resItem['students'] = $buyers;
            }
        }
        
        return $results;
    }

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name'])) {
            $slug = $this->createSlug($data['data']['name']);

            $existingId = $data['id'] ?? null;
            $existing = $this->where('slug', $slug);
            if ($existingId) {
                $existing->where('id !=', $existingId);
            }
            $existing = $existing->first();

            if ($existing) {
                $counter = 1;
                $originalSlug = $slug;
                while ($this->where('slug', $slug)->where('id !=', $existingId ?? 0)->first()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $data['data']['slug'] = $slug;
        }
        return $data;
    }

    private function createSlug($name)
    {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    public function countByWpaId($wpaId)
    {
        $db = \Config\Database::connect();
        $count = 0;

        // Count from main layanan table
        $count += $this->where('wpa_id', $wpaId)->where('status !=', 'archived')->countAllResults(false);

        // Count from layanan_event
        $count += $db->table('layanan_event')
            ->where('wpa_id', $wpaId)
            ->where('status !=', 'archived')
            ->countAllResults(false);

        // Count from layanan_artikel
        $count += $db->table('layanan_artikel')
            ->where('wpa_id', $wpaId)
            ->where('status', 'published')
            ->countAllResults(false);

        // Count from layanan_subscription
        $count += $db->table('layanan_subscription')
            ->where('wpa_id', $wpaId)
            ->where('status', 'published')
            ->countAllResults(false);

        // Count from layanan_tools
        $count += $db->table('layanan_tools')
            ->where('wpa_id', $wpaId)
            ->where('status !=', 'archived')
            ->countAllResults(false);

        return $count;
    }

    public function countActiveByWpaId($wpaId)
    {
        $db = \Config\Database::connect();
        $count = 0;
        $activeStatuses = ['active', 'aktif', 'published'];

        // Count from main layanan table
        $count += $this->where('wpa_id', $wpaId)->whereIn('status', $activeStatuses)->countAllResults();

        // Count from layanan_event
        $count += $db->table('layanan_event')
            ->where('wpa_id', $wpaId)
            ->whereIn('status', $activeStatuses)
            ->countAllResults();

        // Count from layanan_artikel
        $count += $db->table('layanan_artikel')
            ->where('wpa_id', $wpaId)
            ->whereIn('status', $activeStatuses)
            ->countAllResults();

        // Count from layanan_subscription
        $count += $db->table('layanan_subscription')
            ->where('wpa_id', $wpaId)
            ->whereIn('status', $activeStatuses)
            ->countAllResults();

        // Count from layanan_tools
        $count += $db->table('layanan_tools')
            ->where('wpa_id', $wpaId)
            ->whereIn('status', $activeStatuses)
            ->countAllResults();

        return $count;
    }

    

    public function countByCwpaId($cwpaId)
    {
        $db = \Config\Database::connect();
        $count = 0;

        // Count from main layanan table
        if ($db->fieldExists('cwpa_id', 'layanan')) {
            $count += $this->where('cwpa_id', $cwpaId)->where('status !=', 'archived')->countAllResults(false);
        }

        // Count from layanan_event
        if ($db->fieldExists('cwpa_id', 'layanan_event')) {
            $count += $db->table('layanan_event')
                ->where('cwpa_id', $cwpaId)
                ->where('status !=', 'archived')
                ->countAllResults(false);
        }

        // Count from layanan_artikel
        if ($db->fieldExists('cwpa_id', 'layanan_artikel')) {
            $count += $db->table('layanan_artikel')
                ->where('cwpa_id', $cwpaId)
                ->where('status', 'published')
                ->countAllResults(false);
        }

        // Count from layanan_subscription
        if ($db->fieldExists('cwpa_id', 'layanan_subscription')) {
            $count += $db->table('layanan_subscription')
                ->where('cwpa_id', $cwpaId)
                ->where('status', 'published')
                ->countAllResults(false);
        }

        // Count from layanan_tools
        if ($db->fieldExists('cwpa_id', 'layanan_tools')) {
            $count += $db->table('layanan_tools')
                ->where('cwpa_id', $cwpaId)
                ->where('status !=', 'archived')
                ->countAllResults(false);
        }

        return $count;
    }

    // Alias to fix typo on hosting
    public function countByCcwpaId($cwpaId)
    {
        return $this->countByCwpaId($cwpaId);
    }

    public function getTotalStudentsByWpa($wpaId)
    {
        $db = \Config\Database::connect();
        $total = 0;

        // Sum from Layanan Main Table (e.g. self-paced courses)
        $layananResult = $this->selectSum('students')->where('wpa_id', $wpaId)->where('status !=', 'archived')->first();
        $total += ($layananResult['students'] ?? 0);

        // Sum from Events (Webinars, Workshops)
        $eventResult = $db->table('layanan_event')->selectSum('current_participants')->where('wpa_id', $wpaId)->where('status !=', 'archived')->get()->getRowArray();
        $total += ($eventResult['current_participants'] ?? 0);

        return $total;
    }


    public function getByWpaId($wpaId)
    {
        $db = \Config\Database::connect();

        // 1. Get WPA Info
        $wpa = $db->table('wpa')->select('name, photo')->where('id', $wpaId)->get()->getRowArray();
        if (!$wpa) return [];

        // Fix WPA photo URL
        $wpaPhoto = $wpa['photo'];
        if ($wpaPhoto && strpos($wpaPhoto, 'uploads/') === 0) {
            $wpaPhoto = base_url('file/' . $wpaPhoto);
        } elseif ($wpaPhoto && strpos($wpaPhoto, 'writable/') === 0) {
            $wpaPhoto = base_url('file/' . preg_replace('/^writable\//', '', $wpaPhoto));
        }

        $results = [];

        // 2. Fetch from Layanan (Main/Legacy)
        $layanan = $this->where('wpa_id', $wpaId)->where('status !=', 'archived')->findAll();
        foreach ($layanan as $item) {
            $item['wpa_name'] = $wpa['name'];
            $item['wpa_photo'] = $wpaPhoto;
            $item['type'] = 'layanan';

            // Fix Thumbnail
            if (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) {
                $item['thumbnail'] = base_url('file/' . $item['thumbnail']);
            }

            $item['poin_price'] = $item['poin_price'] ?? 0;
            $results[] = $item;
        }

        // 3. Fetch from Layanan Event
        $events = $db->table('layanan_event')
            ->where('wpa_id', $wpaId)
            ->where('status !=', 'archived')
            ->get()->getResultArray();
        foreach ($events as $item) {
            // Get packages for this event
            $packages = $db->table('layanan_prices')
                ->where('layanan_type', $item['type'])
                ->where('layanan_id', $item['id'])
                ->orderBy('price', 'ASC')
                ->get()->getResultArray();

            $hasPackages = !empty($packages);
            $minPrice = $item['price'];
            $maxPrice = $item['price'];

            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
            }

            // Calculate duration for recurring events
            $duration = $this->calculateDuration($item['event_date'], $item['event_end_date']);
            if (!empty($item['is_recurring']) && !empty($item['recurring_day']) && !empty($item['recurring_time'])) {
                $duration = ucfirst($item['recurring_day']) . ' ' . $item['recurring_time'];
            }

            $data = [
                'id' => $item['id'],
                'name' => $item['title'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'category' => 'Layanan WPA',
                'subcategory' => ucfirst($item['type']),
                'description' => $item['description'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'wpa_id' => $item['wpa_id'],
                'wpa_name' => $wpa['name'],
                'wpa_photo' => $wpaPhoto,
                'price' => $hasPackages ? $minPrice : $item['price'],
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_packages' => $hasPackages,
                'original_price' => $item['original_price'] ?? 0,
                'rating' => 0,
                'students' => $item['current_participants'] ?? 0,
                'status' => $item['status'],
                'duration' => $duration,
                'modules' => 0,
                'location' => $item['type'] === 'webinar' ? ($item['location'] ?? 'Zoom') : ($item['location'] ?? 'TBA'),
                'created_at' => $item['created_at'],
                'poin_price' => $item['poin_price'] ?? 0,
                'is_recurring' => $item['is_recurring'] ?? 0,
                'recurring_frequency' => $item['recurring_frequency'] ?? null,
                'recurring_day' => $item['recurring_day'] ?? null,
                'recurring_time' => $item['recurring_time'] ?? null
            ];
            $results[] = $data;
        }

        // 4. Fetch from Layanan Artikel
        $articles = $db->table('layanan_artikel')
            ->where('wpa_id', $wpaId)
            ->whereIn('status', ['published', 'aktif'])
            ->get()->getResultArray();
        foreach ($articles as $item) {
            $data = [
                'id' => $item['id'],
                'name' => $item['title'],
                'slug' => $item['slug'],
                'type' => 'artikel',
                'category' => 'Layanan WPA',
                'subcategory' => 'Artikel',
                'description' => $item['excerpt'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'wpa_id' => $item['wpa_id'],
                'wpa_name' => $wpa['name'],
                'wpa_photo' => $wpaPhoto,
                'price' => 0,
                'poin_price' => $item['poin_price'] ?? 0,
                'min_price' => 0,
                'max_price' => 0,
                'has_packages' => false,
                'original_price' => 0,
                'rating' => 0,
                'students' => $item['views'] ?? 0,
                'status' => $item['status'],
                'duration' => ($item['read_time'] ?? 5) . ' min read',
                'modules' => 0,
                'location' => 'Online',
                'created_at' => $item['published_at'] ?? $item['created_at']
            ];
            $results[] = $data;
        }

        // 5. Fetch from Layanan Tools
        $tools = $db->table('layanan_tools')
            ->where('wpa_id', $wpaId) // Add wpa_id filter
            ->where('status !=', 'archived')
            ->get()->getResultArray();

        foreach ($tools as $item) {
            // Get packages for this tool
            $packages = $db->table('layanan_prices')
                ->where('layanan_type', $item['type'])
                ->where('layanan_id', $item['id'])
                ->orderBy('price', 'ASC')
                ->get()->getResultArray();

            $hasPackages = !empty($packages);
            $minPrice = $item['price'];
            $maxPrice = $item['price'];

            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
            }

            $data = [
                'id' => $item['id'],
                'name' => $item['name'], // Tools use 'name' instead of 'title'
                'slug' => $item['slug'],
                'type' => $item['type'], // 'ea' or 'toolkit'
                'category' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Ultimate',
                'subcategory' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Toolkits',
                'description' => $item['description'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'wpa_id' => $item['wpa_id'],
                'wpa_name' => $wpa['name'],
                'wpa_photo' => $wpaPhoto,
                'price' => $hasPackages ? $minPrice : $item['price'],
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_packages' => $hasPackages,
                'original_price' => $item['original_price'] ?? 0,
                'rating' => 0,
                'students' => 0, // Tools might not have participants/views field readily available or used yet
                'status' => $item['status'],
                'duration' => 'Lifetime',
                'modules' => 1,
                'location' => 'Download',
                'created_at' => $item['created_at'],
                'poin_price' => $item['poin_price'] ?? 0
            ];
            $results[] = $data;
        }

        // 6. Fetch from Layanan Subscription
        $subscriptions = $db->table('layanan_subscription')
            ->where('wpa_id', $wpaId)
            ->where('status !=', 'archived')
            ->get()->getResultArray();

        foreach ($subscriptions as $item) {
            // Get packages for this subscription
            $packages = $db->table('layanan_prices')
                ->where('layanan_type', $item['type'])
                ->where('layanan_id', $item['id'])
                ->orderBy('price', 'ASC')
                ->get()->getResultArray();

            $hasPackages = !empty($packages);
            $minPrice = $item['price'];
            $maxPrice = $item['price'];

            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
            }

            $data = [
                'id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'category' => in_array($item['type'], ['pendampingan', 'profirm']) ? 'Layanan WPA' : 'Almai Ultimate',
                'subcategory' => $this->getSubcategoryLabel($item['type']),
                'description' => $item['description'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'wpa_id' => $item['wpa_id'],
                'wpa_name' => $wpa['name'],
                'wpa_photo' => $wpaPhoto,
                'price' => $hasPackages ? $minPrice : $item['price'],
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_packages' => $hasPackages,
                'original_price' => $item['original_price'] ?? 0,
                'rating' => 0,
                'students' => 0,
                'status' => $item['status'],
                'duration' => ($item['duration_days'] ?? 30) . ' Hari',
                'modules' => 0,
                'location' => 'Consultation',
                'created_at' => $item['created_at'],
                'poin_price' => $item['poin_price'] ?? 0
            ];
            $results[] = $data;
        }

        // Count actual buyers for each service
        foreach ($results as &$resItem) {
            $buyers = $this->getBuyerCount($resItem['id'], $resItem['type']);
            if ($buyers > 0) {
                $resItem['students'] = $buyers;
            }
        }

        // Sort by created_at desc
        usort($results, function ($a, $b) {
            return strtotime($b['created_at'] ?? 0) - strtotime($a['created_at'] ?? 0);
        });

        return $results;
    }

    
    
    

    
    public function getByCwpaId($cwpaId)
    {
        $db = \Config\Database::connect();

        // 1. Get CWPA Info
        $cwpa = $db->table('cwpa')->select('name, photo')->where('id', $cwpaId)->get()->getRowArray();
        if (!$cwpa) return [];

        // Fix CWPA photo URL
        $cwpaPhoto = $cwpa['photo'];
        if ($cwpaPhoto && strpos($cwpaPhoto, 'http') !== 0) {
            $cwpaPhoto = base_url('file/' . ltrim(preg_replace('/^writable\//', '', $cwpaPhoto), '/'));
        }

        $results = [];

        // 2. Fetch from Layanan (Main/Legacy)
        $layanan = [];
        if ($db->fieldExists('cwpa_id', 'layanan')) {
            $layanan = $this->where('cwpa_id', $cwpaId)->where('status !=', 'archived')->findAll();
        }
        foreach ($layanan as $item) {
            $item['cwpa_name'] = $cwpa['name'];
            $item['cwpa_photo'] = $cwpaPhoto;
            $item['type'] = 'layanan';

            // Fix Thumbnail
            if (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'http') !== 0) {
                $item['thumbnail'] = base_url('file/' . ltrim($item['thumbnail'], '/'));
            }

            $item['poin_price'] = $item['poin_price'] ?? 0;
            $results[] = $item;
        }

        // 3. Fetch from Layanan Event
        $events = [];
        if ($db->fieldExists('cwpa_id', 'layanan_event')) {
            $events = $db->table('layanan_event')
                ->where('cwpa_id', $cwpaId)
                ->where('status !=', 'archived')
                ->get()->getResultArray();
        }
        foreach ($events as $item) {
            // Get packages for this event
            $packages = $db->table('layanan_prices')
                ->where('layanan_type', $item['type'])
                ->where('layanan_id', $item['id'])
                ->orderBy('price', 'ASC')
                ->get()->getResultArray();

            $hasPackages = !empty($packages);
            $minPrice = $item['price'];
            $maxPrice = $item['price'];

            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
            }

            // Calculate duration for recurring events
            $duration = $this->calculateDuration($item['event_date'], $item['event_end_date']);
            if (!empty($item['is_recurring']) && !empty($item['recurring_day']) && !empty($item['recurring_time'])) {
                $duration = ucfirst($item['recurring_day']) . ' ' . $item['recurring_time'];
            }

            $data = [
                'id' => $item['id'],
                'name' => $item['title'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'category' => 'Layanan WPA',
                'subcategory' => ucfirst($item['type']),
                'description' => $item['description'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'cwpa_id' => $item['cwpa_id'],
                'cwpa_name' => $cwpa['name'],
                'cwpa_photo' => $cwpaPhoto,
                'price' => $hasPackages ? $minPrice : $item['price'],
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_packages' => $hasPackages,
                'original_price' => $item['original_price'] ?? 0,
                'rating' => 0,
                'students' => $item['current_participants'] ?? 0,
                'status' => $item['status'],
                'duration' => $duration,
                'modules' => 0,
                'location' => $item['type'] === 'webinar' ? ($item['location'] ?? 'Zoom') : ($item['location'] ?? 'TBA'),
                'created_at' => $item['created_at'],
                'poin_price' => $item['poin_price'] ?? 0,
                'is_recurring' => $item['is_recurring'] ?? 0,
                'recurring_frequency' => $item['recurring_frequency'] ?? null,
                'recurring_day' => $item['recurring_day'] ?? null,
                'recurring_time' => $item['recurring_time'] ?? null
            ];
            $results[] = $data;
        }

        // 4. Fetch from Layanan Artikel
        $articles = [];
        if ($db->fieldExists('cwpa_id', 'layanan_artikel')) {
            $articles = $db->table('layanan_artikel')
                ->where('cwpa_id', $cwpaId)
                ->whereIn('status', ['published', 'active', 'aktif'])
                ->get()->getResultArray();
        }
        foreach ($articles as $item) {
            $data = [
                'id' => $item['id'],
                'name' => $item['title'],
                'slug' => $item['slug'],
                'type' => 'artikel',
                'category' => 'Layanan WPA',
                'subcategory' => 'Artikel',
                'description' => $item['excerpt'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'cwpa_id' => $item['cwpa_id'],
                'cwpa_name' => $cwpa['name'],
                'cwpa_photo' => $cwpaPhoto,
                'price' => 0,
                'poin_price' => $item['poin_price'] ?? 0,
                'min_price' => 0,
                'max_price' => 0,
                'has_packages' => false,
                'original_price' => 0,
                'rating' => 0,
                'students' => $item['views'] ?? 0,
                'status' => $item['status'],
                'duration' => ($item['read_time'] ?? 5) . ' min read',
                'modules' => 0,
                'location' => 'Online',
                'created_at' => $item['published_at'] ?? $item['created_at']
            ];
            $results[] = $data;
        }

        // 5. Fetch from Layanan Tools
        $tools = [];
        if ($db->fieldExists('cwpa_id', 'layanan_tools')) {
            $tools = $db->table('layanan_tools')
                ->where('cwpa_id', $cwpaId)
                ->where('status !=', 'archived')
                ->get()->getResultArray();
        }

        foreach ($tools as $item) {
            $packages = $db->table('layanan_prices')
                ->where('layanan_type', $item['type'])
                ->where('layanan_id', $item['id'])
                ->orderBy('price', 'ASC')
                ->get()->getResultArray();

            $hasPackages = !empty($packages);
            $minPrice = $item['price'];
            $maxPrice = $item['price'];

            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
            }

            $data = [
                'id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'category' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Ultimate',
                'subcategory' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Toolkits',
                'description' => $item['description'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'cwpa_id' => $item['cwpa_id'],
                'cwpa_name' => $cwpa['name'],
                'cwpa_photo' => $cwpaPhoto,
                'price' => $hasPackages ? $minPrice : $item['price'],
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_packages' => $hasPackages,
                'original_price' => $item['original_price'] ?? 0,
                'rating' => 0,
                'students' => 0,
                'status' => $item['status'],
                'duration' => 'Lifetime',
                'modules' => 1,
                'location' => 'Download',
                'created_at' => $item['created_at'],
                'poin_price' => $item['poin_price'] ?? 0
            ];
            $results[] = $data;
        }

        // 6. Fetch from Layanan Subscription
        $subscriptions = [];
        if ($db->fieldExists('cwpa_id', 'layanan_subscription')) {
            $subscriptions = $db->table('layanan_subscription')
                ->where('cwpa_id', $cwpaId)
                ->where('status !=', 'archived')
                ->get()->getResultArray();
        }

        foreach ($subscriptions as $item) {
            $packages = $db->table('layanan_prices')
                ->where('layanan_type', $item['type'])
                ->where('layanan_id', $item['id'])
                ->orderBy('price', 'ASC')
                ->get()->getResultArray();

            $hasPackages = !empty($packages);
            $minPrice = $item['price'];
            $maxPrice = $item['price'];

            if ($hasPackages) {
                $prices = array_column($packages, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
            }

            $data = [
                'id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'category' => in_array($item['type'], ['pendampingan', 'profirm']) ? 'Layanan WPA' : 'Almai Ultimate',
                'subcategory' => $this->getSubcategoryLabel($item['type']),
                'description' => $item['description'],
                'thumbnail' => (!empty($item['thumbnail']) && strpos($item['thumbnail'], 'uploads/') === 0) ? base_url('file/' . $item['thumbnail']) : $item['thumbnail'],
                'cwpa_id' => $item['cwpa_id'],
                'cwpa_name' => $cwpa['name'],
                'cwpa_photo' => $cwpaPhoto,
                'price' => $hasPackages ? $minPrice : $item['price'],
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_packages' => $hasPackages,
                'original_price' => $item['original_price'] ?? 0,
                'rating' => 0,
                'students' => 0,
                'status' => $item['status'],
                'duration' => ($item['duration_days'] ?? 30) . ' Hari',
                'modules' => 0,
                'location' => 'Consultation',
                'created_at' => $item['created_at'],
                'poin_price' => $item['poin_price'] ?? 0
            ];
            $results[] = $data;
        }

        // Count actual buyers for each service
        foreach ($results as &$resItem) {
            $buyers = $this->getBuyerCount($resItem['id'], $resItem['type']);
            if ($buyers > 0) {
                $resItem['students'] = $buyers;
            }
        }

        usort($results, function ($a, $b) {
            return strtotime($b['created_at'] ?? 0) - strtotime($a['created_at'] ?? 0);
        });

        return $results;
    }

    private function getSubcategoryLabel($type)
    {
        $labels = [
            'artikel' => 'Artikel',
            'webinar' => 'Webinar',
            'workshop' => 'Workshop',
            'pendampingan' => 'Pendampingan CWPA',
            'profirm' => 'Profirm',
            'ea' => 'Expert Advisor',
            'toolkit' => 'Almai Toolkits',
            'private_konsultan' => 'Private Konsultan',
            'vip_member' => 'VIP Member',
        ];
        return $labels[$type] ?? ucfirst($type);
    }


    public function getUpcomingEvents($limit = 5)
    {
        $db = \Config\Database::connect();
        $results = [];

        // Fetch only Webinar Events (Active/Upcoming) with price < 10 million
        $builder = $db->table('layanan_event');
        $builder->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo');
        $builder->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left');
        $builder->where('layanan_event.status !=', 'archived');
        $builder->whereIn('layanan_event.type', ['webinar', 'workshop', 'live_trade']); // Include other event types
        $builder->where('layanan_event.price <', 1000000); // Only show events under 1 million
        $builder->orderBy('layanan_event.id', 'DESC'); // Show newest created/added first
        $builder->limit($limit);
        $events = $builder->get()->getResultArray();

        foreach ($events as $item) {
            $formatted = $this->formatItem($item, 'event');
            $results[] = $formatted;
        }

        return array_slice($results, 0, $limit);
    }

    private function formatItem($item, $type)
    {
        // Fix WPA Photo
        $wpaPhoto = $item['wpa_photo'] ?? null;
        if ($wpaPhoto && strpos($wpaPhoto, 'uploads/') === 0) {
            $wpaPhoto = base_url('file/' . $wpaPhoto);
        }

        // Fix Thumbnail
        $thumbnail = $item['thumbnail'] ?? 'https://placehold.co/600x400?text=' . ucfirst($type);
        if ($thumbnail && strpos($thumbnail, 'uploads/') === 0) {
            $thumbnail = base_url('file/' . $thumbnail);
        } elseif ($thumbnail && strpos($thumbnail, 'http') !== 0) {
            // Probably a local path without uploads/ prefix or other
            $thumbnail = base_url('file/uploads/' . $thumbnail);
        }

        $data = [
            'id' => $item['id'],
            'name' => $item['title'] ?? ($item['name'] ?? 'Untitled'),
            'slug' => $item['slug'] ?? 'layanan-' . $item['id'],
            'thumbnail' => $thumbnail,
            'price' => $item['price'] ?? ($item['poin_price'] ?? 0),
            'original_price' => $item['original_price'] ?? 0,
            'rating' => 5.0,
            'students' => $item['current_participants'] ?? ($item['views'] ?? 0),
            'wpa_name' => $item['wpa_name'] ?? 'Almai System',
            'wpa_photo' => $wpaPhoto ?? base_url('favicon.ico'),
            'modules' => 0,
        ];

        if ($type === 'event') {
            $duration = $this->calculateDuration($item['event_date'], $item['event_end_date']);
            $mode = ($item['type'] === 'webinar') ? 'Online' : 'Offline';
            if (isset($item['location']) && strpos(strtolower($item['location']), 'zoom') !== false) {
                $mode = 'Online';
            }

            $data['duration'] = $duration;
            $data['level'] = $item['level'] ?? 'All Level';
            $data['category'] = ucfirst($item['type']);
            $data['subcategory'] = ucfirst($item['type']);
            $data['mode'] = $mode;
            $data['location'] = $item['location'] ?? 'Online';
        } elseif ($type === 'tool') {
            $data['duration'] = 'Lifetime';
            $data['level'] = 'Software';
            $data['category'] = 'Expert Advisor';
            $data['subcategory'] = 'Expert Advisor';
            $data['mode'] = 'Download';
            $data['location'] = 'Software';
            $data['modules'] = 1;
        } elseif ($type === 'article') {
            $data['duration'] = ($item['read_time'] ?? 5) . ' min';
            $data['level'] = 'Insight';
            $data['category'] = 'Artikel';
            $data['subcategory'] = 'Artikel';
            $data['mode'] = 'Read';
            $data['location'] = 'Online';
        }

        return $data;
    }

    private function calculateDuration($start, $end)
    {
        if (!$start || !$end) return '-';
        $diff = strtotime($end) - strtotime($start);
        $hours = floor($diff / 3600);
        if ($hours >= 24) {
            return floor($hours / 24) . ' Hari';
        }
        return $hours . ' Jam';
    }

    public function getBuyerCount($layananId, $itemType)
    {
        $db = \Config\Database::connect();
        
        $types = [$itemType];
        
        if ($itemType === 'layanan') {
            $types = ['layanan', 'course'];
        } elseif (in_array($itemType, ['event', 'webinar', 'workshop', 'live_trade', 'layanan_event'])) {
            $types = ['event', 'webinar', 'workshop', 'live_trade'];
        } elseif (in_array($itemType, ['tool', 'tools', 'ea', 'toolkit', 'layanan_tools'])) {
            $types = ['tool', 'tools', 'ea', 'toolkit'];
        } elseif (in_array($itemType, ['artikel', 'layanan_artikel'])) {
            $types = ['artikel'];
        } elseif (in_array($itemType, ['subscription', 'cwpa', 'pendampingan', 'profirm', 'vip_member', 'private_konsultan', 'layanan_subscription'])) {
            $types = ['subscription', 'cwpa', 'pendampingan', 'profirm', 'vip_member', 'private_konsultan'];
        }
        
        return $db->table('transaksi')
            ->where('layanan_id', $layananId)
            ->whereIn('product_type', $types)
            ->where('status', 'confirmed')
            ->countAllResults();
    }

    public function getLaporanByModul($modul, $search = null, $page = 1, $perPage = 20)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan');
        
        $searchCondition = "";
        if ($search) {
            $search = $db->escapeLikeString($search);
            $searchCondition = " AND (name LIKE '%{$search}%' OR location LIKE '%{$search}%')";
        }

        $searchConditionTitle = "";
        if ($search) {
            $searchConditionTitle = " AND title LIKE '%{$search}%'";
        }

        $searchConditionName = "";
        if ($search) {
            $searchConditionName = " AND name LIKE '%{$search}%'";
        }

        // We construct a UNION ALL query
        $queries = [];

        // 1. layanan table
        $queries[] = "SELECT l.id, l.name, l.category, l.subcategory, l.price, l.location, l.event_date, l.status, l.jenis_modul, 'layanan' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, l.description FROM layanan l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$modul}' AND l.status IN ('active', 'aktif') {$searchCondition}";
        
        // 2. layanan_artikel table
        $queries[] = "SELECT l.id, l.title as name, 'Advokasi' as category, 'Artikel' as subcategory, 0 as price, NULL as location, NULL as event_date, l.status, l.jenis_modul, 'layanan_artikel' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, l.excerpt as description FROM layanan_artikel l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$modul}' AND l.status IN ('active', 'aktif') {$searchConditionTitle}";
        
        // 3. layanan_event table
        $queries[] = "SELECT l.id, l.title as name, 'Advokasi' as category, l.type as subcategory, l.price, l.location, l.event_date, l.status, l.jenis_modul, 'layanan_event' as table_name, w.name as wpa_name, l.is_recurring, l.recurring_frequency, l.recurring_day, l.recurring_time, l.description FROM layanan_event l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$modul}' AND l.status IN ('active', 'aktif') {$searchConditionTitle}";
        
        // 4. layanan_tools table
        $queries[] = "SELECT l.id, l.name, l.type as category, l.type as subcategory, l.price, NULL as location, NULL as event_date, l.status, l.jenis_modul, 'layanan_tools' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, l.description FROM layanan_tools l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$modul}' AND l.status IN ('active', 'aktif') {$searchConditionName}";
        
        // 5. layanan_subscription table
        $queries[] = "SELECT l.id, l.name, l.type as category, l.type as subcategory, l.price, NULL as location, NULL as event_date, l.status, l.jenis_modul, 'layanan_subscription' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, l.description FROM layanan_subscription l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$modul}' AND l.status IN ('active', 'aktif') {$searchConditionName}";

        // 6. Old tables
        $searchConditionJudul = $search ? " AND judul LIKE '%{$search}%'" : "";
        $searchConditionKet = $search ? " AND keterangan LIKE '%{$search}%'" : "";
        $searchConditionNamaLay = $search ? " AND nama_layanan LIKE '%{$search}%'" : "";
        $searchConditionNamaKeg = $search ? " AND nama_kegiatan LIKE '%{$search}%'" : "";

        if ($modul == 'Seminar FGD') {
            $queries[] = "SELECT s.id, s.judul as name, s.produk as category, s.topik as subcategory, 0 as price, s.lokasi as location, s.tanggal as event_date, 'active' as status, 'Seminar FGD' as jenis_modul, 'seminar_fgd' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, s.keterangan as description FROM seminar_fgd s LEFT JOIN wpa w ON s.wpa_id = w.id WHERE 1=1 {$searchConditionJudul}";
        } elseif ($modul == 'Pelatihan Simulasi') {
            $queries[] = "SELECT s.id, s.judul as name, s.produk as category, s.topik as subcategory, 0 as price, s.lokasi as location, s.tanggal as event_date, 'active' as status, 'Pelatihan Simulasi' as jenis_modul, 'pelatihan_simulasi' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, s.keterangan as description FROM pelatihan_simulasi s LEFT JOIN wpa w ON s.wpa_id = w.id WHERE 1=1 {$searchConditionJudul}";
        } elseif ($modul == 'Signal') {
            $queries[] = "SELECT s.id, s.keterangan as name, s.produk as category, s.keterangan as subcategory, 0 as price, s.media as location, s.tanggal as event_date, 'active' as status, 'Signal' as jenis_modul, 'signals' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, s.keterangan as description FROM signals s LEFT JOIN wpa w ON s.wpa_id = w.id WHERE 1=1 {$searchConditionKet}";
        } elseif ($modul == 'Konsultasi') {
            $queries[] = "SELECT s.id, s.keterangan as name, s.produk as category, s.keterangan as subcategory, 0 as price, s.media as location, s.tanggal as event_date, 'active' as status, 'Konsultasi' as jenis_modul, 'konsultasi' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, s.keterangan as description FROM konsultasi s LEFT JOIN wpa w ON s.wpa_id = w.id WHERE 1=1 {$searchConditionKet}";
        } elseif ($modul == 'Expert Advisor') {
            $queries[] = "SELECT s.id, s.nama_layanan as name, s.produk as category, s.penjelasan_layanan as subcategory, 0 as price, NULL as location, s.tanggal as event_date, 'active' as status, 'Expert Advisor' as jenis_modul, 'expert_advisor' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, s.keterangan as description FROM expert_advisor s LEFT JOIN wpa w ON s.wpa_id = w.id WHERE 1=1 {$searchConditionNamaLay}";
        } elseif ($modul == 'Kegiatan Lainnya') {
            $queries[] = "SELECT s.id, s.nama_kegiatan as name, s.produk as category, s.keterangan as subcategory, 0 as price, s.media as location, s.tanggal as event_date, 'active' as status, 'Kegiatan Lainnya' as jenis_modul, 'kegiatan_lainnya' as table_name, w.name as wpa_name, 0 as is_recurring, NULL as recurring_frequency, NULL as recurring_day, NULL as recurring_time, s.keterangan as description FROM kegiatan_lainnya s LEFT JOIN wpa w ON s.wpa_id = w.id WHERE 1=1 {$searchConditionNamaKeg}";
        }

        $unionQuery = implode(" UNION ALL ", $queries);
        
        // Count total for pagination
        $countQuery = "SELECT COUNT(*) as total FROM ({$unionQuery}) as count_table";
        $totalRow = $db->query($countQuery)->getRow();
        $total = $totalRow ? $totalRow->total : 0;
        
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Main query with limit offset
        $finalQuery = "{$unionQuery} ORDER BY name ASC LIMIT {$perPage} OFFSET {$offset}";
        $results = $db->query($finalQuery)->getResultArray();

        $absensiModel = new \App\Models\AbsensiPesertaModel();

        // Calculate students
        foreach ($results as &$resItem) {
            // 1. Tentukan tipe kegiatan untuk absensi
            $absensiTypes = [];
            switch ($resItem['jenis_modul']) {
                case 'Seminar FGD': $absensiTypes = ['seminar_fgd', 'seminar']; break;
                case 'Pelatihan Simulasi': $absensiTypes = ['pelatihan_simulasi', 'pelatihan']; break;
                case 'Signal': $absensiTypes = ['signals', 'signal']; break;
                case 'Konsultasi': $absensiTypes = ['konsultasi']; break;
                case 'Expert Advisor': $absensiTypes = ['expert_advisor']; break;
                case 'Kegiatan Lainnya': $absensiTypes = ['kegiatan_lainnya']; break;
            }
            
            $jmlAbsensi = 0;
            if (!empty($absensiTypes)) {
                $jmlAbsensi = $absensiModel->whereIn('kegiatan_type', $absensiTypes)
                                           ->where('kegiatan_id', $resItem['id'])
                                           ->countAllResults();
            }
            
            if ($jmlAbsensi > 0) {
                $resItem['students'] = $jmlAbsensi;
            } else {
                // 2. Fallback to getBuyerCount
                $buyers = $this->getBuyerCount($resItem['id'], $resItem['table_name']);
                $resItem['students'] = $buyers;
            }
        }

        // Create CodeIgniter Pager object manually
        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $total, 'default_full'); // Just making links internally, we can return the pager object
        
        // We will return data and total, but pager can be accessed from $this->pager usually, however this is not Active Record paginate.
        // We can just return the pager instance.
        
        return [
            'data' => $results,
            'pager' => $pager,
            'total' => $total
        ];
    }
}
