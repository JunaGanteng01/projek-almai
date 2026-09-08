<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\LayananModel;
use App\Models\WpaModel;

class Transaksi extends BaseController
{
    public function index()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login')->with('error', 'Profil WPA tidak ditemukan');

        $layananModel = new LayananModel();
        $allLayanan = $layananModel->getByWpaId($wpaId);

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $transactions = [];
        $pager = null;

        if (!empty($allLayanan)) {
            $transaksiModel = new TransaksiModel();
            
            $transaksiModel->groupStart();
            foreach ($allLayanan as $index => $item) {
                // Determine possible product_types for this service type
                $type = $item['type'];
                $dbTypes = [$type];
                if (in_array($type, ['webinar', 'workshop'])) $dbTypes[] = 'event';
                if (in_array($type, ['ea', 'toolkit'])) {
                    $dbTypes[] = 'tool';
                    $dbTypes[] = 'tools';
                    $dbTypes[] = 'ea';
                }
                if (in_array($type, ['layanan', 'course'])) {
                    $dbTypes[] = 'layanan';
                    $dbTypes[] = 'course';
                }
                $dbTypes = array_unique($dbTypes);

                $method = ($index === 0) ? 'groupStart' : 'orGroupStart';
                $transaksiModel->$method();
                
                // Option A: Match by paired ID and Type (Most reliable)
                $transaksiModel->groupStart()
                    ->where('layanan_id', $item['id'])
                    ->whereIn('product_type', $dbTypes)
                ->groupEnd();
                
                // Option B: Match by Name (Fallback for legacy/variants)
                // We only do this for the specific names of THIS WPA's services
                $name = $item['name'] ?? $item['title'] ?? null;
                if ($name) {
                    $transaksiModel->orGroupStart()
                        ->where('product_name', $name)
                        ->orLike('product_name', $name . ' - ', 'after')
                    ->groupEnd();
                }
                
                $transaksiModel->groupEnd();
            }
            $transaksiModel->groupEnd();

            // Apply search (Invoice search remains broad)
            if ($search) {
                $transaksiModel->groupStart()
                    ->like('transaksi.invoice_number', $search)
                    ->orLike('transaksi.product_name', $search)
                    ->orLike('users.name', $search)
                    ->orLike('users.email', $search)
                    ->groupEnd();
            }

            // Apply status filter
            if ($status && $status !== 'all') {
                $transaksiModel->where('transaksi.status', $status);
            }

            $transactions = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email')
                ->join('users', 'users.id = transaksi.user_id', 'left')
                ->orderBy('transaksi.created_at', 'DESC')
                ->paginate(20);

            $pager = $transaksiModel->pager;
        }

        return view('wpa/transaksi/index', [
            'title' => 'Transaksi - WPA Dashboard',
            'activeMenu' => 'transaksi',
            'transactions' => $transactions,
            'pager' => $pager,
            'searchQuery' => $search,
            'currentStatus' => $status
        ]);
    }

    private function getWpaId()
    {
        $wpaId = $this->session->get('wpaId');
        if (!$wpaId) {
            $userId = $this->session->get('userId');
            $wpaModel = new WpaModel();
            $wpa = $wpaModel->where('user_id', $userId)->first();
            if ($wpa) {
                $wpaId = $wpa['id'];
                $this->session->set('wpaId', $wpaId);
            }
        }
        return $wpaId;
    }

    public function invoice($invoiceNumber)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login');

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Invoice tidak ditemukan');

        // Security: Check if WPA owns this transaction
        if (!$this->checkWpaOwnership($wpaId, $transaksi)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke invoice ini');
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

        return view('user/invoice/detail', [
            'title' => 'Invoice ' . $invoiceNumber,
            'transaksi' => $transaksi,
            'bankInfo' => $bankInfo,
            'xenditUrl' => $xenditUrl,
            'activeMenu' => 'transaksi',
            'backUrl' => base_url('wpa/dashboard/transaksi')
        ]);
    }

    public function downloadPerjanjian($invoiceNumber)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login');

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        if (!$this->checkWpaOwnership($wpaId, $transaksi)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses kdokumen ini');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $layananData = $this->getLayananDataForLegal($transaksi);
        $wpaName = $layananData['wpa_name'] ?? 'Tim Almai';

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generatePerjanjianPdf($transaksi, $user, $layananData, $wpaName);

        if (empty($pdf)) return redirect()->back()->with('error', 'Gagal membuat file PDF');

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Perjanjian_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    public function downloadRisiko($invoiceNumber)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/login');

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        if (!$this->checkWpaOwnership($wpaId, $transaksi)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke dokumen ini');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $layananData = $this->getLayananDataForLegal($transaksi);

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generateRisikoPdf($transaksi, $user, $layananData);

        if (empty($pdf)) return redirect()->back()->with('error', 'Gagal membuat file PDF');

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Pemberitahuan_Risiko_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    private function checkWpaOwnership($wpaId, $transaksi)
    {
        // 1. Check if WPA is the referrer (Direct commission buyer used their code)
        $db = \Config\Database::connect();
        $wpa = $db->table('wpa')->select('user_id')->where('id', $wpaId)->get()->getRowArray();
        if ($wpa && $transaksi['referrer_id'] == $wpa['user_id']) {
            return true;
        }

        // 2. Check by product ownership
        $layananModel = new LayananModel();
        $allLayanan = $layananModel->getByWpaId($wpaId);

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

    private function getLayananDataForLegal($transaksi)
    {
        $db = \Config\Database::connect();
        $type = strtolower($transaksi['product_type'] ?? 'layanan');
        $id = $transaksi['layanan_id'];

        $layananData = [
            'id' => $id,
            'name' => $transaksi['product_name'],
            'slug' => url_title($transaksi['product_name'] ?? 'service', '_', true),
            'wpa_name' => 'Tim Almai'
        ];

        $item = null;
        if ($type === 'layanan' || $type === 'course') {
            $item = $db->table('layanan')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
            $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'tool' || $type === 'tools' || $type === 'ea') {
            $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'subscription') {
            $item = $db->table('layanan_subscription')->where('id', $id)->get()->getRowArray();
        }

        if ($item) {
            if (isset($item['title'])) $layananData['name'] = $item['title'];
            if (isset($item['slug'])) $layananData['slug'] = $item['slug'];
            if (isset($item['layanan_utama'])) $layananData['layanan_utama'] = $item['layanan_utama'];
            $wpaId = $item['wpa_id'] ?? null;
            if ($wpaId) {
                $wpa = $db->table('wpa')->where('id', $wpaId)->get()->getRowArray();
                if ($wpa) $layananData['wpa_name'] = $wpa['name'];
            }
        }

        return $layananData;
    }
}
