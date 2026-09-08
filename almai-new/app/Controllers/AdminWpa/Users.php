<?php

namespace App\Controllers\AdminWpa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AdminWpaAssignmentModel;
use App\Models\WpaModel;
use App\Models\LevelModel;

class Users extends BaseController
{
    protected $userModel;
    protected $assignmentModel;
    protected $wpaModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->assignmentModel = new AdminWpaAssignmentModel();
        $this->wpaModel = new WpaModel();
    }

    public function index()
    {
        $currentUserId = session()->get('userId');
        $search = $this->request->getGet('search');
        $wpaFilter = $this->request->getGet('wpa');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        // Get assigned WPAs
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) {
            return view('admin_wpa/users/index', [
                'title' => 'Kelola User - Admin WPA',
                'usersList' => [],
                'pager' => null,
                'activeMenu' => 'users',
                'overviewStats' => [],
                'statsPerWpa' => [],
                'WPAs' => [],
                'currentSearch' => $search,
                'wpaFilter' => $wpaFilter
            ]);
        }

        // Get assigned WPA data
        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        
        $db = \Config\Database::connect();
        $allNetworkIds = [];
        $wpaNetworkMap = []; // wpa_id => [user_ids]
        
        foreach ($WPAs as $wpa) {
            // Use multi-level logic for each WPA
            $networkIds = $this->userModel->getNetworkIds($wpa['user_id'], 10);
            $wpaNetworkMap[$wpa['id']] = $networkIds;
            $allNetworkIds = array_merge($allNetworkIds, $networkIds);
        }
        $allNetworkIds = array_unique($allNetworkIds);

        // --- Calculate Stats ---
        
        // Helper to count active users (those who have at least 1 confirmed transaction)
        $getActiveCount = function($userIds) use ($db) {
            if (empty($userIds)) return 0;
            return $db->table('transaksi')
                ->whereIn('user_id', $userIds)
                ->where('status', 'confirmed')
                ->select('COUNT(DISTINCT user_id) as count')
                ->get()->getRowArray()['count'] ?? 0;
        };

        // Helper to count new users this month within the network
        $getNewMonthCount = function($userIds) use ($db) {
            if (empty($userIds)) return 0;
            return $db->table('users')
                ->whereIn('id', $userIds)
                ->where('created_at >=', date('Y-m-01 00:00:00'))
                ->countAllResults();
        };

        // Overall stats (Global for assigned WPAs)
        $overviewStats = [
            'total' => count($allNetworkIds),
            'active' => $getActiveCount($allNetworkIds),
            'new' => $getNewMonthCount($allNetworkIds)
        ];

        // Stats per WPA
        $statsPerWpa = [];
        foreach ($WPAs as $wpa) {
            $networkIds = $wpaNetworkMap[$wpa['id']] ?? [];
            $statsPerWpa[] = [
                'wpa_id' => $wpa['id'],
                'wpa_name' => $wpa['name'],
                'total' => count($networkIds),
                'active' => $getActiveCount($networkIds)
            ];
        }

        // --- Filter User List ---
        $builder = $this->userModel;
        
        $filterIds = [];
        if ($wpaFilter && isset($wpaNetworkMap[$wpaFilter])) {
            $filterIds = $wpaNetworkMap[$wpaFilter];
        } else {
            $filterIds = $allNetworkIds;
        }

        if (!empty($filterIds)) {
            $builder->whereIn('id', $filterIds);
        } else {
            $builder->where('1 = 0');
        }

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        $usersList = $builder->orderBy('created_at', 'DESC')->paginate($perPage, 'default', $page);

        // Fetch WPA names for each user for "Daftar Lewat" display
        $uplineCodes = array_filter(array_unique(array_column($usersList, 'affiliator_code')));
        $uplines = [];
        if (!empty($uplineCodes)) {
            $uplines = $this->userModel->whereIn('code_referral', $uplineCodes)->select('code_referral, name')->findAll();
            $uplines = array_column($uplines, 'name', 'code_referral');
        }

        foreach ($usersList as &$u) {
            $u['upline_name'] = $uplines[$u['affiliator_code'] ?? ''] ?? null;
        }

        return view('admin_wpa/users/index', [
            'title' => 'Kelola User - Admin WPA',
            'usersList' => $usersList,
            'pager' => $this->userModel->pager,
            'currentSearch' => $search,
            'wpaFilter' => $wpaFilter,
            'WPAs' => $WPAs,
            'overviewStats' => $overviewStats,
            'statsPerWpa' => $statsPerWpa,
            'activeMenu' => 'users'
        ]);
    }

    public function create()
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        
        $wpaUsers = [];
        if (!empty($WPAs)) {
            $userIds = array_column($WPAs, 'user_id');
            $wpaUsers = $this->userModel->whereIn('id', $userIds)->select('id, name, code_referral')->findAll();
        }

        return view('admin_wpa/users/create', [
            'title' => 'Tambah User Baru - Admin WPA',
            'activeMenu' => 'users',
            'wpaUsers' => $wpaUsers
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required|min_length[10]',
            'password' => 'required|min_length[6]',
            'affiliator_code' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali inputan Anda');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'affiliator_code' => $this->request->getPost('affiliator_code'),
            'level_id' => LevelModel::LEVEL_USER,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/admin-wpa/users')->with('success', 'User berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user');
    }

    public function export()
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        if (empty($assignedWpaIds)) return redirect()->back();

        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        $allNetworkIds = [];
        foreach ($WPAs as $wpa) {
            $networkIds = $this->userModel->getNetworkIds($wpa['user_id'], 10);
            $allNetworkIds = array_merge($allNetworkIds, $networkIds);
        }
        $allNetworkIds = array_unique($allNetworkIds);

        if (empty($allNetworkIds)) return redirect()->back()->with('error', 'Tidak ada data untuk diekspor');

        $users = $this->userModel->whereIn('id', $allNetworkIds)->orderBy('created_at', 'DESC')->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_wpa_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nama', 'Email', 'Phone', 'Tanggal Daftar', 'Referral Code', 'Status']);

        foreach ($users as $u) {
            fputcsv($output, [
                $u['name'],
                $u['email'],
                $u['phone'],
                $u['created_at'],
                $u['affiliator_code'],
                $u['status']
            ]);
        }

        fclose($output);
        exit();
    }

    public function view($id)
    {
        $currentUserId = session()->get('userId');
        $assignedWpaIds = $this->assignmentModel->getWpaIdsByAdmin($currentUserId);
        
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('/admin-wpa/users')->with('error', 'User tidak ditemukan');

        // Check if this user belongs to one of the assigned WPAs
        $WPAs = $this->wpaModel->whereIn('id', $assignedWpaIds)->findAll();
        $assignedUserIds = array_column($WPAs, 'user_id');
        $assignedWpaUsers = $this->userModel->whereIn('id', $assignedUserIds)->findAll();
        $referralCodes = array_column($assignedWpaUsers, 'code_referral');

        if (!in_array($user['affiliator_code'], $referralCodes)) {
            return redirect()->to('/admin-wpa/users')->with('error', 'Akses ditolak.');
        }

        // Fetch data for view (copied from Admin\Users::view)
        $db = \Config\Database::connect();
        $kycData = $db->table('kyc_submissions')->where('user_id', $id)->get()->getRowArray();
        if (!$kycData) {
            $kycData = $db->table('user_data')->where('user_id', $id)->get()->getRowArray();
        }

        $poinModel = new \App\Models\PoinModel();
        $poinBalance = $poinModel->getUserBalance($id);
        
        $poinEarned = $poinModel->where('user_id', $id)
            ->whereIn('type', ['earn', 'bonus'])
            ->selectSum('point')
            ->first()['point'] ?? 0;

        $poinUsed = $poinModel->where('user_id', $id)
            ->whereIn('type', ['redeem', 'expired'])
            ->selectSum('point')
            ->first()['point'] ?? 0;
        $poinUsed = abs($poinUsed);

        $transaksiModel = new \App\Models\TransaksiModel();
        $totalTransaksi = $transaksiModel->where('user_id', $id)->countAllResults(false);
        $totalPembelian = $transaksiModel->where('user_id', $id)->where('status', 'confirmed')->selectSum('total')->first();

        $downlineIds = $this->userModel->getNetworkIds($id, 1); // Level 1 only for Admin WPA view?
        $downlineList = [];
        if (!empty($downlineIds)) {
            $downlineList = $this->userModel->whereIn('id', array_slice($downlineIds, 0, 100))
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }
        $downlineCount = count($downlineIds);

        $purchasedServices = $transaksiModel->getWithUser()
            ->where('transaksi.user_id', $id)
            ->whereIn('transaksi.status', ['confirmed', 'paid', 'refunded'])
            ->findAll();

        return view('admin_wpa/users/view', [
            'title' => 'Detail User - Admin WPA',
            'user' => $user,
            'kyc' => $kycData,
            'poinBalance' => $poinBalance,
            'rupiahBalance' => $user['balance'] ?? 0,
            'poinEarned' => $poinEarned,
            'poinUsed' => $poinUsed,
            'totalTransaksi' => $totalTransaksi,
            'totalPembelian' => $totalPembelian['total'] ?? 0,
            'downlineCount' => $downlineCount,
            'downlineList' => $downlineList,
            'purchasedServices' => $purchasedServices,
            'activeMenu' => 'users'
        ]);
    }
}
