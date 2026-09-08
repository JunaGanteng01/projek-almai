<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\CwpaModel;
use App\Models\LayananModel;
use App\Models\TransaksiModel;
use App\Models\WithdrawalModel;
use App\Models\WpaModel;
use App\Models\UserModel;
use App\Models\UserDataModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $cwpaId = $this->session->get('cwpaId');
        
        if (!$cwpaId) {
            return redirect()->to('/cwpa/login')->with('error', 'Sesi kadaluarsa, silakan login kembali.');
        }

        $cwpaModel = new CwpaModel();
        $cwpa = $cwpaModel->find($cwpaId);

        if (!$cwpa) {
            return redirect()->to('/cwpa/login')->with('error', 'Data CWPA tidak ditemukan');
        }

        // Models for stats
        $layananModel = new LayananModel();
        
        // Calculate phases stats
        $phases = CwpaModel::getPhases();
        $currentPhase = $cwpa['current_phase'] ?? 1;
        $totalPhases = count($phases);
        
        $certificates = [];
        if (!empty($cwpa['phase_certificates'])) {
            $certificates = is_string($cwpa['phase_certificates']) 
                ? json_decode($cwpa['phase_certificates'], true) 
                : $cwpa['phase_certificates'];
        }
        $totalCertificates = count($certificates);
        $progress = round(($currentPhase / $totalPhases) * 100);

        // Calculate Finacial Stats (Mirror WPA logic)
        $layananList = $layananModel->getByCwpaId($cwpaId);
        $totalLayanan = count($layananList);
        $totalStudents = 0;
        $totalEarnings = 0;
        $pendingEarnings = 0;
        $recentTransactions = [];
        $recentEnrollments = [];

        $db = \Config\Database::connect();
        if (!empty($layananList)) {
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

            // Total Points Earned from Sales (Actual points in table)
            $totalEarnings = $db->table('points')
                ->where('user_id', $cwpa['user_id'])
                ->where('type', 'earn')
                ->where('pointable_type', 'transaksi')
                ->selectSum('point')
                ->get()->getRowArray()['point'] ?? 0;

            // Pending Points (from transactions that are not confirmed yet)
            $pendingEarnings = 0; 
            
            $pendingTransactionsUnderCwpa = $baseQuery()->whereIn('status', ['pending', 'paid'])->get()->getResultArray();
            foreach ($pendingTransactionsUnderCwpa as $trx) {
                // Approximate from notes which we set in Checkout.php
                if (preg_match('/Pembayaran poin: ([\d,.]+) Poin/', $trx['notes'] ?? '', $matches)) {
                    $pendingEarnings += (int) str_replace(['.', ','], '', $matches[1]);
                }
            }

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

        // Calculate Withdrawal Stats
        $wdModel = new WithdrawalModel();
        $completedWithdrawn = $wdModel->getCompletedTotalCwpa($cwpaId);
        $pendingWithdrawal = $wdModel->getPendingTotalCwpa($cwpaId);
        $availableEarnings = $totalEarnings - ($completedWithdrawn + $pendingWithdrawal);

        // Get personal user points
        $poinModel = new \App\Models\PoinModel();
        $userPoints = $poinModel->getUserBalance($cwpa['user_id']);

        // Get user balance (cash) from users table
        $userModel = new UserModel();
        $user = $userModel->find($cwpa['user_id']);
        $userBalance = $user['balance'] ?? 0;

        $nowDateTime = date('Y-m-d H:i:s');
        $activeStatuses = ['upcoming', 'active', 'aktif', 'published'];

        // Check if this CWPA user also has a WPA ID linked to the same user_id
        $wpaModel = new \App\Models\WpaModel();
        $userWpa = $wpaModel->where('user_id', $cwpa['user_id'])->first();
        $userWpaId = $userWpa['id'] ?? null;

        // 1. Fetch from layanan_event
        $upcomingEventsRaw = $db->table('layanan_event')
            ->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->whereIn('layanan_event.status', $activeStatuses)
            ->get()
            ->getResultArray();

        // 2. Fetch from main layanan table
        $layananEvents = $db->table('layanan')
            ->select('id, name as title, slug, thumbnail, description, event_date, cwpa_id, wpa_id, status, mode as type, location')
            ->whereIn('status', $activeStatuses)
            ->where('event_date >=', $nowDateTime)
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
                'slug' => $event['slug'],
                'title' => $event['title'],
                'thumbnail' => $this->fixLayananImageUrl($event['thumbnail']),
                'type' => $event['type'] ?? 'webinar',
                'event_date' => $eventDate,
                'is_recurring' => (bool) ($event['is_recurring'] ?? false),
                'recurring_frequency' => $event['recurring_frequency'] ?? null,
                'recurring_day' => $engDay ?? $event['recurring_day'] ?? null,
                'recurring_time' => $event['recurring_time'] ?? null,
                'next_session' => $nextSession,
            ];

            $type = $event['type'] ?? 'webinar';
            $absenTypeDb = $type;
            if ($type == 'seminar_fgd') $absenTypeDb = 'seminar';
            if ($type == 'pelatihan_simulasi') $absenTypeDb = 'pelatihan';
            
            $checkInCount = (new \App\Models\AbsensiPesertaModel())
                ->where('kegiatan_type', $absenTypeDb)
                ->where('kegiatan_id', $event['id'])
                ->countAllResults();

            $targetClients = 0;
            // For layanan_event:
            if (isset($event['max_participants'])) {
                $targetClients = $event['max_participants'];
            }
            // For layanan (webinar etc), count confirmed transactions
            if (isset($event['title']) && !isset($event['max_participants'])) {
                $targetClients = \Config\Database::connect()->table('transaksi')
                    ->where('layanan_id', $event['id'])
                    ->where('status', 'confirmed')
                    ->countAllResults();
            }

            // Fallbacks similar to other places
            if ($targetClients == 0) {
                if (isset($event['jml_peserta'])) $targetClients = $event['jml_peserta'];
                elseif (isset($event['jml_klien'])) $targetClients = $event['jml_klien'];
                elseif (isset($event['nama_klien']) && preg_match('/Estimasi:\s*(\d+)/', $event['nama_klien'], $matches)) {
                    $targetClients = $matches[1];
                }
            }

            $belumAbsen = max(0, $targetClients - $checkInCount);
            $allEvents[count($allEvents) - 1]['belum_absen_count'] = $belumAbsen;
        }

        $nowDateTime = date('Y-m-d H:i:s');
        // For "Upcoming Events" list
        $upcomingEvents = array_filter($allEvents, function($e) use ($nowDateTime) {
            return $e['event_date'] >= $nowDateTime;
        });

        // Sort by date
        usort($upcomingEvents, function($a, $b) {
            return strtotime($a['event_date']) - strtotime($b['event_date']);
        });

        // Limit to 10 for the list
        $upcomingEvents = array_slice($upcomingEvents, 0, 10);

        // Check if user can checkin today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $alreadyCheckedIn = $poinModel->where('user_id', $cwpa['user_id'])
                                      ->where('pointable_type', 'chekin')
                                      ->where('created_at >=', $todayStart)
                                      ->where('created_at <=', $todayEnd)
                                      ->first();
        $canCheckin = !$alreadyCheckedIn;

        // Calculate pending event check-ins (events with active QR code that user hasn't scanned)
        $userId = $cwpa['user_id'];
        $absensiModel = new \App\Models\AbsensiPesertaModel();
        $absensiList = $absensiModel->where('user_id', $userId)->findAll();
        
        $pendingEventAbsenCount = 0;
        $models = [
            'seminar_fgd' => new \App\Models\SeminarModel(),
            'pelatihan_simulasi' => new \App\Models\PelatihanModel(),
            'signals' => new \App\Models\SignalModel(),
            'konsultasi' => new \App\Models\KonsultasiModel(),
            'expert_advisor' => new \App\Models\ExpertAdvisorModel(),
            'kegiatan_lainnya' => new \App\Models\KegiatanLainnyaModel(),
        ];
        
        foreach ($models as $type => $model) {
            $events = $model->where('kode_qr IS NOT NULL')
                ->groupStart()
                    ->where('expired_link_kode_qr IS NULL')
                    ->orWhere('expired_link_kode_qr >=', $nowDateTime)
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

        // Get daily checkin amount from settings
        $settingModel = new \App\Models\SettingModel();
        $poinDailyCheckin = $settingModel->get('poin_daily_checkin', 10);

        $data = [
            'title' => 'CWPA Dashboard - Almai',
            'activeMenu' => 'dashboard',
            'cwpa' => $cwpa,
            'stats' => [
                'balance' => $userBalance,           // Display user balance from users table
                'totalPoin' => $userPoints,          // Display personal user points in the poin card
                'totalKelas' => $totalLayanan,       // Layanan Created
                'totalSertifikat' => $totalStudents, // Students (using Sertifikat label for layout match)
                'totalTransaksi' => count($recentTransactions),
                'totalEarnings' => $totalEarnings,
                'pendingEarnings' => $pendingEarnings
            ],
            'upcomingEvents' => $upcomingEvents,
            'allEvents' => $allEvents,
            'recentTransactions' => $recentTransactions,
            'recentEnrollments' => $recentEnrollments,
            'totalStudents' => $totalStudents, // Keep for backward compatibility if any
            'totalLayanan' => $totalLayanan,
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings,
            'portofolio_accounts' => (new \App\Models\ProfirmEaAccountModel())->where('user_id', $cwpa['user_id'])->findAll(),
            'canCheckin' => $canCheckin,
            'poinDailyCheckin' => $poinDailyCheckin,
            'pendingEventAbsenCount' => $pendingEventAbsenCount
        ];

        return view('cwpa/dashboard', $data);
    }

    public function withdraw()
    {
        $cwpaId = $this->session->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

        $wdModel = new WithdrawalModel();
        $layananModel = new LayananModel();

        $cwpaModel = new CwpaModel();
        $cwpa = $cwpaModel->find($cwpaId);
        if (!$cwpa) return redirect()->to('/cwpa/login');

        // Check KYC Status
        $userModel = new UserModel();
        $user = $userModel->find($cwpa['user_id']);

        $userDataModel = new UserDataModel();
        $userData = $userDataModel->where('user_id', $cwpa['user_id'])->first();
        
        if (!$userData || !$user || $user['kyc_status'] !== 'approved') {
            return redirect()->to('/cwpa/dashboard/kyc')->with('error', 'Silakan lengkapi dan verifikasi KYC Anda terlebih dahulu sebelum melakukan penarikan dana.');
        }

        // Get Bank Info from User Data
        $userDataModel = new UserDataModel();
        $userData = $userDataModel->where('user_id', $cwpa['user_id'])->first();

        // Calculate available balance
        $layananList = $layananModel->getByCwpaId($cwpaId);
        $totalEarnings = 0;

        if (!empty($layananList)) {
            $layananIds = array_filter(array_column($layananList, 'id'));
            $layananNames = array_unique(array_filter(array_map(function($item) {
                return $item['name'] ?? $item['title'] ?? null;
            }, $layananList)));

            $db = \Config\Database::connect();
            $totalEarnings = $db->table('points')
                ->where('user_id', $cwpa['user_id'])
                ->where('type', 'earn')
                ->where('pointable_type', 'transaksi')
                ->selectSum('point')
                ->get()->getRowArray()['point'] ?? 0;
        }

        $completedWithdrawn = $wdModel->getCompletedTotalCwpa($cwpaId);
        $pendingWithdrawal = $wdModel->getPendingTotalCwpa($cwpaId);
        $availableBalance = $totalEarnings - ($completedWithdrawn + $pendingWithdrawal);

        $data = [
            'title' => 'Tarik Dana - CWPA Dashboard',
            'activeMenu' => 'withdraw',
            'totalEarnings' => $totalEarnings,
            'totalWithdrawn' => $completedWithdrawn,
            'pendingWithdrawal' => $pendingWithdrawal,
            'availableBalance' => $availableBalance,
            'withdrawals' => $wdModel->where('cwpa_id', $cwpaId)->orderBy('created_at', 'DESC')->findAll(),
            'userBank' => [
                'bank_name' => $userData['bank_name'] ?? '',
                'account_number' => $userData['account_number'] ?? '',
                'account_holder' => $userData['account_name'] ?? $user['name'] ?? '',
            ]
        ];

        return view('cwpa/withdraw', $data);
    }

    public function storeWithdraw()
    {
        $cwpaId = $this->session->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

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

        $wdModel = new WithdrawalModel();
        $layananModel = new LayananModel();

        $cwpaModel = new CwpaModel();
        $cwpa = $cwpaModel->find($cwpaId);
        if (!$cwpa) return redirect()->to('/cwpa/login');

        // Check KYC Status
        $userModel = new UserModel();
        $user = $userModel->find($cwpa['user_id']);

        $userDataModel = new UserDataModel();
        $userData = $userDataModel->where('user_id', $cwpa['user_id'])->first();

        if (!$userData || !$user || $user['kyc_status'] !== 'approved') {
            return redirect()->to('/cwpa/dashboard/kyc')->with('error', 'Silakan lengkapi dan verifikasi KYC Anda terlebih dahulu sebelum melakukan penarikan dana.');
        }

        $layananList = $layananModel->getByCwpaId($cwpaId);

        $totalEarnings = 0;
        if (!empty($layananList)) {
            $layananIds = array_filter(array_column($layananList, 'id'));
            $layananNames = array_unique(array_filter(array_map(function($item) {
                return $item['name'] ?? $item['title'] ?? null;
            }, $layananList)));

            $db = \Config\Database::connect();
            $totalEarnings = $db->table('points')
                ->where('user_id', $cwpa['user_id'])
                ->where('type', 'earn')
                ->where('pointable_type', 'transaksi')
                ->selectSum('point')
                ->get()->getRowArray()['point'] ?? 0;
        }

        $completedWithdrawn = $wdModel->getCompletedTotalCwpa($cwpaId);
        $pendingWithdrawal = $wdModel->getPendingTotalCwpa($cwpaId);
        $availableBalance = $totalEarnings - ($completedWithdrawn + $pendingWithdrawal);

        if ($amount > $availableBalance) {
            return redirect()->back()->withInput()->with('error', 'Saldo tidak mencukupi untuk penarikan ini.');
        }

        $wdModel->insert([
            'cwpa_id' => $cwpaId,
            'amount' => $amount,
            'bank_name' => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_holder' => $this->request->getPost('account_holder'),
            'status' => 'pending',
        ]);

        // --- ADD NOTIFICATIONS ---
        $notifModel = new \App\Models\NotificationModel();
        
        $userId = $cwpa['user_id'];
        $userName = $cwpa['name'];

        // 1. Notif ke User
        $notifModel->createNotification(
            $userId,
            'Penarikan Dana Diajukan 💸',
            'Pengajuan penarikan sebesar Rp ' . number_format($amount, 0, ',', '.') . ' telah kami terima dan sedang menunggu verifikasi admin.',
            'info',
            '/cwpa/dashboard/withdraw'
        );

        // 2. Notif ke Semua Admin
        $admins = $userModel->whereIn('level_id', [
            \App\Models\LevelModel::LEVEL_ADMIN,
            \App\Models\LevelModel::LEVEL_SUPER_ADMIN
        ])->findAll();

        foreach ($admins as $admin) {
            $notifModel->createNotification(
                $admin['id'],
                'Withdraw Baru (CWPA) 🔔',
                'Ada pengajuan penarikan baru dari CWPA: ' . $userName . ' sebesar Rp ' . number_format($amount, 0, ',', '.') . '.',
                'warning',
                '/admin/withdrawals'
            );
        }
        // --- END NOTIFICATIONS ---

        return redirect()->to('/cwpa/dashboard/withdraw')->with('success', 'Permintaan penarikan berhasil diajukan! Tunggu konfirmasi admin.');
    }

    public function earnings()
    {
        $cwpaId = $this->session->get('cwpaId');
        if (!$cwpaId) return redirect()->to('/cwpa/login');

        $layananModel = new LayananModel();
        $layananList = $layananModel->getByCwpaId($cwpaId);

        $transactions = [];
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
            
            $transactions = $builder->select('transaksi.*, users.name as user_name')
                ->join('users', 'users.id = transaksi.user_id', 'left')
                ->orderBy('transaksi.created_at', 'DESC')
                ->get()->getResultArray();
        }

        return view('cwpa/earnings', [
            'title' => 'Penghasilan - CWPA Dashboard',
            'activeMenu' => 'earnings',
            'transactions' => $transactions
        ]);
    }

    public function layananSaya()
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();

        // Fetch ALL confirmed transactions for this user
        $transactions = $transaksiModel
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $layananModel = new LayananModel();
        
        $myLayanan = [];

        foreach ($transactions as $trx) {
            if (!empty($trx['layanan_id'])) {
                $layananCheck = $layananModel->find($trx['layanan_id']);
                $layanan = null;

                if ($layananCheck && $layananCheck['name'] === $trx['product_name']) {
                    $layanan = $layananCheck;
                    $layanan['type_label'] = 'Course';
                }

                if (!$layanan) {
                    $db = \Config\Database::connect();
                    $tool = $db->table('layanan_tools')->where('id', $trx['layanan_id'])->get()->getRowArray();
                    if ($tool && $tool['name'] === $trx['product_name']) {
                        $layanan = [
                            'id' => $tool['id'],
                            'name' => $tool['name'],
                            'thumbnail' => $this->fixLayananImageUrl($tool['thumbnail']),
                            'description' => $tool['description'],
                            'type_label' => 'Tools'
                        ];
                    }

                    if (!$layanan) {
                        $event = $db->table('layanan_event')->where('id', $trx['layanan_id'])->get()->getRowArray();
                        if ($event && (
                            $event['title'] === $trx['product_name'] ||
                            stripos($trx['product_name'], $event['title']) !== false
                        )) {
                            $layanan = [
                                'id' => $event['id'],
                                'name' => $event['title'],
                                'thumbnail' => $this->fixLayananImageUrl($event['thumbnail']),
                                'type_label' => 'Event',
                                'type' => 'live',
                                'next_session' => $event['event_date']
                            ];
                        }
                    }
                }

                if ($layanan) {
                    $layanan['id'] = $trx['id'];
                    $layanan['layanan_id'] = $trx['layanan_id'];
                    $layanan['title'] = $layanan['name'];
                    $layanan['created_at'] = $trx['created_at'];
                    $myLayanan[] = $layanan;
                }
            }
        }

        return view('cwpa/layanan_saya', [
            'title' => 'Layanan Saya - CWPA Dashboard',
            'activeMenu' => 'layanan-saya',
            'myKelas' => $myLayanan,
            'pageTitle' => 'Layanan Saya'
        ]);
    }

    private function fixLayananImageUrl($path)
    {
        if (empty($path)) return null;
        if (strpos($path, 'http') === 0) return $path;
        
        $path = preg_replace('/^writable\//', '', $path);
        return base_url('file/' . ltrim($path, '/'));
    }


    public function absen()
    {
        $userId = session()->get('cwpa_user_id') ?? session()->get('userId');
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

        return view('cwpa/dashboard_absen', [
            'title' => 'Event - Almai',
            'pageTitle' => 'Event',
            'activeMenu' => 'dashboard',
            'absensiList' => $absensiList,
            'activeEvents' => $activeEvents,
            'user' => $user
        ]);
    }
}