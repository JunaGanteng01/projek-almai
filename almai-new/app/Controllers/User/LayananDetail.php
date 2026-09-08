<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\WpaModel;
use App\Models\LayananModel;
use App\Models\UserModel;
use App\Models\LayananCompletionModel;
use App\Models\CertificateModel;
use App\Models\LayananResourceModel;
use App\Models\EaLicenseModel;
use App\Models\LayananUlasanModel;
use App\Models\NotificationModel;

class LayananDetail extends BaseController
{
    public function index($id)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $layananModel = new LayananModel();
        $wpaModel = new WpaModel();

        // Get product type from URL parameter (if provided)
        $productType = $this->request->getGet('type');
        $transaksiId = $this->request->getGet('trx');

        // DEBUG LOGGING
        log_message('info', '=== LayananDetail.index() DEBUG ===');
        log_message('info', 'User ID: ' . $userId);
        log_message('info', 'Layanan ID: ' . $id);
        log_message('info', 'Transaksi ID: ' . ($transaksiId ?? 'null'));
        log_message('info', 'Product Type: ' . ($productType ?? 'null'));

        // Check if user has purchased this layanan
        $purchase = null;

        if (!empty($transaksiId)) {
            $purchase = $transaksiModel
                ->where('id', $transaksiId)
                ->where('user_id', $userId)
                ->where('status', 'confirmed')
                ->first();
            log_message('info', 'Purchase by transaksi ID: ' . ($purchase ? 'FOUND' : 'NOT FOUND'));
        }

        if (!$purchase) {
            $purchase = $transaksiModel
                ->where('user_id', $userId)
                ->where('layanan_id', $id)
                ->where('status', 'confirmed')
                ->orderBy('id', 'DESC')
                ->first();
            log_message('info', 'Purchase by layanan_id: ' . ($purchase ? 'FOUND' : 'NOT FOUND'));
        }

        if (!$purchase) {
            // FALLBACK: If strict check fails, try to find ANY confirmed transaction for this user
            // that might match the requested service name or product_name
            // useful if layanan_id wasn't updated correctly in the previous steps

            $targetService = $layananModel->find($id);
            if ($targetService) {
                $fuzzy = $transaksiModel
                    ->where('user_id', $userId)
                    ->where('status', 'confirmed')
                    ->like('product_name', $targetService['name'])
                    ->first();
                if ($fuzzy) {
                    $purchase = $fuzzy;
                    log_message('info', 'Purchase by fuzzy name match: FOUND');
                    // Auto-fix the link for next time
                    $transaksiModel->update($fuzzy['id'], ['layanan_id' => $id]);
                }
            }
        }

        if (!$purchase) {
            log_message('error', 'NO PURCHASE FOUND - Redirecting to layanan-saya');
            return redirect()->to('/user/layanan-saya')->with('error', 'Anda belum membeli layanan ini');
        }

        log_message('info', 'Purchase found: ID=' . $purchase['id'] . ' ProductType=' . $purchase['product_type'] . ' ProductName=' . $purchase['product_name']);

        // Determine type from transaction if available, or use URL parameter
        $transactionProductType = strtolower($purchase['product_type'] ?? '');
        $purchaseName = $purchase['product_name'] ?? '';
        $layanan = null;

        // Use product_type from URL if provided, otherwise use transaction type
        // BUT: Don't override if URL type is just a display hint (like 'recorded')
        if (!empty($productType) && $productType !== 'recorded' && $productType !== 'live') {
            $transactionProductType = strtolower($productType);
        }

        log_message('info', 'Transaction Product Type: ' . $transactionProductType);
        log_message('info', 'Purchase Name: ' . $purchaseName);

        // Fetch layanan data based on product_type from transaction
        $db = \Config\Database::connect();

        // 1. Try Layanan Tools Table (for tools/EA)
        if (!$layanan && ($transactionProductType === 'tools' || $transactionProductType === 'tool')) {
            log_message('info', 'Trying layanan_tools table...');
            $tool = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
            log_message('info', 'Tool found: ' . ($tool ? 'YES' : 'NO'));

            if ($tool) {
                // Check if transaction matches this tool (by ID or Name)
                $isMatch = ($purchase['layanan_id'] == $id);
                if (!$isMatch) {
                    $isMatch = (stripos($purchaseName, strtolower($tool['name'])) !== false || stripos(strtolower($tool['name']), $purchaseName) !== false);
                }

                // Extra fallback: if product_type matches 'tools'
                if (!$isMatch && ($transactionProductType === 'tools' || $transactionProductType === 'tool')) {
                    $isMatch = true; // Assume ID match inferred from context if type is explicit
                }

                log_message('info', 'Tool match: ' . ($isMatch ? 'YES' : 'NO'));

                if ($isMatch) {
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
                        'layanan_utama' => $tool['layanan_utama'] ?? null,
                        'youtube_tutorials' => $tool['youtube_tutorials'] ?? null,
                        'type' => 'tool'
                    ];
                    log_message('info', 'Tool layanan created successfully');
                }
            }

            // 2. Try Layanan Event Table - search by ID first, then by name
            if (!$layanan && ($transactionProductType === 'event' || $transactionProductType === 'webinar' || $transactionProductType === 'workshop' || $transactionProductType === 'live' || $transactionProductType === 'live_trade')) {
                log_message('info', 'Trying layanan_event table...');
                // Try by ID first (most reliable)
                $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
                log_message('info', 'Event found by ID: ' . ($event ? 'YES' : 'NO'));

                if ($event) {
                    $evTitle = strtolower(trim($event['title'] ?? ''));
                    $purTitle = strtolower(trim($purchaseName));
                    $match = ($evTitle === $purTitle) || (stripos($purTitle, $evTitle) !== false) || (stripos($evTitle, $purTitle) !== false);
                    if (!$match) {
                        $event = null;
                    }
                }

                // If not found by ID or name mismatch, try by name and base name
                if (!$event) {
                    $event = $db->table('layanan_event')->where('title', $purchaseName)->get()->getRowArray();
                    if (!$event) {
                        $event = $db->table('layanan_event')->like('title', $purchaseName)->get()->getRowArray();
                    }
                    if (!$event && strpos($purchaseName, ' - ') !== false) {
                        $baseName = explode(' - ', $purchaseName)[0];
                        $event = $db->table('layanan_event')->like('title', $baseName)->get()->getRowArray();
                    }
                    if (!$event) {
                        $base = strtolower(trim($purchaseName));
                        $base = preg_replace('/\s+/', ' ', $base);
                        if (strpos($base, ' - ') !== false) {
                            $base = explode(' - ', $base)[0];
                        }
                        $slugGuess = preg_replace('/[^a-z0-9]+/i', '-', $base);
                        $slugGuess = trim($slugGuess, '-');
                        $event = $db->table('layanan_event')->where('slug', $slugGuess)->get()->getRowArray();
                    }
                    log_message('info', 'Event found by name: ' . ($event ? 'YES' : 'NO'));
                }

                if ($event) {
                    if ($event['id'] != $id) {
                        $id = $event['id'];
                        $transaksiModel->update($purchase['id'], ['layanan_id' => $id]);
                    }
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
                        'layanan_utama' => $event['layanan_utama'] ?? null,
                        'youtube_tutorials' => $event['youtube_tutorials'] ?? null,
                        'type' => 'live'
                    ];
                }
            }
        }

        // 3. Try Main Layanan Table (for main layanan products)
        if (!$layanan && ($transactionProductType === 'layanan' || $transactionProductType === 'kelas')) {
            log_message('info', 'Trying main layanan table...');
            // For main layanan, try to find by ID first (most reliable)
            $layanan = $layananModel->find($id);
            log_message('info', 'Layanan found by ID: ' . ($layanan ? 'YES' : 'NO'));

            // If not found by ID, try by name (exact match)
            if (!$layanan) {
                $layanan = $layananModel->where('name', $purchaseName)->first();
                log_message('info', 'Layanan found by exact name: ' . ($layanan ? 'YES' : 'NO'));
            }

            // If still not found, try partial match (handle package names like "Angel Gold - Trial")
            if (!$layanan) {
                // Extract base name (before " - ")
                $baseName = explode(' - ', $purchaseName)[0];
                $layanan = $layananModel->where('name', $baseName)->first();
                log_message('info', 'Layanan found by base name "' . $baseName . '": ' . ($layanan ? 'YES' : 'NO'));
            }

            // If still not found, try fuzzy match
            if (!$layanan) {
                $layanan = $layananModel->like('name', $purchaseName)->first();
                log_message('info', 'Layanan found by fuzzy match: ' . ($layanan ? 'YES' : 'NO'));
            }

            if ($layanan) {
                $layanan['type'] = 'recorded';
                $layanan['thumbnail'] = $this->fixLayananImageUrl($layanan['thumbnail'] ?? '');
                log_message('info', 'Main layanan created successfully');
            }
        }

        // 3a. Try Layanan Artikel Table
        if (!$layanan && ($transactionProductType === 'artikel' || stripos($transactionProductType, 'artikel') !== false)) {
            log_message('info', 'Trying layanan_artikel table...');
            $artikel = $db->table('layanan_artikel')->where('id', $id)->get()->getRowArray();
            if (!$artikel) {
                $artikel = $db->table('layanan_artikel')->where('title', $purchaseName)->get()->getRowArray();
            }

            if ($artikel) {
                $layanan = [
                    'id' => $artikel['id'],
                    'name' => $artikel['title'],
                    'slug' => $artikel['slug'],
                    'category' => 'Artikel',
                    'subcategory' => 'Artikel',
                    'description' => $artikel['content'],
                    'thumbnail' => $this->fixLayananImageUrl($artikel['thumbnail'] ?? ''),
                    'wpa_id' => $artikel['wpa_id'],
                    'price' => 0, // Artikels usually don't have price
                    'rating' => 0,
                    'students' => 0,
                    'mode' => 'Read',
                    'duration' => '-',
                    'level' => 'All Level',
                    'modules' => 1,
                    'layanan_utama' => '',
                    'youtube_tutorials' => $artikel['youtube_tutorials'] ?? null,
                    'type' => 'artikel',
                    'is_premium' => $artikel['is_pro_only'] ?? 0
                ];
                log_message('info', 'Artikel layanan created successfully');
            }
        }

        // 3b. Try Layanan Subscription Table
        if (!$layanan && ($transactionProductType === 'subscription' || in_array($transactionProductType, ['pendampingan', 'profirm', 'vip_member', 'private_konsultan']))) {
            log_message('info', 'Trying layanan_subscription table...');
            $sub = $db->table('layanan_subscription')->where('id', $id)->get()->getRowArray();
            
            if ($sub) {
                $layanan = [
                    'id' => $sub['id'],
                    'name' => $sub['name'],
                    'slug' => $sub['slug'],
                    'category' => 'Subscription',
                    'subcategory' => $sub['type'],
                    'description' => $sub['description'],
                    'thumbnail' => $this->fixLayananImageUrl($sub['thumbnail'] ?? ''),
                    'wpa_id' => $sub['wpa_id'] ?? null,
                    'price' => $sub['price'],
                    'rating' => 0,
                    'students' => 0,
                    'mode' => 'Online',
                    'duration' => $sub['duration_days'] . ' Hari',
                    'level' => 'All Level',
                    'modules' => 0,
                    'layanan_utama' => $sub['features'] ?? '',
                    'youtube_tutorials' => $sub['youtube_tutorials'] ?? null,
                    'type' => 'subscription'
                ];
                log_message('info', 'Subscription layanan created successfully');
            }
        }

        // 4. Final fallback - try any table by ID if still not found
        if (!$layanan) {
            log_message('info', 'Trying final fallback - main layanan table by ID...');
            // Try main layanan table as last resort
            $layanan = $layananModel->find($id);
            log_message('info', 'Fallback layanan found: ' . ($layanan ? 'YES' : 'NO'));
            if ($layanan) {
                $layanan['type'] = 'recorded';
                $layanan['thumbnail'] = $this->fixLayananImageUrl($layanan['thumbnail'] ?? '');
                log_message('info', 'Fallback layanan created successfully');
            }
        }

        // 5. Ultimate cross-table fallback by name if still not found
        if (!$layanan) {
            log_message('info', 'Ultimate fallback - cross-table by name');
            // Try Event by name/base
            $event = $db->table('layanan_event')->where('title', $purchaseName)->get()->getRowArray();
            if (!$event) {
                $event = $db->table('layanan_event')->like('title', $purchaseName)->get()->getRowArray();
            }
            if (!$event && strpos($purchaseName, ' - ') !== false) {
                $baseName = explode(' - ', $purchaseName)[0];
                $event = $db->table('layanan_event')->like('title', $baseName)->get()->getRowArray();
            }
            if ($event) {
                $id = $event['id'];
                $transaksiModel->update($purchase['id'], ['layanan_id' => $id]);
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
                    $schedule = "Setiap $dbDay, $time WIB";
                    $nextDate = new \DateTime("next $engDay $time");
                    if ($nextDate < new \DateTime()) {
                        $nextDate->modify('+1 week');
                    }
                    $nextSession = $nextDate->format('d M Y, H:i');
                }
                $confirmedCount = $transaksiModel->where('layanan_id', $id)->where('status', 'confirmed')->countAllResults();
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
                    'location' => $event['location'] ?? 'Online',
                    'duration' => '-',
                    'level' => 'All Level',
                    'modules' => 0,
                    'schedule' => $schedule,
                    'next_session' => $nextSession,
                    'zoom_link' => $event['zoom_link'] ?? '#',
                    'zoom_meeting_id' => $event['meeting_id'] ?? '-',
                    'zoom_password' => $event['meeting_password'] ?? '-',
                    'layanan_utama' => $event['layanan_utama'] ?? null,
                    'youtube_tutorials' => $event['youtube_tutorials'] ?? null,
                    'type' => 'live'
                ];
            }
        }

        if (!$layanan) {
            log_message('error', 'LAYANAN NOT FOUND - All lookups failed');
            log_message('error', 'Tried: ProductType=' . $transactionProductType . ' ID=' . $id . ' Name=' . $purchaseName);
            return redirect()->to('/user/layanan-saya')->with('error', 'Layanan tidak ditemukan');
        }

        log_message('info', 'Layanan found: ' . $layanan['name']);

        $isArtikel = (($layanan['type'] ?? '') === 'artikel');

        // Keep article detail on dedicated URL to avoid mixed layout/logic with class pages.
        $currentPath = trim($this->request->getUri()->getPath(), '/');
        if ($isArtikel && strpos($currentPath, 'user/layanan-artikel-detail/') !== 0) {
            $query = $this->request->getGet();
            $queryString = !empty($query) ? ('?' . http_build_query($query)) : '';
            return redirect()->to('/user/layanan-artikel-detail/' . $layanan['id'] . $queryString);
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
        $completionModel = new LayananCompletionModel();
        $certificateModel = new CertificateModel();

        if (($layanan['type'] ?? '') === 'artikel') {
            $layananType = 'artikel';
            $resourceType = 'artikel';
        } elseif (($layanan['type'] ?? '') === 'tool') {
            $layananType = 'tool';
            $resourceType = isset($tool['type']) ? $tool['type'] : 'tool';
        } elseif (isset($event)) {
            $layananType = 'event';
            $resourceType = isset($event['type']) ? $event['type'] : 'event';
        } else {
            $layananType = 'layanan';
            $resourceType = 'layanan';
        }

        $resourceModel = new LayananResourceModel();
        $materials = $resourceModel->getByLayanan($resourceType, $id);
        $isCompleted = $completionModel->isCompleted($userId, $id, $layananType);
        $certificate = null;

        if ($isCompleted) {
            $certificate = $certificateModel->getCertificateForLayanan($userId, $id, $layananType);
        }

        // Check for EA License if it's a tool
        // Check for EA License Capability
        $license = null;
        $tool = null;
        $db = \Config\Database::connect();

        // Cekapakah ini Tools atau Event yang berlisensi
        // Cekapakah ini Tools atau Event yang berlisensi
        // Note: For events, $layanan['type'] is mapped to 'live'
        if (isset($layanan['type']) && ($layanan['type'] === 'tool' || $layanan['type'] === 'live' || $layanan['type'] === 'webinar' || $layanan['type'] === 'workshop')) {
            if ($layanan['type'] === 'tool') {
                $tool = $db->table('layanan_tools')->where('id', $layanan['id'])->get()->getRowArray();
            } else {
                // For live/webinar/workshop
                $tool = $db->table('layanan_event')->where('id', $layanan['id'])->get()->getRowArray();
            }
            // DEBUG LICENSE FORM
            // echo "<!-- DEBUG: Type={$layanan['type']} ToolExists=" . ($tool ? 'Yes' : 'No') . " LicenseFlag=" . ($tool['is_license_product'] ?? 'Null') . " -->";

            // Check if license exists for this transaction
            if ($purchase) {
                $eaLicenseModel = new EaLicenseModel();
                $license = $eaLicenseModel->where('order_id', $purchase['id'])->first();
            }
        }

        if ($isArtikel) {
            return view('user/layanan-artikel-detail', [
                'title' => $layanan['name'] . ' - Almai',
                'kelas' => $layanan,
                'wpa' => $wpa,
                'materials' => $materials,
                'transaksiId' => $purchase['id'] ?? null,
            ]);
        }

        return view('user/kelas-detail', [
            'title' => $layanan['name'] . ' - Almai',
            'kelas' => $layanan,
            'wpa' => $wpa,
            'isLive' => $isLive,
            'materials' => $materials,
            'isCompleted' => $isCompleted,
            'certificate' => $certificate,
            'layananType' => $layananType,
            'transaksiId' => $purchase['id'] ?? null,
            'tool' => $tool,
            'license' => $license
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
        $layananModel = new LayananModel();
        $wpaModel = new WpaModel();
        $userModel = new UserModel();
        $completionModel = new LayananCompletionModel();
        $certificateModel = new CertificateModel();

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
                ->where('status', 'confirmed')
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        if (!$purchase) {
            return redirect()->to('/user/layanan-saya')->with('error', 'Transaksi tidak ditemukan');
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
            return redirect()->to('/user/layanan-detail/' . $id)->with('error', 'Layanan tidak ditemukan');
        }

        // Get user info
        $user = $userModel->find($userId);
        $userName = $user['name'] ?? 'User';

        // Process Review / Testimonial
        $rating = $this->request->getPost('rating');
        $testimoni = $this->request->getPost('testimoni');
        
        if ($rating > 0 && !empty($testimoni)) {
            $ulasanModel = new LayananUlasanModel();
            
            // Check if already reviewed to avoid duplicates
            if (!$ulasanModel->hasUserReviewed($userId, $id, $layananType)) {
                $ulasanModel->insert([
                    'user_id' => $userId,
                    'layanan_id' => $id,
                    'layanan_type' => $layananType,
                    'rating' => $rating,
                    'ulasan' => $testimoni,
                    'status' => 'approved'
                ]);
            }
        }

        // Update License Info if provided (Sync with EA License)
        $realBroker = $this->request->getPost('real_broker');
        $realAccount = $this->request->getPost('real_account');
        if ($realBroker && $realAccount) {
            $licenseModel = new EaLicenseModel();
            $license = $licenseModel->where('order_id', $purchase['id'])->first();
            if ($license) {
                $licenseModel->update($license['id'], [
                    'broker_name' => $realBroker,
                    'account_trading_number' => $realAccount
                ]);
            }
        }

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

        // Create notification
        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $userId,
            'Selamat! Anda mendapat Sertifikat',
            'Anda telah menyelesaikan layanan "' . $layananName . '" dan sertifikat sudah tersedia.',
            'success',
            '/user/sertifikat'
        );

        return redirect()->to('/user/layanan-detail/' . $id)->with('success', 'Selamat! Anda telah menyelesaikan layanan ini. Sertifikat sudah tersedia.');
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
        $tableUsed = '';
        $productType = strtolower($transaksi['product_type'] ?? '');
        $purchaseName = strtolower($transaksi['item_name'] ?? '');

        // 1. Precise Lookup based on Product Type
        if ($productType === 'tools' || $productType === 'tool' || stripos($purchaseName, 'ea') !== false || stripos($purchaseName, 'bot') !== false) {
            // It's a Tool/EA
            $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
            $tableUsed = 'layanan_tools';
        } elseif ($productType === 'event' || $productType === 'webinar' || $productType === 'workshop') {
            // It's an Event
            $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            $tableUsed = 'layanan_event';
        }

        // 2. Fallback if not found yet (maybe type mismatch)
        if (!$item) {
            // Try event first (legacy default)
            $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            if ($item) {
                $tableUsed = 'layanan_event';
            } else {
                $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
                if ($item) {
                    $tableUsed = 'layanan_tools';
                }
            }
        }

        if (!$item) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan.');
        }

        // DEBUG: Log which table was used
        log_message('info', 'Activate License: Table queried: ' . $tableUsed . ' for ID: ' . $id);

        $productTitle = $item['title'] ?? $item['name'] ?? 'PRODUCT'; // For logging
        $random4Digit = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 0000-9999
        
        $prefixFormat = $item['license_prefix'] ?? 'ALMAI-{id_akun}-{random4}';
        
        // Replace placeholders
        $licenseKey = str_replace(
            ['{id_akun}', '{random4}', '{account_number}'], 
            [$accountNumber, $random4Digit, $accountNumber], 
            $prefixFormat
        );

        // Fallback or ensure uniqueness if needed, but here we just follow the format

        // Calculate Expiration - EXPLICIT DEBUGGING
        $expiresAt = null;

        // Get license_duration - check multiple possible field names
        // Note: 'duration_days' is often used in subscription fields
        $durationRaw = $item['license_duration'] ?? $item['duration'] ?? $item['duration_days'] ?? null;
        $licenseDuration = intval($durationRaw);

        // EXTENSIVE DEBUG LOGGING
        log_message('info', '=== LICENSE ACTIVATION DEBUG ===');
        log_message('info', 'Product ID: ' . $id);
        log_message('info', 'Product Title: ' . $productTitle);
        log_message('info', 'Product Type: ' . $productType);
        log_message('info', 'Account Number: ' . $accountNumber);
        log_message('info', 'License Key: ' . $licenseKey);
        log_message('info', '--- Item Data (Full) ---');
        log_message('info', json_encode($item, JSON_PRETTY_PRINT));
        log_message('info', '--- License Duration Check ---');
        log_message('info', 'Duration Raw Value: ' . var_export($durationRaw, true));
        log_message('info', 'Duration (int): ' . $licenseDuration);
        log_message('info', 'Duration > 0? ' . ($licenseDuration > 0 ? 'YES' : 'NO'));

        if ($licenseDuration > 0) {
            // Calculate expiration date based on duration in days
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$licenseDuration} days"));
            log_message('info', '✓ EXPIRATION CALCULATED: ' . $expiresAt . ' (' . $licenseDuration . ' days from now)');
        } else {
            // Lifetime license - expires_at stays NULL
            log_message('info', '⚠ LICENSE IS LIFETIME (expires_at = NULL) - Duration is ' . $licenseDuration);
            // No error shown to user, assume lifetime is valid if duration is 0
        }

        $licenseModel = new EaLicenseModel();

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
            // Send Email Notification
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if ($user && !empty($user['email'])) {
                $email = \Config\Services::email();

                // Email content
                $emailSubject = '✅ Lisensi EA Anda Berhasil Diaktivasi - ' . $productTitle;

                $expiryText = $expiresAt ? date('d M Y H:i', strtotime($expiresAt)) . ' WIB' : 'Lifetime (Selamanya)';
                $durationText = $licenseDuration > 0 ? $licenseDuration . ' Hari' : 'Lifetime';

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
                
                <div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>
                    <strong>⚠️ Penting:</strong> Simpan License Key Anda dengan aman. License ini terkunci pada akun trading <strong>{$accountNumber}</strong> dan tidak dapat diubah.
                </div>
                
                <p style='margin-top: 30px;'>
                    Jika ada pertanyaan, silakan hubungi kami melalui:<br>
                    📧 Email: support@almai.id<br>
                    📱 WhatsApp: +62 851-8323-1800
                </p>
                
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
                        log_message('info', 'EA file attached to email: ' . $item['ea_file_path']);
                    } else {
                        log_message('warning', 'EA file not found for attachment: ' . $eaFilePath);
                    }
                }

                // Send email
                if ($email->send()) {
                    log_message('info', '✓ License activation email sent to: ' . $user['email']);
                } else {
                    log_message('error', '✗ Failed to send license email: ' . $email->printDebugger(['headers']));
                }
            }

            return redirect()->back()->with('success', 'Lisensi berhasil diaktivasi! Silakan cek detail di bawah.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengaktivasi lisensi. Silakan coba lagi.');
        }
    }

    /**
     * Renew license for 30 days
     */
    public function renewLicense($id)
    {
        $userId = session()->get('userId');
        $transaksiId = $this->request->getPost('transaksi_id');

        if (!$transaksiId) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $licenseModel = new EaLicenseModel();
        $license = $licenseModel->where('order_id', $transaksiId)->where('user_id', $userId)->first();

        if (!$license) {
            return redirect()->back()->with('error', 'Lisensi tidak ditemukan untuk diperpanjang.');
        }

        // Get current expiry or use now if already expired or NULL
        $currentExpiry = !empty($license['expires_at']) ? strtotime($license['expires_at']) : time();
        if ($currentExpiry < time()) {
            $currentExpiry = time();
        }

        // Add 30 days
        $newExpiry = date('Y-m-d H:i:s', strtotime('+30 days', $currentExpiry));

        $updated = $licenseModel->update($license['id'], [
            'expires_at' => $newExpiry,
            'status' => 'active', // Ensure it's active if it was expired
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Lisensi berhasil diperpanjang 30 hari! Berlaku hingga ' . date('d M Y, H:i', strtotime($newExpiry)) . ' WIB');
        } else {
            return redirect()->back()->with('error', 'Gagal memperpanjang lisensi.');
        }
    }
}
