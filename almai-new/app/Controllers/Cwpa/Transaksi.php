<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\LayananModel;
use App\Models\CwpaModel;

class Transaksi extends BaseController
{
    protected $cwpaModel;

    public function __construct()
    {
        $this->cwpaModel = new CwpaModel();
    }

    private function getCwpaId()
    {
        // Menggunakan session key yang sesuai dengan dashboard CWPA
        return session()->get('cwpaId');
    }

    public function index()
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) {
            return redirect()->to('/login')->with('error', 'Silakan login sebagai CWPA');
        }

        $transaksiModel = new TransaksiModel();
        $layananModel = new LayananModel();
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $tab = $this->request->getGet('tab') ?? 'penjualan'; // Default tab: penjualan

        $cwpa = $this->cwpaModel->find($cwpaId);
        $userId = $cwpa['user_id'] ?? 0;

        // ========== TAB 1: PENJUALAN (Transaksi layanan yang dia punya) ==========
        $penjualanTransactions = [];
        $penjualanPager = null;
        
        if ($tab === 'penjualan') {
            $layananList = $layananModel->getByCwpaId($cwpaId);
            
            $penjualanModel = new TransaksiModel();
            
            if (!empty($layananList)) {
                $layananIds = array_filter(array_column($layananList, 'id'));
                $layananNames = array_unique(array_filter(array_map(function($item) {
                    return $item['name'] ?? $item['title'] ?? null;
                }, $layananList)));

                $penjualanModel->groupStart();
                if (!empty($layananIds)) {
                    $penjualanModel->whereIn('transaksi.layanan_id', $layananIds);
                }
                if (!empty($layananNames)) {
                    $penjualanModel->orWhereIn('transaksi.product_name', $layananNames);
                    foreach ($layananNames as $name) {
                        $penjualanModel->orLike('transaksi.product_name', $name . ' - ', 'after');
                    }
                }
                $penjualanModel->groupEnd();
            } else {
                // No services = No transactions
                $penjualanModel->where('1=0');
            }

            if ($status) {
                $penjualanModel->where('transaksi.status', $status);
            }

            if ($search) {
                $penjualanModel->groupStart()
                    ->like('transaksi.invoice_number', $search)
                    ->orLike('users.name', $search)
                    ->orLike('transaksi.product_name', $search)
                    ->groupEnd();
            }

            $penjualanTransactions = $penjualanModel->select('transaksi.*, users.name as user_name, users.email as user_email, p.point as poin_cwpa')
                ->join('users', 'users.id = transaksi.user_id', 'left')
                ->join('points p', "p.pointable_id = transaksi.id AND p.pointable_type = 'transaksi' AND p.user_id = $userId", 'left')
                ->orderBy('transaksi.created_at', 'DESC')
                ->paginate(20);
            
            $penjualanPager = $penjualanModel->pager;
        }

        // ========== TAB 2: PEMBELIAN (Transaksi layanan yang dia beli) ==========
        $pembelianTransactions = [];
        $pembelianPager = null;
        
        if ($tab === 'pembelian') {
            $pembelianModel = new TransaksiModel();
            
            // Filter by user_id (transaksi yang dia beli)
            $pembelianModel->where('transaksi.user_id', $userId);

            if ($status) {
                $pembelianModel->where('transaksi.status', $status);
            }

            if ($search) {
                $pembelianModel->groupStart()
                    ->like('transaksi.invoice_number', $search)
                    ->orLike('transaksi.product_name', $search)
                    ->groupEnd();
            }

            $pembelianTransactions = $pembelianModel->select('transaksi.*')
                ->orderBy('transaksi.created_at', 'DESC')
                ->paginate(20);
            
            $pembelianPager = $pembelianModel->pager;
        }

        $data = [
            'title' => 'Riwayat Transaksi',
            'tab' => $tab,
            'penjualanTransactions' => $penjualanTransactions,
            'pembelianTransactions' => $pembelianTransactions,
            'penjualanPager' => $penjualanPager,
            'pembelianPager' => $pembelianPager,
            'currentStatus' => $status,
            'searchQuery' => $search
        ];

        return view('cwpa/transaksi/index', $data);
    }

    public function detail($id)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login');

        $transaksiModel = new TransaksiModel();
        
        $transaksi = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->find($id);

        if (!$transaksi || !$this->checkOwnership($transaksi, $cwpaId)) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau bukan milik Anda');
        }

        $data = [
            'title' => 'Detail Transaksi ' . $transaksi['invoice_number'],
            'trx' => $transaksi
        ];

        return view('cwpa/transaksi/detail', $data);
    }

    private function checkOwnership($transaksi, $cwpaId)
    {
        $layananModel = new LayananModel();
        $allLayanan = $layananModel->getByCwpaId($cwpaId);

        foreach ($allLayanan as $item) {
            $name = $item['name'] ?? $item['title'];
            if (
                $transaksi['layanan_id'] == $item['id'] ||
                $transaksi['product_name'] === $name ||
                stripos($transaksi['product_name'], $name . ' - ') === 0
            ) {
                return true;
            }
        }
        return false;
    }

    public function syncPoin()
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login');

        $db = \Config\Database::connect();
        $transaksiModel = new TransaksiModel();

        // Get confirmed transactions
        $transactions = $db->table('transaksi')
            ->where('status', 'confirmed')
            ->get()
            ->getResultArray();

        $fixedCount = 0;
        $commissionCount = 0;

        foreach ($transactions as $trx) {
            $id = $trx['id'];
            $needsUpdate = false;
            $updateData = [];

            // Fix Amount if 0
            if ($trx['payment_method'] === 'poin' && (float)$trx['total'] <= 0) {
                if (preg_match('/Pembayaran poin: ([\d\.,]+) Poin/i', $trx['notes'] ?? '', $matches)) {
                    $pointsValue = (float) str_replace(['.', ','], ['', '.'], $matches[1]);
                    $updateData['total'] = $pointsValue;
                    $needsUpdate = true;
                }
            }

            if ($needsUpdate) {
                $db->table('transaksi')->where('id', $id)->update($updateData);
                $fixedCount++;
            }

            // Trigger commission
            if ($transaksiModel->processWpaCommission($id)) {
                $commissionCount++;
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi Berhasil: $fixedCount data diperbaiki, $commissionCount komisi diproses.");
    }

    public function invoice($invoiceNumber)
    {
        // Try to get CWPA ID from session
        $cwpaId = $this->getCwpaId();
        
        // If no CWPA session, check if user is logged in as regular user
        if (!$cwpaId) {
            $userId = session()->get('userId');
            if (!$userId) {
                log_message('error', 'CWPA Invoice Access: No cwpaId or userId in session');
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }
            
            // Try to find CWPA by user_id
            $cwpa = $this->cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) {
                $cwpaId = $cwpa['id'];
                // Set session for future requests
                session()->set('cwpaId', $cwpaId);
            }
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel
            ->select('transaksi.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.invoice_number', $invoiceNumber)
            ->first();

        if (!$transaksi) {
            log_message('error', 'CWPA Invoice: Transaction not found - ' . $invoiceNumber);
            return redirect()->to('/cwpa/dashboard/transaksi')->with('error', 'Invoice tidak ditemukan');
        }

        // Get CWPA user_id
        if ($cwpaId) {
            $cwpa = $this->cwpaModel->find($cwpaId);
            $userId = $cwpa['user_id'] ?? session()->get('userId');
        } else {
            $userId = session()->get('userId');
        }

        // Security: Check if CWPA owns this transaction (as seller) OR is the buyer
        $isOwner = $cwpaId ? $this->checkOwnership($transaksi, $cwpaId) : false;
        $isBuyer = ($transaksi['user_id'] == $userId);

        if (!$isOwner && !$isBuyer) {
            log_message('error', 'CWPA Invoice Access Denied: cwpaId=' . $cwpaId . ', userId=' . $userId . ', trx_user=' . $transaksi['user_id']);
            return redirect()->to('/cwpa/dashboard/transaksi')->with('error', 'Anda tidak memiliki akses ke invoice ini');
        }

        $settingModel = new \App\Models\SettingModel();
        $bankInfo = [
            'bank_name' => $settingModel->get('bank_name', 'Bank BCA'),
            'account_number' => $settingModel->get('bank_account', '1234567890'),
            'account_name' => $settingModel->get('bank_account_name', 'PT Alma Indonesia Raya'),
        ];

        $xenditUrl = null;
        if ($transaksi['payment_method'] === 'xendit' && $transaksi['status'] === 'pending') {
            $xendit = new \App\Libraries\XenditService();
            $result = $xendit->getInvoiceByExternalId($invoiceNumber);
            if ($result['success'] && !empty($result['data'])) {
                $invoiceData = is_array($result['data']) && isset($result['data'][0]) ? $result['data'][0] : $result['data'];
                if (isset($invoiceData['invoice_url']) && $invoiceData['status'] === 'PENDING') {
                    $xenditUrl = $invoiceData['invoice_url'];
                }
            }
        }

        return view('cwpa/invoice/detail', [
            'title' => 'Invoice ' . $invoiceNumber,
            'transaksi' => $transaksi,
            'bankInfo' => $bankInfo,
            'xenditUrl' => $xenditUrl,
            'activeMenu' => 'transaksi',
            'backUrl' => base_url('cwpa/dashboard/transaksi')
        ]);
    }

    public function downloadPerjanjian($invoiceNumber)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) {
            $userId = session()->get('userId');
            if (!$userId) return redirect()->to('/login');
            $cwpa = $this->cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) $cwpaId = $cwpa['id'];
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Check access
        $cwpa = $this->cwpaModel->find($cwpaId);
        $userId = $cwpa['user_id'] ?? session()->get('userId');
        $isOwner = $cwpaId ? $this->checkOwnership($transaksi, $cwpaId) : false;
        $isBuyer = ($transaksi['user_id'] == $userId);

        if (!$isOwner && !$isBuyer) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke dokumen ini');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $layananData = $this->getLayananDataForLegal($transaksi);
        $wpaName = $layananData['wpa_name'] ?? 'Tim Almai';

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generatePerjanjianPdf($transaksi, $user, $layananData, $wpaName);

        if (empty($pdf)) {
            return redirect()->back()->with('error', 'Gagal membuat file PDF');
        }

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Perjanjian_Pemberian_Jasa_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    public function downloadRisiko($invoiceNumber)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) {
            $userId = session()->get('userId');
            if (!$userId) return redirect()->to('/login');
            $cwpa = $this->cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) $cwpaId = $cwpa['id'];
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Check access
        $cwpa = $this->cwpaModel->find($cwpaId);
        $userId = $cwpa['user_id'] ?? session()->get('userId');
        $isOwner = $cwpaId ? $this->checkOwnership($transaksi, $cwpaId) : false;
        $isBuyer = ($transaksi['user_id'] == $userId);

        if (!$isOwner && !$isBuyer) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke dokumen ini');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $layananData = $this->getLayananDataForLegal($transaksi);

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generateRisikoPdf($transaksi, $user, $layananData);

        if (empty($pdf)) {
            return redirect()->back()->with('error', 'Gagal membuat file PDF');
        }

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Pemberitahuan_Risiko_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    public function downloadProfilPerusahaan($invoiceNumber)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) {
            $userId = session()->get('userId');
            if (!$userId) return redirect()->to('/login');
            $cwpa = $this->cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) $cwpaId = $cwpa['id'];
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Check access
        $cwpa = $this->cwpaModel->find($cwpaId);
        $userId = $cwpa['user_id'] ?? session()->get('userId');
        $isOwner = $cwpaId ? $this->checkOwnership($transaksi, $cwpaId) : false;
        $isBuyer = ($transaksi['user_id'] == $userId);

        if (!$isOwner && !$isBuyer) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke dokumen ini');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generateProfilPerusahaanPdf($transaksi, $user);

        if (empty($pdf)) {
            return redirect()->back()->with('error', 'Gagal membuat file PDF');
        }

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Profil_Perusahaan_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    public function downloadPerjanjianWpa($invoiceNumber)
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) {
            $userId = session()->get('userId');
            if (!$userId) return redirect()->to('/login');
            $cwpa = $this->cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) $cwpaId = $cwpa['id'];
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Check access
        $cwpa = $this->cwpaModel->find($cwpaId);
        $userId = $cwpa['user_id'] ?? session()->get('userId');
        $isOwner = $cwpaId ? $this->checkOwnership($transaksi, $cwpaId) : false;
        $isBuyer = ($transaksi['user_id'] == $userId);

        if (!$isOwner && !$isBuyer) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke dokumen ini');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $layananData = $this->getLayananDataForLegal($transaksi);

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generatePerjanjianWpaPdf($transaksi, $user, $layananData);

        if (empty($pdf)) {
            return redirect()->back()->with('error', 'Gagal membuat file PDF');
        }

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Perjanjian_WPA_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    private function getLayananDataForLegal($transaksi)
    {
        $db = \Config\Database::connect();
        $type = strtolower($transaksi['product_type'] ?? 'layanan');
        $id = $transaksi['layanan_id'];

        $layananData = [
            'id' => $id,
            'name' => $transaksi['product_name'],
            'slug' => url_title($transaksi['product_name'] ?? 'service', '_', true),
            'wpa_name' => 'Tim Almai',
            'cwpa_name' => 'Tim Almai'
        ];

        $item = null;
        if ($type === 'layanan' || $type === 'course') {
            $item = $db->table('layanan')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
            $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'tool' || $type === 'tools' || $type === 'ea') {
            $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'subscription' || in_array($type, ['cwpa', 'pendampingan', 'profirm', 'vip_member', 'private_konsultan'])) {
            $item = $db->table('layanan_subscription')->where('id', $id)->get()->getRowArray();
        }

        if ($item) {
            if (isset($item['title'])) $layananData['name'] = $item['title'];
            if (isset($item['slug'])) $layananData['slug'] = $item['slug'];
            if (isset($item['layanan_utama'])) $layananData['layanan_utama'] = $item['layanan_utama'];
            
            // Get WPA name
            $wpaId = $item['wpa_id'] ?? null;
            if ($wpaId) {
                $wpa = $db->table('wpa')->select('name')->where('id', $wpaId)->get()->getRowArray();
                if ($wpa) $layananData['wpa_name'] = $wpa['name'];
            }

            // Get CWPA name
            $cwpaId = $item['cwpa_id'] ?? null;
            if ($cwpaId) {
                $cwpa = $db->table('cwpa')->select('name')->where('id', $cwpaId)->get()->getRowArray();
                if ($cwpa) $layananData['cwpa_name'] = $cwpa['name'];
            }
        }

        return $layananData;
    }
}
