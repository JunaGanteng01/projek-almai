<?php

namespace App\Controllers;

use App\Models\WpaModel;
use App\Models\KelasModel;
use App\Models\ArtikelModel;
use App\Models\EventModel;

class Home extends BaseController
{
    public function index()
    {
        $cache = \Config\Services::cache();

        // 1. Cache User Data (60 seconds)
        if (!$userStats = $cache->get('home_user_data')) {
            $userModel = new \App\Models\UserModel();
            $userStats = [
                'total' => $userModel->countAllResults(),
                'recent' => $userModel->select('name, avatar')->orderBy('created_at', 'DESC')->limit(20)->find()
            ];
            $cache->save('home_user_data', $userStats, 60);
        }
        $totalUsers = $userStats['total'];
        $recentUsers = $userStats['recent'];

        // 2. Cache Glossaries (1 hour)
        if (!$glossaries = $cache->get('home_glossaries')) {
            $glossaryModel = new \App\Models\GlossaryModel();
            $glossaries = $glossaryModel->orderBy('term', 'ASC')->findAll();
            $cache->save('home_glossaries', $glossaries, 3600);
        }

        // 3. Cache Events & Calendar (1 hour)
        if (!$eventData = $cache->get('home_events_data_vfinal_secure')) {
            $eventModel = new \App\Models\LayananEventModel();
            $today = date('Y-m-d');
            $todayDT = new \DateTime($today);

            // Fetch One-Time Events (Upcoming) - Only status 'aktif'
            $oneTimeRaw = $eventModel->select('layanan_event.*, wpa.name as wpa_name')
                ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
                ->where('is_recurring', 0)
                ->where('layanan_event.status', 'aktif')
                ->where('event_date >=', $today)
                ->where('price <', 1000000)
                ->orderBy('event_date', 'ASC')
                ->limit(10)
                ->findAll();

            // Fetch All Active Recurring Events - Only status 'aktif'
            $recurringRaw = $eventModel->select('layanan_event.*, wpa.name as wpa_name')
                ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
                ->where('is_recurring', 1)
                ->where('layanan_event.status', 'aktif')
                ->where('price <', 1000000)
                ->findAll();

            $dayMap = ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday'];
            $allUpcoming = $oneTimeRaw;

            // Expand Recurring Events for the next 30 days to find upcoming instances
            $endPeriod = (clone $todayDT)->modify('+30 days');
            $period = new \DatePeriod($todayDT, new \DateInterval('P1D'), $endPeriod);

            foreach ($recurringRaw as $rev) {
                $frequency = $rev['recurring_frequency'] ?? 'weekly';
                $count = 0;
                foreach ($period as $dt) {
                    if ($count >= 2) break; // Limit to next 2 instances per recurring event
                    $shouldAdd = false;
                    $currentDayOfMonth = $dt->format('j');
                    $currentDayName = strtolower($dt->format('l'));

                    if ($frequency === 'daily') {
                        $shouldAdd = true;
                    } elseif ($frequency === 'weekly') {
                        $targetDay = strtolower($rev['recurring_day'] ?? '');
                        $targetDayEng = $dayMap[$targetDay] ?? $targetDay;
                        if ($targetDayEng === $currentDayName) $shouldAdd = true;
                    } elseif ($frequency === 'monthly') {
                        $targetDayOfMonth = !empty($rev['recurring_day']) && is_numeric($rev['recurring_day']) ? (int)$rev['recurring_day'] : (int)date('j', strtotime($rev['event_date']));
                        if ($currentDayOfMonth == $targetDayOfMonth) $shouldAdd = true;
                    }

                    if ($shouldAdd) {
                        $instance = $rev;
                        $instance['event_date'] = $dt->format('Y-m-d');
                        if (!empty($rev['recurring_time'])) $instance['event_date'] .= ' ' . $rev['recurring_time'];
                        $allUpcoming[] = $instance;
                        $count++;
                    }
                }
            }

            // Sort all by date
            usort($allUpcoming, function($a, $b) {
                return strtotime($a['event_date']) - strtotime($b['event_date']);
            });

            // Take Top 3 for slider
            $topEvents = array_slice($allUpcoming, 0, 3);

            $dayMapIndo = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];

            $events = array_map(function ($e) use ($dayMapIndo) {
                $dayEng = date('l', strtotime($e['event_date']));
                $dayIndo = $dayMapIndo[$dayEng] ?? $dayEng;

                $e['date'] = $e['event_date'];
                $e['short_date'] = $dayIndo . ', ' . date('d M Y', strtotime($e['event_date']));
                $e['time'] = date('H:i', strtotime($e['event_date']));
                $e['image'] = !empty($e['thumbnail']) ? base_url('file/' . $e['thumbnail']) : 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop';
                $e['category'] = $e['type'];
                $e['hosted_by'] = $e['wpa_name'] ?? 'Almai Event';
                $e['city'] = $e['location'] ?? 'Online';
                $e['participants'] = '0/0';
                return $e;
            }, $topEvents);

            $monthStart = date('Y-m-01');
            $monthEnd = date('Y-m-t');

            $oneTimeEvents = $eventModel->select('id, title, slug, thumbnail, event_date as date, is_recurring, recurring_day')
                ->where('is_recurring', 0)
                ->where('status', 'aktif')
                ->where('event_date >=', $monthStart)
                ->where('event_date <=', $monthEnd)
                ->where('price <', 1000000)
                ->orderBy('event_date', 'ASC')
                ->findAll();

            $recurringEvents = $eventModel->select('id, title, slug, thumbnail, event_date as date, is_recurring, recurring_frequency, recurring_day, recurring_time')
                ->where('is_recurring', 1)
                ->where('status', 'aktif')
                ->where('price <', 1000000)
                ->findAll();

            $calendarEvents = $oneTimeEvents;
            $start = new \DateTime($monthStart);
            $end = new \DateTime($monthEnd);
            $end->modify('+1 day');
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);
            $dayMap = ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday'];

            foreach ($recurringEvents as $rev) {
                $frequency = $rev['recurring_frequency'] ?? 'weekly';
                foreach ($period as $dt) {
                    $shouldAdd = false;
                    $currentDayOfMonth = $dt->format('j');
                    $currentDayName = strtolower($dt->format('l'));

                    if ($frequency === 'daily') {
                        $shouldAdd = true;
                    } elseif ($frequency === 'weekly') {
                        $targetDay = strtolower($rev['recurring_day'] ?? '');
                        $targetDayEng = $dayMap[$targetDay] ?? $targetDay;
                        if ($targetDayEng === $currentDayName) {
                            $shouldAdd = true;
                        }
                    } elseif ($frequency === 'monthly') {
                        $targetDayOfMonth = !empty($rev['recurring_day']) && is_numeric($rev['recurring_day']) ? (int)$rev['recurring_day'] : (int)date('j', strtotime($rev['date']));
                        if ($currentDayOfMonth == $targetDayOfMonth) {
                            $shouldAdd = true;
                        }
                    }

                    if ($shouldAdd) {
                        $instance = $rev;
                        $instance['date'] = $dt->format('Y-m-d');
                        if (!empty($rev['recurring_time'])) {
                            $instance['date'] .= ' ' . $rev['recurring_time'];
                        }
                        $calendarEvents[] = $instance;
                    }
                }
            }

            $eventData = [
                'events' => $events,
                'calendarEvents' => $calendarEvents
            ];
            $cache->save('home_events_data_vfinal_secure', $eventData, 3600);
        }
        $events = $eventData['events'];
        $calendarEvents = $eventData['calendarEvents'];

        $bannerModel = new \App\Models\EventBannerModel();
        $eventBanners = $bannerModel->where('is_active', 1)->orderBy('order', 'ASC')->findAll();

        $data = [
            'title' => 'Almai - PT. ALMA INDONESIA RAYA | Platform Penasihat Berjangka.',
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'glossaries' => $glossaries,
            'events' => $events,
            'calendarEvents' => $calendarEvents,
            'eventBanners' => $eventBanners
        ];

        return view('pages/home', $data);
    }


    public function getRealtimeStats()
    {
        $cache = \Config\Services::cache();
        $cacheKey = 'home_realtime_stats';
        
        if (!$stats = $cache->get($cacheKey)) {
            $userModel = new \App\Models\UserModel();
            $total = $userModel->countAllResults();
            $latest = $userModel->select('name, avatar, created_at')
                ->orderBy('created_at', 'DESC')
                ->first();

            $stats = [
                'total' => $total,
                'latest' => [
                    'name' => $latest['name'] ?? 'User',
                    'avatar' => !empty($latest['avatar']) ? base_url('file/' . $latest['avatar']) : base_url('images/default-avatar.png'),
                    'ts' => strtotime($latest['created_at'] ?? 'now')
                ]
            ];
            
            // Cache for 60 seconds
            $cache->save($cacheKey, $stats, 60);
        }

        return $this->response->setJSON($stats);
    }

    public function getRecentUsers()
    {
        $userModel = new \App\Models\UserModel();
        $recentUsers = $userModel->select('name, avatar')
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->find();

        return $this->response->setJSON([
            'users' => $recentUsers
        ]);
    }
}
