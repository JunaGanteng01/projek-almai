<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KycSubmissionModel;
use App\Models\TransaksiModel;
use App\Models\PoinModel;

class Users extends BaseController
{
    protected $userModel;
    protected $kycModel;
    protected $poinModel;

    /**
     * Override Write Access for Users Module
     * Admin (Level 5) is allowed to Add/Edit Users (unlike other modules)
     */
    protected function canWriteAdmin(): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $levelId = (int)session()->get('level_id');

        // Allow Admin (5) and Super Admin (7)
        return in_array($levelId, [
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ]);
    }

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kycModel = new KycSubmissionModel();
        $this->poinModel = new PoinModel();
    }

    public function index()
    {
        $role = $this->request->getGet('role');
        $search = $this->request->getGet('search');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        $builder = $this->userModel;

        // Get current admin info
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $this->userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        // ADMIN (Level 5) - Only see users who used their referral code
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            // Filter by users who registered with this admin's referral code only
            if (!empty($currentReferralCode)) {
                $builder->where('affiliator_code', $currentReferralCode);
            } else {
                // If admin has no referral code, show empty result
                $builder->where('1', '0'); // Always false condition
            }
        }
        // SUPER ADMIN (Level 7) - See all users (no filter)

        if ($role && $role !== 'all') {
            $levelIds = \App\Models\LevelModel::getLevelIdsByRoleFilter($role);
            if ($levelIds !== null) {
                $builder->whereIn('level_id', $levelIds);
            }
        }

        // Filter by Affiliator Code - Recursive for Super Admin
        $affiliatorCode = $this->request->getGet('affiliator_code');
        if ($affiliatorCode && $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN) {
            $affiliatorCode = trim($affiliatorCode);

            if ($affiliatorCode === 'none') {
                $builder->groupStart()
                    ->where('affiliator_code', null)
                    ->orWhere('affiliator_code', '')
                ->groupEnd();
            } else {
                $rootUser = $this->userModel->groupStart()
                    ->where('code_referral', $affiliatorCode)
                    ->orWhere('referral_code', $affiliatorCode)
                    ->orWhere('LOWER(name)', strtolower($affiliatorCode))
                ->groupEnd()
                ->orderBy('CASE WHEN code_referral = ' . $this->userModel->db->escape($affiliatorCode) . ' THEN 0 ELSE 1 END', 'ASC')
                ->first();

                if ($rootUser) {
                    $collectedIds = $this->userModel->getNetworkIds($rootUser['id']);

                    if (!empty($collectedIds)) {
                        $builder->groupStart();
                        foreach (array_chunk($collectedIds, 1000) as $chunk) {
                            $builder->orWhereIn('users.id', $chunk);
                        }
                        $builder->groupEnd();
                    } else {
                        $builder->where('users.id', 0);
                    }
                } else {
                    $builder->where('affiliator_code', $affiliatorCode);
                }
            }
        }

        // Search
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        // Time Filter
        $timeFilter = $this->request->getGet('time_filter');
        if ($timeFilter) {
            $now = date('Y-m-d H:i:s');
            switch ($timeFilter) {
                case 'today':
                    $builder->where('DATE(created_at)', date('Y-m-d'));
                    break;
                case 'week':
                    $builder->where('YEARWEEK(created_at, 1)', date('YW'));
                    break;
                case 'month':
                    $builder->where('MONTH(created_at)', date('m'))
                        ->where('YEAR(created_at)', date('Y'));
                    break;
                case 'year':
                    $builder->where('YEAR(created_at)', date('Y'));
                    break;
            }
        }

        // Sort
        $sort = $this->request->getGet('sort') ?? 'newest';
        if ($sort === 'oldest') {
            $builder->orderBy('created_at', 'ASC');
        } else {
            $builder->orderBy('created_at', 'DESC');
        }

        // Get total for pagination
        $total = $builder->countAllResults(false);
        $usersList = $builder->paginate($perPage, 'default', $page);
        $pager = $this->userModel->pager;

        // Fetch referrer names for the paginate results
        if (!empty($usersList)) {
            $affCodes = [];
            foreach ($usersList as $u) {
                if (!empty($u['affiliator_code'])) {
                    $affCodes[] = $u['affiliator_code'];
                }
            }
            $affCodes = array_unique($affCodes);
            if (!empty($affCodes)) {
                $referrers = $this->userModel->select('code_referral, name')
                    ->whereIn('code_referral', $affCodes)
                    ->findAll();
                $referrerMap = [];
                foreach ($referrers as $ref) {
                    $referrerMap[$ref['code_referral']] = $ref['name'];
                }
                foreach ($usersList as &$u) {
                    $u['referrer_name'] = $referrerMap[$u['affiliator_code']] ?? null;
                }
                unset($u);
            }
        }

        // Get Affiliator Stats for Filter Dropdown (Super Admin only)
        $affiliatorStats = [];
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN) {
            $distinctCodes = $this->userModel
                ->select('affiliator_code, COUNT(id) as total_usage')
                ->where('affiliator_code IS NOT NULL')
                ->where('affiliator_code !=', '')
                ->groupBy('affiliator_code')
                ->findAll();

            // Combine totals for groups
            $groupTotals = [];
            $codeToGroup = [];
            $allAffiliators = $this->userModel->where('code_referral IS NOT NULL')->where('code_referral !=', '')->findAll();
            
            foreach ($allAffiliators as $a) {
                if (!empty($a['referral_group_id'])) {
                    $codeToGroup[$a['code_referral']] = $a['referral_group_id'];
                }
            }
            
            foreach ($distinctCodes as $dc) {
                $code = $dc['affiliator_code'];
                $usage = $dc['total_usage'];
                if (isset($codeToGroup[$code])) {
                    $groupId = $codeToGroup[$code];
                    $groupTotals[$groupId] = ($groupTotals[$groupId] ?? 0) + $usage;
                }
            }

            foreach ($distinctCodes as &$dc) {
                $code = $dc['affiliator_code'];
                if (isset($codeToGroup[$code])) {
                    $groupId = $codeToGroup[$code];
                    $dc['total_usage'] = $groupTotals[$groupId];
                }
            }
            
            // Sort again by new total_usage descending
            usort($distinctCodes, function($a, $b) {
                return $b['total_usage'] <=> $a['total_usage'];
            });
            
            // Slice top 200
            $distinctCodes = array_slice($distinctCodes, 0, 200);

            foreach ($distinctCodes as $codeItem) {
                $affiliatorStats[] = [
                    'affiliator_code' => $codeItem['affiliator_code'],
                    'total_usage' => $codeItem['total_usage']
                ];
            }
        }

        // Count by level group - ONLY with Admin filter (no role/search/time filters)
        // This shows total count for each category across ALL users with admin's referral

        // Admin: Admin, Accounting, Super Admin
        $adminCountBuilder = $this->userModel;
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $adminCountBuilder = $adminCountBuilder->where('affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $adminCountBuilder = $adminCountBuilder->where('1', '0');
        }
        $totalAdmin = (clone $adminCountBuilder)->whereIn('level_id', [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_ACCOUNTING, \App\Models\LevelModel::LEVEL_SUPER_ADMIN])->countAllResults();

        // WPA: WPA
        $wpaCountBuilder = $this->userModel;
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $wpaCountBuilder = $wpaCountBuilder->where('affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $wpaCountBuilder = $wpaCountBuilder->where('1', '0');
        }
        $totalWpa = (clone $wpaCountBuilder)->where('level_id', \App\Models\LevelModel::LEVEL_WPA)->countAllResults();

        // CWPA: CWPA
        $cwpaCountBuilder = $this->userModel;
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $cwpaCountBuilder = $cwpaCountBuilder->where('affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $cwpaCountBuilder = $cwpaCountBuilder->where('1', '0');
        }
        $totalCwpa = (clone $cwpaCountBuilder)->where('level_id', \App\Models\LevelModel::LEVEL_CWPA)->countAllResults();

        // User PRO: User PRO only
        $proCountBuilder = $this->userModel;
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $proCountBuilder = $proCountBuilder->where('affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $proCountBuilder = $proCountBuilder->where('1', '0');
        }
        $totalUserPro = (clone $proCountBuilder)->whereIn('level_id', [\App\Models\LevelModel::LEVEL_PRO])->countAllResults();

        // User: Standard User only
        $userCountBuilder = $this->userModel;
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $userCountBuilder = $userCountBuilder->where('affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $userCountBuilder = $userCountBuilder->where('1', '0');
        }
        $totalUser = (clone $userCountBuilder)->where('level_id', \App\Models\LevelModel::LEVEL_USER)->countAllResults();

        // Grand Total: All visible users
        $grandTotalBuilder = $this->userModel;
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $grandTotalBuilder = $grandTotalBuilder->where('affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $grandTotalBuilder = $grandTotalBuilder->where('1', '0');
        }
        $grandTotal = (clone $grandTotalBuilder)->countAllResults();

        // Direct: No affiliator code
        $directCountBuilder = clone $grandTotalBuilder;
        // Important: grandTotalBuilder already has the admin filter.
        // If admin filter is applied (where('affiliator_code', ...)), then 'Direct' (where code is null) might conflict or return 0. 
        // Logic: 
        // - If Super Admin: Sees everyone. Direct = code is null/empty.
        // - If Admin: Sees only their downlines (code = their code). So they have NO 'Direct' users (orphans). 
        //   However, if 'Direct' means "Directly referred by ME (Admin)", then it's `referred_by` = Me? 
        //   But usually 'Direct' implies Organic traffic. 
        //   Let's stick to strict interpretation: Direct = Organic (No code).
        //   If Admin filters by "Must have my code", then Direct count will be 0. This is correct behavior for Admin. Super Admin will see Organic users.
        $totalDirect = (clone $directCountBuilder)->groupStart()->where('affiliator_code', null)->orWhere('affiliator_code', '')->groupEnd()->countAllResults();

        // SPI: Affiliator code = 'SPI'
        $spiCountBuilder = clone $grandTotalBuilder;
        $totalSpi = (clone $spiCountBuilder)->where('affiliator_code', 'SPI')->countAllResults();

        // Kampus: Affiliator code = 'Kampus'
        $kampusCountBuilder = clone $grandTotalBuilder;
        $totalKampus = (clone $kampusCountBuilder)->where('affiliator_code', 'Kampus')->countAllResults();

        $keuanganCountBuilder = clone $grandTotalBuilder;
        $totalKeuangan = (clone $keuanganCountBuilder)->where('level_id', \App\Models\LevelModel::LEVEL_ACCOUNTING)->countAllResults();

        // Calculate Total Confirmed Transactions (Pembelian)
        // Uses the same filter builder as $grandTotalBuilder to respect Admin/SuperAdmin/Referral constraints
        $transaksiModel = new TransaksiModel();

        // Use user IDs from grandTotalBuilder to filter transactions
        // Note: This might be heavy if user list is huge. 
        // Optimization: If Admin, filter by affiliator_code join? Or subquery.
        // For now, let's keep it simple: Filter by confirmed status.
        // BUT, we need to respect the "Admin View" (only my downlines).

        $amountBuilder = $transaksiModel->where('transaksi.status', 'confirmed');

        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            // Join users to filter by affiliator code
            $amountBuilder->join('users', 'users.id = transaksi.user_id')
                ->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            // Admin with no code sees nothing
            $amountBuilder->where('1', '0');
        }


        $totalConfirmedTransactions = $amountBuilder->countAllResults();

        return view('admin/users/index', [
            'title' => 'Kelola Users - Admin Dashboard',
            'usersList' => $usersList,
            'pager' => $pager,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'grandTotal' => $grandTotal, // New
            'totalDirect' => $totalDirect, // New
            'totalSpi' => $totalSpi, // New
            'totalKampus' => $totalKampus, // New
            'totalKeuangan' => $totalKeuangan, // New
            'totalAdmin' => $totalAdmin,
            'totalWpa' => $totalWpa,
            'totalCwpa' => $totalCwpa,
            'totalUser' => $totalUser,
            'totalUserPro' => $totalUserPro,
            'totalConfirmedTransactions' => $totalConfirmedTransactions, // Updated to count
            'affiliatorStats' => $affiliatorStats,
            'currentRole' => $role,
            'currentAffiliator' => $affiliatorCode,
            'currentSearch' => $search,
            'currentTimeFilter' => $timeFilter,
            'currentSort' => $sort,
            'activeMenu' => 'users',
            'canWrite' => $this->canWriteAdmin(),
            'isAdmin' => $currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN,
            'isSuperAdmin' => $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN,
            'currentReferralCode' => $currentReferralCode // For debugging
        ]);
    }

    public function create()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menambah user.');
        }

        $currentLevelId = (int)session()->get('level_id');
        $currentUserId = session()->get('userId');
        $isSuperAdmin = $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN;

        // Both Super Admin and Regular Admin can choose any affiliator freely
        $affiliators = $this->userModel->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_WPA,
            \App\Models\LevelModel::LEVEL_CWPA,
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ])
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('admin/users/create', [
            'title' => 'Tambah User - Admin Dashboard',
            'activeMenu' => 'users',
            'affiliators' => $affiliators,
            'defaultRole' => 'user',
            'isRoleReadOnly' => !$isSuperAdmin, // Only Super Admin can change role
            'defaultAffiliatorId' => null, // No default, admin can choose freely
            'isAffiliatorReadOnly' => false // Both admin and super admin can choose
        ]);
    }

    public function store()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menambah user.');
        }

        // Basic validation rules
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone' => 'required|min_length[10]',
            'role' => 'required|in_list[admin,cwpa,wpa,user,admin-wpa,admin-partnership,accounting]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if email already exists
        $email = $this->request->getPost('email');
        $existingEmail = $this->userModel->where('email', $email)->first();
        if ($existingEmail) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'Email "' . $email . '" sudah terdaftar di database. Silakan gunakan email lain.'
            ]);
        }

        // Check if phone already exists
        $phone = $this->request->getPost('phone');
        $existingPhone = $this->userModel->where('phone', $phone)->first();
        if ($existingPhone) {
            return redirect()->back()->withInput()->with('errors', [
                'phone' => 'Nomor WhatsApp "' . $phone . '" sudah terdaftar di database. Silakan gunakan nomor lain.'
            ]);
        }

        // Map role string to level_id
        $role = $this->request->getPost('role');
        $levelId = \App\Models\LevelModel::levelFromRoleString($role);

        // Generate 6-digit OTP as password
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'password' => $otp, // OTP sebagai password
            'level_id' => $levelId,
            'status' => $this->request->getPost('status') ?: 'active',
        ];

        // Handle Affiliator Assignment
        $affiliatorId = $this->request->getPost('affiliator_id');
        if ($affiliatorId) {
            $affiliator = $this->userModel->find($affiliatorId);
            if ($affiliator) {
                // Set affiliator_code based on Affiliator's name (or code_referral if present, but name requested)
                // "kode reff nya sesuai nama afilator nya" -> implies strictly Name or Code matching Name.
                // We prefer code_referral if it exists as it is unique. If not, fallback to Name.
                $data['affiliator_code'] = !empty($affiliator['code_referral']) ? $affiliator['code_referral'] : $affiliator['name'];
                $data['referred_by'] = $affiliator['id'];
            }
        }

        $userId = $this->userModel->insert($data);

        if ($userId) {
            \App\Models\AuditLogModel::record('Tambah User Baru', 'users', $userId, $data);
            // Get program name from affiliator for notifications
            $programName = 'WPA'; // Default
            if (isset($affiliator) && $affiliator) {
                // Use affiliator's name as program name
                $programName = $affiliator['name'];
            }

            // Send WhatsApp OTP (Password)
            if (!empty($data['phone'])) {
                try {
                    $ivosights = new \App\Libraries\IvosightsService();

                    // 1. Send OTP as password notification
                    $ivosights->sendOtp($data['phone'], $otp);
                    log_message('info', 'WhatsApp OTP sent for new user: ' . $data['email']);

                    // Small delay to ensure messages arrive in order
                    usleep(500000); // 0.5 second delay

                    // 2. Send account created notification with program name
                    $ivosights->sendAccountCreatedNotification($data['phone'], $data['name'], $programName);
                    log_message('info', 'WhatsApp account notification sent for new user: ' . $data['email']);
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to send WA notifications for new user: ' . $e->getMessage());
                }
            }

            // Send Email with Credentials (OTP as password)
            try {
                $emailService = new \App\Libraries\EmailService();
                $result = $emailService->sendAccountCredentials($data['email'], $data['name'], $otp, $programName);

                if ($result['success']) {
                    log_message('info', 'Email credentials sent for new user: ' . $data['email']);
                } else {
                    log_message('error', 'Failed to send email credentials: ' . ($result['message'] ?? 'Unknown error'));
                }
            } catch (\Throwable $e) {
                log_message('error', 'Failed to send email for new user: ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan! Kode OTP telah dikirim via WA dan Email sebagai password.');
    }

    public function view($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        // Fetch full KYC submission data using raw query for maximum precision
        $db = \Config\Database::connect();

        // Try kyc_submissions first
        $kycData = $db->table('kyc_submissions')->where('user_id', $id)->get()->getRowArray();

        if (!$kycData) {
            // Try user_data fallback
            $kycData = $db->table('user_data')->where('user_id', $id)->get()->getRowArray();
        }

        // Get poin balance from poin table
        $poinBalance = $this->poinModel->getUserBalance($id);

        // Get poin stats
        // Get poin stats
        // Note: In new schema, 'earn' and 'bonus' are positive points, 'redeem' and 'expired' are negative.
        // We sum specific types for display.
        $poinEarned = $this->poinModel->where('user_id', $id)
            ->whereIn('type', ['earn', 'bonus'])
            ->selectSum('point')
            ->first()['point'] ?? 0;

        $poinUsed = $this->poinModel->where('user_id', $id)
            ->whereIn('type', ['redeem', 'expired'])
            ->selectSum('point')
            ->first()['point'] ?? 0;

        // Convert negative used points to positive for display
        $poinUsed = abs($poinUsed);

        // Get transaction stats
        $transaksiModel = new TransaksiModel();
        $totalTransaksi = $transaksiModel->where('user_id', $id)->countAllResults(false);
        $totalPembelian = $transaksiModel->where('user_id', $id)->where('status', 'confirmed')->selectSum('total')->first();

        // Get downline data - RECURSIVE (Multi-level)
        $downlineIds = $this->userModel->getNetworkIds($id, 10);
        $downlineList = [];
        if (!empty($downlineIds)) {
            // Limit to 1000 records for display performance safety
            $downlineList = $this->userModel->whereIn('id', array_slice($downlineIds, 0, 1000))
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }
        $downlineCount = count($downlineIds);

        // Get rupiah balance from users.balance column
        $rupiahBalance = $user['balance'] ?? 0;

        // Get user transactions (purchased services)
        $purchasedServices = $transaksiModel->getWithUser()
            ->where('transaksi.user_id', $id)
            ->whereIn('transaksi.status', ['confirmed', 'paid', 'refunded'])
            ->findAll();

        return view('admin/users/view', [
            'title' => 'Detail User - Admin',
            'user' => $user,
            'kyc' => $kycData,
            'poinBalance' => $poinBalance,
            'rupiahBalance' => $rupiahBalance,
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

    public function edit($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit user.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        // Infer role from level_id for the form
        if (!isset($user['role'])) {
            $user['role'] = \App\Models\LevelModel::roleStringFromLevel((int) $user['level_id']);
        }

        return view('admin/users/edit', [
            'title' => 'Edit User - Admin Dashboard',
            'user' => $user,
            'activeMenu' => 'users'
        ]);
    }

    public function update($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit user.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'phone' => 'required|min_length[10]',
        ];

        // Check email uniqueness only if changed
        if ($this->request->getPost('email') !== $user['email']) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        }

        // Check code_referral uniqueness if provided and changed
        $inputCodeReferral = $this->request->getPost('code_referral');
        if (!empty($inputCodeReferral) && $inputCodeReferral !== $user['code_referral']) {
            $rules['code_referral'] = 'is_unique[users.code_referral]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle Roles (Multi-Role)
        $roles = $this->request->getPost('roles') ?? [];
        if (!is_array($roles)) $roles = [];

        $selectedIds = [];
        $isProSelected = false;

        foreach ($roles as $r) {
            if ($r === 'user_pro') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_PRO;
                $isProSelected = true;
            } elseif ($r === 'cwpa') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_CWPA;
            } elseif ($r === 'wpa') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_WPA;
            } elseif ($r === 'admin') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_ADMIN;
            } elseif ($r === 'admin-wpa') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_ADMIN_WPA;
            } elseif ($r === 'admin-partnership') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_PARTNERSHIP;
            } elseif ($r === 'accounting') {
                $selectedIds[] = \App\Models\LevelModel::LEVEL_ACCOUNTING;
            }
        }

        // Ensure unique and sort descending to prioritize highest level (Primary Role)
        // Example: Selected [Admin(5), CWPA(3)] -> Sorted [5, 3] -> Primary: 5, Secondary: [3]
        $selectedIds = array_unique($selectedIds);
        rsort($selectedIds);

        // Highest ID becomes the main level_id (for login permissions)
        $primaryLevelId = !empty($selectedIds) ? $selectedIds[0] : \App\Models\LevelModel::LEVEL_USER;

        // Remaining IDs become secondary roles
        $secondaryIds = array_slice($selectedIds, 1);

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'code_referral' => $this->request->getPost('code_referral') ?: null,
            'affiliator_code' => $this->request->getPost('affiliator_code') ?: null,
            'level_id' => $primaryLevelId,
            'secondary_level_ids' => !empty($secondaryIds) ? json_encode($secondaryIds) : null,
            'status' => $this->request->getPost('status'),
            'is_pro' => $isProSelected ? 1 : 0
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = $password;
        }

        $this->userModel->update($id, $data);
        \App\Models\AuditLogModel::record('Update User', 'users', $id, ['old' => $user, 'new' => $data]);

        return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate!');
    }

    public function delete($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus user.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        // Prevent deleting own account
        if ($user['id'] == session()->get('userId')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        // Prevent deleting admin
        if (in_array($user['level_id'], [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_SUPER_ADMIN])) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus akun admin');
        }

        // Prevent deleting User PRO
        if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus User PRO');
        }

        // Check if user has completed transactions (optional safety)
        // For now, we allow full delete including transactions as requested by "Delete" action.

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Disable foreign key checks to force cleanup
            $db->query('SET FOREIGN_KEY_CHECKS=0');
            log_message('debug', 'Foreign key checks disabled for user deletion.');

            // Unlink referrals (update downlines to have no referrer)
            try {
                $db->table('users')->where('referred_by', $id)->update(['referred_by' => null]);
                log_message('debug', "Unlinked referrals for user ID: $id.");
            } catch (\Throwable $t) {
                log_message('error', 'Failed to unlink referrals for user ID: ' . $id . ' - ' . $t->getMessage());
            }

            // List of tables to delete related data from
            $tablesToDelete = [
                'user_data',
                'kyc_submissions',
                'points', // Correct table name
                'transaksi',
                // 'user_layanan', // Table does not exist, kept commented out
                'wpa',
                'cwpa',
                'certificates',
                'ea_licenses',
                'merchandise_redemptions',
                'notifications',
                'otp',
                'password_resets', // Handled specially by email
                'voucher_usage',
                'withdrawals',
                'chat_leads', // Handled specially by phone
                'ulasan',
                'layanan_ulasan',
                'poin_sharing', // Handled specially by sender/receiver ID
                'artikel_purchases',
                'layanan_completions'
            ];

            foreach ($tablesToDelete as $table) {
                try {
                    // Skip if table does not exist
                    if (!$db->tableExists($table)) {
                        continue;
                    }

                    if ($table === 'password_resets') {
                        if ($db->fieldExists('email', $table)) {
                            $db->table($table)->where('email', $user['email'])->delete();
                            log_message('debug', "Deleted from $table for user email: " . $user['email']);
                        }
                    } elseif ($table === 'chat_leads') {
                        if ($db->fieldExists('whatsapp', $table)) {
                            $db->table($table)->where('whatsapp', $user['phone'])->delete();
                            log_message('debug', "Deleted from $table for user phone: " . $user['phone']);
                        }
                    } elseif ($table === 'poin_sharing') {
                        if ($db->fieldExists('sender_id', $table) && $db->fieldExists('receiver_id', $table)) {
                            $db->table($table)->groupStart()->where('sender_id', $id)->orWhere('receiver_id', $id)->groupEnd()->delete();
                            log_message('debug', "Deleted from $table for user ID: $id (sender/receiver).");
                        }
                    } else {
                        // Standard user_id delete
                        if ($db->fieldExists('user_id', $table)) {
                            $db->table($table)->where('user_id', $id)->delete();
                            log_message('debug', "Deleted from $table for user ID: $id.");
                        } else {
                            log_message('debug', "Skipped table $table: 'user_id' column not found.");
                        }
                    }
                } catch (\Throwable $t) {
                    // Log the error but continue with other deletions if possible
                    log_message('warning', "Cleanup failed for table '$table' for user ID: $id - " . $t->getMessage());
                }
            }

            // Delete the user itself
            $db->table('users')->where('id', $id)->delete();
            \App\Models\AuditLogModel::record('Hapus User', 'users', $id, $user);
            log_message('debug', "Deleted user ID: $id from 'users' table.");

            // Re-enable foreign key checks
            $db->query('SET FOREIGN_KEY_CHECKS=1');
            log_message('debug', 'Foreign key checks re-enabled.');

            $db->transComplete();

            if ($db->transStatus() === false) {
                // Get the last error
                $error = $db->error();
                return redirect()->to('/admin/users')->with('error', 'Gagal menghapus user: ' . ($error['message'] ?? 'Unknown database error'));
            }
        } catch (\Throwable $e) {
            // Ensure checks are re-enabled if error occurs
            $db->query('SET FOREIGN_KEY_CHECKS=1');
            $db->transRollback();
            // Log error
            log_message('error', 'User delete failed: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }

        return redirect()->to('/admin/users')->with('success', 'User dan data terkait berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengubah status user.');
        }

        $user = $this->userModel->find($id) ?: (is_numeric($id) ? $this->userModel->find($id) : null);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        $newStatus = ($user['status'] == 'active' || $user['status'] == 1) ? 'inactive' : 'active';
        $this->userModel->update($id, ['status' => $newStatus]);
        \App\Models\AuditLogModel::record('Ubah Status User', 'users', $id, ['old_status' => $user['status'], 'new_status' => $newStatus]);

        return redirect()->to('/admin/users')->with('success', 'Status user berhasil diubah!');
    }

    public function downloadAgreement($transactionId, $type)
    {
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->find($transactionId);

        if (!$transaksi || !in_array($transaksi['status'], ['confirmed', 'paid'])) {
            return redirect()->back()->with('error', 'Transaksi tidak valid atau belum lunas.');
        }

        $user = $this->userModel->find($transaksi['user_id']);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Get layanan info
        $layananModel = new \App\Models\LayananModel();
        $layanan = $layananModel->find($transaksi['layanan_id']);

        $pdfService = new \App\Libraries\LegalDocumentPdfService();

        if ($type === 'perjanjian') {
            $pdfContent = $pdfService->generatePerjanjianPdf($transaksi, $user, $layanan);
            $filename = 'Perjanjian_' . $transaksi['invoice_number'] . '.pdf';
        } else {
            $pdfContent = $pdfService->generateRisikoPdf($transaksi, $user, $layanan);
            $filename = 'Risiko_' . $transaksi['invoice_number'] . '.pdf';
        }

        if (!$pdfContent) {
            return redirect()->back()->with('error', 'Gagal menghasilkan PDF.');
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($pdfContent);
    }
    public function batchAffiliator()
    {
        // Must be Super Admin
        if (session()->get('level_id') != \App\Models\LevelModel::LEVEL_SUPER_ADMIN) {
            return redirect()->to('/admin/users')->with('error', 'Fitur ini hanya untuk Superadmin.');
        }

        $search = $this->request->getGet('search');
        
        $builder = $this->userModel
            ->groupStart()
                ->where('affiliator_code', null)
                ->orWhere('affiliator_code', '')
            ->groupEnd()
            ->where('level_id', \App\Models\LevelModel::LEVEL_USER); // Only standard users

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        $users = $builder->orderBy('created_at', 'DESC')->findAll();

        // Get list of potential affiliators (WPA, CWPA, Admin)
        $affiliators = $this->userModel->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_WPA,
            \App\Models\LevelModel::LEVEL_CWPA,
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ])->orderBy('name', 'ASC')->findAll();

        return view('admin/users/batch_affiliator', [
            'title' => 'Ubah Affiliator - Superadmin',
            'activeMenu' => 'users',
            'users' => $users,
            'affiliators' => $affiliators,
            'search' => $search
        ]);
    }

    public function processBatchAffiliator()
    {
        if (session()->get('level_id') != \App\Models\LevelModel::LEVEL_SUPER_ADMIN) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $userId = $this->request->getPost('user_id');
        $affiliatorCode = $this->request->getPost('affiliator_code');

        if (!$userId || !$affiliatorCode) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap.']);
        }

        // Validate user existence and current status (must be unaffiliated)
        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan.']);
        }

        // Prepare update data
        $updateData = [
            'affiliator_code' => $affiliatorCode
        ];

        // Try to find the root user for this code to secondary fill referred_by
        $rootUser = $this->userModel->groupStart()
            ->where('code_referral', $affiliatorCode)
            ->orWhere('referral_code', $affiliatorCode)
            ->orWhere('name', $affiliatorCode)
            ->groupEnd()
            ->orderBy('CASE WHEN code_referral = ' . $this->userModel->db->escape($affiliatorCode) . ' THEN 0 ELSE 1 END', 'ASC')
            ->first();
        
        if ($rootUser) {
            $updateData['referred_by'] = $rootUser['id'];
        }

        if ($this->userModel->update($userId, $updateData)) {
            \App\Models\AuditLogModel::record('Update Affiliator Manual', 'users', $userId, [
                'old_code' => $user['affiliator_code'],
                'new_code' => $affiliatorCode
            ]);
            return $this->response->setJSON(['success' => true, 'message' => 'Affiliator berhasil diupdate.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal update database.']);
    }
    public function mergeReferrals()
    {
        $userIds = $this->request->getPost('user_ids');
        
        if (empty($userIds) || !is_array($userIds) || count($userIds) < 2) {
            return redirect()->back()->with('error', 'Pilih minimal 2 user untuk digabungkan referralnya.');
        }

        $db = \Config\Database::connect();
        
        $db->transStart();
        
        $db->table('referral_groups')->insert(['created_at' => date('Y-m-d H:i:s')]);
        $groupId = $db->insertID();

        $userModel = new \App\Models\UserModel();
        foreach ($userIds as $userId) {
            $userModel->update($userId, ['referral_group_id' => $groupId]);
        }
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menggabungkan referral.');
        }

        return redirect()->back()->with('success', 'Referral berhasil digabungkan.');
    }
}
