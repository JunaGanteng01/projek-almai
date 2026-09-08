<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ToolsModel;
use App\Models\WpaModel;

class ToolsManagement extends BaseController
{
    protected $toolsModel;
    protected $wpaModel;

    public function __construct()
    {
        $this->toolsModel = new ToolsModel();
        $this->wpaModel = new WpaModel();
    }

    public function index()
    {
        $filter = $this->request->getGet('filter') ?? 'all';
        $category = $this->request->getGet('category') ?? '';
        
        $builder = $this->toolsModel->select('tools.*, wpa.name as wpa_name')
                                    ->join('wpa', 'wpa.id = tools.wpa_id', 'left');
        
        if ($filter === 'active') {
            $builder->where('tools.status', 'active');
        } elseif ($filter === 'inactive') {
            $builder->where('tools.status', 'inactive');
        }
        
        if ($category) {
            $builder->where('tools.category', $category);
        }
        
        $toolsList = $builder->orderBy('tools.created_at', 'DESC')->paginate(20);
        $pager = $this->toolsModel->pager;
        
        // Get real sales count per tool from transaksi
        $db = \Config\Database::connect();
        $toolIds = array_column($toolsList, 'id');
        $salesPerTool = [];
        
        if (!empty($toolIds)) {
            $salesQuery = $db->query("
                SELECT 
                    SUBSTRING_INDEX(SUBSTRING_INDEX(notes, 'Tool ID: ', -1), ' ', 1) as tool_id,
                    COUNT(*) as sales_count,
                    SUM(total) as revenue
                FROM transaksi 
                WHERE product_type = 'tools' AND status = 'confirmed'
                GROUP BY tool_id
            ");
            
            foreach ($salesQuery->getResult() as $row) {
                $salesPerTool[$row->tool_id] = [
                    'count' => $row->sales_count,
                    'revenue' => $row->revenue
                ];
            }
        }
        
        // Add sales data to each tool
        foreach ($toolsList as &$tool) {
            $tool['real_sales'] = $salesPerTool[$tool['id']]['count'] ?? 0;
            $tool['real_revenue'] = $salesPerTool[$tool['id']]['revenue'] ?? 0;
        }
        
        // Stats
        $totalTools = $this->toolsModel->countAllResults();
        $activeTools = $this->toolsModel->where('status', 'active')->countAllResults();
        
        // Get real sales data from transaksi table
        $salesData = $db->query("
            SELECT COUNT(*) as total_sales, COALESCE(SUM(total), 0) as total_revenue 
            FROM transaksi 
            WHERE product_type = 'tools' AND status = 'confirmed'
        ")->getRow();
        
        $totalSales = $salesData->total_sales ?? 0;
        $totalRevenue = $salesData->total_revenue ?? 0;

        return view('admin/tools/index', [
            'title' => 'Kelola Tools - Admin Dashboard',
            'pageTitle' => 'Kelola Tools',
            'toolsList' => $toolsList,
            'pager' => $pager,
            'activeMenu' => 'tools',
            'currentFilter' => $filter,
            'currentCategory' => $category,
            'categories' => $this->toolsModel->getCategories(),
            'totalTools' => $totalTools,
            'activeTools' => $activeTools,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
        ]);
    }

    public function create()
    {
        $wpaList = $this->wpaModel->where('status', 'active')->findAll();

        return view('admin/tools/create', [
            'title' => 'Tambah Tools - Admin Dashboard',
            'pageTitle' => 'Tambah Tools',
            'wpaList' => $wpaList,
            'categories' => $this->toolsModel->getCategories(),
            'platforms' => $this->toolsModel->getPlatforms(),
            'activeMenu' => 'tools'
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'category' => 'required',
            'platform' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|min_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true) . '-' . time();

        // Process features and compatibility
        $features = array_filter(array_map('trim', explode("\n", $this->request->getPost('features') ?? '')));
        $compatibility = array_filter(array_map('trim', explode("\n", $this->request->getPost('compatibility') ?? '')));

        $this->toolsModel->insert([
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'name' => $name,
            'slug' => $slug,
            'thumbnail' => $this->request->getPost('thumbnail') ?: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600',
            'price' => $this->request->getPost('price'),
            'original_price' => $this->request->getPost('original_price') ?: $this->request->getPost('price'),
            'category' => $this->request->getPost('category'),
            'platform' => $this->request->getPost('platform'),
            'description' => $this->request->getPost('description'),
            'features' => json_encode($features),
            'compatibility' => json_encode($compatibility),
            'download_url' => $this->request->getPost('download_url'),
            'documentation_url' => $this->request->getPost('documentation_url'),
            'status' => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/admin/tools')->with('success', 'Tools berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tool = $this->toolsModel->find($id);
        if (!$tool) {
            return redirect()->to('/admin/tools')->with('error', 'Tools tidak ditemukan');
        }

        // Decode JSON fields
        $tool['features_text'] = implode("\n", json_decode($tool['features'] ?? '[]', true) ?: []);
        $tool['compatibility_text'] = implode("\n", json_decode($tool['compatibility'] ?? '[]', true) ?: []);

        $wpaList = $this->wpaModel->where('status', 'active')->findAll();

        return view('admin/tools/edit', [
            'title' => 'Edit Tools - Admin Dashboard',
            'pageTitle' => 'Edit Tools',
            'tool' => $tool,
            'wpaList' => $wpaList,
            'categories' => $this->toolsModel->getCategories(),
            'platforms' => $this->toolsModel->getPlatforms(),
            'activeMenu' => 'tools'
        ]);
    }

    public function update($id)
    {
        $tool = $this->toolsModel->find($id);
        if (!$tool) {
            return redirect()->to('/admin/tools')->with('error', 'Tools tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'category' => 'required',
            'platform' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|min_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true);

        // Process features and compatibility
        $features = array_filter(array_map('trim', explode("\n", $this->request->getPost('features') ?? '')));
        $compatibility = array_filter(array_map('trim', explode("\n", $this->request->getPost('compatibility') ?? '')));

        $this->toolsModel->update($id, [
            'wpa_id' => $this->request->getPost('wpa_id') ?: null,
            'name' => $name,
            'slug' => $slug,
            'thumbnail' => $this->request->getPost('thumbnail') ?: $tool['thumbnail'],
            'price' => $this->request->getPost('price'),
            'original_price' => $this->request->getPost('original_price') ?: $this->request->getPost('price'),
            'category' => $this->request->getPost('category'),
            'platform' => $this->request->getPost('platform'),
            'description' => $this->request->getPost('description'),
            'features' => json_encode($features),
            'compatibility' => json_encode($compatibility),
            'download_url' => $this->request->getPost('download_url'),
            'documentation_url' => $this->request->getPost('documentation_url'),
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/tools')->with('success', 'Tools berhasil diupdate!');
    }

    public function delete($id)
    {
        $tool = $this->toolsModel->find($id);
        if (!$tool) {
            return redirect()->to('/admin/tools')->with('error', 'Tools tidak ditemukan');
        }

        $this->toolsModel->delete($id);

        return redirect()->to('/admin/tools')->with('success', 'Tools berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $tool = $this->toolsModel->find($id);
        if (!$tool) {
            return redirect()->to('/admin/tools')->with('error', 'Tools tidak ditemukan');
        }

        $newStatus = $tool['status'] === 'active' ? 'inactive' : 'active';
        $this->toolsModel->update($id, ['status' => $newStatus]);

        return redirect()->to('/admin/tools')->with('success', 'Status tools berhasil diubah!');
    }
}
