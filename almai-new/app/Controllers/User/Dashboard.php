<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\PoinModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    private function fixLayananImageUrl($path)
    {
        if (empty($path)) return null;
        if (strpos($path, 'http') === 0) return $path;
        if (strpos($path, 'uploads/') === 0) return base_url('file/' . $path);
        return base_url($path);
    }

    protected function getStats($userId)
    {
        $transaksiModel = new TransaksiModel();
        $poinModel = new PoinModel();
        $userModel = new UserModel();
        $certificateModel = new \App\Models\CertificateModel();

        $myKelas = $transaksiModel
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->countAllResults();

        $totalTransaksi = $transaksiModel->where('user_id', $userId)->countAllResults();
        $totalPoin = $poinModel->getUserBalance($userId);
        $poinEarned = $poinModel->where('user_id', $userId)->where('type', 'earn')->selectSum('point')->first()['point'] ?? 0;
        $poinUsed = $poinModel->where('user_id', $userId)->whereIn('type', ['spend', 'redeem'])->selectSum('point')->first()['point'] ?? 0;

        // Count actual certificates
        $totalSertifikat = $certificateModel->where('user_id', $userId)->countAllResults();

        // Referral count fix: Count users who have this user's code as their affiliator_code
        // First get current user's referral code
        $currentUser = $userModel->find($userId);
        $myReferralCode = $currentUser['code_referral'] ?? null;
        $myGroupId = $currentUser['referral_group_id'] ?? null;

        if ($myGroupId) {
            $groupMembers = $userModel->where('referral_group_id', $myGroupId)->find();
            $codes = array_filter(array_column($groupMembers, 'code_referral'));
            if (!empty($codes)) {
                $totalReferral = $userModel->whereIn('affiliator_code', $codes)->countAllResults();
            } else {
                $totalReferral = 0;
            }
        } else if ($myReferralCode) {
            $totalReferral = $userModel->where('affiliator_code', $myReferralCode)->countAllResults();
        } else {
            $totalReferral = 0;
        }

        return [
            'totalKelas' => $myKelas,
            'totalTransaksi' => $totalTransaksi,
            'totalSertifikat' => $totalSertifikat,
            'totalPoin' => $totalPoin,
            'poinEarned' => $poinEarned,
            'poinUsed' => $poinUsed,
            'totalReferral' => $totalReferral,
            'balance' => $currentUser['balance'] ?? 0,
        ];
    }

    private function calculateDownlineStats($userId)
    {
        $userModel = new UserModel();
        $currentUser = $userModel->find($userId);

        // 1. Get Recursive Downlines (Max 10 Levels)
        $referralUserIds = []; // Direct (Level 1)
        $allDownlineIds = [];  // Total Recursive (Level 1-10)

        // Start from Current User
        $currentLevelUsers = [$currentUser];
        $processedIds = [$userId]; // Prevent cycles (exclude self)

        for ($level = 1; $level <= 10; $level++) {
            if (empty($currentLevelUsers)) break;

            $parentIds = [];
            $parentNamesLower = [];
            $parentCodes = [];

            foreach ($currentLevelUsers as $p) {
                $parentIds[] = $p['id'];
                if (!empty($p['name'])) $parentNamesLower[] = strtolower($p['name']);
                if (!empty($p['code_referral'])) $parentCodes[] = $p['code_referral'];
                // Check 'referral_code' legacy column just in case
                if (!empty($p['referral_code'])) $parentCodes[] = $p['referral_code'];
            }

            $parentIds = array_unique($parentIds);
            $parentNamesLower = array_unique(array_filter($parentNamesLower));
            $parentCodes = array_unique(array_filter($parentCodes));

            if (empty($parentIds) && empty($parentCodes) && empty($parentNamesLower)) break;

            // Use a fresh model instance to avoid query builder contamination
            $searchModel = new UserModel();
            $subBuilder = $searchModel->whereNotIn('id', $processedIds);

            $subBuilder->groupStart();
            $hasCriteria = false;

            if (!empty($parentIds)) {
                $subBuilder->whereIn('referred_by', $parentIds);
                $hasCriteria = true;
            }

            if (!empty($parentCodes)) {
                if ($hasCriteria) $subBuilder->orWhereIn('affiliator_code', $parentCodes);
                else {
                    $subBuilder->whereIn('affiliator_code', $parentCodes);
                    $hasCriteria = true;
                }
            }

            if (!empty($parentNamesLower)) {
                $namesStr = implode("','", array_map(function ($n) {
                    return \Config\Database::connect()->escapeString($n);
                }, $parentNamesLower));

                $rawSql = "LOWER(affiliator_code) IN ('$namesStr')";
                if ($hasCriteria) $subBuilder->orWhere($rawSql);
                else {
                    $subBuilder->where($rawSql);
                    $hasCriteria = true;
                }
            }
            $subBuilder->groupEnd();

            $newDownlines = $subBuilder->findAll();
            if (empty($newDownlines)) break;

            $nextLevelUsers = [];
            foreach ($newDownlines as $d) {
                if (!in_array($d['id'], $processedIds)) {
                    $allDownlineIds[] = $d['id'];
                    $processedIds[] = $d['id'];
                    $nextLevelUsers[] = $d;

                    if ($level === 1) {
                        $referralUserIds[] = $d['id'];
                    }
                }
            }
            $currentLevelUsers = $nextLevelUsers;
        }

        // Stats Calculation
        $stats = [
            'totalUserCount' => count($allDownlineIds),
            'totalDirectCount' => count($referralUserIds),
            'totalCwpaCount' => 0,
            'totalProCount' => 0,
            'totalStandardUserCount' => 0,
            'totalTransactions' => 0
        ];

        if (!empty($allDownlineIds)) {
            $statsModel = new UserModel();
            $statsQuery = $statsModel->whereIn('id', $allDownlineIds)
                ->select('level_id, count(id) as count')
                ->groupBy('level_id')
                ->findAll();

            foreach ($statsQuery as $stat) {
                if ($stat['level_id'] == \App\Models\LevelModel::LEVEL_PRO) {
                    $stats['totalProCount'] = $stat['count'];
                } elseif ($stat['level_id'] == \App\Models\LevelModel::LEVEL_CWPA) {
                    $stats['totalCwpaCount'] = $stat['count'];
                } elseif ($stat['level_id'] == \App\Models\LevelModel::LEVEL_USER) {
                    $stats['totalStandardUserCount'] = $stat['count'];
                }
            }

            // Total Pembelian (Transactions from DOWNLINE)
            $transaksiModel = new TransaksiModel();
            $stats['totalTransactions'] = $transaksiModel->whereIn('user_id', $allDownlineIds)
                ->where('status', 'confirmed')
                ->countAllResults();
        }

        return $stats;
    }

    public function index()
    {
        $userId = session()->get('userId');

        $transaksiModel = new TransaksiModel();
        $layananModel = new \App\Models\LayananModel();
        $wpaModel = new \App\Models\WpaModel();
        $userModel = new UserModel();
        $levelModel = new \App\Models\LevelModel();

        // Get current user
        $currentUser = $userModel->find($userId);

        // Get user level
        $userLevel = $levelModel->find($currentUser['level_id'] ?? 1);

        // Check if user can checkin today
        $poinModel = new PoinModel();
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $poinModel->where('user_id', $userId)
            ->where('pointable_type', 'chekin')
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->first();
        $canCheckin = !$alreadyCheckedIn;

        // Get daily checkin amount from settings
        $settingModel = new \App\Models\SettingModel();
        $poinDailyCheckin = $settingModel->get('poin_daily_checkin', 10);

        // Get recent transactions
        $recentTransaksi = $transaksiModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Get confirmed layanan purchases (limit 3)
        // Get confirmed layanan purchases (limit 3)
        $recentPurchases = $transaksiModel
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            // Don't filter by product_type, show all services
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->findAll();

        $myLayanan = [];
        $db = \Config\Database::connect();

        foreach ($recentPurchases as $trx) {
            // CWPA Handler
            if ($trx['product_type'] === 'cwpa' || stripos($trx['product_name'], 'CWPA') !== false) {
                $item = $trx;
                // Fix: Don't overwrite ID with 0 if valid ID exists
                $item['kelas_id'] = (!empty($trx['layanan_id']) && $trx['layanan_id'] > 0) ? $trx['layanan_id'] : 'cwpa';
                $item['layanan_id'] = (!empty($trx['layanan_id']) && $trx['layanan_id'] > 0) ? $trx['layanan_id'] : 0;

                $item['title'] = 'Pendampingan CWPA';
                $item['thumbnail'] = 'uploads/cwpa.jpg';
                $item['type'] = 'cwpa'; // Unique Type
                $item['type_label'] = 'Mentorship';
                $item['schedule'] = null;
                $item['next_session'] = null;
                $item['description'] = 'Program Persiapan Ujian CWPA';
                $item['mode'] = 'Hybrid';
                $item['level'] = 'Professional';
                $item['wpa_name'] = 'Tim Almai';
                $myLayanan[] = $item;
                continue;
            }

            if (!empty($trx['layanan_id'])) {
                // Special Handler for Advocacy Membership
                if ($trx['layanan_id'] == 9999) {
                    $layanan = [
                        'id' => 9999,
                        'name' => 'Membership Advokasi Almai',
                        'thumbnail' => base_url('images/adv.png'),
                        'description' => 'Akses penuh ke ekosistem perlindungan dan edukasi Almai selama 1 tahun.',
                        'mode' => 'Online',
                        'event_date' => null,
                        'wpa_id' => 0
                    ];
                } else {
                    // First try main layanan table
                    $layanan = $layananModel->find($trx['layanan_id']);
                }

                // If not found, try layanan_event table
                if (!$layanan) {
                    $event = $db->table('layanan_event')->where('id', $trx['layanan_id'])->get()->getRowArray();

                    if ($event) {
                        $layanan = [
                            'id' => $event['id'],
                            'name' => $event['title'],
                            'thumbnail' => base_url('file/' . $event['thumbnail']),
                            'description' => $event['description'],
                            'mode' => ($event['type'] == 'webinar') ? 'Live' : 'Offline',
                            'event_date' => $event['event_date'],
                            'wpa_id' => $event['wpa_id']
                        ];
                    }
                }

                if ($layanan) {
                    $item = $trx;
                    $item['kelas_id'] = $trx['layanan_id']; // For view compatibility
                    $item['layanan_id'] = $trx['layanan_id'];
                    $item['title'] = $layanan['name'];
                    $item['thumbnail'] = $layanan['thumbnail'];
                    $item['type'] = ($layanan['mode'] ?? 'Online') === 'Online' ? 'recorded' : 'live';
                    $item['schedule'] = $layanan['event_date'] ?? null;

                    // WPA Info
                    if (!empty($layanan['wpa_id'])) {
                        $wpa = $wpaModel->find($layanan['wpa_id']);
                        if ($wpa) {
                            $item['wpa_name'] = $wpa['name'];
                            $item['wpa_photo'] = $wpa['photo'];
                        }
                    }
                    $myLayanan[] = $item;
                }
            }
        }

        // Get upcoming events from BOTH layanan_event and main layanan table
        $db = \Config\Database::connect();
        $nowDateTime = date('Y-m-d H:i:s');
        $activeStatuses = ['upcoming', 'active', 'aktif', 'published'];

        // 1. Fetch from layanan_event
        $upcomingEventsRaw = $db->table('layanan_event')
            ->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->whereIn('layanan_event.status', $activeStatuses)
            ->groupStart()
                ->where('layanan_event.is_recurring', 1)
                ->orWhere('layanan_event.event_date >=', $nowDateTime)
            ->groupEnd()
            ->orderBy('layanan_event.event_date', 'ASC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // 2. Fetch from main layanan table (if they have event_date)
        $layananEvents = $db->table('layanan')
            ->select('id, name as title, slug, thumbnail, description, event_date, wpa_id, status, mode as type, location')
            ->whereIn('status', $activeStatuses)
            ->where('event_date >=', $nowDateTime)
            ->orderBy('event_date', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Add WPA info to layananEvents
        foreach ($layananEvents as &$le) {
            $wpa = $wpaModel->find($le['wpa_id']);
            $le['wpa_name'] = $wpa['name'] ?? 'Tim Almai';
            $le['wpa_photo'] = $wpa['photo'] ?? '';
            $le['is_recurring'] = 0; // Main table doesn't have recurring field yet
            $le['zoom_link'] = null;
        }
        
        // Merge them
        $allEventsRaw = array_merge($upcomingEventsRaw, $layananEvents);

        $upcomingEvents = [];
        $dayMap = [
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
            'Sabtu' => 'Saturday',
            'Minggu' => 'Sunday'
        ];

        foreach ($allEventsRaw as $event) {
            $nextSession = null;
            $eventDate = null;

            // Calculate next session for recurring events
            if (!empty($event['is_recurring']) && $event['is_recurring'] == 1) {
                $dbDay = $event['recurring_day'] ?? 'Selasa';
                $engDay = $dayMap[$dbDay] ?? 'Tuesday';
                $time = $event['recurring_time'] ?? '20:00:00';

                try {
                    $now = new \DateTime();
                    $nextDate = new \DateTime("this $engDay");
                    $nextDate->setTime((int)substr($time, 0, 2), (int)substr($time, 3, 2));
                    if ($nextDate < $now) {
                        $nextDate = new \DateTime("next $engDay");
                        $nextDate->setTime((int)substr($time, 0, 2), (int)substr($time, 3, 2));
                    }
                    $nextSession = $nextDate->format('d M Y, H:i');
                    $eventDate = $nextDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $nextSession = 'Setiap ' . $dbDay;
                    $eventDate = date('Y-m-d H:i:s'); // Fallback to now for calendar markers
                }
            } elseif (!empty($event['event_date']) && strtotime($event['event_date']) > 0) {
                $eventDate = $event['event_date'];
                $nextSession = date('d M Y, H:i', strtotime($event['event_date']));
            }

            if (empty($eventDate)) {
                continue;
            }

            // Map to expected format
            $upcomingEvents[] = [
                'id' => $event['id'],
                'slug' => $event['slug'],
                'name' => $event['title'],
                'title' => $event['title'],
                'thumbnail' => (strpos($event['thumbnail'], 'http') === 0) ? $event['thumbnail'] : base_url('file/' . $event['thumbnail']),
                'description' => strip_tags($event['description'] ?? ''),
                'type' => $event['type'] ?? 'webinar',
                'mode' => (($event['type'] ?? '') == 'webinar' || ($event['type'] ?? '') == 'live_trade') ? 'Live' : 'Offline',
                'event_date' => $eventDate,
                'next_session' => $nextSession,
                'location' => $event['location'] ?? 'Online',
                'zoom_link' => $event['zoom_link'] ?? null,
                'wpa_name' => $event['wpa_name'] ?? 'Tim Almai',
                'wpa_photo' => $event['wpa_photo'] ?? '',
                'is_recurring' => $event['is_recurring'] ?? 0,
                'recurring_day' => $event['recurring_day'] ?? null,
                'recurring_time' => $event['recurring_time'] ?? null,
            ];
        }

        // Sort upcomingEvents by date
        usort($upcomingEvents, function($a, $b) {
            return strtotime($a['event_date']) - strtotime($b['event_date']);
        });

        // Calculate pending event check-ins (events with active QR code that user hasn't scanned)
        $absensiModel = new \App\Models\AbsensiPesertaModel();
        $absensiList = $absensiModel->where('user_id', $userId)->findAll();
        
        $pendingEventAbsenCount = 0;
        $modelsCheck = [
            'seminar_fgd' => new \App\Models\SeminarModel(),
            'pelatihan_simulasi' => new \App\Models\PelatihanModel(),
            'signals' => new \App\Models\SignalModel(),
            'konsultasi' => new \App\Models\KonsultasiModel(),
            'expert_advisor' => new \App\Models\ExpertAdvisorModel(),
            'kegiatan_lainnya' => new \App\Models\KegiatanLainnyaModel(),
        ];
        
        $nowDateTimeForCheckin = date('Y-m-d H:i:s');
        foreach ($modelsCheck as $type => $model) {
            $events = $model->where('kode_qr IS NOT NULL')
                ->groupStart()
                    ->where('expired_link_kode_qr IS NULL')
                    ->orWhere('expired_link_kode_qr >=', $nowDateTimeForCheckin)
                ->groupEnd()
                ->findAll();
                
            foreach ($events as $event) {
                $alreadyCheckedIn = false;
                foreach ($absensiList as $absen) {
                    $absenType = $absen['kegiatan_type'];
                    if ($absenType == 'seminar') $absenType = 'seminar_fgd';
                    if ($absenType == 'pelatihan') $absenType = 'pelatihan_simulasi';
                    
                    if ($absenType == $type && $absen['kegiatan_id'] == $event['id']) {
                        $alreadyCheckedIn = true;
                        break;
                    }
                }
                if (!$alreadyCheckedIn) {
                    $pendingEventAbsenCount++;
                }
            }
        }

        return view('user/dashboard', [
            'title' => 'Dashboard - Almai',
            'pageTitle' => 'Dashboard',
            'activeMenu' => 'dashboard',
            'pendingEventAbsenCount' => $pendingEventAbsenCount,
            'myKelas' => $myLayanan, // Variable name in view is likely 'myKelas'
            'recentTransaksi' => $recentTransaksi,
            'upcomingEvents' => $upcomingEvents,
            'stats' => $this->getStats($userId), // getStats might also need update
            'promos' => [], // Empty promos for now - can be populated from database later
            'currentUser' => $currentUser,
            'userLevel' => $userLevel,
            'downlineStats' => $this->calculateDownlineStats($userId), // New stats for PRO/User
            'unreadCount' => (new \App\Models\NotificationModel())->getUnreadCount($userId),
            'canCheckin' => $canCheckin,
            'poinDailyCheckin' => $poinDailyCheckin,
        ]);
    }

    public function layananSaya()
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();

        // Fetch ALL confirmed transactions for this user
        $transactions = $transaksiModel
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $layananModel = new \App\Models\LayananModel();
        $wpaModel = new \App\Models\WpaModel();

        $myLayanan = [];

        foreach ($transactions as $trx) {
            // Special Handler for CWPA Mentorship
            if ($trx['product_type'] === 'cwpa' || stripos($trx['product_name'], 'CWPA') !== false) {
                $item = $trx;
                // Fix: Don't overwrite ID with 0 if valid ID exists, otherwise use 'cwpa' slug
                $realId = (!empty($trx['layanan_id']) && $trx['layanan_id'] > 0) ? $trx['layanan_id'] : 'cwpa';
                $item['kelas_id'] = $realId;
                $item['layanan_id'] = $realId; // Important: View uses layanan_id ?? kelas_id. 0 is not null!
                $item['title'] = 'Pendampingan CWPA';
                // Use a default image if available, or placeholder
                $item['thumbnail'] = '/uploads/cwpa.jpg';
                $item['description'] = 'Program pendampingan intensif untuk persiapan ujian Waiil Penasihat Berjangka (CWPA). Akses materi, grup diskusi, dan bimbingan eksklusif.';
                $item['type'] = 'cwpa'; // Special type for view handling
                $item['type_label'] = 'Mentorship';
                $item['mode'] = 'Hybrid';
                $item['duration'] = 'Lifetime';
                $item['modules'] = 1;
                $item['level'] = 'Professional';
                $item['rating'] = 5;
                $item['schedule'] = null;
                $item['next_session'] = null;
                $item['package_name'] = 'Full Package';
                $item['layanan_type_id'] = 'cwpa';

                $myLayanan[] = $item;
                continue;
            }

            // Check if it has a linked service (layanan_id)
            if (!empty($trx['layanan_id'])) {
                // Fetch full details from LayananModel (Main Table)
                $layananCheck = $layananModel->find($trx['layanan_id']);
                $layanan = null;
                $event = null;
                $tool = null;

                // Check main layanan table with flexible matching
                if ($layananCheck) {
                    // Try exact match first
                    if ($layananCheck['name'] === $trx['product_name']) {
                        $layanan = $layananCheck;
                        $layanan['type_label'] = 'Course'; // Default for main table
                    }
                    // Try partial match (product_name contains layanan name or vice versa)
                    elseif (
                        stripos($trx['product_name'], $layananCheck['name']) !== false ||
                        stripos($layananCheck['name'], explode(' - ', $trx['product_name'])[0]) !== false
                    ) {
                        $layanan = $layananCheck;
                        $layanan['type_label'] = 'Course'; // Default for main table
                    }
                    // Try matching base name (before " - " for packages like "Angel Gold - Trial")
                    elseif (strpos($trx['product_name'], ' - ') !== false) {
                        $baseName = explode(' - ', $trx['product_name'])[0];
                        if ($layananCheck['name'] === $baseName) {
                            $layanan = $layananCheck;
                            $layanan['type_label'] = 'Course'; // Default for main table
                        }
                    }
                }



                // Priority 0: Advocacy Membership (ID 9999)
                if (!$layanan && $trx['layanan_id'] == 9999) {
                    $layanan = [
                        'id' => 9999,
                        'name' => 'Membership Advokasi Almai',
                        'thumbnail' => base_url('images/adv.png'),
                        'description' => 'Akses penuh ke ekosistem perlindungan dan edukasi Almai selama 1 tahun.',
                        'mode' => 'Online',
                        'duration' => '1 Tahun',
                        'modules' => 0,
                        'level' => 'All Level',
                        'rating' => 5,
                        'event_date' => null,
                        'next_session' => null,
                        'wpa_id' => 0,
                        'type_label' => 'Subscription'
                    ];
                }

                // If not found in Main Table, try Event Table
                if (!$layanan) {
                    $db = \Config\Database::connect();

                    // Priority 1: Check if it's a Tool (EA/Toolkit)
                    $tool = $db->table('layanan_tools')->where('id', $trx['layanan_id'])->get()->getRowArray();
                    if (!$tool) {
                        $tool = $db->table('layanan_tools')->like('name', $trx['product_name'])->get()->getRowArray();
                        if (!$tool && strpos($trx['product_name'], ' - ') !== false) {
                            $baseName = explode(' - ', $trx['product_name'])[0];
                            $tool = $db->table('layanan_tools')->like('name', $baseName)->get()->getRowArray();
                        }
                    }
                    if ($tool && $tool['name'] === $trx['product_name']) {
                        $layanan = [
                            'id' => $tool['id'],
                            'name' => $tool['name'],
                            'thumbnail' => $this->fixLayananImageUrl($tool['thumbnail']),
                            'description' => $tool['description'],
                            'mode' => 'Online',
                            'duration' => 'Lifetime',
                            'modules' => 1,
                            'level' => 'Advanced',
                            'rating' => 0,
                            'event_date' => null,
                            'next_session' => null,
                            'wpa_id' => null,
                            'type_label' => 'Tools'
                        ];
                    }

                    // Priority 2: Check if it's an Event (Webinar/Workshop)
                    if (!$layanan) {
                        $event = $db->table('layanan_event')->where('id', $trx['layanan_id'])->get()->getRowArray();
                        if (!$event) {
                            $event = $db->table('layanan_event')->where('title', $trx['product_name'])->get()->getRowArray();
                            if (!$event) {
                                $event = $db->table('layanan_event')->like('title', $trx['product_name'])->get()->getRowArray();
                            }
                            if (!$event && strpos($trx['product_name'], ' - ') !== false) {
                                $baseName = explode(' - ', $trx['product_name'])[0];
                                $event = $db->table('layanan_event')->like('title', $baseName)->get()->getRowArray();
                            }
                        }

                        // IMPORTANT: Only match if name matches product_name (to avoid ID collision)
                        if ($event && (
                            $event['title'] === $trx['product_name'] ||
                            stripos($trx['product_name'], $event['title']) !== false ||
                            stripos($event['title'], explode(' - ', $trx['product_name'])[0]) !== false
                        )) {
                            // Calculate next session for recurring events
                            $nextSession = null;
                            if (!empty($event['is_recurring']) && $event['is_recurring'] == 1) {
                                // ... dayMap logic ...
                                $dayMap = [
                                    'Senin' => 'Monday',
                                    'Selasa' => 'Tuesday',
                                    'Rabu' => 'Wednesday',
                                    'Kamis' => 'Thursday',
                                    'Jumat' => 'Friday',
                                    'Sabtu' => 'Saturday',
                                    'Minggu' => 'Sunday'
                                ];
                                $dbDay = $event['recurring_day'] ?? 'Selasa';
                                $engDay = $dayMap[$dbDay] ?? 'Tuesday';
                                $time = $event['recurring_time'] ?? '20:00:00';

                                try {
                                    $nextDate = new \DateTime("next $engDay");
                                    $nextDate->setTime((int)substr($time, 0, 2), (int)substr($time, 3, 2));
                                    $nextSession = $nextDate->format('d M Y, H:i');
                                } catch (\Exception $e) {
                                    $nextSession = 'Jadwal Rutin';
                                }
                            } elseif (!empty($event['event_date']) && strtotime($event['event_date']) > 0) {
                                $nextSession = date('d M Y, H:i', strtotime($event['event_date']));
                            }

                            $layanan = [
                                'id' => $event['id'],
                                'name' => $event['title'],
                                'thumbnail' => $this->fixLayananImageUrl($event['thumbnail']),
                                'description' => $event['description'],
                                'mode' => ($event['type'] == 'webinar') ? 'Live' : 'Offline',
                                'duration' => '-',
                                'modules' => 0,
                                'level' => 'All Level',
                                'rating' => 0,
                                'event_date' => $event['event_date'],
                                'next_session' => $nextSession,
                                'wpa_id' => $event['wpa_id'],
                                'type_label' => ucfirst($event['type'])
                            ];
                        }
                    }

                    // Priority 3: Check Artikel
                    if (!$layanan) {
                        $artikel = $db->table('layanan_artikel')->where('id', $trx['layanan_id'])->get()->getRowArray();
                        if (!$artikel) {
                            $artikel = $db->table('layanan_artikel')->where('title', $trx['product_name'])->get()->getRowArray();
                            if (!$artikel) {
                                $artikel = $db->table('layanan_artikel')->like('title', $trx['product_name'])->get()->getRowArray();
                            }
                            if (!$artikel && strpos($trx['product_name'], ' - ') !== false) {
                                $baseName = explode(' - ', $trx['product_name'])[0];
                                $artikel = $db->table('layanan_artikel')->like('title', $baseName)->get()->getRowArray();
                            }
                        }
                        if ($artikel && $artikel['title'] === $trx['product_name']) {
                            $layanan = [
                                'id' => $artikel['id'],
                                'name' => $artikel['title'],
                                'thumbnail' => $this->fixLayananImageUrl($artikel['thumbnail']),
                                'description' => $artikel['excerpt'],
                                'mode' => 'Online',
                                'duration' => 'Unlimited',
                                'modules' => 1,
                                'level' => 'Beginner',
                                'rating' => 0,
                                'event_date' => null,
                                'next_session' => null,
                                'wpa_id' => $artikel['wpa_id'],
                                'type_label' => 'Artikel'
                            ];
                        }
                    }

                    // Priority 4: Check Subscriptions
                    if (!$layanan) {
                        $sub = $db->table('layanan_subscription')->where('id', $trx['layanan_id'])->get()->getRowArray();
                        if (!$sub) {
                            $sub = $db->table('layanan_subscription')->where('name', $trx['product_name'])->get()->getRowArray();
                            if (!$sub) {
                                $sub = $db->table('layanan_subscription')->like('name', $trx['product_name'])->get()->getRowArray();
                            }
                            if (!$sub && strpos($trx['product_name'], ' - ') !== false) {
                                $baseName = explode(' - ', $trx['product_name'])[0];
                                $sub = $db->table('layanan_subscription')->like('name', $baseName)->get()->getRowArray();
                            }
                        }
                        // Use flexible matching: exact match OR product_name contains sub name OR sub name contains product_name
                        if ($sub && (
                            $sub['name'] === $trx['product_name'] ||
                            stripos($trx['product_name'], $sub['name']) !== false ||
                            stripos($sub['name'], explode(' - ', $trx['product_name'])[0]) !== false
                        )) {
                            $layanan = [
                                'id' => $sub['id'],
                                'name' => $sub['name'],
                                'thumbnail' => $this->fixLayananImageUrl($sub['thumbnail']),
                                'description' => $sub['description'],
                                'mode' => 'Hybrid',
                                'duration' => ($sub['duration_days'] ?? 30) . ' Hari',
                                'modules' => 0,
                                'level' => 'All Level',
                                'rating' => 0,
                                'event_date' => null,
                                'next_session' => null,
                                'wpa_id' => $sub['wpa_id'] ?? null,
                                'type_label' => 'Subscription',
                                'subcategory' => $sub['subcategory'] ?? 'pendampingan'
                            ];
                        }
                    }
                }

                if ($layanan) {
                    // Map to the format expected by the view
                    $item = $trx; // Start with transaction data

                    // Essential ID for links (mapped to kelas_id for view compatibility)
                    $item['kelas_id'] = $layanan['id'];
                    $item['layanan_id'] = $layanan['id'];
                    $transaksiModel->update($trx['id'], ['layanan_id' => $layanan['id']]);

                    // Display details
                    $item['title'] = $layanan['name'];
                    $item['thumbnail'] = $layanan['thumbnail'];
                    $item['description'] = $layanan['description'];

                    // Logic to determine type/mode
                    $item['type'] = ($layanan['mode'] ?? 'Online') === 'Online' ? 'recorded' : 'live';

                    $item['duration'] = $layanan['duration'];
                    $item['modules'] = $layanan['modules'];
                    $item['level'] = $layanan['level'];
                    $item['rating'] = $layanan['rating'];
                    $item['schedule'] = $layanan['event_date'] ?? null; // Use event_date as schedule
                    $item['next_session'] = $layanan['next_session'] ?? null; // Calculated next session
                    $item['mode'] = $layanan['mode'];
                    $item['type_label'] = $layanan['type_label'] ?? '';

                    // Ulasan status
                    $item['layanan_type_id'] = 'layanan'; // Default
                    if ($item['type_label'] === 'Tools') {
                        $item['layanan_type_id'] = 'tool';
                    } elseif ($item['type_label'] === 'Event' || (isset($event) && $item['type_label'] === ucfirst($event['type'] ?? ''))) {
                        $item['layanan_type_id'] = 'event';
                    }
                    $item['has_reviewed'] = (new \App\Models\LayananUlasanModel())->hasUserReviewed($userId, $item['layanan_id'], $item['layanan_type_id']);

                    // WPA Info
                    if (!empty($layanan['wpa_id'])) {
                        $wpa = $wpaModel->find($layanan['wpa_id']);
                        if ($wpa) {
                            $item['wpa_name'] = $wpa['name'];
                            $item['wpa_photo'] = $wpa['photo'];
                            $item['wpa_specialty'] = $wpa['specialty'] ?? null;
                        }
                    }

                    // Extract package name from product_name (e.g., "Angel Gold - Trial" -> "Trial")
                    $productName = $trx['product_name'] ?? '';
                    $packageName = '';
                    if (strpos($productName, ' - ') !== false) {
                        $parts = explode(' - ', $productName);
                        $packageName = end($parts); // Get the last part as package name
                    }
                    $item['package_name'] = $packageName;
                    $item['package_price'] = $trx['total'] ?? $trx['amount'] ?? 0;

                    $myLayanan[] = $item;
                }
            } else {
                // FALLBACK: ID is missing
                $trxName = $trx['product_name'];
                $foundLayanan = null;
                $sourceTable = 'layanan'; // default

                // Strategy 1: Contains in Layanan (Main)
                $foundLayanan = $layananModel->like('name', $trxName)->first();

                // Strategy 2: Contains in Layanan Event
                if (!$foundLayanan) {
                    $db = \Config\Database::connect();
                    $foundEvent = $db->table('layanan_event')->like('title', $trxName)->get()->getRowArray();

                    if ($foundEvent) {
                        $foundLayanan = [
                            'id' => $foundEvent['id'],
                            'name' => $foundEvent['title'],
                            'thumbnail' => base_url('file/' . $foundEvent['thumbnail']),
                            'description' => $foundEvent['description'],
                            'mode' => ($foundEvent['type'] == 'webinar') ? 'Live' : 'Offline',
                            'duration' => '-',
                            'modules' => 0,
                            'level' => 'All Level',
                            'rating' => 0,
                            'event_date' => $foundEvent['event_date'],
                            'wpa_id' => $foundEvent['wpa_id']
                        ];
                        $sourceTable = 'layanan_event';
                    }
                }

                // Strategy 3: Reverse Contains / Split in Layanan
                if (!$foundLayanan) {
                    $parts = explode(' - ', $trxName);
                    if (count($parts) > 1) {
                        $foundLayanan = $layananModel->like('name', trim($parts[0]))->first();
                    }
                }

                // Strategy 4: Reverse Contains / Split in Layanan Event
                if (!$foundLayanan && isset($parts) && count($parts) > 1) {
                    $db = \Config\Database::connect();
                    $foundEvent = $db->table('layanan_event')->like('title', trim($parts[0]))->get()->getRowArray();
                    if ($foundEvent) {
                        $foundLayanan = [
                            'id' => $foundEvent['id'],
                            'name' => $foundEvent['title'],
                            'thumbnail' => base_url('file/' . $foundEvent['thumbnail']),
                            'description' => $foundEvent['description'],
                            'mode' => ($foundEvent['type'] == 'webinar') ? 'Live' : 'Offline',
                            'duration' => '-',
                            'modules' => 0,
                            'level' => 'All Level',
                            'rating' => 0,
                            'event_date' => $foundEvent['event_date'],
                            'wpa_id' => $foundEvent['wpa_id']
                        ];
                        $sourceTable = 'layanan_event';
                    }
                }

                // Strategy 5: Specific for "Angel Gold"
                if (!$foundLayanan && stripos($trxName, 'Angel Gold') !== false) {
                    // Try Event first as it is likely an event
                    $db = \Config\Database::connect();
                    $foundEvent = $db->table('layanan_event')->like('title', 'Angel Gold')->get()->getRowArray();
                    if ($foundEvent) {
                        $foundLayanan = [
                            'id' => $foundEvent['id'],
                            'name' => $foundEvent['title'],
                            'thumbnail' => base_url('file/' . $foundEvent['thumbnail']),
                            'description' => $foundEvent['description'],
                            'mode' => ($foundEvent['type'] == 'webinar') ? 'Live' : 'Offline',
                            'duration' => '-',
                            'modules' => 0,
                            'level' => 'All Level',
                            'rating' => 0,
                            'event_date' => $foundEvent['event_date'],
                            'wpa_id' => $foundEvent['wpa_id']
                        ];
                        $sourceTable = 'layanan_event';
                    } else {
                        // Fallback to main
                        $foundLayanan = $layananModel->like('name', 'Angel Gold')->first();
                    }
                }

                // Strategy 6: Check Subscriptions by name
                if (!$foundLayanan) {
                    $db = \Config\Database::connect();
                    $foundSub = $db->table('layanan_subscription')->like('name', $trxName)->get()->getRowArray();

                    // Try with split product name (e.g., "CWPA - Paket 1" -> "CWPA")
                    if (!$foundSub && isset($parts) && count($parts) > 1) {
                        $foundSub = $db->table('layanan_subscription')->like('name', trim($parts[0]))->get()->getRowArray();
                    }

                    if ($foundSub) {
                        $foundLayanan = [
                            'id' => $foundSub['id'],
                            'name' => $foundSub['name'],
                            'thumbnail' => $this->fixLayananImageUrl($foundSub['thumbnail']),
                            'description' => $foundSub['description'],
                            'mode' => 'Hybrid',
                            'duration' => ($foundSub['duration_days'] ?? 30) . ' Hari',
                            'modules' => 0,
                            'level' => 'All Level',
                            'rating' => 0,
                            'event_date' => null,
                            'wpa_id' => $foundSub['wpa_id'] ?? null
                        ];
                        $sourceTable = 'layanan_subscription';
                    }
                }

                if ($foundLayanan) {
                    // Healing - Update DB
                    // Note: If source is layanan_event, we just store ID. Since we can't easily distinguish source in 'layanan_id' column alone without a type column update.
                    // But we will update it anyway.
                    $transaksiModel->update($trx['id'], ['layanan_id' => $foundLayanan['id']]);

                    $item = $trx;
                    $item['kelas_id'] = $foundLayanan['id'];
                    $item['layanan_id'] = $foundLayanan['id'];
                    $item['title'] = $foundLayanan['name'];
                    $item['thumbnail'] = $foundLayanan['thumbnail'];
                    $item['description'] = $foundLayanan['description'];
                    $item['type'] = ($foundLayanan['mode'] ?? 'Online') === 'Online' ? 'recorded' : 'live';
                    $item['duration'] = $foundLayanan['duration'];
                    $item['modules'] = $foundLayanan['modules'];
                    $item['level'] = $foundLayanan['level'];
                    $item['rating'] = $foundLayanan['rating'];
                    $item['schedule'] = $foundLayanan['event_date'] ?? null;
                    $item['mode'] = $foundLayanan['mode'];

                    // Ulasan status
                    $item['type_label'] = ($sourceTable === 'layanan_event') ? 'Event' : (($sourceTable === 'layanan_tools') ? 'Tools' : 'Course');
                    $item['layanan_type_id'] = $item['type_label'] === 'Tools' ? 'tool' : ($item['type_label'] === 'Event' ? 'event' : 'layanan');
                    $item['has_reviewed'] = (new \App\Models\LayananUlasanModel())->hasUserReviewed($userId, $item['layanan_id'], $item['layanan_type_id']);

                    if (!empty($foundLayanan['wpa_id'])) {
                        $wpa = $wpaModel->find($foundLayanan['wpa_id']);
                        if ($wpa) {
                            $item['wpa_name'] = $wpa['name'];
                            $item['wpa_photo'] = $wpa['photo'];
                        }
                    }
                    $myLayanan[] = $item;
                } else {
                    // No match found
                    $item = $trx;
                    $item['kelas_id'] = 0;
                    $item['layanan_id'] = 0;
                    $item['title'] = $trx['product_name'];
                    $item['thumbnail'] = 'https://via.placeholder.com/400x200?text=' . urlencode($trx['product_name']);
                    $item['description'] = 'Layanan ini tidak memiliki detail tertaut.';
                    $item['type'] = 'recorded';
                    $item['duration'] = '-';
                    $item['modules'] = 0;
                    $item['level'] = '-';
                    $item['rating'] = 0;
                    $item['schedule'] = null;
                    $item['mode'] = 'Online';
                    $myLayanan[] = $item;
                }
            }
        }

        return view('user/kelas', [
            'title' => 'Layanan Saya - Almai',
            'pageTitle' => 'Layanan Saya',
            'pageSubtitle' => 'Layanan yang sudah Anda beli',
            'activeMenu' => 'layanan_saya',
            'myKelas' => $myLayanan, // Pass as myKelas to avoid changing view variable names
        ]);
    }

    public function transaksi()
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();

        $transaksiList = $transaksiModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/transaksi', [
            'title' => 'Riwayat Transaksi - Almai',
            'pageTitle' => 'Riwayat Transaksi',
            'pageSubtitle' => 'Semua pembelian Anda',
            'activeMenu' => 'transaksi',
            'transaksiList' => $transaksiList,
        ]);
    }

    public function sertifikat()
    {
        $userId = session()->get('userId');
        $certificateModel = new \App\Models\CertificateModel();

        $certificates = $certificateModel->getUserCertificates($userId);

        // Debug: Log the data being passed to view
        log_message('debug', 'Sertifikat page - User ID: ' . $userId);
        log_message('debug', 'Sertifikat page - Certificates count: ' . count($certificates));

        return view('user/sertifikat', [
            'title' => 'Sertifikat - Almai',
            'pageTitle' => 'Sertifikat Saya',
            'pageSubtitle' => 'Sertifikat yang Anda peroleh',
            'activeMenu' => 'sertifikat',
            'sertifikatList' => $certificates,
        ]);
    }

    public function poin()
    {
        $userId = session()->get('userId');
        $poinModel = new PoinModel();
        $merchandiseModel = new \App\Models\MerchandiseModel();
        $redemptionModel = new \App\Models\MerchandiseRedemptionModel();
        $poinPackageModel = new \App\Models\PoinPackageModel();
        $userModel = new UserModel();

        $user = $userModel->find($userId);

        // Fix: Fetch Affiliator info
        $affiliator = null;
        if (!empty($user['affiliator_code'])) {
            $affiliator = $userModel->where('code_referral', $user['affiliator_code'])->first();
        }

        $poinHistory = $poinModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        // Determine if user can check-in today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $poinModel->where('user_id', $userId)
                                      ->where('pointable_type', 'chekin')
                                      ->where('created_at >=', $todayStart)
                                      ->where('created_at <=', $todayEnd)
                                      ->first();
        $canCheckin = !$alreadyCheckedIn;

        // Get active merchandise
        $merchandise = $merchandiseModel->getActive();

        // Get user's merchandise redemptions
        $redemptions = $redemptionModel
            ->select('merchandise_redemptions.*, merchandise.name as merchandise_name, merchandise.image as merchandise_image')
            ->join('merchandise', 'merchandise.id = merchandise_redemptions.merchandise_id')
            ->where('merchandise_redemptions.user_id', $userId)
            ->orderBy('merchandise_redemptions.created_at', 'DESC')
            ->findAll();

        // Get poin packages
        $poinPackages = $poinPackageModel->getActive();

        // Statistics for User Detailed Breakdown
        $poinStats = [
            // LEFT SIDE: PENGELUARAN / USAGE
            'redeem' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->selectSum('point')->first()['point'] ?? 0),
            'artikel' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'artikel')->selectSum('point')->first()['point'] ?? 0),
            'layanan_usage' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'layanan')->selectSum('point')->first()['point'] ?? 0),
            'merchandise' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->where('type', 'redeem')->where('pointable_type', 'merchandise')->selectSum('point')->first()['point'] ?? 0),
            'share_out' => abs($poinModel->where('user_id', $userId)->where('point <', 0)->whereIn('pointable_type', ['share', 'poin_sharing', 'share_out'])->selectSum('point')->first()['point'] ?? 0),
            
            // RIGHT SIDE: PEMASUKAN / EARNING
            'bonus_total' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->selectSum('point')->first()['point'] ?? 0,
            'register' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->where('pointable_type', 'registration')->selectSum('point')->first()['point'] ?? 0,
            'referral' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->whereIn('pointable_type', ['referral', 'transaksi'])->selectSum('point')->first()['point'] ?? 0,
            'layanan_earn' => $poinModel->where('user_id', $userId)->where('point >', 0)->where('type', 'earn')->where('pointable_type', 'layanan')->selectSum('point')->first()['point'] ?? 0,
            'share_in' => $poinModel->where('user_id', $userId)->where('point >', 0)->whereIn('pointable_type', ['share', 'poin_sharing', 'share_in'])->selectSum('point')->first()['point'] ?? 0,
        ];

        // Global Pool Stats Context
        $maxSupply = 3000000000; // 3 Billion
        $totalDistributed = $poinModel->getTotalPoinDistributed();
        $totalRedeemed = $poinModel->getTotalPoinRedeemed();
        $totalActive = $totalDistributed - $totalRedeemed;

        return view('user/poin', [
            'title' => 'ALMAI Poin - Almai',
            'pageTitle' => 'ALMAI Poin',
            'pageSubtitle' => 'Kumpulkan dan tukarkan poin Anda',
            'activeMenu' => 'poin',
            'user' => $user,
            'referralCode' => $user['code_referral'] ?? null,
            'poinHistory' => $poinHistory,
            'pager' => $poinModel->pager,
            'merchandise' => $merchandise,
            'redemptions' => $redemptions,
            'poinPackages' => $poinPackages,
            'stats' => $this->getStats($userId),
            'poinStats' => $poinStats,
            'maxSupply' => $maxSupply,
            'totalActive' => $totalActive,
            'poinBalance' => $poinModel->getUserBalance($userId),
            'totalEarnings' => $user['balance'] ?? 0,
            // Settings for points
            'poinToRupiah' => (int) (new \App\Models\SettingModel())->get('poin_to_rupiah', 100),
            'minRedeem' => (int) (new \App\Models\SettingModel())->get('poin_minimum_redeem', 10000),
            'referralBonus' => (int) (new \App\Models\SettingModel())->get('poin_referral_registration', 1000),
            'newUserBonus' => (int) (new \App\Models\SettingModel())->get('poin_new_user_referral_bonus', 500),
            'referralPurchasePercent' => (int) (new \App\Models\SettingModel())->get('poin_referral_purchase_percent', 5),
            'wpaCommissionPercent' => (int) (new \App\Models\SettingModel())->get('poin_wpa_commission_percent', 10),
            'poinLayananUlasan' => (int) (new \App\Models\SettingModel())->get('poin_layanan_ulasan', 50),
            'poinFollowAccount' => (int) (new \App\Models\SettingModel())->get('poin_follow_account', 20),
            'poinDailyCheckin' => (int) (new \App\Models\SettingModel())->get('poin_daily_checkin', 10),
            'canCheckin' => $canCheckin,
            'affiliator' => $affiliator,
        ]);
    }

    public function profile()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();

        $user = $userModel->find($userId);

        $stats = $this->getStats($userId);
        $user['total_poin'] = $stats['totalPoin'] ?? 0;

        return view('user/profile', [
            'title' => 'Profile - Almai',
            'pageTitle' => 'Profile Saya',
            'pageSubtitle' => 'Kelola informasi akun Anda',
            'activeMenu' => 'profile',
            'user' => $user,
        ]);
    }

    public function profileEdit()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();

        $user = $userModel->find($userId);

        $stats = $this->getStats($userId);
        $user['total_poin'] = $stats['totalPoin'] ?? 0;

        return view('user/profile', [
            'title' => 'Edit Data Pribadi - Almai',
            'pageTitle' => 'Profile Saya',
            'pageSubtitle' => 'Kelola informasi akun Anda',
            'activeMenu' => 'profile',
            'user' => $user,
            'isEdit' => true,
        ]);
    }

    public function referral()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Calculate total stats
        $stats = $this->getStats($userId);

        return view('user/referral', [
            'title' => 'Ajak Teman - Almai',
            'pageTitle' => 'Ajak Teman',
            'pageSubtitle' => 'Bagikan kebaikan dan dapatkan hadiah',
            'activeMenu' => 'profile',
            'user' => $user,
            'stats' => $stats
        ]);
    }

    public function updateReferralCode()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Check Level (PRO or above) - Level 2 is PRO.
        $levelId = (int)($user['level_id'] ?? 1);
        if ($levelId < \App\Models\LevelModel::LEVEL_PRO) {
            return redirect()->back()->with('error', 'Fitur ini hanya untuk User PRO, CWPA, dan WPA.');
        }

        // Check if already customized
        if (!empty($user['is_referral_customized'])) {
            return redirect()->back()->with('error', 'Anda hanya bisa mengubah kode referral 1 kali.');
        }

        $newCode = strtoupper(trim($this->request->getPost('code_referral')));

        // Validate format (Alphanumeric, min 4 chars)
        if (!preg_match('/^[A-Z0-9]{4,20}$/', $newCode)) {
            return redirect()->back()->with('error', 'Kode harus 4-20 karakter alfanumerik (Huruf & Angka).');
        }

        // Check uniqueness
        $existing = $userModel->where('code_referral', $newCode)->first();
        if ($existing && $existing['id'] != $userId) {
            return redirect()->back()->with('error', 'Kode referral sudah digunakan orang lain.');
        }

        $userModel->update($userId, [
            'code_referral' => $newCode,
            'is_referral_customized' => 1
        ]);

        return redirect()->back()->with('success', 'Kode referral berhasil diubah! Link referral Anda otomatis diperbarui.');
    }

    public function downloadPerjanjian($invoiceNumber)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->where('user_id', $userId)->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $layananData = $this->getLayananDataForLegal($transaksi);
        $wpaName = $layananData['wpa_name'] ?? 'Tim Almai';

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generatePerjanjianPdf($transaksi, $user, $layananData, $wpaName);

        if (empty($pdf)) return redirect()->back()->with('error', 'Gagal membuat file PDF. Silakan coba sesaat lagi.');

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Perjanjian_Pemberian_Jasa_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    public function downloadRisiko($invoiceNumber)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $invoiceNumber)->where('user_id', $userId)->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $layananData = $this->getLayananDataForLegal($transaksi);

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();
        $pdf = $legalPdfService->generateRisikoPdf($transaksi, $user, $layananData);

        if (empty($pdf)) return redirect()->back()->with('error', 'Gagal membuat file PDF. Silakan coba sesaat lagi.');

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Pemberitahuan_Risiko_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }

    public function getLayananDataForLegal($transaksi)
    {
        $db = \Config\Database::connect();
        $type = strtolower($transaksi['product_type'] ?? 'layanan');
        $id = $transaksi['layanan_id'];

        $layananData = [
            'id' => $id,
            'name' => $transaksi['product_name'],
            'slug' => url_title($transaksi['product_name'], '_', true),
            'wpa_name' => 'Tim Almai'
        ];

        $item = null;
        if ($type === 'layanan' || $type === 'course') {
            $item = $db->table('layanan')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'event' || $type === 'webinar' || $type === 'workshop') {
            $item = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'tool' || $type === 'tools' || $type === 'ea') {
            $item = $db->table('layanan_tools')->where('id', $id)->get()->getRowArray();
        } elseif ($type === 'subscription' || in_array($type, ['pendampingan', 'profirm', 'vip_member'])) {
            $item = $db->table('layanan_subscription')->where('id', $id)->get()->getRowArray();
        }

        if ($item) {
            if (isset($item['title'])) $layananData['name'] = $item['title'];
            if (isset($item['slug'])) $layananData['slug'] = $item['slug'];
            if (isset($item['layanan_utama'])) $layananData['layanan_utama'] = $item['layanan_utama'];

            // Get WPA Name
            $wpaId = $item['wpa_id'] ?? null;
            if ($wpaId) {
                $wpa = $db->table('wpa')->where('id', $wpaId)->get()->getRowArray();
                if ($wpa) $layananData['wpa_name'] = $wpa['name'];
            }
        }

        return $layananData;
    }

    public function user()
    {
        $userId = session()->get('userId');
        $userModel = new UserModel();

        $currentUser = $userModel->find($userId);

        if (!in_array(($currentUser['level_id'] ?? 0), [\App\Models\LevelModel::LEVEL_USER, \App\Models\LevelModel::LEVEL_PRO, \App\Models\LevelModel::LEVEL_CWPA])) {
            return redirect()->to('/user/dashboard')->with('error', 'Akses ditolak.');
        }

        $referralCode = $currentUser['code_referral'] ?? null;
        $tab = $this->request->getGet('tab') ?? 'all';
        $search = $this->request->getGet('search');

        // 1. Get Recursive Downlines (Max 10 Levels) using Robust Method
        $allDownlineIds = $userModel->getNetworkIds($userId, 10);
        $referralUserIds = $userModel->getNetworkIds($userId, 1);


        // Stats Calculation
        $totalUserCount = count($allDownlineIds);
        $totalDirectCount = count($referralUserIds); // Direct is Level 1
        $totalCwpaCount = 0;
        $totalProCount = 0;
        $totalStandardUserCount = 0;

        $users = [];
        $pager = null;

        if (!empty($allDownlineIds)) {
            $db = \Config\Database::connect();
            $idsChunk = array_chunk($allDownlineIds, 1000);
            
            $statsTotal = [];
            foreach ($idsChunk as $chunk) {
                $chunkStr = implode(',', $chunk);
                $chunkQuery = $db->query("SELECT level_id, count(id) as count FROM users WHERE id IN ($chunkStr) GROUP BY level_id")->getResultArray();
                foreach ($chunkQuery as $row) {
                    $statsTotal[$row['level_id']] = ($statsTotal[$row['level_id']] ?? 0) + $row['count'];
                }
            }

            foreach ($statsTotal as $lid => $count) {
                if ($lid == \App\Models\LevelModel::LEVEL_PRO) {
                    $totalProCount = $count;
                } elseif ($lid == \App\Models\LevelModel::LEVEL_CWPA) {
                    $totalCwpaCount = $count;
                } elseif ($lid == \App\Models\LevelModel::LEVEL_USER) {
                    $totalStandardUserCount = $count;
                }
            }


            // Get Users for Display - Chunked whereIn to avoid regex limits
            $finalUserModel = new UserModel();
            $finalUserModel->groupStart();
            foreach (array_chunk($allDownlineIds, 1000) as $chunk) {
                $finalUserModel->orWhereIn('id', $chunk);
            }
            $finalUserModel->groupEnd();
            $builder = $finalUserModel;


            if ($search) {
                $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
            }
            $users = $builder->orderBy('created_at', 'DESC')->paginate(20);
            $pager = $finalUserModel->pager;
        }

        // Enrich User Data with "Purchases" (Transactions count)
        $transaksiModel = new TransaksiModel();
        foreach ($users as &$u) {
            $u['total_purchases'] = $transaksiModel->where('user_id', $u['id'])->where('status', 'confirmed')->countAllResults();
            $sum = $transaksiModel->where('user_id', $u['id'])->where('status', 'confirmed')->selectSum('total')->first();
            $u['total_spent'] = $sum['total'] ?? 0;
            $u['is_referral'] = true;
        }

        // Total Pembelian (Transactions from DOWNLINE) - Chunked
        $totalTransactions = 0;
        if (!empty($allDownlineIds)) {
            $transaksiModel->groupStart();
            foreach (array_chunk($allDownlineIds, 1000) as $chunk) {
                $transaksiModel->orWhereIn('user_id', $chunk);
            }
            $transaksiModel->groupEnd();
            
            $totalTransactions = $transaksiModel->where('status', 'confirmed')
                ->countAllResults();
        }


        return view('user/user_index', [
            'title' => 'User Management - Almai',
            'pageTitle' => 'User Management',
            'pageSubtitle' => 'Kelola user dan referral Anda',
            'activeMenu' => 'user',
            'users' => $users,
            'pager' => $pager,
            'tab' => $tab,
            'search' => $search,
            'totalUserCount' => $totalUserCount,
            'totalDirectCount' => $totalDirectCount, // Passed as 'totalDirectCount' for view
            'totalCwpaCount' => $totalCwpaCount,
            'totalProCount' => $totalProCount,
            'totalStandardUserCount' => $totalStandardUserCount,
            'totalTransactions' => $totalTransactions,
            'referralCode' => $referralCode,
            'currentUser' => $currentUser, // Add currentUser for role checks in view
        ]);
    }
    public function rwa()
    {
        $userId = session()->get('userId');
        $poinModel = new \App\Models\PoinModel();
        $botModel = new \App\Models\BotModel();
        $orderModel = new \App\Models\BotOrderModel();
        
        $tradeMode = session()->get('rwa_trade_mode') ?? 'demo';
        $realBalanceStr = 'Rp 0';
        $isConnected = false;
        $dailyPnL = 0;
        $totalAssetBase = 0; // Base balance tanpa profit

        // =====================================================
        // 1. RUNNING BOTS - Sesuai Mode (demo/live)
        // =====================================================
        $userBots = $botModel->where('user_id', $userId)->findAll();
        $activeBots = [];
        $botIds = [];
        foreach ($userBots as $bot) {
            $botIds[] = $bot->id;
            if ($bot->status === 'active') {
                $activeBots[] = $bot;
            }
        }
        
        // Filter trades today sesuai mode
        $tradesTodayCount = 0;
        $todayStart = date('Y-m-d 00:00:00');
        if (!empty($botIds)) {
            $orderBuilder = $orderModel->whereIn('bot_id', $botIds)
                ->where('created_at >=', $todayStart);
            if ($tradeMode === 'live') {
                $orderBuilder->notLike('exchange_order_id', 'MOCK_', 'after');
            } else {
                $orderBuilder->like('exchange_order_id', 'MOCK_', 'after');
            }
            $tradesTodayCount = $orderBuilder->countAllResults(false);
        }

        // =====================================================
        // 2. DAILY PnL - Hitung dari orders hari ini sesuai mode
        // =====================================================
        if (!empty($botIds)) {
            $pnlBuilder = $orderModel->selectSum('pnl')
                ->whereIn('bot_id', $botIds)
                ->where('created_at >=', $todayStart)
                ->where('status', 'closed');
            if ($tradeMode === 'live') {
                $pnlBuilder->notLike('exchange_order_id', 'MOCK_', 'after');
            } else {
                $pnlBuilder->like('exchange_order_id', 'MOCK_', 'after');
            }
            $pnlResult = $pnlBuilder->first();
            $dailyPnL = (float)($pnlResult->pnl ?? 0);
        }

        // =====================================================
        // 3. TOTAL PROFIT dari semua bot (sesuai mode) untuk ditambahkan ke asset
        // =====================================================
        $totalBotProfit = 0;
        if (!empty($botIds)) {
            $profitBuilder = $orderModel->selectSum('pnl')
                ->whereIn('bot_id', $botIds)
                ->where('status', 'closed');
            if ($tradeMode === 'live') {
                $profitBuilder->notLike('exchange_order_id', 'MOCK_', 'after');
            } else {
                $profitBuilder->like('exchange_order_id', 'MOCK_', 'after');
            }
            $profitResult = $profitBuilder->first();
            $totalBotProfit = (float)($profitResult->pnl ?? 0);
        }

        // =====================================================
        // 4. TOTAL ASSET VALUE - Base + Bot Profit sesuai mode
        // =====================================================
        $connectedNames = [];
        if ($tradeMode === 'demo') {
            $totalAssetBase = 100000000; // Rp 100.000.000 virtual
            $isConnected = true;
            $totalAsset = $totalAssetBase + $totalBotProfit;
            $realBalanceStr = 'Rp ' . number_format($totalAsset, 0, ',', '.');
        } else {
            // Live Mode - Ambil dari Exchange API via trade-engine
            $exchangeModel = new \App\Models\UserExchangeKeyModel();
            $keys = $exchangeModel->where('user_id', $userId)->where('is_active', 1)->findAll();
            
            
            if (!empty($keys)) {
                $isConnected = true;
                $client = \Config\Services::curlrequest();
                $totalIdrFromAllExchanges = 0;
                
                foreach ($keys as $key) {
                    $connectedNames[] = ucfirst($key->exchange);
                    try {
                        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
                        $response = $client->get(rtrim($tradeEngineUrl, '/') . '/api/balance/' . $userId . '?exchange=' . urlencode($key->exchange), [
                            'timeout' => 5,
                            'http_errors' => false,
                            'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
                        ]);
                        $res = json_decode($response->getBody(), true);
                        if ($res && !empty($res['success'])) {
                            if (isset($res['fiat_valuation']) && $res['fiat_valuation'] > 0) {
                                $totalIdrFromAllExchanges += $res['fiat_valuation'];
                            } else {
                                $balanceData = $res['data']['total'] ?? [];
                                $totalIdr = 0;
                                $priceMap = [
                                    'IDR' => 1,
                                    'USDT' => 16000,
                                    'PAXG' => 45000000,
                                    'XAUT' => 38000000,
                                    'SLVON' => 500000,
                                ];
                                foreach ($balanceData as $asset => $amount) {
                                    if ($amount > 0) {
                                        $assetPrice = $priceMap[strtoupper($asset)] ?? 0;
                                        $totalIdr += ($amount * $assetPrice);
                                    }
                                }
                                $totalIdrFromAllExchanges += $totalIdr;
                            }
                        }
                    } catch (\Exception $e) {
                        // Skip if one exchange fails
                    }
                }
                
                $totalAssetBase = $totalIdrFromAllExchanges;
                if ($totalAssetBase == 0) {
                     $realBalanceStr = 'Rp 0';
                }
            } else {
                $realBalanceStr = 'Belum terhubung ke Exchange';
            }
            
            // Total Asset = Exchange Balance + Bot Profit (live orders only)
            if ($isConnected && $totalAssetBase > 0) {
                $totalAsset = $totalAssetBase + $totalBotProfit;
                $realBalanceStr = 'Rp ' . number_format($totalAsset, 0, ',', '.');
            }
        }

        // Hitung PnL Percent
        $dailyPnLPercent = 0;
        if ($totalAssetBase > 0 && $dailyPnL != 0) {
            $dailyPnLPercent = ($dailyPnL / $totalAssetBase) * 100;
        }
        $dailyPnLPercentStr = ($dailyPnL >= 0 ? '+' : '') . number_format($dailyPnLPercent, 2) . '%';

        // =====================================================
        // 5. QUICK MARKET - Data harga real dari Trade Engine
        // =====================================================
        $prices = $this->fetchRealMarketPrices();

        // =====================================================
        // 6. RECENT ACTIVITY - 5 teratas sesuai mode
        // =====================================================
        $recentActivities = [];
        if (!empty($botIds)) {
            $actBuilder = $orderModel->whereIn('bot_id', $botIds);
            if ($tradeMode === 'live') {
                $actBuilder->notLike('exchange_order_id', 'MOCK_', 'after');
            } else {
                $actBuilder->like('exchange_order_id', 'MOCK_', 'after');
            }
            $recentOrders = $actBuilder->orderBy('created_at', 'DESC')->limit(5)->findAll();
            
            // Map bot names for display
            $botNameMap = [];
            foreach ($userBots as $b) {
                $botNameMap[$b->id] = $b->name;
            }
            
            foreach ($recentOrders as $order) {
                $pnlVal = (float)($order->pnl ?? 0);
                $recentActivities[] = [
                    'bot_name'  => $botNameMap[$order->bot_id] ?? 'Bot',
                    'symbol'    => $order->symbol,
                    'side'      => $order->side,
                    'type'      => $order->type,
                    'price'     => (float)$order->price,
                    'amount'    => (float)$order->amount,
                    'pnl'       => $pnlVal,
                    'status'    => $order->status,
                    'time'      => $order->created_at,
                ];
            }
        }

        $data = [
            'title'              => 'Dashboard RWA - Almai',
            'tradeMode'          => $tradeMode,
            'realBalanceStr'     => $realBalanceStr,
            'dailyPnL'           => $dailyPnL,
            'dailyPnLPercentStr' => $dailyPnLPercentStr,
            'isConnected'        => $isConnected,
            'almaiPoin'          => $poinModel->getUserBalance($userId),
            'activeBotsCount'    => count($activeBots),
            'tradesTodayCount'   => $tradesTodayCount,
            'prices'             => $prices,
            'recentActivities'   => $recentActivities,
            'connectedNames'     => $connectedNames,
        ];

        return view('user/rwa/dashboard', $data);
    }

    /**
     * Fetch real market prices from Trade Engine (ccxt Indodax/Tokocrypto)
     * Trade Engine endpoint: GET /api/ws/prices
     * Returns real prices from ccxt exchange without dummy/fallback
     */
    private function fetchRealMarketPrices(): array
    {
        try {
            $client = \Config\Services::curlrequest();
            $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
            
            // Trade Engine fetches real prices via ccxt (Indodax/Tokocrypto)
            $response = $client->get(rtrim($tradeEngineUrl, '/') . '/api/ws/prices', [
                'timeout' => 10,
                'http_errors' => false,
                'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
            ]);
            
            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody(), true);
                if (!empty($res['success']) && !empty($res['data'])) {
                    $prices = [];
                    $targetSymbols = ['PAXG/IDR', 'XAUT/IDR', 'SLVON/IDR'];
                    
                    foreach ($res['data'] as $item) {
                        $symbol = $item['symbol'] ?? '';
                        if (in_array($symbol, $targetSymbols)) {
                            $changePercent = (float)($item['changePercent'] ?? 0);
                            $prices[] = [
                                'symbol'         => str_replace('/', '_', $symbol),
                                'name'           => $symbol,
                                'price'          => (float)($item['price'] ?? 0),
                                'is_positive'    => $changePercent >= 0,
                                'change_percent' => $changePercent,
                            ];
                        }
                    }
                    if (!empty($prices)) return $prices;
                }
            }

            // Jika trade engine error/down, return empty (no dummy)
            return [
                ['symbol' => 'PAXG_IDR', 'name' => 'PAXG/IDR', 'price' => 0, 'is_positive' => true, 'change_percent' => 0],
                ['symbol' => 'XAUT_IDR', 'name' => 'XAUT/IDR', 'price' => 0, 'is_positive' => true, 'change_percent' => 0],
                ['symbol' => 'SLVON_IDR', 'name' => 'SLVON/IDR', 'price' => 0, 'is_positive' => true, 'change_percent' => 0],
            ];

        } catch (\Exception $e) {
            return [
                ['symbol' => 'PAXG_IDR', 'name' => 'PAXG/IDR', 'price' => 0, 'is_positive' => true, 'change_percent' => 0],
                ['symbol' => 'XAUT_IDR', 'name' => 'XAUT/IDR', 'price' => 0, 'is_positive' => true, 'change_percent' => 0],
                ['symbol' => 'SLVON_IDR', 'name' => 'SLVON/IDR', 'price' => 0, 'is_positive' => true, 'change_percent' => 0],
            ];
        }
    }

    public function setMode()
    {
        $mode = $this->request->getPost('mode');
        
        // Cek apakah ada bot yang sedang berjalan
        $botModel = new \App\Models\BotModel();
        $userId = session()->get('userId');
        $activeBots = $botModel->where('user_id', $userId)
                               ->where('status', 'active')
                               ->countAllResults();

        if ($activeBots > 0) {
            return redirect()->back()->with('error', 'Mode tidak bisa diubah karena masih ada bot yang berjalan. Silakan matikan (Stop) bot terlebih dahulu.');
        }

        if ($mode === 'live') {
            $db = \Config\Database::connect();
            
            // Check if user has purchased AIWE-RWA
            $layananRwa = $db->table('layanan_tools')->where('slug', 'AIWE-RWA')->get()->getRowArray();
            $rwaId = $layananRwa ? $layananRwa['id'] : 0;

            $hasPurchased = false;
            if ($rwaId) {
                $hasPurchased = $db->table('transaksi')
                    ->where('user_id', $userId)
                    ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                    ->where('layanan_id', $rwaId)
                    ->groupStart()
                        ->where('expires_at >=', date('Y-m-d H:i:s'))
                        ->orWhere('expires_at IS NULL', null, false)
                    ->groupEnd()
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getRowArray();
            }
            
            // Fallback check by product_name
            if (!$hasPurchased) {
                $hasPurchased = $db->table('transaksi')
                    ->where('user_id', $userId)
                    ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
                    ->like('product_name', 'AIWE')
                    ->groupStart()
                        ->where('expires_at >=', date('Y-m-d H:i:s'))
                        ->orWhere('expires_at IS NULL', null, false)
                    ->groupEnd()
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getRowArray();
            }

            if (!$hasPurchased) {
                $buyLink = base_url('user/dashboard/daftar-wpa/layanan/AIWE-RWA');
                $errorMessage = 'Mode LIVE terkunci. Anda harus membeli layanan AIWE-RWA terlebih dahulu. <a href="' . $buyLink . '" style="color: #33e818; text-decoration: underline; font-weight: bold;">Beli Sekarang</a>';
                return redirect()->back()->with('error', $errorMessage);
            }
        }

        if (in_array($mode, ['demo', 'live'])) {
            session()->set('rwa_trade_mode', $mode);
            $msg = $mode === 'live' ? 'Mode trading diubah ke LIVE (Risiko nyata).' : 'Mode trading diubah ke DEMO (Simulasi).';
            return redirect()->back()->with('success', $msg);
        }
        return redirect()->back()->with('error', 'Mode tidak valid.');
    }

        public function rwaPortfolio()
    {
        $userId = session()->get('userId');
        $currentUser = session()->get();
        $exchangeModel = new \App\Models\UserExchangeKeyModel();
        $keys = $exchangeModel->where('user_id', $userId)->where('is_active', 1)->first();
        
        $portfolioSummary = [
            'total_balance_str' => 'Rp 0',
            'live_note'         => 'Real balance aggregated from connected exchanges.',
            'connected_count'   => $keys ? 1 : 0,
            'exchange_count'    => 1,
            'allocation_segments' => [],
        ];
        
        $portfolioExchanges = [];
        $portfolioHoldings = [];
        
        if ($keys) {
            $client = \Config\Services::curlrequest();
            try {
                $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
                $response = $client->get(rtrim($tradeEngineUrl, '/') . '/api/balance/' . $userId, [
                    'timeout' => 5,
                    'http_errors' => false,
                    'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
                ]);
                $res = json_decode($response->getBody(), true);
                if ($res && !empty($res['success'])) {
                    $totalFiat = $res['fiat_valuation'] ?? 0;
                    $portfolioSummary['total_balance_str'] = 'Rp ' . number_format($totalFiat, 0, ',', '.');
                    
                    $portfolioExchanges[] = [
                        'exchange' => ucfirst($res['exchange'] ?? 'Mobee'),
                        'logo' => 'https://dev.cimara.net/images/mobee.jpeg',
                        'status' => 'Connected',
                        'account_name' => $res['account_name'] ?? 'Main Account',
                        'balance_str' => 'Rp ' . number_format($totalFiat, 0, ',', '.'),
                        'assets' => count($res['data']['total'] ?? []),
                        'error' => ''
                    ];
                    
                    $balances = $res['data']['total'] ?? [];
                    $fiatBalances = $res['fiat_balances'] ?? [];
                    
                    $colors = ['#7CFF00', '#00E5FF', '#FFB800', '#FF3232', '#A200FF'];
                    $colorIdx = 0;
                    
                    $segments = [];
                    $currentPercent = 0;
                    
                    foreach ($balances as $asset => $amount) {
                        if ($amount > 0) {
                            $assetFiat = $fiatBalances[$asset] ?? 0;
                            $percent = $totalFiat > 0 ? ($assetFiat / $totalFiat) * 100 : 0;
                            
                            $color = $colors[$colorIdx % count($colors)];
                            $colorIdx++;
                            
                            $portfolioHoldings[] = [
                                'asset_code' => $asset,
                                'quantity_str' => $amount,
                                'percent' => $percent,
                                'value_str' => 'Rp ' . number_format($assetFiat, 0, ',', '.'),
                                'sources_text' => 'Mobee Exchange',
                                'color' => $color,
                                'icon_path' => 'https://dev.cimara.net/images/crypto_icons/' . $asset . '.png'
                            ];
                            
                            if ($percent > 0) {
                                $segments[] = "$color $currentPercent% " . ($currentPercent + $percent) . "%";
                                $currentPercent += $percent;
                            }
                        }
                    }
                    
                    $portfolioSummary['allocation_segments'] = $segments;
                }
            } catch (\Exception $e) {
                // error fetching
            }
        }
        
        $data = [
            'title'              => 'Portfolio RWA - Almai',
            'currentUser'        => $currentUser,
            'portfolioSummary'   => $portfolioSummary,
            'portfolioExchanges' => $portfolioExchanges,
            'portfolioHoldings'  => $portfolioHoldings
        ];

        return view('user/rwa/portfolio', $data);
    }

    public function absen()
    {
        $userId = session()->get('userId');
        $absensiModel = new \App\Models\AbsensiPesertaModel();
        
        // Build query to get user's attendance
        $absensiList = $absensiModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        $db = \Config\Database::connect();
        
        // Enrich data with activity title
        foreach ($absensiList as &$row) {
            $table = '';
            if ($row['kegiatan_type'] == 'seminar' || $row['kegiatan_type'] == 'seminar_fgd') $table = 'seminar_fgd';
            elseif ($row['kegiatan_type'] == 'pelatihan' || $row['kegiatan_type'] == 'pelatihan_simulasi') $table = 'pelatihan_simulasi';
            elseif ($row['kegiatan_type'] == 'kegiatan_lainnya') $table = 'kegiatan_lainnya';
            elseif ($row['kegiatan_type'] == 'konsultasi') $table = 'konsultasi';
            elseif ($row['kegiatan_type'] == 'expert_advisor') $table = 'expert_advisor';
            elseif ($row['kegiatan_type'] == 'signal' || $row['kegiatan_type'] == 'signals') $table = 'signals';
            
            $row['kegiatan_name'] = '-';
            if ($table && $row['kegiatan_id']) {
                $activity = $db->table($table)->where('id', $row['kegiatan_id'])->get()->getRowArray();
                if ($activity) {
                    if ($table == 'expert_advisor') {
                        $row['kegiatan_name'] = $activity['nama_layanan'] ?? 'Unknown';
                    } elseif ($table == 'signals' || $table == 'konsultasi') {
                        $row['kegiatan_name'] = $activity['keterangan'] ?? 'Unknown';
                    } elseif ($table == 'kegiatan_lainnya') {
                        $row['kegiatan_name'] = $activity['nama_kegiatan'] ?? ($activity['keterangan'] ?? 'Unknown');
                    } else {
                        // seminar_fgd, pelatihan_simulasi
                        $row['kegiatan_name'] = $activity['judul'] ?? 'Unknown';
                    }
                }
            }
        }
        
        // Fetch active events for check-in
        $activeEvents = [];
        $models = [
            'seminar_fgd' => new \App\Models\SeminarModel(),
            'pelatihan_simulasi' => new \App\Models\PelatihanModel(),
            'signals' => new \App\Models\SignalModel(),
            'konsultasi' => new \App\Models\KonsultasiModel(),
            'expert_advisor' => new \App\Models\ExpertAdvisorModel(),
            'kegiatan_lainnya' => new \App\Models\KegiatanLainnyaModel(),
        ];
        
        $now = date('Y-m-d H:i:s');
        foreach ($models as $type => $model) {
            $events = $model->where('kode_qr IS NOT NULL')
                ->groupStart()
                    ->where('expired_link_kode_qr IS NULL')
                    ->orWhere('expired_link_kode_qr >=', $now)
                ->groupEnd()
                ->findAll();
                
            foreach ($events as $event) {
                // Determine title
                $eventName = '';
                if (in_array($type, ['seminar_fgd', 'pelatihan_simulasi'])) {
                    $eventName = $event['judul'];
                } elseif (in_array($type, ['signals', 'konsultasi'])) {
                    $eventName = $event['keterangan'] ?? 'Kegiatan Almai';
                } elseif ($type == 'kegiatan_lainnya') {
                    $eventName = $event['nama_kegiatan'] ?? ($event['keterangan'] ?? 'Kegiatan Almai');
                } elseif ($type == 'expert_advisor') {
                    $eventName = $event['nama_layanan'];
                }

                // Check if user already checked in
                $alreadyCheckedIn = false;
                foreach ($absensiList as $absen) {
                    $absenType = $absen['kegiatan_type'];
                    if ($absenType == 'seminar') $absenType = 'seminar_fgd';
                    if ($absenType == 'pelatihan') $absenType = 'pelatihan_simulasi';
                    
                    if ($absenType == $type && $absen['kegiatan_id'] == $event['id']) {
                        $alreadyCheckedIn = true;
                        break;
                    }
                }
                
                // Add alreadyCheckedIn status to event
                $event['alreadyCheckedIn'] = $alreadyCheckedIn;

                $event['kegiatan_type'] = $type;
                $event['kegiatan_name'] = $eventName;
                
                // Count check-ins
                $absenTypesDb = [$type];
                if ($type == 'seminar_fgd') $absenTypesDb[] = 'seminar';
                if ($type == 'pelatihan_simulasi') $absenTypesDb[] = 'pelatihan';
                
                $checkInCount = (new \App\Models\AbsensiPesertaModel())
                    ->whereIn('kegiatan_type', $absenTypesDb)
                    ->where('kegiatan_id', $event['id'])
                    ->countAllResults();
                $event['checkin_count'] = $checkInCount;

                // Fetch Target Clients
                $targetClients = 0;
                if (isset($event['jml_peserta'])) {
                    $targetClients = $event['jml_peserta'];
                } elseif (isset($event['jml_klien'])) {
                    $targetClients = $event['jml_klien'];
                } elseif (isset($event['nama_klien'])) {
                    preg_match('/Estimasi:\s*(\d+)/', $event['nama_klien'], $matches);
                    if (!empty($matches[1])) {
                        $targetClients = $matches[1];
                    }
                }
                $event['target_clients'] = $targetClients;

                // Fetch Location
                $locationName = '';
                if (!empty($event['lokasi'])) {
                    $locationName = $event['lokasi'];
                } elseif (!empty($event['media'])) {
                    $locationName = $event['media'];
                } elseif (!empty($event['keterangan']) && strpos($event['keterangan'], 'Lokasi: ') === 0) {
                    $locationName = str_replace('Lokasi: ', '', $event['keterangan']);
                }
                $event['location_name'] = $locationName;
                
                $activeEvents[] = $event;
            }
        }
        
        $user = (new \App\Models\UserModel())->find($userId);

        return view('user/dashboard_absen', [
            'title' => 'Event - Almai',
            'pageTitle' => 'Event',
            'activeMenu' => 'dashboard',
            'absensiList' => $absensiList,
            'activeEvents' => $activeEvents,
            'user' => $user
        ]);
    }
}



