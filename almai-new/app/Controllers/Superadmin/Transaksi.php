<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $productType = $this->request->getGet('product_type');
        $productName = $this->request->getGet('product_name');
        $creator = $this->request->getGet('creator'); 

        // Get current admin info
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $this->userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        $builder = $this->transaksiModel->getWithUser();

        // Admin Filter
        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $builder->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $builder->where('1', '0');
        }

        if ($status && $status !== 'all') {
            $builder->where('transaksi.status', $status);
        }

        if ($productType && $productType !== 'all') {
            $builder->where('transaksi.product_type', $productType);
        }

        if ($productName && $productName !== 'all') {
            $builder->where('transaksi.product_name', $productName);
        }

        if ($creator && $creator !== 'all') {
            $parts = explode(':', $creator);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                if ($type === 'wpa') {
                    $builder->groupStart()
                        ->where('layanan.wpa_id', $id)
                        ->orWhere('layanan_event.wpa_id', $id)
                        ->orWhere('layanan_tools.wpa_id', $id)
                        ->orWhere('layanan_subscription.wpa_id', $id)
                    ->groupEnd();
                } elseif ($type === 'cwpa') {
                    $builder->groupStart()
                        ->where('layanan.cwpa_id', $id)
                        ->orWhere('layanan_event.cwpa_id', $id)
                        ->orWhere('layanan_tools.cwpa_id', $id)
                        ->orWhere('layanan_subscription.cwpa_id', $id)
                    ->groupEnd();
                }
            }
        }

        if ($search) {
            $builder->groupStart()
                ->like('transaksi.invoice_number', $search)
                ->orLike('transaksi.product_name', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        if ($dateFrom) {
            $builder->where('transaksi.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('transaksi.created_at <=', $dateTo . ' 23:59:59');
        }

        $transaksiList = $builder->paginate(20);
        $pager = $this->transaksiModel->pager;

        $db = \Config\Database::connect();
        $statsWhere = "WHERE 1=1";
        $statsJoin = "";

        if ($creator && $creator !== 'all') {
            $parts = explode(':', $creator);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                $statsJoin .= " LEFT JOIN layanan ON layanan.id = transaksi.layanan_id ";
                $statsJoin .= " LEFT JOIN layanan_event ON layanan_event.id = transaksi.layanan_id ";
                $statsJoin .= " LEFT JOIN layanan_tools ON layanan_tools.id = transaksi.layanan_id ";
                $statsJoin .= " LEFT JOIN layanan_subscription ON layanan_subscription.id = transaksi.layanan_id ";
                
                $idEscaped = $db->escape($id);
                if ($type === 'wpa') {
                    $statsWhere .= " AND (layanan.wpa_id = $idEscaped OR layanan_event.wpa_id = $idEscaped OR layanan_tools.wpa_id = $idEscaped OR layanan_subscription.wpa_id = $idEscaped)";
                } elseif ($type === 'cwpa') {
                    $statsWhere .= " AND (layanan.cwpa_id = $idEscaped OR layanan_event.cwpa_id = $idEscaped OR layanan_tools.cwpa_id = $idEscaped OR layanan_subscription.cwpa_id = $idEscaped)";
                }
            }
        }

        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $statsJoin .= " JOIN users ON users.id = transaksi.user_id ";
            $statsWhere .= " AND users.affiliator_code = " . $db->escape($currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $statsWhere = "WHERE 1=0";
        }

        $statsQuery = $db->query("
            SELECT 
                SUM(CASE WHEN transaksi.status = 'pending' THEN 1 ELSE 0 END) as total_pending,
                SUM(CASE WHEN transaksi.status = 'paid' THEN 1 ELSE 0 END) as total_paid,
                SUM(CASE WHEN transaksi.status = 'confirmed' THEN 1 ELSE 0 END) as total_confirmed,
                SUM(CASE WHEN transaksi.status = 'refunded' THEN 1 ELSE 0 END) as total_refunded,
                SUM(CASE WHEN transaksi.status = 'confirmed' AND transaksi.payment_method != 'poin' THEN transaksi.total ELSE 0 END) as total_revenue,
                SUM(CASE WHEN transaksi.status = 'confirmed' AND transaksi.payment_method != 'poin' AND DATE(transaksi.created_at) = CURDATE() THEN transaksi.total ELSE 0 END) as income_today,
                SUM(CASE WHEN transaksi.status = 'confirmed' AND transaksi.payment_method != 'poin' AND YEARWEEK(transaksi.created_at, 1) = YEARWEEK(CURDATE(), 1) THEN transaksi.total ELSE 0 END) as income_week,
                SUM(CASE WHEN transaksi.status = 'confirmed' AND transaksi.payment_method != 'poin' AND MONTH(transaksi.created_at) = MONTH(CURDATE()) AND YEAR(transaksi.created_at) = YEAR(CURDATE()) THEN transaksi.total ELSE 0 END) as income_month,
                SUM(CASE WHEN transaksi.status = 'confirmed' AND transaksi.payment_method != 'poin' AND YEAR(transaksi.created_at) = YEAR(CURDATE()) THEN transaksi.total ELSE 0 END) as income_year
            FROM transaksi
            $statsJoin
            $statsWhere
        ")->getRow();

        $totalPending = $statsQuery->total_pending ?? 0;
        $totalPaid = $statsQuery->total_paid ?? 0;
        $totalConfirmed = $statsQuery->total_confirmed ?? 0;
        $totalRefunded = $statsQuery->total_refunded ?? 0;
        $totalRevenue = $statsQuery->total_revenue ?? 0;
        $incomeToday = $statsQuery->income_today ?? 0;
        $incomeWeek = $statsQuery->income_week ?? 0;
        $incomeMonth = $statsQuery->income_month ?? 0;
        $incomeYear = $statsQuery->income_year ?? 0;

        // Get product types for filter
        $productTypes = $db->query("SELECT DISTINCT product_type FROM transaksi WHERE product_type IS NOT NULL ORDER BY product_type")->getResultArray();

        // Get unique products (layanan) for filter
        $productsList = $db->query("SELECT DISTINCT product_name FROM transaksi WHERE product_name IS NOT NULL ORDER BY product_name")->getResultArray();

        // Get WPAs and CWPAs for creator filter
        $wpaList = $db->table('wpa')->select('id, name')->where('status', 'active')->orderBy('name', 'ASC')->get()->getResultArray();
        $cwpaList = $db->table('cwpa')->select('id, name')->where('status', 'active')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('superadmin/transaksi/index', [
            'title' => 'Transaksi - Admin Dashboard',
            'transaksiList' => $transaksiList,
            'pager' => $pager,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
            'totalConfirmed' => $totalConfirmed,
            'totalRefunded' => $totalRefunded,
            'totalRevenue' => $totalRevenue,
            'incomeToday' => $incomeToday,
            'incomeWeek' => $incomeWeek,
            'incomeMonth' => $incomeMonth,
            'incomeYear' => $incomeYear,
            'currentStatus' => $status,
            'currentSearch' => $search,
            'currentProductType' => $productType,
            'currentProductName' => $productName,
            'currentCreator' => $creator,
            'productTypes' => $productTypes,
            'productsList' => $productsList,
            'wpaList' => $wpaList,
            'cwpaList' => $cwpaList,
            'isSuperAdmin' => ($currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'activeMenu' => 'transaksi',
            'canWrite' => $this->canWriteAdmin()
        ]);
    }

    /**
     * Export transaksi to Excel
     */
    public function exportExcel()
    {
        $status = $this->request->getGet('status');
        $productType = $this->request->getGet('product_type');
        $productName = $this->request->getGet('product_name');
        $creator = $this->request->getGet('creator');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->transaksiModel->getWithUser();

        // Admin Filter
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $this->userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $builder->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $builder->where('1', '0');
        }

        if ($status && $status !== 'all') {
            $builder->where('transaksi.status', $status);
        }
        if ($productType && $productType !== 'all') {
            $builder->where('transaksi.product_type', $productType);
        }
        if ($productName && $productName !== 'all') {
            $builder->where('transaksi.product_name', $productName);
        }
        if ($creator && $creator !== 'all') {
            $parts = explode(':', $creator);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                if ($type === 'wpa') {
                    $builder->groupStart()
                        ->where('layanan.wpa_id', $id)
                        ->orWhere('layanan_event.wpa_id', $id)
                        ->orWhere('layanan_tools.wpa_id', $id)
                        ->orWhere('layanan_subscription.wpa_id', $id)
                    ->groupEnd();
                } elseif ($type === 'cwpa') {
                    $builder->groupStart()
                        ->where('layanan.cwpa_id', $id)
                        ->orWhere('layanan_event.cwpa_id', $id)
                        ->orWhere('layanan_tools.cwpa_id', $id)
                        ->orWhere('layanan_subscription.cwpa_id', $id)
                    ->groupEnd();
                }
            }
        }
        if ($dateFrom) {
            $builder->where('transaksi.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('transaksi.created_at <=', $dateTo . ' 23:59:59');
        }

        $data = $builder->orderBy('transaksi.created_at', 'DESC')->findAll();

        // Generate CSV (Excel compatible)
        $filename = 'transaksi_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // BOM for Excel UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header
        fputcsv($output, ['No', 'Invoice', 'Tanggal', 'User', 'Email', 'Produk', 'Tipe', 'Harga', 'Diskon', 'Total', 'Metode Bayar', 'Status'], ';');

        // Data
        $no = 1;
        foreach ($data as $row) {
            fputcsv($output, [
                $no++,
                $row['invoice_number'],
                date('d/m/Y H:i', strtotime($row['created_at'])),
                $row['user_name'] ?? '-',
                $row['user_email'] ?? '-',
                $row['product_name'],
                ucfirst($row['product_type']),
                $row['amount'],
                $row['discount'],
                $row['total'],
                ucfirst($row['payment_method']),
                ucfirst($row['status'])
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * Export transaksi to PDF
     */
    public function exportPdf()
    {
        $status = $this->request->getGet('status');
        $productType = $this->request->getGet('product_type');
        $productName = $this->request->getGet('product_name');
        $creator = $this->request->getGet('creator');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->transaksiModel->getWithUser();

        // Admin Filter
        $currentUserId = session()->get('userId');
        $currentLevelId = session()->get('level_id');
        $currentUser = $this->userModel->find($currentUserId);
        $currentReferralCode = $currentUser['code_referral'] ?? null;

        if ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN && !empty($currentReferralCode)) {
            $builder->where('users.affiliator_code', $currentReferralCode);
        } elseif ($currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN) {
            $builder->where('1', '0');
        }

        if ($status && $status !== 'all') {
            $builder->where('transaksi.status', $status);
        }
        if ($productType && $productType !== 'all') {
            $builder->where('transaksi.product_type', $productType);
        }
        if ($productName && $productName !== 'all') {
            $builder->where('transaksi.product_name', $productName);
        }
        if ($creator && $creator !== 'all') {
            $parts = explode(':', $creator);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                if ($type === 'wpa') {
                    $builder->groupStart()
                        ->where('layanan.wpa_id', $id)
                        ->orWhere('layanan_event.wpa_id', $id)
                        ->orWhere('layanan_tools.wpa_id', $id)
                        ->orWhere('layanan_subscription.wpa_id', $id)
                    ->groupEnd();
                } elseif ($type === 'cwpa') {
                    $builder->groupStart()
                        ->where('layanan.cwpa_id', $id)
                        ->orWhere('layanan_event.cwpa_id', $id)
                        ->orWhere('layanan_tools.cwpa_id', $id)
                        ->orWhere('layanan_subscription.cwpa_id', $id)
                    ->groupEnd();
                }
            }
        }
        if ($dateFrom) {
            $builder->where('transaksi.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('transaksi.created_at <=', $dateTo . ' 23:59:59');
        }

        $data = $builder->orderBy('transaksi.created_at', 'DESC')->findAll();

        // Calculate totals (exclude points from Rupiah sum)
        $totalAmount = array_sum(array_filter(array_column($data, 'total'), function($val, $key) use ($data) {
            return ($data[$key]['payment_method'] ?? '') !== 'poin';
        }, ARRAY_FILTER_USE_BOTH));
        $totalConfirmed = count(array_filter($data, fn($r) => $r['status'] === 'confirmed'));

        return view('superadmin/transaksi/export-pdf', [
            'data' => $data,
            'totalAmount' => $totalAmount,
            'totalConfirmed' => $totalConfirmed,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'status' => $status,
            'productType' => $productType,
            'productName' => $productName,
        ]);
    }

    public function detail($id)
    {
        $transaksi = $this->transaksiModel->getWithUser()->where('transaksi.id', $id)->first();
        if (!$transaksi) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Transaksi tidak ditemukan');
        }

        $currentLevelId = session()->get('level_id');

        return view('superadmin/transaksi/detail', [
            'title' => 'Detail Transaksi - Admin Dashboard',
            'transaksi' => $transaksi,
            'activeMenu' => 'transaksi',
            'canWrite' => $this->canWriteAdmin(),
            'isSuperAdmin' => $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ]);
    }

    public function updateStatus($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Akses ditolak.');
        }

        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Transaksi tidak ditemukan');
        }

        $newStatus = $this->request->getPost('status');
        $data = ['status' => $newStatus];

        if ($newStatus === 'paid') {
            $data['paid_at'] = date('Y-m-d H:i:s');
        } elseif ($newStatus === 'confirmed') {
            $data['confirmed_at'] = date('Y-m-d H:i:s');
        }

        $this->transaksiModel->update($id, $data);

        // Process when confirmed
        if ($newStatus === 'confirmed') {
            $messages = [];

            // Process poin purchase (Service Fee)
            if ($transaksi['product_type'] === 'poin') {
                $poinAdded = $this->processPoinPurchase($transaksi);
                if ($poinAdded > 0) {
                    $messages[] = number_format($poinAdded) . ' poin ditambahkan ke user';
                }
            }

            // Process referral poin
            $poinGiven = $this->transaksiModel->processReferralPoin($id);
            if ($poinGiven) {
                $messages[] = "Referral poin: {$poinGiven}";
            }

            // Process WPA Commission
            $this->transaksiModel->processWpaCommission($id);

            // Process EA License Generation
            $licenseGenerated = $this->transaksiModel->processEaLicense($id);
            if ($licenseGenerated) {
                $messages[] = "License EA Generated";
            }

            // [NEW] Process Advocacy Activation
            if (strpos($transaksi['product_name'], 'Advokasi') !== false) {
                $pengaduanModel = new \App\Models\LayananPengaduanModel();
                // Find pending_payment complaints for this user
                $pendingComplaints = $pengaduanModel->where('user_id', $transaksi['user_id'])
                                                  ->where('status', 'pending_payment')
                                                  ->findAll();
                
                if (!empty($pendingComplaints)) {
                    foreach ($pendingComplaints as $pc) {
                        $pengaduanModel->update($pc['id'], ['status' => 'pending']);
                    }
                    $messages[] = "Status Advokasi diaktifkan";
                }
            }

            $successMsg = 'Status transaksi berhasil diupdate!';
            if (!empty($messages)) {
                $successMsg .= ' (' . implode(', ', $messages) . ')';
            }

            return redirect()->to('/superadmin/transaksi')->with('success', $successMsg);
        }

        return redirect()->to('/superadmin/transaksi')->with('success', 'Status transaksi berhasil diupdate!');
    }

    /**
     * Process poin purchase after admin confirms
     */
    protected function processPoinPurchase($transaksi)
    {
        $poinModel = new \App\Models\PoinModel();
        $notifModel = new \App\Models\NotificationModel();

        // Check if poin already added (prevent duplicate)
        $existing = $poinModel->where('pointable_type', 'purchase')
            ->where('pointable_id', $transaksi['id'])
            ->first();
        if ($existing) {
            return 0; // Already processed
        }

        // Parse poin from notes (format: "Poin: 1000 + Bonus: 100 | ...")
        $notes = $transaksi['notes'] ?? '';
        preg_match('/Poin: ([\d,]+)/', $notes, $poinMatch);
        preg_match('/Bonus: ([\d,]+)/', $notes, $bonusMatch);

        $poinAmount = isset($poinMatch[1]) ? (int) str_replace(',', '', $poinMatch[1]) : 0;
        $bonusAmount = isset($bonusMatch[1]) ? (int) str_replace(',', '', $bonusMatch[1]) : 0;

        if ($poinAmount <= 0) {
            log_message('error', 'Poin purchase: Invalid poin amount for transaksi ' . $transaksi['id'] . ' - notes: ' . $notes);
            return 0;
        }

        // Add main poin
        $poinModel->insert([
            'user_id' => (int) $transaksi['user_id'],
            'type' => 'earn',
            'point' => $poinAmount,
            'description' => 'Pembelian Service Fee: ' . number_format($poinAmount) . ' Poin',
            'pointable_type' => 'purchase',
            'pointable_id' => (int) $transaksi['id'],
        ]);

        // Add bonus poin if any
        if ($bonusAmount > 0) {
            $poinModel->insert([
                'user_id' => (int) $transaksi['user_id'],
                'type' => 'bonus',
                'point' => $bonusAmount,
                'description' => 'Bonus pembelian Service Fee',
                'pointable_type' => 'purchase_bonus',
                'pointable_id' => (int) $transaksi['id'],
            ]);
        }

        $totalPoin = $poinAmount + $bonusAmount;

        // Send notification to user
        $notifModel->createNotification(
            (int) $transaksi['user_id'],
            'Poin Berhasil Ditambahkan',
            'Selamat! ' . number_format($totalPoin) . ' poin telah ditambahkan ke akun Anda.' . ($bonusAmount > 0 ? ' (termasuk bonus ' . number_format($bonusAmount) . ' poin)' : ''),
            'success',
            '/user/poin'
        );

        log_message('info', 'Poin purchase processed by admin: User ' . $transaksi['user_id'] . ' received ' . $totalPoin . ' poin');

        return $totalPoin;
    }

    public function delete($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Akses ditolak.');
        }

        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Transaksi tidak ditemukan');
        }

        $this->transaksiModel->delete($id);
        return redirect()->to('/superadmin/transaksi')->with('success', 'Transaksi berhasil dihapus!');
    }

    /**
     * Resend Transaction Emails
     */
    public function resendEmail($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $type = $this->request->getPost('type');
        $transaksi = $this->transaksiModel->find($id);

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($transaksi['user_id']);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $emailService = new \App\Libraries\EmailService();

        try {
            switch ($type) {
                case 'invoice_pending':
                    // Extract payment URL from notes
                    $paymentUrl = null;
                    if (strpos($transaksi['notes'], 'Xendit ID:') !== false) {
                        preg_match('/Xendit ID: ([a-z0-9]+)/', $transaksi['notes'], $matches);
                        if (isset($matches[1])) {
                            $xenditId = $matches[1];
                            $domain = (ENVIRONMENT === 'production') ? 'checkout.xendit.co' : 'checkout-staging.xendit.co';
                            $paymentUrl = "https://{$domain}/web/{$xenditId}";
                        }
                    }

                    $result = $emailService->sendInvoicePending($user['email'], $user['name'], $transaksi, $paymentUrl);
                    if ($result['success']) {
                        $this->transaksiModel->update($id, ['mail_invoice_pending' => 'sent']);
                        return redirect()->back()->with('success', 'Email Invoice Pembayaran berhasil dikirim ulang.');
                    }
                    break;

                case 'invoice_success':
                    // Generate PDF invoice
                    $pdfService = new \App\Libraries\InvoicePdfService();
                    $pdfPath = $pdfService->generate($transaksi, $user);

                    $result = $emailService->sendPaymentSuccess($user['email'], $user['name'], $transaksi, $pdfPath);
                    if ($result['success']) {
                        $this->transaksiModel->update($id, ['mail_invoice_success' => 'sent']);
                        return redirect()->back()->with('success', 'Email Invoice Berhasil berhasil dikirim ulang.');
                    }
                    break;

                case 'legal':
                    // Prepare data
                    $layananData = [
                        'id' => $transaksi['layanan_id'],
                        'name' => $transaksi['product_name'],
                        'wpa_name' => 'Tim Almai'
                    ];

                    $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
                    $tempDir = WRITEPATH . 'uploads/invoices/';
                    if (!is_dir($tempDir)) mkdir($tempDir, 0755, true);

                    $attachments = [];
                    $trackingData = [];

                    // 1. Perjanjian Pemberian Jasa
                    $perjanjianPdf = $legalPdfService->generatePerjanjianPdf($transaksi, $user, $layananData, $layananData['wpa_name']);
                    if ($perjanjianPdf) {
                        $path = $tempDir . 'resend_legal_perjanjian_' . $transaksi['invoice_number'] . '.pdf';
                        file_put_contents($path, $perjanjianPdf);
                        $attachments['Perjanjian_Pemberian_Jasa_' . $transaksi['invoice_number']] = $path;
                    }

                    // 2. Dokumen Pemberitahuan Risiko
                    $risikoPdf = $legalPdfService->generateRisikoPdf($transaksi, $user, $layananData);
                    if ($risikoPdf) {
                        $path = $tempDir . 'resend_legal_risiko_' . $transaksi['invoice_number'] . '.pdf';
                        file_put_contents($path, $risikoPdf);
                        $attachments['Dokumen_Pemberitahuan_Risiko_' . $transaksi['invoice_number']] = $path;
                    }

                    // CWPA Specific
                    if ($transaksi['product_type'] === 'cwpa') {
                        $profilPdf = $legalPdfService->generateProfilPerusahaanPdf($transaksi, $user);
                        if ($profilPdf) {
                            $path = $tempDir . 'resend_profil_perusahaan_' . $transaksi['invoice_number'] . '.pdf';
                            file_put_contents($path, $profilPdf);
                            $attachments['Profil_Perusahaan_' . $transaksi['invoice_number']] = $path;
                        }
                        $wpaPdf = $legalPdfService->generatePerjanjianWpaPdf($transaksi, $user, $layananData);
                        if ($wpaPdf) {
                            $path = $tempDir . 'resend_perjanjian_wpa_' . $transaksi['invoice_number'] . '.pdf';
                            file_put_contents($path, $wpaPdf);
                            $attachments['Perjanjian_WPA_' . $transaksi['invoice_number']] = $path;
                        }
                    }

                    if (empty($attachments)) {
                        return redirect()->back()->with('error', 'Gagal membuat dokumen legal untuk pengiriman ulang.');
                    }

                    $result = $emailService->sendLegalDocuments($user['email'], $user['name'], $attachments, $transaksi['invoice_number']);

                    // Clean up
                    foreach ($attachments as $path) {
                        if (file_exists($path)) @unlink($path);
                    }

                    if ($result['success']) {
                        $trackingData['mail_legal_pemberian_jasa'] = isset($attachments['Perjanjian_Pemberian_Jasa_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                        $trackingData['mail_legal_risiko'] = isset($attachments['Dokumen_Pemberitahuan_Risiko_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                        if ($transaksi['product_type'] === 'cwpa') {
                            $trackingData['mail_legal_profil'] = isset($attachments['Profil_Perusahaan_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                            $trackingData['mail_legal_wpa'] = isset($attachments['Perjanjian_WPA_' . $transaksi['invoice_number']]) ? 'sent' : 'not_sent';
                        }
                        $this->transaksiModel->update($id, $trackingData);
                        return redirect()->back()->with('success', 'Email Dokumen Legal berhasil dikirim ulang.');
                    }
                    break;
            }

            return redirect()->back()->with('error', 'Gagal mengirim ulang email: ' . ($result['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            log_message('error', 'Resend Email Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function createManual()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        
        // Get all active users
        $users = $db->table('users')
            ->select('id, name, email')
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        // Get all services from all tables
        $layanan = $db->table('layanan')
            ->select('layanan.id, layanan.name as title, layanan.price, "layanan" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan.wpa_id', 'left')
            ->where('layanan.status', 'published')
            ->get()->getResultArray();

        $events = $db->table('layanan_event')
            ->select('layanan_event.id, layanan_event.title, layanan_event.price, "event" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->whereIn('layanan_event.status', ['upcoming', 'published', 'aktif'])
            ->get()->getResultArray();

        $tools = $db->table('layanan_tools')
            ->select('layanan_tools.id, layanan_tools.name as title, layanan_tools.price, "tool" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan_tools.wpa_id', 'left')
            ->whereIn('layanan_tools.status', ['published', 'aktif'])
            ->get()->getResultArray();

        $subs = $db->table('layanan_subscription')
            ->select('layanan_subscription.id, layanan_subscription.name as title, layanan_subscription.price, "subscription" as type, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = layanan_subscription.wpa_id', 'left')
            ->whereIn('layanan_subscription.status', ['published', 'aktif'])
            ->get()->getResultArray();

        $services = array_merge($layanan, $events, $tools, $subs);
        
        // Sort services by title
        usort($services, function($a, $b) {
            return strcasecmp($a['title'], $b['title']);
        });

        return view('superadmin/transaksi/create_manual', [
            'title' => 'Tambah Transaksi Manual',
            'users' => $users,
            'services' => $services,
            'isSuperAdmin' => (session()->get('level_id') == \App\Models\LevelModel::LEVEL_SUPER_ADMIN)
        ]);
    }

    public function storeManual()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->to('/superadmin/transaksi')->with('error', 'Akses ditolak.');
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

        // Find user
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User tidak ditemukan.');
        }

        // Find service to get name
        $db = \Config\Database::connect();
        $productName = 'Unknown Service';
        $tableMapping = [
            'layanan' => 'layanan',
            'event' => 'layanan_event',
            'tool' => 'layanan_tools',
            'subscription' => 'layanan_subscription',
            'webinar' => 'layanan_event',
            'workshop' => 'layanan_event',
            'course' => 'layanan',
            'pendampingan' => 'layanan_subscription',
            'cwpa' => 'layanan_subscription',
        ];

        $tableName = $tableMapping[$productType] ?? 'layanan';
        $srv = $db->table($tableName)->where('id', $productId)->get()->getRowArray();
        if ($srv) {
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
            'description' => 'Pembelian ' . $productName . ' (Manual Admin)',
            'item_name' => $productName,
            'phone' => $user['phone'] ?? '',
        ];

        $xenditResponse = $xenditService->createInvoice($xenditData);
        
        if ($xenditResponse['success']) {
            $invoiceId = $xenditResponse['data']['id'];
            $invoiceUrl = $xenditResponse['data']['invoice_url'];
            
            // Update transaction with Xendit ID
            $currentNotes = $description ? $description . ' | ' : '';
            $this->transaksiModel->update($transaksiId, [
                'notes' => $currentNotes . 'Xendit ID: ' . $invoiceId
            ]);

            return redirect()->to('/superadmin/transaksi/detail/' . $transaksiId)->with('success', 'Transaksi manual berhasil dibuat. Link Pembayaran: <a href="'.$invoiceUrl.'" target="_blank" class="underline font-bold text-blue-500">'.$invoiceUrl.'</a>');
        } else {
            return redirect()->to('/superadmin/transaksi/detail/' . $transaksiId)->with('warning', 'Transaksi berhasil dibuat, namun gagal membuat link Xendit: ' . $xenditResponse['error']);
        }
    }

}
