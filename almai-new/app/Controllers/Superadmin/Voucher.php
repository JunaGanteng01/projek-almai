<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\VoucherModel;
use App\Models\VoucherUsageModel;
use App\Models\LayananModel;
use App\Models\LayananArtikelModel;
use App\Models\LayananEventModel;
use App\Models\LayananToolsModel;
use App\Models\LayananSubscriptionModel;
use App\Models\KategoriLayananModel;

class Voucher extends BaseController
{
    protected $voucherModel;
    protected $layananModel;
    protected $artikelLayananModel;
    protected $eventLayananModel;
    protected $toolsLayananModel;
    protected $subscriptionLayananModel;
    protected $kategoriLayananModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
        $this->layananModel = new LayananModel();
        $this->artikelLayananModel = new LayananArtikelModel();
        $this->eventLayananModel = new LayananEventModel();
        $this->toolsLayananModel = new LayananToolsModel();
        $this->subscriptionLayananModel = new LayananSubscriptionModel();
        $this->kategoriLayananModel = new KategoriLayananModel();
    }

    private function getLayananItems()
    {
        $result = [];
        
        // Items are grouped by category for the view logic
        
        // 0. Main Layanan table items
        $layananItems = $this->layananModel->whereIn('status', ['active', 'aktif'])->findAll();
        foreach ($layananItems as $item) {
            $cat = strtolower(str_replace(' ', '-', $item['category']));
            if ($cat === 'almai-ultimate') $cat = 'ultimate';
            
            if (!isset($result[$cat])) $result[$cat] = [];
            
            $result[$cat][] = [
                'id' => $item['id'],
                'name' => '[Course] ' . $item['name'],
                'price' => $item['price'],
                'type' => 'layanan',
                'subcategory' => $item['subcategory']
            ];
        }
        
        // 1. Advokasi
        if (!isset($result['advokasi'])) $result['advokasi'] = [];
        $advokasi = &$result['advokasi'];
        // Artikel
        $artikels = $this->artikelLayananModel->whereIn('status', ['published', 'aktif'])->findAll();
        foreach ($artikels as $item) {
            $advokasi[] = ['id' => $item['id'], 'name' => '[Artikel] ' . $item['title'], 'price' => $item['poin_price'], 'type' => 'artikel', 'subcategory' => 'artikel'];
        }
        // Events
        $events = $this->eventLayananModel->whereIn('status', ['upcoming', 'published', 'aktif'])->findAll();
        foreach ($events as $item) {
            $advokasi[] = ['id' => $item['id'], 'name' => '[' . ucfirst($item['type']) . '] ' . $item['title'], 'price' => $item['price'], 'type' => 'event', 'subcategory' => $item['type']];
        }
        // Subscriptions (Advokasi)
        $subs = $this->subscriptionLayananModel->whereIn('type', ['pendampingan', 'profirm'])->whereIn('status', ['active', 'published', 'aktif'])->findAll();
        foreach ($subs as $item) {
            $advokasi[] = ['id' => $item['id'], 'name' => '[Sub] ' . $item['name'], 'price' => $item['price'], 'type' => 'subscription', 'subcategory' => $item['type']];
        }
        // $result['advokasi'] = $advokasi; // Already referenced indirectly via &$result['advokasi']

        // 2. Expert Advisor
        if (!isset($result['expert-advisor'])) $result['expert-advisor'] = [];
        $eaItems = &$result['expert-advisor'];
        $toolsEa = $this->toolsLayananModel->where('type', 'ea')->whereIn('status', ['active', 'published', 'aktif'])->findAll();
        foreach ($toolsEa as $item) {
            $eaItems[] = ['id' => $item['id'], 'name' => '[EA] ' . $item['name'], 'price' => $item['price'], 'type' => 'tool', 'subcategory' => 'ea'];
        }
        // $result['expert-advisor'] = $eaItems;

        // 3. Almai Ultimate
        if (!isset($result['almai-ultimate'])) $result['almai-ultimate'] = [];
        $ultimate = &$result['almai-ultimate'];
        // Toolkit
        $toolsToolkit = $this->toolsLayananModel->where('type', 'toolkit')->whereIn('status', ['active', 'published', 'aktif'])->findAll();
        foreach ($toolsToolkit as $item) {
            $ultimate[] = ['id' => $item['id'], 'name' => '[Toolkit] ' . $item['name'], 'price' => $item['price'], 'type' => 'tool', 'subcategory' => 'toolkit'];
        }
        // Subscriptions (Ultimate)
        $subsUlt = $this->subscriptionLayananModel->whereIn('type', ['private_konsultan', 'vip_member'])->whereIn('status', ['active', 'published', 'aktif'])->findAll();
        foreach ($subsUlt as $item) {
            $ultimate[] = ['id' => $item['id'], 'name' => '[Sub] ' . $item['name'], 'price' => $item['price'], 'type' => 'subscription', 'subcategory' => $item['type']];
        }
        // $result['almai-ultimate'] = $ultimate;

        return $result;
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->voucherModel->orderBy('created_at', 'DESC');
        
        if ($status && $status !== 'all') {
            $builder->where('status', $status);
        }

        $vouchers = $builder->paginate(20);
        $pager = $this->voucherModel->pager;

        // Stats
        $totalVouchers = $this->voucherModel->countAllResults(false);
        $activeVouchers = $this->voucherModel->where('status', 'active')->countAllResults(false);
        $totalUsage = $this->voucherModel->selectSum('used_count')->first()['used_count'] ?? 0;

        return view('superadmin/voucher/index', [
            'title' => 'Kelola Voucher - Admin Dashboard',
            'vouchers' => $vouchers,
            'pager' => $pager,
            'totalVouchers' => $totalVouchers,
            'activeVouchers' => $activeVouchers,
            'totalUsage' => $totalUsage,
            'currentStatus' => $status,
            'activeMenu' => 'voucher'
        ]);
    }

    public function create()
    {
        return view('superadmin/voucher/create', [
            'title' => 'Tambah Voucher - Admin Dashboard',
            'categories' => $this->kategoriLayananModel->getActive(),
            'layananList' => $this->getLayananItems(),
            'generatedCode' => $this->voucherModel->generateCode(),
            'activeMenu' => 'voucher'
        ]);
    }

    public function store()
    {
        $rules = [
            'code' => 'required|min_length[3]|is_unique[vouchers.code]',
            'name' => 'required|min_length[3]',
            'discount_type' => 'required|in_list[percentage,fixed]',
            'discount_value' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get product type with robust fallback
        $productType = trim($this->request->getPost('product_type'));
        if (empty($productType) || !in_array($productType, ['all', 'layanan'])) {
            $productType = 'all';
        }
        
        $productIds = null;
        if ($productType !== 'all') {
            $selectedProducts = $this->request->getPost('product_ids');
            if (!empty($selectedProducts)) {
                $productIds = json_encode(array_map('intval', $selectedProducts));
            } else {
                // If layanan is selected but no products chosen, default to 'all'
                $productType = 'all';
            }
        }

        $this->voucherModel->insert([
            'code' => strtoupper($this->request->getPost('code')),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_purchase' => $this->request->getPost('min_purchase') ?: 0,
            'max_discount' => $this->request->getPost('max_discount') ?: null,
            'usage_limit' => $this->request->getPost('usage_limit') ?: null,
            'per_user_limit' => $this->request->getPost('per_user_limit') ?: 1,
            'product_type' => $productType,
            'product_ids' => $productIds,
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'status' => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/superadmin/voucher')->with('success', 'Voucher berhasil dibuat!');
    }

    public function edit($id)
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->to('/superadmin/voucher')->with('error', 'Voucher tidak ditemukan');
        }

        // Decode product IDs
        $selectedProducts = [];
        if ($voucher['product_ids']) {
            $selectedProducts = json_decode($voucher['product_ids'], true) ?: [];
        }

        return view('superadmin/voucher/edit', [
            'title' => 'Edit Voucher - Admin Dashboard',
            'voucher' => $voucher,
            'selectedProducts' => $selectedProducts,
            'categories' => $this->kategoriLayananModel->getActive(),
            'layananList' => $this->getLayananItems(),
            'activeMenu' => 'voucher'
        ]);
    }

    public function update($id)
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->to('/superadmin/voucher')->with('error', 'Voucher tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'discount_type' => 'required|in_list[percentage,fixed]',
            'discount_value' => 'required|numeric|greater_than[0]',
        ];

        // Check code uniqueness only if changed
        $newCode = strtoupper($this->request->getPost('code'));
        if ($newCode !== $voucher['code']) {
            $rules['code'] = 'required|min_length[3]|is_unique[vouchers.code]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get product type with robust fallback
        $productType = trim($this->request->getPost('product_type'));
        if (empty($productType) || !in_array($productType, ['all', 'layanan'])) {
            $productType = 'all';
        }
        
        $productIds = null;
        if ($productType !== 'all') {
            $selectedProducts = $this->request->getPost('product_ids');
            if (!empty($selectedProducts)) {
                $productIds = json_encode(array_map('intval', $selectedProducts));
            } else {
                // If layanan is selected but no products chosen, default to 'all'
                $productType = 'all';
            }
        }

        $this->voucherModel->update($id, [
            'code' => $newCode,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_purchase' => $this->request->getPost('min_purchase') ?: 0,
            'max_discount' => $this->request->getPost('max_discount') ?: null,
            'usage_limit' => $this->request->getPost('usage_limit') ?: null,
            'per_user_limit' => $this->request->getPost('per_user_limit') ?: 1,
            'product_type' => $productType,
            'product_ids' => $productIds,
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'status' => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/superadmin/voucher')->with('success', 'Voucher berhasil diupdate!');
    }

    public function delete($id)
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->to('/superadmin/voucher')->with('error', 'Voucher tidak ditemukan');
        }

        $this->voucherModel->delete($id);

        return redirect()->to('/superadmin/voucher')->with('success', 'Voucher berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->to('/superadmin/voucher')->with('error', 'Voucher tidak ditemukan');
        }

        $newStatus = $voucher['status'] === 'active' ? 'inactive' : 'active';
        $this->voucherModel->update($id, ['status' => $newStatus]);

        return redirect()->to('/superadmin/voucher')->with('success', 'Status voucher berhasil diubah!');
    }

    public function usages($id)
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            return redirect()->to('/superadmin/voucher')->with('error', 'Voucher tidak ditemukan');
        }

        $usageModel = new VoucherUsageModel();
        $usages = $usageModel->getUsageWithDetails($id);

        return view('superadmin/voucher/usages', [
            'title' => 'Riwayat Penggunaan Voucher - Admin Dashboard',
            'voucher' => $voucher,
            'usages' => $usages,
            'activeMenu' => 'voucher'
        ]);
    }
}
