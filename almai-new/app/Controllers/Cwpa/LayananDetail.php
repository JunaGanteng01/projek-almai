<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\WpaModel;
use App\Models\UserModel;
use App\Models\LayananModel;
use App\Models\LayananResourceModel;
use App\Models\LayananCompletionModel;
use App\Models\CertificateModel;
use App\Models\EaLicenseModel;
use App\Models\LayananUlasanModel;
use App\Services\EmailService;

class LayananDetail extends BaseController
{
    public function index($id)
    {
        $userId = session()->get('userId');
        $cwpaId = session()->get('cwpaId');
        
        if (!$cwpaId) {
            return redirect()->to('/login');
        }

        $transaksiModel = new TransaksiModel();
        $layananModel = new LayananModel();
        $wpaModel = new WpaModel();

        $transaksiId = $this->request->getGet('trx');
        $purchase = null;

        if (!empty($transaksiId)) {
            $purchase = $transaksiModel
                ->where('id', $transaksiId)
                ->where('user_id', $userId)
                ->where('status', 'confirmed')
                ->first();
        }

        if (!$purchase) {
            $purchase = $transaksiModel
                ->where('user_id', $userId)
                ->where('layanan_id', $id)
                ->where('status', 'confirmed')
                ->orderBy('id', 'DESC')
                ->first();
        }

        if (!$purchase) {
            return redirect()->to('/cwpa/dashboard/layanan')->with('error', 'Anda belum membeli layanan ini');
        }

        // Adapted from User\LayananDetail
        $db = \Config\Database::connect();
        $layanan = null;
        $purchaseName = $purchase['product_name'] ?? '';
        $transactionProductType = $purchase['product_type'] ?? '';

        // Try main layanan table
        $layananCheck = $layananModel->find($id);
        if ($layananCheck) {
            $layanan = $layananCheck;
            $layanan['thumbnail'] = $this->fixLayananImageUrl($layanan['thumbnail']);
            $layanan['type'] = ($layanan['mode'] ?? 'Online') === 'Online' ? 'recorded' : 'live';
        } else {
            // Try event table
            $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            if ($event) {
                // Count real participants
                $participantsCount = $transaksiModel->where('layanan_id', $id)->where('status', 'confirmed')->countAllResults();
                
                $layanan = [
                    'id' => $event['id'],
                    'name' => $event['title'],
                    'title' => $event['title'],
                    'thumbnail' => $this->fixLayananImageUrl($event['thumbnail']),
                    'description' => $event['description'],
                    'wpa_id' => $event['wpa_id'],
                    'type' => 'live',
                    'zoom_link' => $event['zoom_link'] ?? '#',
                    'duration' => $event['duration'] ?? '1 Session',
                    'level' => 'General',
                    'students' => $participantsCount,
                    'rating' => '5.0',
                    'layanan_utama' => $event['key_features'] ?? null,
                    'youtube_tutorials' => $event['youtube_tutorials'] ?? null,
                    'tool_id' => $event['tool_id'] ?? null
                ];
            } else {
                // Try tool table
                $toolRow = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
                if ($toolRow) {
                    $participantsCount = $transaksiModel->where('layanan_id', $id)->where('status', 'confirmed')->countAllResults();
                    
                    $layanan = [
                        'id' => $toolRow['id'],
                        'name' => $toolRow['name'],
                        'title' => $toolRow['name'],
                        'thumbnail' => $this->fixLayananImageUrl($toolRow['thumbnail']),
                        'description' => $toolRow['description'],
                        'type' => 'tool',
                        'duration' => 'Lifetime',
                        'level' => 'Advanced',
                        'students' => $participantsCount,
                        'rating' => '5.0',
                        'layanan_utama' => $toolRow['layanan_utama'] ?? null,
                        'youtube_tutorials' => $toolRow['youtube_tutorials'] ?? null,
                        'requirements' => json_decode($toolRow['requirements'] ?? '[]', true),
                        'changelog' => $toolRow['changelog'] ?? '',
                    ];
                }
            }
        }

        if (!$layanan) {
            return redirect()->to('/cwpa/dashboard/layanan')->with('error', 'Layanan tidak ditemukan');
        }

        $layanan['title'] = $layanan['name'] ?? $layanan['title'];
        $isLive = ($layanan['type'] ?? '') === 'live';

        // Get WPA info
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $wpaModel->find($layanan['wpa_id']);
            if ($wpa) {
                if ($wpa['photo'] && !str_starts_with($wpa['photo'], 'http')) {
                    $wpa['photo'] = base_url('file/' . $wpa['photo']);
                }
            }
        }

        // Resource & Materials
        $resourceModel = new LayananResourceModel();
        $materials = $resourceModel->where('layanan_id', $id)->findAll();

        // Completion status
        $completionModel = new LayananCompletionModel();
        $isCompleted = $completionModel->isCompleted($userId, $id, $purchase['product_type'] ?? 'layanan');

        // Get certificate if completed
        $certificateModel = new CertificateModel();
        $certificate = null;
        if ($isCompleted) {
            $certificate = $certificateModel->getCertificateForLayanan($userId, $id, $purchase['product_type'] ?? 'layanan');
        }

        // Determine if live or recorded
        $isLive = ($layanan['mode'] ?? 'Online') !== 'Online' && ($layanan['type'] ?? '') !== 'tool';

        // Check for EA License Capability - Matching logic from User\LayananDetail.php
        $license = null;
        $tool = null;

        // Use the type for normalization
        $typeForLicense = $layanan['type'] ?? '';
        
        if (in_array($typeForLicense, ['tool', 'live', 'webinar', 'workshop'])) {
            if ($typeForLicense === 'tool') {
                $tool = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
            } else {
                // For live/webinar/workshop, try from layanan_event
                $tool = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
                
                // If not found in event, fallback to tool_id if present
                if (!$tool && !empty($layanan['tool_id'])) {
                    $tool = $db->table('layanan_tools')->where('id', $layanan['tool_id'])->get()->getRowArray();
                }
            }

            // Final fallback by ID if product_type is strictly 'tool'
            if (!$tool && ($transactionProductType === 'tools' || $transactionProductType === 'tool')) {
                $tool = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
            }

            // Fetch license if tool exists and has license capability
            if ($purchase) {
                $eaLicenseModel = new EaLicenseModel();
                $license = $eaLicenseModel->where('order_id', $purchase['id'])->first();
            }
        }

        return view('cwpa/layanan/detail', [
            'title' => 'Detail Layanan - CWPA Dashboard',
            'activeMenu' => 'layanan-saya',
            'kelas' => $layanan,
            'purchase' => $purchase,
            'wpa' => $wpa,
            'materials' => $materials,
            'isCompleted' => $isCompleted,
            'certificate' => $certificate,
            'license' => $license,
            'tool' => $tool,
            'isLive' => $isLive,
            'transaksiId' => $purchase['id'],
            'isCwpaDashboard' => true
        ]);
    }

    public function complete($id)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $completionModel = new LayananCompletionModel();
        $certificateModel = new CertificateModel();
        $userModel = new UserModel();

        $transaksiId = $this->request->getPost('transaksi_id');
        $purchase = null;

        if ($transaksiId) {
            $purchase = $transaksiModel->where('id', $transaksiId)->where('user_id', $userId)->first();
        }

        if (!$purchase) {
            $purchase = $transaksiModel->where('layanan_id', $id)->where('user_id', $userId)->where('status', 'confirmed')->first();
        }

        if ($purchase) {
            $type = $purchase['product_type'] ?? 'layanan';
            $completion = $completionModel->markComplete($userId, $id, $type, $purchase['id']);
            
            // Get user and WPA info for certificate
            $userName = session()->get('cwpaName') ?? session()->get('userName');
            
            // Try to find WPA name from layanan
            $wpaModel = new \App\Models\WpaModel();
            $layananModel = new \App\Models\LayananModel();
            $layananInfo = $layananModel->find($id);
            $wpaName = 'ALMAI Team';
            if ($layananInfo && !empty($layananInfo['wpa_id'])) {
                $wpa = $wpaModel->find($layananInfo['wpa_id']);
                $wpaName = $wpa['name'] ?? $wpaName;
            }

            $certificateModel->createCertificate(
                $userId, 
                $id, 
                $type, 
                $purchase['product_name'], 
                $userName, 
                $wpaName, 
                $completion['id'] ?? null
            );
            return redirect()->back()->with('success', 'Selamat! Layanan telah diselesaikan.');
        }

        return redirect()->back()->with('error', 'Gagal memproses penyelesaian layanan.');
    }

    public function activateLicense($id)
    {
        $userId = session()->get('userId');
        $transaksiId = $this->request->getPost('transaksi_id');
        $broker = $this->request->getPost('broker_name');
        $account = $this->request->getPost('account_number');

        $eaLicenseModel = new EaLicenseModel();
        
        // Simple logic mirroring User\LayananDetail
        $existing = $eaLicenseModel->where('order_id', $transaksiId)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Lisensi sudah diaktivasi untuk transaksi ini.');
        }

        $licenseKey = strtoupper(substr(md5(uniqid()), 0, 16));
        $eaLicenseModel->insert([
            'user_id' => $userId,
            'layanan_id' => $id,
            'order_id' => $transaksiId,
            'broker_name' => $broker,
            'account_trading_number' => $account,
            'license_key' => $licenseKey,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Lisensi berhasil diaktivasi!');
    }

    private function fixLayananImageUrl($path)
    {
        if (!$path) return 'https://via.placeholder.com/400x200';
        if (str_starts_with($path, 'http')) return $path;
        return base_url('file/' . $path);
    }
}
