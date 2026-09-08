<?php

namespace App\Controllers\AdminWpa;

use App\Controllers\BaseController;
use App\Models\AdminWpaAssignmentModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class Transaksi extends BaseController
{
    protected $assignmentModel;
    protected $transaksiModel;
    protected $userModel;
    protected $wpaModel; // Added

    public function __construct()
    {
        $this->assignmentModel = new AdminWpaAssignmentModel();
        $this->transaksiModel = new TransaksiModel();
        $this->userModel = new UserModel();
        $this->wpaModel = new \App\Models\WpaModel(); // Added
    }

    public function index()
    {
        $currentUserId = session()->get('userId');
        $wpaFilter = $this->request->getGet('wpa_id');
        $statusFilter = $this->request->getGet('status');
        
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) {
            return view('admin_wpa/transaksi/index', [
                'title' => 'Transaksi WPA - Admin WPA',
                'transactions' => [],
                'pager' => null,
                'assignedWPAs' => [],
                'stats' => [],
                'currentWpa' => $wpaFilter,
                'currentStatus' => $statusFilter,
                'activeMenu' => 'transaksi'
            ]);
        }

        $wpaModel = new \App\Models\WpaModel();
        $WPAs = $wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        
        $targetWpaIds = ($wpaFilter && in_array($wpaFilter, $assignedWpaIds)) ? [$wpaFilter] : $assignedWpaIds;

        // Fetch Transactions with Pagination using the COALESCE logic from getWithUser
        $coalesceWpa = "COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id)";
        $wpaIdsList = implode(',', $targetWpaIds);

        $builder = $this->transaksiModel->getWithUser();
        $builder->where("$coalesceWpa IN ($wpaIdsList)", null, false);
        
        if ($statusFilter) {
            $builder->where('transaksi.status', $statusFilter);
        }

        $transactions = $builder->paginate(20, 'default');
        $pager = $this->transaksiModel->pager;

        // Calculate Stats precisely per WPA using SQL
        $db = \Config\Database::connect();
        $stats = [];
        
        foreach ($WPAs as $wpa) {
            $wpaId = (int)$wpa['id'];
            $sql = "
                SELECT 
                    COUNT(transaksi.id) as sales_count,
                    SUM(CASE WHEN transaksi.payment_method != 'poin' THEN transaksi.total ELSE 0 END) as total_revenue
                FROM transaksi
                LEFT JOIN layanan ON layanan.id = transaksi.layanan_id
                LEFT JOIN layanan_event ON layanan_event.id = transaksi.layanan_id
                LEFT JOIN layanan_tools ON layanan_tools.id = transaksi.layanan_id
                LEFT JOIN layanan_subscription ON layanan_subscription.id = transaksi.layanan_id
                WHERE COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id) = ?
                AND transaksi.status = 'confirmed'
            ";
            
            $query = $db->query($sql, [$wpaId]);
            $wpaRes = $query->getRowArray();

            $stats[] = [
                'wpa_id' => $wpa['id'],
                'wpa_name' => $wpa['name'],
                'revenue' => (float)($wpaRes['total_revenue'] ?? 0),
                'sales' => (int)($wpaRes['sales_count'] ?? 0)
            ];
        }

        return view('admin_wpa/transaksi/index', [
            'title' => 'Transaksi WPA - Admin WPA',
            'transactions' => $transactions,
            'pager' => $pager,
            'assignedWPAs' => $WPAs,
            'stats' => $stats,
            'currentWpa' => $wpaFilter,
            'currentStatus' => $statusFilter,
            'activeMenu' => 'transaksi'
        ]);
    }

    public function view($id)
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        $transaksi = $this->transaksiModel->getWithUser()->where('transaksi.id', $id)->first();
        if (!$transaksi || !in_array($transaksi['creator_wpa_id'], $assignedWpaIds)) {
            return redirect()->to('/admin-wpa/transaksi')->with('error', 'Akses ditolak.');
        }

        return view('admin_wpa/transaksi/view', [
            'title' => 'Detail Transaksi - Admin WPA',
            'transaksi' => $transaksi,
            'activeMenu' => 'transaksi'
        ]);
    }

    public function confirm($id)
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        $transaksi = $this->transaksiModel->getWithUser()->where('transaksi.id', $id)->first();
        if (!$transaksi || !in_array($transaksi['creator_wpa_id'], $assignedWpaIds)) {
            return redirect()->to('/admin-wpa/transaksi')->with('error', 'Akses ditolak.');
        }

        if ($transaksi['status'] === 'confirmed') {
            return redirect()->to('/admin-wpa/transaksi')->with('error', 'Transaksi sudah dikonfirmasi.');
        }

        // Update Status
        $this->transaksiModel->update($id, [
            'status' => 'confirmed',
            'confirmed_at' => date('Y-m-d H:i:s')
        ]);

        // Process logic
        $this->transaksiModel->processReferralPoin($id);
        $this->transaksiModel->processWpaCommission($id);
        $this->transaksiModel->processEaLicense($id);

        return redirect()->to('/admin-wpa/transaksi')->with('success', 'Transaksi berhasil dikonfirmasi!');
    }

    public function export()
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) return redirect()->back();

        $targetWpaIds = $assignedWpaIds;
        $wpaIdsList = implode(',', $targetWpaIds);
        $coalesceWpa = "COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id)";

        $builder = $this->transaksiModel->getWithUser();
        $builder->where("$coalesceWpa IN ($wpaIdsList)", null, false);
        
        $statusFilter = $this->request->getGet('status');
        if ($statusFilter) {
            $builder->where('transaksi.status', $statusFilter);
        }

        $wpaFilter = $this->request->getGet('wpa_id');
        if ($wpaFilter && in_array($wpaFilter, $assignedWpaIds)) {
            $builder->where("$coalesceWpa = $wpaFilter", null, false);
        }

        $transactions = $builder->orderBy('transaksi.created_at', 'DESC')->findAll();

        if (empty($transactions)) {
            return redirect()->back()->with('error', 'Tidak ada data transaksi untuk diekspor');
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transaksi_wpa_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Invoice', 'Produk', 'User', 'Email', 'Amount', 'Discount', 'Total', 'Metode', 'Status', 'Tanggal']);

        foreach ($transactions as $t) {
            fputcsv($output, [
                $t['invoice_number'] ?? $t['external_id'] ?? '-',
                $t['product_name'],
                $t['user_name'],
                $t['user_email'],
                $t['amount'],
                $t['discount'],
                $t['total'],
                $t['payment_method'],
                $t['status'],
                $t['created_at']
            ]);
        }

        fclose($output);
        exit();
    }

    public function createManual()
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) {
            return redirect()->to('/admin-wpa/transaksi')->with('error', 'Anda tidak memiliki akses ke WPA manapun.');
        }

        $db = \Config\Database::connect();
        
        // Get WPAs info
        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        
        // Get all users in network (referred by these WPAs)
        $allNetworkIds = [];
        foreach ($WPAs as $wpa) {
            $networkIds = $this->userModel->getNetworkIds($wpa['user_id'], 10);
            $allNetworkIds = array_merge($allNetworkIds, $networkIds);
        }
        $allNetworkIds = array_unique($allNetworkIds);
        
        if (empty($allNetworkIds)) {
             $users = [];
        } else {
            $users = $db->table('users')
                ->select('id, name, email')
                ->whereIn('id', $allNetworkIds)
                ->where('status', 'active')
                ->orderBy('name', 'ASC')
                ->get()->getResultArray();
        }

        // Get services for assigned WPAs ONLY
        $layanan = $db->table('layanan')
            ->select('layanan.id, layanan.name as title, layanan.price, "layanan" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan.wpa_id', 'left')
            ->whereIn('layanan.wpa_id', $assignedWpaIds)
            ->where('layanan.status', 'published')
            ->get()->getResultArray();
            
        $events = $db->table('layanan_event')
            ->select('layanan_event.id, layanan_event.title, layanan_event.price, "event" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->whereIn('layanan_event.wpa_id', $assignedWpaIds)
            ->whereIn('layanan_event.status', ['upcoming', 'published', 'aktif'])
            ->get()->getResultArray();
            
        $tools = $db->table('layanan_tools')
            ->select('layanan_tools.id, layanan_tools.name as title, layanan_tools.price, "tool" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan_tools.wpa_id', 'left')
            ->whereIn('layanan_tools.wpa_id', $assignedWpaIds)
            ->where('layanan_tools.status', 'published')
            ->get()->getResultArray();
            
        $subs = $db->table('layanan_subscription')
            ->select('layanan_subscription.id, layanan_subscription.name as title, layanan_subscription.price, "subscription" as type, wpa.name as wpa_name, layanan_subscription.duration_days')
            ->join('wpa', 'wpa.id = layanan_subscription.wpa_id', 'left')
            ->whereIn('layanan_subscription.wpa_id', $assignedWpaIds)
            ->whereIn('layanan_subscription.status', ['published', 'aktif'])
            ->get()->getResultArray();

        // Sub Prices from layanan_prices
        $subPrices = [];
        $typeToTable = [
            'layanan' => 'layanan', 'course' => 'layanan',
            'event' => 'layanan_event', 'webinar' => 'layanan_event', 'workshop' => 'layanan_event',
            'tool' => 'layanan_tools',
            'subscription' => 'layanan_subscription', 'pendampingan' => 'layanan_subscription', 'cwpa' => 'layanan_subscription',
        ];

        foreach ($typeToTable as $stype => $baseTable) {
            $nameField = in_array($stype, ['event', 'webinar', 'workshop']) ? 'title' : 'name';
            
            $rows = $db->table('layanan_prices')
                ->select("layanan_prices.id, layanan_prices.name as sub_name, layanan_prices.price, layanan_prices.layanan_type, base.{$nameField} as base_title, wpa.name as w_name")
                ->join($baseTable . ' base', 'base.id = layanan_prices.layanan_id')
                ->join('wpa', 'wpa.id = base.wpa_id')
                ->where('layanan_prices.layanan_type', $stype)
                ->whereIn('base.wpa_id', $assignedWpaIds)
                ->get()->getResultArray();
            
            foreach ($rows as $row) {
                $subPrices[] = [
                    'id' => $row['id'],
                    'title' => $row['base_title'] . ' ('. $row['sub_name'] .')',
                    'price' => $row['price'],
                    'type' => 'price',
                    'wpa_name' => $row['w_name']
                ];
            }
        }

        $services = array_merge($layanan, $events, $tools, $subs, $subPrices);
        usort($services, function($a, $b) {
            return strcasecmp($a['title'], $b['title']);
        });

        return view('admin_wpa/transaksi/create_manual', [
            'title' => 'Tambah Transaksi Manual - Admin WPA',
            'users' => $users,
            'services' => $services,
            'activeMenu' => 'transaksi'
        ]);
    }

    public function storeManual()
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) {
            return redirect()->to('/admin-wpa/transaksi')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'user_id' => 'required|numeric',
            'service_full' => 'required',
            'amount' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        $userId = $this->request->getPost('user_id');
        $serviceFull = $this->request->getPost('service_full'); // Format: type:id
        $amount = (float) $this->request->getPost('amount');
        $description = $this->request->getPost('description');

        $parts = explode(':', $serviceFull);
        if (count($parts) !== 2) {
            return redirect()->back()->withInput()->with('error', 'Layanan tidak valid.');
        }

        $productType = $parts[0];
        $productId = $parts[1];

        // Verify user is in network
        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        $allNetworkIds = [];
        foreach ($WPAs as $wpa) {
            $networkIds = $this->userModel->getNetworkIds($wpa['user_id'], 10);
            $allNetworkIds = array_merge($allNetworkIds, $networkIds);
        }
        if (!in_array($userId, $allNetworkIds)) {
            return redirect()->back()->withInput()->with('error', 'User tidak berada dalam jaringan Anda.');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $productName = 'Unknown Service';

        if ($productType === 'price') {
            // Handle Sub Price
            $subPrice = $db->table('layanan_prices')->where('id', $productId)->get()->getRowArray();
            if (!$subPrice) {
                return redirect()->back()->withInput()->with('error', 'Sub harga tidak ditemukan.');
            }
            
            $productType = $subPrice['layanan_type'];
            $productId = $subPrice['layanan_id'];

            // Verify Ownership via base table
            $tableMapping = [
                'layanan' => 'layanan', 'course' => 'layanan',
                'event' => 'layanan_event', 'webinar' => 'layanan_event', 'workshop' => 'layanan_event',
                'tool' => 'layanan_tools',
                'subscription' => 'layanan_subscription', 'pendampingan' => 'layanan_subscription', 'cwpa' => 'layanan_subscription',
            ];
            $baseTable = $tableMapping[$productType] ?? 'layanan';
            $srv = $db->table($baseTable)->where('id', $productId)->get()->getRowArray();
            
            if (!$srv || !in_array($srv['wpa_id'], $assignedWpaIds)) {
                return redirect()->back()->withInput()->with('error', 'Layanan tidak ditemukan atau bukan milik Anda.');
            }

            // Include base name in product name for better records
            $productName = ($srv['name'] ?? $srv['title'] ?? '') . ' (' . $subPrice['name'] . ')';
        } else {
            // Find service and verify owner
            $tableMapping = [
                'layanan' => 'layanan',
                'event' => 'layanan_event',
                'tool' => 'layanan_tools',
                'subscription' => 'layanan_subscription',
            ];

            $tableName = $tableMapping[$productType] ?? 'layanan';
            $srv = $db->table($tableName)->where('id', $productId)->get()->getRowArray();
            
            if (!$srv || !in_array($srv['wpa_id'], $assignedWpaIds)) {
                return redirect()->back()->withInput()->with('error', 'Layanan tidak ditemukan atau bukan milik Anda.');
            }

            $productName = $srv['name'] ?? $srv['title'] ?? 'Manual Service';
        }

        // Create transaction
        $invoiceNumber = $this->transaksiModel->generateInvoiceNumber();
        
        $transaksiData = [
            'invoice_number' => $invoiceNumber,
            'user_id' => $userId,
            'layanan_id' => $productId,
            'product_type' => $productType,
            'product_name' => $productName,
            'amount' => $amount,
            'total' => $amount,
            'payment_method' => 'xendit',
            'status' => 'pending',
            'notes' => $description
        ];

        $transaksiId = $this->transaksiModel->insert($transaksiData);
        if (!$transaksiId) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat transaksi.');
        }

        // Generate Xendit Invoice
        $xenditService = new \App\Libraries\XenditService();
        $xenditData = [
            'external_id' => $invoiceNumber,
            'amount' => (int)$amount,
            'email' => $user['email'],
            'customer_name' => $user['name'],
            'description' => 'Pembelian ' . $productName . ' (Manual Admin WPA)',
            'item_name' => $productName,
            'phone' => $user['phone'] ?? '',
        ];

        $result = $xenditService->createInvoice($xenditData);

        if ($result['success']) {
            $this->transaksiModel->update($transaksiId, [
                'notes' => ($description ? $description . ' | ' : '') . 'Manual Admin WPA | Xendit ID: ' . $result['data']['id']
            ]);
            return redirect()->to('/admin-wpa/transaksi')->with('success', 'Transaksi manual berhasil dibuat. Link Xendit: ' . $result['data']['invoice_url']);
        }

        return redirect()->to('/admin-wpa/transaksi')->with('warning', 'Transaksi manual dibuat, namun gagal generate link Xendit.');
    }
}
