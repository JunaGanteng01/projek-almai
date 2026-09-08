<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\WpaModel;

class LayananDetail extends BaseController
{
    public function index($id)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $layananModel = new \App\Models\LayananModel();
        $wpaModel = new WpaModel();

        // Check availability of WPA session
        if (!session()->get('wpaId')) {
            return redirect()->to('/wpa/login');
        }

        // Check if user has purchased this layanan
        $transaksiId = $this->request->getGet('trx');
        $purchase = null;

        if (!empty($transaksiId)) {
            $purchase = $transaksiModel
                ->where('id', $transaksiId)
                ->where('user_id', $userId)
                ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                ->first();
        }

        if (!$purchase) {
            $purchase = $transaksiModel
                ->where('user_id', $userId)
                ->where('layanan_id', $id)
                ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                ->orderBy('id', 'DESC')
                ->first();
        }

        if (!$purchase) {
            // FALLBACK: If strict check fails, try to find ANY confirmed transaction for this user
            // that might match the requested service name or product_name
            // useful if layanan_id wasn't updated correctly in the previous steps

            $targetService = $layananModel->find($id);
            if ($targetService) {
                $fuzzy = $transaksiModel
                    ->where('user_id', $userId)
                    ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                    ->like('product_name', $targetService['name'])
                    ->first();
                if ($fuzzy) {
                    $purchase = $fuzzy;
                    // Auto-fix the link for next time
                    $transaksiModel->update($fuzzy['id'], ['layanan_id' => $id]);
                }
            }
        }

        if (!$purchase) {
            return redirect()->to('/wpa/dashboard/layanan-saya')->with('error', 'Anda belum membeli layanan ini');
        }

        // Determine type from transaction if available
        $productType = strtolower($purchase['product_type'] ?? '');
        $purchaseName = strtolower($purchase['product_name'] ?? '');
        $layanan = null;

        // 1. Try Layanan Tools Table
        if ($productType === 'tools' || $productType === 'tool' || stripos($purchaseName, 'bot') !== false || stripos($purchaseName, 'panel') !== false || stripos($purchaseName, 'ea') !== false) {
            $db = \Config\Database::connect();
            $tool = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();

            // Validate name if found
            if ($tool && (stripos($purchaseName, strtolower($tool['name'])) !== false || stripos(strtolower($tool['name']), $purchaseName) !== false)) {
                // Count real participants from confirmed transactions
                $confirmedCount = $transaksiModel
                    ->where('layanan_id', $id)
                    ->where('status', 'confirmed')
                    ->countAllResults();

                $layanan = [
                    'id' => $tool['id'],
                    'name' => $tool['name'],
                    'slug' => $tool['slug'] ?? 'tool-' . $tool['id'],
                    'category' => 'Tools',
                    'subcategory' => ($tool['type'] == 'ea') ? 'Expert Advisor' : 'Toolkit',
                    'description' => $tool['description'],
                    'thumbnail' => $this->fixLayananImageUrl($tool['thumbnail'] ?? ''),
                    'wpa_id' => null,
                    'price' => $tool['price'],
                    'rating' => 0,
                    'students' => $confirmedCount, // Real count from transactions
                    'mode' => 'Download',
                    'duration' => 'Lifetime',
                    'level' => 'Advanced',
                    'modules' => 1,
                    'download_url' => !empty($tool['file_path']) ? base_url('file/' . $tool['file_path']) : '#',
                    'guide_url' => $tool['documentation_url'] ?? '#',
                    'version' => $tool['version'] ?? '1.0.0',
                    'compatibility' => json_decode($tool['compatibility'] ?? '[]', true),
                    'features' => json_decode($tool['features'] ?? '[]', true),
                    'requirements' => json_decode($tool['requirements'] ?? '[]', true),
                    'changelog' => $tool['changelog'] ?? '',
                    'youtube_tutorials' => $tool['youtube_tutorials'] ?? null,
                    'type' => 'tool'
                ];
            }
        }

        // 2. Try Layanan Event Table
        if (!$layanan && ($productType === 'event' || $productType === 'webinar' || $productType === 'workshop' || stripos($purchaseName, 'webinar') !== false || stripos($purchaseName, 'angel') !== false)) {
            $db = \Config\Database::connect();
            $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();

            if ($event && (stripos($purchaseName, strtolower($event['title'])) !== false || stripos(strtolower($event['title']), $purchaseName) !== false)) {
                // Calculate Schedule
                $schedule = $event['event_date'];
                $nextSession = $event['event_date'];

                if (!empty($event['is_recurring']) && $event['is_recurring'] == 1) {
                    $dayMap = [
                        'Senin' => 'Monday',
                        'Selasa' => 'Tuesday',
                        'Rabu' => 'Wednesday',
                        'Kamis' => 'Thursday',
                        'Jumat' => 'Friday',
                        'Sabtu' => 'Saturday',
                        'Minggu' => 'Sunday'
                    ];
                    $dbDay = $event['recurring_day'] ?? 'Monday';
                    $engDay = $dayMap[$dbDay] ?? 'Monday';
                    $time = $event['recurring_time'] ?? '20:00';

                    // Logic to find next specific day
                    $schedule = "Setiap $dbDay, $time WIB";
                    $nextDate = new \DateTime("next $engDay $time");
                    if ($nextDate < new \DateTime()) {
                        $nextDate->modify('+1 week');
                    }
                    $nextSession = $nextDate->format('d M Y, H:i');
                } else {
                    $schedule = date('d M Y, H:i', strtotime($event['event_date'])) . ' WIB';
                    $nextSession = date('d M Y', strtotime($event['event_date']));
                }

                // Map Event to Layanan structure
                // Count real participants from confirmed transactions
                $confirmedCount = $transaksiModel
                    ->where('layanan_id', $id)
                    ->where('status', 'confirmed')
                    ->countAllResults();

                $layanan = [
                    'id' => $event['id'],
                    'name' => $event['title'],
                    'slug' => $event['slug'],
                    'category' => 'Event',
                    'subcategory' => $event['type'],
                    'description' => $event['description'],
                    'thumbnail' => $this->fixLayananImageUrl($event['thumbnail'] ?? ''),
                    'wpa_id' => $event['wpa_id'],
                    'price' => $event['price'],
                    'rating' => 0,
                    'students' => $confirmedCount, // Real count from transactions
                    'mode' => ($event['type'] == 'webinar') ? 'Live' : 'Offline',
                    'location' => $event['location'] ?? 'Online',
                    'duration' => '-',
                    'level' => 'All Level',
                    'modules' => 0,
                    'schedule' => $schedule,
                    'next_session' => $nextSession,
                    'zoom_link' => $event['zoom_link'] ?? '#',
                    'zoom_meeting_id' => $event['meeting_id'] ?? '-',
                    'zoom_password' => $event['meeting_password'] ?? '-',
                    'youtube_tutorials' => $event['youtube_tutorials'] ?? null,
                    'type' => 'live'
                ];
            }
        }

        // FINAL FALLBACK: If not found by type, check ALL tables sequentially (robust lookup)
        if (!$layanan) {
            $db = \Config\Database::connect();
            
            // 1. Try Main Table
            $layanan = $layananModel->find($id);
            if ($layanan) {
                // Count real participants from confirmed transactions
                $confirmedCount = $transaksiModel
                    ->where('layanan_id', $id)
                    ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                    ->countAllResults();
                
                $layanan['type'] = 'recorded';
                $layanan['thumbnail'] = $this->fixLayananImageUrl($layanan['thumbnail'] ?? '');
                $layanan['students'] = $confirmedCount;
            }
            
            // 2. Try Tools
            if (!$layanan) {
                $tool = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
                if ($tool) {
                    // Count real participants from confirmed transactions
                    $confirmedCount = $transaksiModel
                        ->where('layanan_id', $id)
                        ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                        ->countAllResults();
                    
                    $layanan = [
                        'id' => $tool['id'],
                        'name' => $tool['name'],
                        'slug' => $tool['slug'] ?? 'tool-' . $tool['id'],
                        'category' => 'Tools',
                        'subcategory' => ($tool['type'] == 'ea') ? 'Expert Advisor' : 'Toolkit',
                        'description' => $tool['description'],
                        'thumbnail' => $this->fixLayananImageUrl($tool['thumbnail'] ?? ''),
                        'price' => $tool['price'],
                        'rating' => 0,
                        'students' => $confirmedCount,
                        'mode' => 'Download',
                        'duration' => 'Lifetime',
                        'level' => 'Advanced',
                        'modules' => 1,
                        'youtube_tutorials' => $tool['youtube_tutorials'] ?? null,
                        'type' => 'tool'
                    ];
                }
            }

            // 3. Try Event
            if (!$layanan) {
                $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
                if ($event) {
                    // Count real participants from confirmed transactions
                    $confirmedCount = $transaksiModel
                        ->where('layanan_id', $id)
                        ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                        ->countAllResults();
                    
                    $layanan = [
                        'id' => $event['id'],
                        'name' => $event['title'],
                        'slug' => $event['slug'],
                        'category' => 'Event',
                        'subcategory' => $event['type'],
                        'description' => $event['description'],
                        'thumbnail' => $this->fixLayananImageUrl($event['thumbnail'] ?? ''),
                        'wpa_id' => $event['wpa_id'],
                        'price' => $event['price'],
                        'rating' => 0,
                        'students' => $confirmedCount,
                        'mode' => ($event['type'] == 'webinar') ? 'Live' : 'Offline',
                        'youtube_tutorials' => $event['youtube_tutorials'] ?? null,
                        'type' => 'live'
                    ];
                }
            }
            
            // 4. Try Subscription
            if (!$layanan) {
                $sub = $db->table('layanan_subscription')->where('id', $id)->get()->getRowArray();
                if ($sub) {
                    // Count real participants from confirmed transactions
                    $confirmedCount = $transaksiModel
                        ->where('layanan_id', $id)
                        ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                        ->countAllResults();
                    
                    $layanan = [
                        'id' => $sub['id'],
                        'name' => $sub['name'],
                        'slug' => $sub['subcategory'] ?? 'subscription',
                        'category' => 'Subscription',
                        'subcategory' => $sub['subcategory'] ?? 'General',
                        'description' => $sub['description'],
                        'thumbnail' => $this->fixLayananImageUrl($sub['thumbnail'] ?? ''),
                        'price' => $sub['price'],
                        'rating' => 0,
                        'students' => $confirmedCount,
                        'mode' => 'Online',
                        'youtube_tutorials' => $sub['youtube_tutorials'] ?? null,
                        'type' => 'subscription'
                    ];
                }
            }
        }


        if (!$layanan) {
            return redirect()->to('/wpa/dashboard/layanan-saya')->with('error', 'Layanan tidak ditemukan');
        }

        // Map fields for view compatibility
        $layanan['title'] = $layanan['name'];

        // Get WPA info
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $wpaModel->find($layanan['wpa_id']);
        }

        // Determine if live or recorded
        $isLive = ($layanan['mode'] ?? 'Online') !== 'Online' && ($layanan['type'] ?? '') !== 'tool';

        // Materials & Resources (Fetched below after layananType is defined)
        $materials = [];

        // Check completion status
        $completionModel = new \App\Models\LayananCompletionModel();
        $certificateModel = new \App\Models\CertificateModel();

        $layananType = ($layanan['type'] ?? '') === 'tool' ? 'tool' : (isset($event) ? 'event' : 'layanan');

        $resourceType = ($layanan['type'] ?? '') === 'tool' ? 'tool' : (isset($event) ? 'event' : 'layanan');
        if (isset($tool['type'])) {
            $resourceType = $tool['type'];
        } elseif (isset($event['type'])) {
            $resourceType = $event['type'];
        }

        $resourceModel = new \App\Models\LayananResourceModel();
        $materials = $resourceModel->getByLayanan($resourceType, $id);
        $isCompleted = $completionModel->isCompleted($userId, $id, $layananType);
        $certificate = null;

        if ($isCompleted) {
            $certificate = $certificateModel->getCertificateForLayanan($userId, $id, $layananType);
        }

        // Check for EA License if it's a tool
        // Check for EA License Capability
        $license = null;
        $toolItem = null; // Renamed to avoid confusion
        $db = \Config\Database::connect();

        // Cekapakah ini Tools atau Event yang berlisensi
        if (isset($layanan['type']) && ($layanan['type'] === 'tool' || $layanan['type'] === 'live' || $layanan['type'] === 'webinar' || $layanan['type'] === 'workshop')) {
            if ($layanan['type'] === 'tool') {
                $toolItem = $db->table('layanan_tools')->where('id', $layanan['id'])->get()->getRowArray();
            } else {
                // For live/webinar/workshop
                $toolItem = $db->table('layanan_event')->where('id', $layanan['id'])->get()->getRowArray();
            }

            // Check if license exists for this transaction
            if ($purchase) {
                $eaLicenseModel = new \App\Models\EaLicenseModel();
                $license = $eaLicenseModel->where('order_id', $purchase['id'])->first();
            }
        }

        return view('wpa/layanan_detail', [
            'title' => $layanan['name'] . ' - Almai WPA',
            'kelas' => $layanan,
            'wpa' => $wpa,
            'isLive' => $isLive,
            'materials' => $materials,
            'isCompleted' => $isCompleted,
            'certificate' => $certificate,
            'layananType' => $layananType,
            'transaksiId' => $purchase['id'] ?? null,
            'tool' => $toolItem,
            'license' => $license,
            'pageTitle' => 'Detail Layanan',
            'activeMenu' => 'layanan-saya'
        ]);
    }

    private function fixLayananImageUrl($path)
    {
        if (!$path) return 'https://via.placeholder.com/400x200';
        if (str_starts_with($path, 'http')) return $path;
        return base_url('file/' . $path);
    }

    /**
     * Mark layanan as completed and generate certificate
     */
    public function complete($id)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $layananModel = new \App\Models\LayananModel();
        $wpaModel = new \App\Models\WpaModel();
        $userModel = new \App\Models\UserModel();
        $completionModel = new \App\Models\LayananCompletionModel();
        $certificateModel = new \App\Models\CertificateModel();

        // Handle ID collision by checking for type or specific transaction ID
        $transaksiId = $this->request->getGet('trx');
        $purchase = null;

        if (!empty($transaksiId)) {
            $purchase = $transaksiModel->where('id', $transaksiId)->where('user_id', $userId)->first();
        }

        if (!$purchase) {
            $purchase = $transaksiModel
                ->where('user_id', $userId)
                ->where('layanan_id', $id)
                ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                ->orderBy('created_at', 'DESC')
                ->first();

        }

        if (!$purchase) {
            return redirect()->to('/wpa/dashboard/layanan-saya')->with('error', 'Transaksi tidak ditemukan');
        }

        // Get layanan info
        $layananName = '';
        $wpaName = '';
        $layananType = 'layanan';

        $layanan = $layananModel->find($id);
        if ($layanan) {
            $layananName = $layanan['name'];
            $wpa = $wpaModel->find($layanan['wpa_id'] ?? 0);
            $wpaName = $wpa['name'] ?? 'ALMAI Team';
        } else {
            // Check event table
            $db = \Config\Database::connect();
            $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            if ($event) {
                $layananName = $event['title'];
                $layananType = 'event';
                $wpa = $wpaModel->find($event['wpa_id'] ?? 0);
                $wpaName = $wpa['name'] ?? 'ALMAI Team';
            }
        }

        if (!$layananName) {
            return redirect()->to('/wpa/dashboard/layanan-detail/' . $id)->with('error', 'Layanan tidak ditemukan');
        }

        // Get user info
        $user = $userModel->find($userId);
        $userName = $user['name'] ?? 'User';

        // Mark as completed
        $completion = $completionModel->markComplete($userId, $id, $layananType, $purchase['id']);

        // Create certificate
        $certificate = $certificateModel->createCertificate(
            $userId,
            $id,
            $layananType,
            $layananName,
            $userName,
            $wpaName,
            $completion['id']
        );

        // Update completion with certificate ID
        $completionModel->setCertificate($completion['id'], $certificate['id']);

        return redirect()->to('/wpa/dashboard/layanan-detail/' . $id)->with('success', 'Selamat! Anda telah menyelesaikan layanan ini. Sertifikat sudah tersedia.');
    }

    /**
     * Handle manual license activation by user
     */
    public function activateLicense($id)
    {
        $userId = session()->get('userId');
        $transaksiId = $this->request->getPost('transaksi_id');
        $brokerName = $this->request->getPost('broker_name');
        $accountNumber = $this->request->getPost('account_number');

        if (!$transaksiId || !$brokerName || !$accountNumber) {
            return redirect()->back()->with('error', 'Mohon lengkapi semua data aktivasi.');
        }

        $transaksiModel = new TransaksiModel();

        // Update user notes/profile with this new info? 
        // Or pass directly to license generator.

        // We'll update the transaction notes to store Broker Name for record
        $transaksi = $transaksiModel->find($transaksiId);
        if (!$transaksi || $transaksi['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Transaksi tidak valid.');
        }

        $currentNotes = $transaksi['notes'] ?? '';
        $newNotes = $currentNotes . " | Broker: $brokerName | Account: $accountNumber";
        $transaksiModel->update($transaksiId, ['notes' => $newNotes]);

        // Determine table based on transaction product type
        $db = \Config\Database::connect();
        $item = null;
        $productType = strtolower($transaksi['product_type'] ?? '');

        // FORCE CHECK: Try layanan_event first (priority for webinar/workshop with licenses)
        // This ensures we get license_duration field correctly
        $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();

        // If not found in event, try tools table
        if (!$item) {
            $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
        }

        if (!$item) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan.');
        }

        // Generate License Key - Format: ANGEL-GOLD-{ACCOUNT}-{4RANDOM}
        // Example: ANGEL-GOLD-279447062-8411
        $productTitle = $item['title'] ?? $item['name'] ?? 'PRODUCT'; // For logging
        $random4Digit = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 0000-9999
        $licenseKey = 'ANGEL-GOLD-' . $accountNumber . '-' . $random4Digit;

        // Calculate Expiration - EXPLICIT DEBUGGING
        $expiresAt = null;

        // Get license_duration - check multiple possible field names
        $durationRaw = $item['license_duration'] ?? $item['duration'] ?? null;
        $licenseDuration = intval($durationRaw);

        if ($licenseDuration > 0) {
            // Calculate expiration date based on duration in days
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$licenseDuration} days"));
        } else {
            // Lifetime license - expires_at stays NULL
        }

        $licenseModel = new \App\Models\EaLicenseModel();

        // Check if already exists
        $exist = $licenseModel->where('order_id', $transaksiId)->first();
        if ($exist) {
            return redirect()->back()->with('error', 'Lisensi sudah pernah diaktivasi untuk transaksi ini.');
        }

        $licenseId = $licenseModel->insert([
            'order_id' => $transaksiId,
            'user_id' => $userId,
            'broker_name' => $brokerName,
            'account_trading_number' => $accountNumber,
            'license_key' => $licenseKey,
            'status' => 'active',
            'expires_at' => $expiresAt,  // Will be NULL for lifetime, or date for limited
            'created_at' => date('Y-m-d H:i:s'),
            'license_activated_at' => date('Y-m-d H:i:s')
        ]);

        if ($licenseId) {
            // Send Email Notification (Same as user controller)
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);

            if ($user && !empty($user['email'])) {
                $email = \Config\Services::email();

                // Email content
                $emailSubject = '✅ Lisensi EA Anda Berhasil Diaktivasi - ' . $productTitle;

                $expiryText = $expiresAt ? date('d M Y H:i', strtotime($expiresAt)) . ' WIB' : 'Lifetime (Selamanya)';
                $durationText = $licenseDuration > 0 ? $licenseDuration . ' Hari' : 'Lifetime';

                // Construct basic body - similar to user controller
                $emailBody = "
                <h2 style='color: #33e818;'>🎉 Lisensi EA Berhasil Diaktivasi!</h2>
                <p>Halo <strong>{$user['name']}</strong>,</p>
                <p>Lisensi Expert Advisor Anda untuk produk <strong>{$productTitle}</strong> telah berhasil diaktivasi.</p>
                
                <div style='background: #f5f5f5; padding: 20px; border-radius: 10px; margin: 20px 0;'>
                    <h3 style='color: #333; margin-top: 0;'>Detail Lisensi:</h3>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='padding: 8px 0;'><strong>License Key:</strong></td>
                            <td style='padding: 8px 0; font-family: monospace; color: #33e818; font-size: 16px;'><strong>{$licenseKey}</strong></td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Broker:</strong></td>
                            <td style='padding: 8px 0;'>{$brokerName}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Akun Trading:</strong></td>
                            <td style='padding: 8px 0; font-family: monospace;'>{$accountNumber}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Durasi:</strong></td>
                            <td style='padding: 8px 0;'>{$durationText}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Berlaku Hingga:</strong></td>
                            <td style='padding: 8px 0;'>{$expiryText}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Status:</strong></td>
                            <td style='padding: 8px 0; color: #33e818;'><strong>ACTIVE</strong></td>
                        </tr>
                    </table>
                </div>
                
                <h3>📥 Cara Menggunakan:</h3>
                <div style='background: #e7f9e7; padding: 15px; border-left: 4px solid #33e818; margin: 15px 0;'>
                    <strong>✅ File EA sudah terlampir di email ini!</strong> Download attachment untuk mendapatkan file Expert Advisor.
                </div>
                <ol>
                    <li>Download file Expert Advisor dari <strong>attachment email ini</strong> atau dari halaman produk Anda</li>
                    <li>Install EA ke folder MQL5/Experts (MT5) atau MQL4/Experts (MT4)</li>
                    <li>Restart MetaTrader</li>
                    <li>Pasang EA pada chart</li>
                    <li>Masukkan <strong>License Key</strong> pada parameter input EA</li>
                    <li>Pastikan <strong>Allow WebRequest</strong> sudah diaktifkan di Tools → Options → Expert Advisors</li>
                </ol>
                
                <p style='color: #666; font-size: 12px; margin-top: 30px;'>
                    Email ini dikirim otomatis dari sistem Almai.id<br>
                    © 2025 Almai.id - Made with 💚 in Bali
                </p>
                ";

                $email->setFrom('noreply@almai.id', 'Almai Platform');
                $email->setTo($user['email']);
                $email->setSubject($emailSubject);
                $email->setMessage($emailBody);

                // Attach EA file if exists
                if (!empty($item['ea_file_path'])) {
                    $eaFilePath = WRITEPATH . $item['ea_file_path'];
                    if (file_exists($eaFilePath)) {
                        $email->attach($eaFilePath);
                    }
                }

                $email->send();
            }

            return redirect()->back()->with('success', 'Lisensi berhasil diaktivasi! Silakan cek detail di bawah.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengaktivasi lisensi. Silakan coba lagi.');
        }
    }
}
