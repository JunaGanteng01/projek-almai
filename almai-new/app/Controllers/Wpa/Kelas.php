<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\LayananModel;
use App\Models\TransaksiModel;

class Kelas extends BaseController
{
    protected $layananModel;
    
    public function __construct()
    {
        $this->layananModel = new LayananModel();
    }
    
    public function index()
    {
        $wpaId = $this->session->get('wpaId');
        
        if (!$wpaId) {
            $userId = $this->session->get('userId');
            $wpaModel = new \App\Models\WpaModel();
            $wpa = $wpaModel->where('user_id', $userId)->first();
            if ($wpa) {
                $wpaId = $wpa['id'];
                $this->session->set('wpaId', $wpaId);
            }
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        
        $allLayanan = $this->layananModel->getByWpaId($wpaId);
        
        // Ensure all items have necessary keys for the view
        foreach ($allLayanan as &$item) {
            $item['title'] = $item['name'] ?? ($item['title'] ?? ($item['product_name'] ?? '-'));
            $item['mode'] = $item['mode'] ?? ($item['location'] ?? 'Online');
            $item['category'] = $item['category'] ?? 'Layanan';
            $item['price'] = $item['price'] ?? 0;
            $item['status'] = $item['status'] ?? 'active';
            $item['students'] = $item['students'] ?? 0;
            $item['rating'] = $item['rating'] ?? 0;
            $item['thumbnail'] = $item['thumbnail'] ?? 'https://via.placeholder.com/400x300';
        }

        $filteredLayanan = $allLayanan;
        if ($search) {
            $filteredLayanan = array_filter($filteredLayanan, function($item) use ($search) {
                return (strpos(strtolower($item['title']), strtolower($search)) !== false) || 
                       (strpos(strtolower($item['category']), strtolower($search)) !== false);
            });
        }
        
        if ($status && $status !== 'all') {
            $filteredLayanan = array_filter($filteredLayanan, function($item) use ($status) {
                return $item['status'] === $status;
            });
        }
        
        $totalLayanan = count($allLayanan);
        $activeLayanan = count(array_filter($allLayanan, function($item) {
            $st = $item['status'];
            return $st === 'active' || $st === 'published' || $st === 'upcoming';
        }));
        
        // Batched stats calculation for individual items
        if (!empty($filteredLayanan)) {
            $fLayananIds = array_filter(array_column($filteredLayanan, 'id'));
            $fLayananNames = array_unique(array_filter(array_map(fn($item) => $item['title'] ?? $item['name'] ?? null, $filteredLayanan)));

            $itemStatsModel = new TransaksiModel();
            $itemStatsModel->select('layanan_id, product_name, COUNT(id) as total_sales, SUM(total) as total_revenue');
            $itemStatsModel->where('status', 'confirmed');
            $itemStatsModel->groupStart();
            if (!empty($fLayananIds)) $itemStatsModel->whereIn('layanan_id', $fLayananIds);
            if (!empty($fLayananNames)) {
                $itemStatsModel->orWhereIn('product_name', $fLayananNames);
                if (count($fLayananNames) < 50) {
                    foreach ($fLayananNames as $name) $itemStatsModel->orLike('product_name', $name . ' - ', 'after');
                }
            }
            $itemStatsModel->groupEnd();
            $itemStatsModel->groupBy('layanan_id, product_name');
            $allItemStats = $itemStatsModel->findAll();

            foreach ($filteredLayanan as &$item) {
                $item['total_sales'] = 0;
                $item['total_revenue'] = 0;
                $name = $item['title'] ?? $item['name'] ?? '';

                foreach ($allItemStats as $stat) {
                    if ($stat['layanan_id'] == $item['id'] || $stat['product_name'] === $name || (isset($stat['product_name']) && stripos($stat['product_name'], $name . ' - ') === 0)) {
                        $item['total_sales'] += $stat['total_sales'];
                        $item['total_revenue'] += $stat['total_revenue'];
                    }
                }
            }
        }
        
        $db = \Config\Database::connect();
        $totalStudents = 0;
        $totalRevenue = 0;
        
        if (!empty($allLayanan)) {
            $builder = $db->table('transaksi');
            $layananIds = array_filter(array_column($allLayanan, 'id'));
            $layananNames = array_unique(array_filter(array_map(function($item) {
                return $item['title'] ?? $item['name'] ?? null;
            }, $allLayanan)));

            $builder->groupStart();
            if (!empty($layananIds)) {
                $builder->whereIn('layanan_id', $layananIds);
            }
            if (!empty($layananNames)) {
                $builder->orWhereIn('product_name', $layananNames);
                if (count($layananNames) < 50) {
                    foreach ($layananNames as $name) {
                        $builder->orLike('product_name', $name . ' - ', 'after');
                    }
                }
            }
            $builder->groupEnd();
            
            $totalStudents = (clone $builder)->where('status', 'confirmed')
                ->select('COUNT(DISTINCT user_id) AS total_count')
                ->get()->getRowArray()['total_count'] ?? 0;
            
            $totalRevenue = (clone $builder)->where('status', 'confirmed')
                ->select('SUM(total) AS total_sum')
                ->get()->getRowArray()['total_sum'] ?? 0;
        }
        
        return view('wpa/kelas/index', [
            'title' => 'Layanan Saya - WPA Dashboard',
            'activeMenu' => 'kelas',
            'kelasList' => $filteredLayanan,
            'pager' => null,
            'search' => $search,
            'currentStatus' => $status,
            'totalKelas' => $totalLayanan,
            'activeKelas' => $activeLayanan,
            'totalStudents' => $totalStudents,
            'totalRevenue' => $totalRevenue,
            'categories' => LayananModel::getCategories(),
        ]);
    }
    
    public function create()
    {
        return view('wpa/kelas/create', [
            'title' => 'Buat Layanan Baru - WPA Dashboard',
            'activeMenu' => 'kelas',
            'categories' => LayananModel::getCategories(),
        ]);
    }
    
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[5]',
            'category' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $wpaId = $this->session->get('wpaId');
        $model = new \App\Models\LayananModel();
        
        $thumbnail = $this->request->getPost('thumbnail') ?? 'https://via.placeholder.com/400x300';
        $thumbnailFile = $this->request->getFile('thumbnail_file');
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $newName = 'layanan_' . time() . '_' . $thumbnailFile->getRandomName();
            $thumbnailFile->move(WRITEPATH . 'uploads/layanan', $newName);
            $thumbnail = 'uploads/layanan/' . $newName;
        }
        
        $model->insert([
            'wpa_id' => $wpaId,
            'name' => $this->request->getPost('title'),
            'thumbnail' => $thumbnail,
            'price' => $this->request->getPost('price'),
            'original_price' => $this->request->getPost('original_price') ?: 0,
            'duration' => $this->request->getPost('duration') ?? '2 Jam',
            'modules' => $this->request->getPost('modules') ?? 0,
            'level' => $this->request->getPost('level') ?? 'Beginner',
            'category' => $this->request->getPost('category'),
            'mode' => $this->request->getPost('mode') ?? 'Online',
            'location' => $this->request->getPost('location'),
            'description' => $this->request->getPost('description'),
            'status' => 'active',
        ]);
        
        return redirect()->to('/wpa/dashboard/layanan')->with('success', 'Layanan berhasil dibuat!');
    }
}
