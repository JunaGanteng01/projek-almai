<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\LayananModel;
use App\Models\TransaksiModel;
use App\Models\WithdrawalModel;
use App\Models\PoinModel;

class Dashboard extends BaseController
{
    private function fixLayananImageUrl($path)
    {
        if (empty($path)) return null;
        if (strpos($path, 'http') === 0) return $path;
        if (strpos($path, 'uploads/') === 0) return base_url('file/' . $path);
        return base_url($path);
    }
    public function index()
    {
        $wpaId = $this->session->get('wpaId');

        $wpaModel = new WpaModel();
        $layananModel = new LayananModel();
        $transaksiModel = new TransaksiModel();

        if (!$wpaId) {
            $userId = $this->session->get('userId');
            $wpa = $wpaModel->where('user_id', $userId)->first();
            if ($wpa) {
                $wpaId = $wpa['id'];
                $this->session->set('wpaId', $wpaId);
            }
        } else {
            $wpa = $wpaModel->find($wpaId);
        }

        if (!$wpaId) {
            return redirect()->to('/login')->with('error', 'Profil WPA tidak ditemukan');
        }

        // Fetch real data
        $layananList = $layananModel->getByWpaId($wpaId);
        foreach ($layananList as &$item) {
            $item['title'] = $item['name'] ?? ($item['title'] ?? '-');
        }

        $totalLayanan = count($layananList);
        $totalStudents = 0;
        $totalEarnings = 0;
        $pendingEarnings = 0;
        $recentTransactions = [];
        $recentEnrollments = [];

        if (!empty($layananList)) {
            $db = \Config\Database::connect();

            // Build the base query for this WPA's products
            $baseQuery = function () use ($db, $layananList) {
                $builder = $db->table('transaksi');
                
                $layananIds = array_filter(array_column($layananList, 'id'));
                $layananNames = array_unique(array_filter(array_map(function($item) {
                    return $item['name'] ?? $item['title'] ?? null;
                }, $layananList)));

                $builder->groupStart();
                if (!empty($layananIds)) {
                    $builder->whereIn('layanan_id', $layananIds);
                }
                if (!empty($layananNames)) {
                    $builder->orWhereIn('product_name', $layananNames);
                    // Add LIKE only for few items to avoid regex issues
                    if (count($layananNames) < 50) {
                        foreach ($layananNames as $name) {
                            $builder->orLike('product_name', $name . ' - ', 'after');
                        }
                    }
                }
                $builder->groupEnd();
                
                return $builder;
            };

            $totalStudents = $baseQuery()->where('status', 'confirmed')
                ->select('COUNT(DISTINCT user_id) AS total_count')
                ->get()->getRowArray()['total_count'] ?? 0;

            $totalEarnings = $baseQuery()->where('status', 'confirmed')
                ->where('payment_method !=', 'poin')
                ->select('SUM(total) AS total_sum')
                ->get()->getRowArray()['total_sum'] ?? 0;

            $pendingEarnings = $baseQuery()->whereIn('status', ['pending', 'paid'])
                ->where('payment_method !=', 'poin')
                ->select('SUM(total) AS total_sum')
                ->get()->getRowArray()['total_sum'] ?? 0;

            $recentTransactions = $baseQuery()->select('transaksi.*, users.name as user_name')
                ->join('users', 'users.id = transaksi.user_id', 'left')
                ->orderBy('transaksi.created_at', 'DESC')
                ->limit(5)
                ->get()->getResultArray();

            $recentEnrollments = $baseQuery()->select('transaksi.*, users.name as user_name, transaksi.product_name as kelas_title')
                ->join('users', 'users.id = transaksi.user_id', 'left')
                ->where('transaksi.status', 'confirmed')
                ->orderBy('transaksi.created_at', 'DESC')
                ->limit(4)
                ->get()->getResultArray();
        }

        $wdModel = new WithdrawalModel();
        $totalWithdrawn = $wdModel->getCompletedTotal($wpaId);
        $pendingWithdrawal = $wdModel->getPendingTotal($wpaId);
        
        // Get balance from users table
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($wpa['user_id']);
        $availableBalance = $user['balance'] ?? 0;

        // Get total poin for WPA user
        $poinModel = new PoinModel();
        $userId = $wpa['user_id'];
        $totalPoin = $poinModel->getUserBalance($userId);

        // Check if user can checkin today
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

        // Get all events for calendar with preprocessing like CWPA
        $db = \Config\Database::connect();
        $nowDateTime = date('Y-m-d H:i:s');
        
        // 1. Fetch from layanan_event (without status filter to see all data)
        $upcomingEventsRaw = $db->table('layanan_event')
            ->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->get()
            ->getResultArray();

        // 2. Fetch from main layanan table (without recurring fields)
        $layananEvents = $db->table('layanan')
            ->select('id, name as title, slug, thumbnail, description, event_date, cwpa_id, wpa_id, status, mode as type, location')
            ->where('event_date IS NOT NULL')
            ->get()
            ->getResultArray();
        
        // Merge them
        $allEventsMerged = array_merge($upcomingEventsRaw, $layananEvents);

        $dayMap = [
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
            'Sabtu' => 'Saturday',
            'Minggu' => 'Sunday'
        ];

        $allEvents = [];
        foreach ($allEventsMerged as $event) {
            $nextSession = null;
            $eventDate = null;
            $engDay = null;

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
                    $eventDate = date('Y-m-d H:i:s'); 
                }
            } elseif (!empty($event['event_date']) && strtotime($event['event_date']) > 0) {
                $eventDate = $event['event_date'];
                $nextSession = date('d M Y, H:i', strtotime($event['event_date']));
            }

            if (empty($eventDate)) continue;

            $allEvents[] = [
                'id' => $event['id'],
                'slug' => $event['slug'] ?? '',
                'title' => $event['title'],
                'thumbnail' => $event['thumbnail'] ?? '',
                'type' => $event['type'] ?? 'webinar',
                'event_date' => $eventDate,
                'is_recurring' => (bool) ($event['is_recurring'] ?? false),
                'recurring_frequency' => $event['recurring_frequency'] ?? null,
                'recurring_day' => $engDay ?? $event['recurring_day'] ?? null,
                'recurring_time' => $event['recurring_time'] ?? null,
                'next_session' => $nextSession,
            ];
        }

        // Calculate pending event check-ins (events with active QR code that user hasn't scanned)
        $userId = $wpa['user_id'];
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

        $data = [
            'title' => 'WPA Dashboard - Almai',
            'activeMenu' => 'dashboard',
            'wpa' => $wpa,
            'stats' => [
                'totalKelas' => $totalLayanan,
                'totalStudents' => $totalStudents,
                'totalEarnings' => $totalEarnings,
                'totalWithdrawn' => $totalWithdrawn,
                'availableBalance' => $availableBalance,
                'pendingEarnings' => $pendingEarnings,
                'totalPoin' => $totalPoin
            ],
            'kelasList' => $layananList,
            'recentTransactions' => $recentTransactions,
            'recentEnrollments' => $recentEnrollments,
            'canCheckin' => $canCheckin,
            'poinDailyCheckin' => $poinDailyCheckin,
            'allEvents' => $allEvents,
            'pendingEventAbsenCount' => $pendingEventAbsenCount,
        ];

        return view('wpa/dashboard', $data);
    }

    public function withdraw()
    {
        $wpaId = $this->session->get('wpaId');
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $wpaModel = new \App\Models\WpaModel();
        $wpa = $wpaModel->find($wpaId);
        if (!$wpa) return redirect()->to('/wpa/dashboard');

        // Check KYC Status
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($wpa['user_id']);
        $userDataModel = new \App\Models\UserDataModel();
        $userData = $userDataModel->where('user_id', $wpa['user_id'])->first();

        if (!$userData || !$user || $user['kyc_status'] !== 'approved') {
            return redirect()->to('/wpa/dashboard/kyc')->with('error', 'Silakan lengkapi dan verifikasi KYC Anda terlebih dahulu sebelum melakukan penarikan dana.');
        }

        $wdModel = new WithdrawalModel();
        $transaksiModel = new TransaksiModel();
        $layananModel = new LayananModel();

        // Calculate available balance
        $layananList = $layananModel->getByWpaId($wpaId);
        $totalEarnings = 0;

        if (!empty($layananList)) {
            $layananIds = array_filter(array_column($layananList, 'id'));
            $layananNames = array_unique(array_filter(array_map(function($item) {
                return $item['name'] ?? $item['title'] ?? null;
            }, $layananList)));

            $db = \Config\Database::connect();
            $builder = $db->table('transaksi');
            
            $builder->groupStart();
            if (!empty($layananIds)) {
                $builder->whereIn('layanan_id', $layananIds);
            }
            if (!empty($layananNames)) {
                $builder->orWhereIn('product_name', $layananNames);
                if (count($layananNames) < 50) {
                    foreach ($layananNames as $name) {
                        $builder->orLike('product_name', $name . ' - ', 'after');
                    }
                }
            }
            $builder->groupEnd();

            $totalEarnings = (clone $builder)->where('status', 'confirmed')
                ->select('SUM(total) AS total_sum')
                ->get()->getRowArray()['total_sum'] ?? 0;
        }

        $completedWithdrawn = $wdModel->getCompletedTotal($wpaId);
        $pendingWithdrawal = $wdModel->getPendingTotal($wpaId);

        $availableBalance = $totalEarnings - ($completedWithdrawn + $pendingWithdrawal);

        $data = [
            'title' => 'Withdraw - WPA Dashboard',
            'activeMenu' => 'dashboard',
            'totalEarnings' => $totalEarnings,
            'totalWithdrawn' => $completedWithdrawn,
            'pendingWithdrawal' => $pendingWithdrawal,
            'availableBalance' => $availableBalance,
            'withdrawals' => $wdModel->where('wpa_id', $wpaId)->orderBy('created_at', 'DESC')->findAll(),
            'userBank' => [
                'bank_name' => $userData['bank_name'] ?? '',
                'account_number' => $userData['account_number'] ?? '',
                'account_holder' => $userData['account_name'] ?? $user['name'] ?? '',
            ]
        ];

        return view('wpa/withdraw', $data);
    }

    public function storeWithdraw()
    {
        $wpaId = $this->session->get('wpaId');
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $rules = [
            'amount' => 'required|numeric|greater_than_equal_to[1000000]',
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_holder' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid atau jumlah penarikan terlalu kecil (Min Rp 1.000.000)');
        }

        $amount = (float) $this->request->getPost('amount');

        $wpaModel = new \App\Models\WpaModel();
        $wpa = $wpaModel->find($wpaId);
        if (!$wpa) return redirect()->to('/wpa/dashboard');

        // Check KYC Status
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($wpa['user_id']);
        $userDataModel = new \App\Models\UserDataModel();
        $userData = $userDataModel->where('user_id', $wpa['user_id'])->first();

        if (!$userData || !$user || $user['kyc_status'] !== 'approved') {
            return redirect()->to('/wpa/dashboard/kyc')->with('error', 'Silakan lengkapi dan verifikasi KYC Anda terlebih dahulu sebelum melakukan penarikan dana.');
        }

        // Re-calculate balance to prevent exploitation
        $wdModel = new WithdrawalModel();
        $transaksiModel = new TransaksiModel();
        $layananModel = new LayananModel();
        $layananList = $layananModel->getByWpaId($wpaId);

        $totalEarnings = 0;
        if (!empty($layananList)) {
            $layananIds = array_filter(array_column($layananList, 'id'));
            $layananNames = array_unique(array_filter(array_map(function($item) {
                return $item['name'] ?? $item['title'] ?? null;
            }, $layananList)));

            $db = \Config\Database::connect();
            $builder = $db->table('transaksi');
            
            $builder->groupStart();
            if (!empty($layananIds)) {
                $builder->whereIn('layanan_id', $layananIds);
            }
            if (!empty($layananNames)) {
                $builder->orWhereIn('product_name', $layananNames);
                if (count($layananNames) < 50) {
                    foreach ($layananNames as $name) {
                        $builder->orLike('product_name', $name . ' - ', 'after');
                    }
                }
            }
            $builder->groupEnd();
            $totalEarnings = (clone $builder)->where('status', 'confirmed')
                ->select('SUM(total) AS total_sum')
                ->get()->getRowArray()['total_sum'] ?? 0;
        }

        $completedWithdrawn = $wdModel->getCompletedTotal($wpaId);
        $pendingWithdrawal = $wdModel->getPendingTotal($wpaId);
        $availableBalance = $totalEarnings - ($completedWithdrawn + $pendingWithdrawal);

        if ($amount > $availableBalance) {
            return redirect()->back()->withInput()->with('error', 'Saldo tidak mencukupi untuk penarikan ini.');
        }

        $wdModel->insert([
            'wpa_id' => $wpaId,
            'amount' => $amount,
            'bank_name' => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_holder' => $this->request->getPost('account_holder'),
            'status' => 'pending',
        ]);

        // --- ADD NOTIFICATIONS ---
        $notifModel = new \App\Models\NotificationModel();
        $userModel = new \App\Models\UserModel();
        $wpa = (new \App\Models\WpaModel())->find($wpaId);
        $userId = $wpa['user_id'];
        $userName = $wpa['name'];

        // 1. Notif ke User
        $notifModel->createNotification(
            $userId,
            'Penarikan Dana Diajukan 💸',
            'Pengajuan penarikan sebesar Rp ' . number_format($amount, 0, ',', '.') . ' telah kami terima dan sedang menunggu verifikasi admin.',
            'info',
            '/wpa/dashboard/withdraw'
        );

        // 2. Notif ke Semua Admin
        $admins = $userModel->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ])->findAll();

        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Withdraw Baru (WPA) 🔔',
                'Ada pengajuan penarikan baru dari WPA: ' . $userName . ' sebesar Rp ' . number_format($amount, 0, ',', '.') . '.',
                'warning',
                '/admin/withdrawals'
            );
        }
        // --- END NOTIFICATIONS ---


        return redirect()->to('/wpa/dashboard/withdraw')->with('success', 'Permintaan penarikan berhasil diajukan! Tunggu konfirmasi admin.');
    }

    public function layananSaya()
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();

        // Fetch ALL valid transactions for this user
        $transactions = $transaksiModel
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->orderBy('created_at', 'DESC')
            ->findAll();


        $layananModel = new LayananModel();
        $wpaModel = new WpaModel();

        $myLayanan = [];

        foreach ($transactions as $trx) {
            // Check if it has a linked service (layanan_id)
            if (!empty($trx['layanan_id'])) {
                // Fetch full details from LayananModel (Main Table)
                $layananCheck = $layananModel->find($trx['layanan_id']);
                $layanan = null;
                $event = null;
                $tool = null;

                // ONLY accept if name matches (flexible matching to allow for package name suffixes)
                if ($layananCheck && (
                    $layananCheck['name'] === $trx['product_name'] ||
                    stripos($trx['product_name'], $layananCheck['name']) !== false ||
                    stripos($layananCheck['name'], explode(' - ', $trx['product_name'])[0]) !== false
                )) {
                    $layanan = $layananCheck;
                    $layanan['type_label'] = 'Course'; // Default for main table
                }


                // If not found in Main Table, try Event Table
                if (!$layanan) {
                    $db = \Config\Database::connect();

                    // Priority 1: Check if it's a Tool (EA/Toolkit)
                    $tool = $db->table('layanan_tools')->where('id', $trx['layanan_id'])->get()->getRowArray();
                    if ($tool && (
                        $tool['name'] === $trx['product_name'] ||
                        stripos($trx['product_name'], $tool['name']) !== false ||
                        stripos($tool['name'], explode(' - ', $trx['product_name'])[0]) !== false
                    )) {

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
                        if ($artikel && (
                            $artikel['title'] === $trx['product_name'] ||
                            stripos($trx['product_name'], $artikel['title']) !== false
                        )) {

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
                    $item['kelas_id'] = $trx['layanan_id'];
                    $item['layanan_id'] = $trx['layanan_id'];

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

        return view('wpa/layanan_saya', [
            'title' => 'Layanan Saya - Almai',
            'pageTitle' => 'Layanan Saya',
            'pageSubtitle' => 'Layanan yang sudah Anda beli',
            'activeMenu' => 'layanan-saya',
            'myKelas' => $myLayanan,
        ]);
    }

    public function absen()
    {
        $userId = session()->get('wpa_user_id') ?? session()->get('userId');
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

        return view('wpa/dashboard_absen', [
            'title' => 'Event - Almai',
            'pageTitle' => 'Event',
            'activeMenu' => 'dashboard',
            'absensiList' => $absensiList,
            'activeEvents' => $activeEvents,
            'user' => $user
        ]);
    }
}
