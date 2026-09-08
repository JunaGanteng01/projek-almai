<?php

namespace App\Controllers;

use App\Models\LayananEventModel;

class Event extends BaseController
{
    protected $eventModel;
    protected $bannerModel;

    public function __construct()
    {
        $this->eventModel = new LayananEventModel();
        $this->bannerModel = new \App\Models\EventBannerModel();
    }

    public function index()
    {
        $request = \Config\Services::request();
        $search = $request->getGet('q');
        $category = $request->getGet('category');
        $type = $request->getGet('type');
        $date = $request->getGet('date'); // Format: YYYY-MM-DD

        // Base query for the main list and calendar
        $query = $this->eventModel->asArray()
            ->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo, cwpa.name as cwpa_name, cwpa.photo as cwpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->join('cwpa', 'cwpa.id = layanan_event.cwpa_id', 'left');


        // New Requirement: Only show events under 1 million
        // New Requirement: Only show events under 1 million or free (NULL/0)
        $query->groupStart()
            ->where('price <', 1000000)
            ->orWhere('price IS NULL')
            ->orWhere('price', '')
            ->groupEnd();
        $query->whereIn('layanan_event.status', ['upcoming', 'aktif', 'published']);

        if ($search) {
            $query->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('wpa.name', $search)
                ->groupEnd();
        }

        if ($category && $category !== 'Semua') {
            $query->where('specialist', $category);
        }

        if ($type && $type !== 'Semua') {
            $query->where('type', $type);
        }

        // Fetch all matching events (we'll expand them in PHP)
        $allBaseEvents = $query->orderBy('event_date', 'ASC')->findAll();

        // Expand for the next 90 days to ensure events beyond current month are visible
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+90 days'));
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end->modify('+1 day');
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);


        $dayMap = [
            'senin' => 'monday',
            'selasa' => 'tuesday',
            'rabu' => 'wednesday',
            'kamis' => 'thursday',
            'jumat' => 'friday',
            'sabtu' => 'saturday',
            'minggu' => 'sunday'
        ];

        $expandedEvents = [];
        $todayStr = date('Y-m-d');
        
        foreach ($allBaseEvents as $e) {
            $isFree = (empty($e['price']) || $e['price'] == 0);

            if (!$e['is_recurring']) {
                // One-time event
                if (!empty($e['event_date']) && $e['event_date'] !== '0000-00-00 00:00:00') {
                    // Show if it's future/today OR if it's FREE (always show free services)
                    if (strtotime($e['event_date']) >= strtotime($todayStr) || $isFree) {
                        $e['date'] = $e['event_date'];
                        $expandedEvents[] = $e;
                    }
                } 
                elseif ($isFree) {
                    // FREE event with no date - show as "Always available"
                    $e['date'] = date('Y-m-d H:i:s');
                    $e['is_placeholder_date'] = true;
                    $expandedEvents[] = $e;
                }
            } else {
                // Recurring event expansion
                $frequency = $e['recurring_frequency'] ?? 'weekly';
                $dbDay = strtolower(trim($e['recurring_day'] ?? ''));
                $targetDayEng = $dayMap[$dbDay] ?? $dbDay;
                
                $foundAnyInstance = false;
                foreach ($period as $dt) {
                    $shouldAdd = false;
                    $currentDayName = strtolower($dt->format('l'));
                    
                    if ($frequency === 'daily') {
                        $shouldAdd = true;
                    } elseif ($frequency === 'weekly' && $targetDayEng === $currentDayName) {
                        $shouldAdd = true;
                    } elseif ($frequency === 'monthly') {
                         $targetDayOfMonth = !empty($e['recurring_day']) && is_numeric($e['recurring_day'])
                             ? (int)$e['recurring_day']
                             : (int)date('j', strtotime($e['event_date'] ?? $todayStr));
                         if ($dt->format('j') == $targetDayOfMonth) $shouldAdd = true;
                    }

                    if ($shouldAdd) {
                        $instance = $e;
                        $instance['date'] = $dt->format('Y-m-d') . ' ' . ($e['recurring_time'] ?? '00:00:00');
                        $expandedEvents[] = $instance;
                        $foundAnyInstance = true;
                    }
                }
                
                // If it's FREE and recurring but NO instance found in 90 days (weird config), show at least one
                if ($isFree && !$foundAnyInstance) {
                    $e['date'] = date('Y-m-d H:i:s');
                    $e['is_placeholder_date'] = true;
                    $expandedEvents[] = $e;
                }
            }
        }


        // Filter the main list by date if selected
        if ($date) {
            $eventsToShow = array_filter($expandedEvents, function ($e) use ($date) {
                return date('Y-m-d', strtotime($e['date'])) === $date;
            });
        } else {
            // No date selected? Show upcoming ones or all expanded for the month
            // To keep it simple, show everything for the month sorted by date
            $eventsToShow = $expandedEvents;
        }

        // Sort by date
        usort($eventsToShow, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        // Format for view
        $formattedEvents = array_map(function ($e) {
            $e['short_date'] = !empty($e['is_placeholder_date']) ? 'Selalu Tersedia' : date('d M Y', strtotime($e['date']));
            $e['time'] = !empty($e['is_placeholder_date']) ? '' : date('H:i', strtotime($e['date']));
            $e['image'] = !empty($e['thumbnail']) ? base_url('file/' . $e['thumbnail']) : 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop';
            $e['category'] = !empty($e['specialist']) ? $e['specialist'] : $e['type'];
            $e['hosted_by'] = $e['wpa_name'] ?? ($e['cwpa_name'] ?? 'Almai Event');
            $e['city'] = $e['location'] ?? 'Online';

            $e['views'] = 0;
            $e['shares'] = 0;
            if (isset($e['current_participants']) && isset($e['max_participants'])) {
                $e['participants'] = $e['current_participants'] . '/' . $e['max_participants'];
            }
            return $e;
        }, $eventsToShow);

        $banners = $this->bannerModel->where('is_active', 1)->orderBy('order', 'ASC')->findAll();

        $data = [
            'title' => 'Jadwal Event - Almai ID',
            'events' => $formattedEvents,
            'calendarEvents' => $expandedEvents, // For the calendar widget
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'selectedCategory' => $category,
            'selectedType' => $type,
            'searchQuery' => $search,
            'selectedDate' => $date,
            'banners' => $banners
        ];

        return view('pages/event/index', $data);
    }

    public function detail($slug)
    {
        // Capture referral code from URL
        $ref = $this->request->getGet('ref');
        if ($ref) {
            session()->set('checkout_ref', $ref);
        }

        // Enforce price limit even on detail page
        $event = $this->eventModel->select('layanan_event.*, wpa.name as wpa_name, wpa.slug as wpa_slug, wpa.photo as wpa_photo, cwpa.name as cwpa_name, cwpa.slug as cwpa_slug, cwpa.photo as cwpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left')
            ->join('cwpa', 'cwpa.id = layanan_event.cwpa_id', 'left')
            ->where('layanan_event.slug', $slug)
            ->groupStart()
                ->where('price <', 1000000)
                ->orWhere('price IS NULL')
                ->orWhere('price', '')
            ->groupEnd()
            ->whereIn('layanan_event.status', ['upcoming', 'aktif', 'published'])
            ->first();

        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Events generated from Absensi Kegiatan use the attendance/check-in flow.
        // This branch is isolated from all paid and manually-created events.
        if (!empty($event['attendance_code'])
            && isset($event['is_paid'])
            && (int) $event['is_paid'] === 0) {
            return redirect()->to('/absensi/checkin/' . rawurlencode($event['attendance_code']));
        }

        // Redirect CWPA to user dashboard if logged in (has registration modal)
        if (in_array($event['slug'], ['pendampingan-cwpa', 'cwpa']) && session()->get('isLoggedIn')) {
            $refCode = $this->request->getGet('ref');
            $redirectUrl = base_url('user/layanan/' . $event['slug']);
            if ($refCode) {
                $redirectUrl .= '?ref=' . urlencode($refCode);
            }
            return redirect()->to($redirectUrl);
        }

        // Get Price Packages
        $priceModel = new \App\Models\LayananPriceModel();
        $packages = $priceModel->getPackages($event['type'], $event['id']);

        // Fetch real confirmed participants count from transaksi table (Matching Layanan.php logic)
        $db = \Config\Database::connect();
        $pxCount = $db->table('transaksi')
            ->where('product_name', $event['title'])
            ->where('status', 'confirmed')
            ->countAllResults();

        // Format data for view - match layanan detail structure
        $event['name'] = $event['title'];
        $event['date'] = $event['event_date']; 
        $event['short_date'] = date('d M Y', strtotime($event['event_date']));
        $event['time'] = date('H:i', strtotime($event['event_date']));

        // Map fields for view compatibility
        $event['image'] = !empty($event['thumbnail']) ? base_url('file/' . $event['thumbnail']) : 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop';
        $event['thumbnail'] = $event['image'];
        $event['category'] = 'Advokasi';
        $event['subcategory'] = ucfirst($event['type']);
        $event['hosted_by'] = $event['wpa_name'] ?? 'Almai Event';
        $event['city'] = $event['location'] ?? 'Online';
        $event['address'] = $event['location'] ?? 'Online Event';
        $event['mode'] = $event['type'] === 'webinar' ? 'Online' : 'Offline';
        $event['level'] = 'All Level';
        $event['is_premium'] = $event['is_pro_only'];

        // Fix WPA photo URL
        if (!empty($event['wpa_photo'])) {
            if (strpos($event['wpa_photo'], 'http') !== 0) {
                $event['wpa_photo'] = base_url($event['wpa_photo']);
            }
        } else {
            $event['wpa_photo'] = 'https://almai.id/images/alma.gif';
        }

        // Set participant count from real transactions
        $event['students'] = $pxCount;
        if (isset($event['max_participants']) && $event['max_participants'] > 0) {
            $event['participants'] = $pxCount . '/' . $event['max_participants'];
        } else {
            $event['participants'] = $pxCount . '+';
        }

        // Get Reviews
        $ulasanModel = new \App\Models\LayananUlasanModel();
        $reviews = $ulasanModel->getReviews($event['id'], 'event');
        $averageRating = 0;
        if (!empty($reviews)) {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $averageRating = $totalRating / count($reviews);
        }

        // Check if user has purchased
        $hasPurchased = false;
        $hasReviewed = false;
        if (session()->get('isLoggedIn')) {
            $transaksiModel = new \App\Models\TransaksiModel();
            $purchase = $transaksiModel->where('user_id', session()->get('userId'))
                ->where('product_name', $event['title'])
                ->where('status', 'confirmed')
                ->first();

            $hasPurchased = ($purchase !== null);

            if ($hasPurchased) {
                $hasReviewed = $ulasanModel->hasUserReviewed(session()->get('userId'), $event['id'], 'event');
            }
        }

        // Prepare meta description
        $metaDescription = strip_tags($event['description']);
        $metaDescription = mb_substr($metaDescription, 0, 160);
        if (mb_strlen(strip_tags($event['description'])) > 160) {
            $metaDescription .= '...';
        }

        $data = [
            'title' => $event['title'] . ' - Almai Event',
            'meta_title' => $event['title'] . ' - Event | Almai',
            'meta_description' => $metaDescription,
            'meta_image' => $event['image'],
            'event' => $event, // Still pass as event for backward compatibility if needed
            'layanan' => $event, // Aliased as layanan for shared view logic
            'packages' => $packages,
            'reviews' => $reviews,
            'averageRating' => $averageRating ?: 5.0,
            'reviewCount' => count($reviews),
            'hasPurchased' => $hasPurchased,
            'hasReviewed' => $hasReviewed,
            'extendedInfo' => [
                'warning' => null,
                'layanan_utama' => $event['layanan_utama'] ?? null
            ]
        ];

        return view('pages/event/detail', $data);
    }


    public function list()
    {
        $request = \Config\Services::request();
        $search = $request->getGet('q');
        $category = $request->getGet('category');
        $date = $request->getGet('date');

        $query = $this->eventModel->asArray()
            ->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left');

        $query->groupStart()
            ->where('price <', 1000000)
            ->orWhere('price IS NULL')
            ->orWhere('price', '')
            ->groupEnd();
        $query->whereIn('layanan_event.status', ['upcoming', 'aktif', 'published']);

        if ($search) {
            $query->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('wpa.name', $search)
                ->groupEnd();
        }

        if ($category && $category !== 'Semua') {
            $query->where('specialist', $category);
        }

        if ($date) {
            $query->like('event_date', $date);
        }

        $allEvents = $query->orderBy('event_date', 'ASC')->findAll();

        $events = array_map(function ($e) {
            $e['date'] = $e['event_date'];
            $e['short_date'] = date('d M Y', strtotime($e['event_date']));
            $e['time'] = date('H:i', strtotime($e['event_date']));
            $e['image'] = !empty($e['thumbnail']) ? base_url('file/' . $e['thumbnail']) : 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop';
            $e['category'] = $e['type'];
            $e['hosted_by'] = $e['wpa_name'] ?? 'Almai Event';
            $e['city'] = $e['location'] ?? 'Online';
            $e['views'] = 0;
            $e['shares'] = 0;

            if (isset($e['current_participants']) && isset($e['max_participants'])) {
                $e['participants'] = $e['current_participants'] . '/' . $e['max_participants'];
            }
            return $e;
        }, $allEvents);

        $data = [
            'title' => 'Semua Event - Almai ID',
            'events' => $events,
            'selectedCategory' => $category,
            'searchQuery' => $search
        ];

        return view('pages/event_list', $data);
    }

    /**
     * Static method to get events for use in other controllers (e.g. Checkout)
     */
    public static function getEvents()
    {
        $eventModel = new LayananEventModel();

        $query = $eventModel->asArray()
            ->select('layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo')
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left');

        // Only show events under 1 million or free
        $query->groupStart()
            ->where('price <', 1000000)
            ->orWhere('price IS NULL')
            ->orWhere('price', '')
            ->groupEnd();
        $query->whereIn('layanan_event.status', ['upcoming', 'aktif', 'published']);

        $allEvents = $query->orderBy('event_date', 'ASC')->findAll();

        return array_map(function ($e) {
            $e['date'] = $e['event_date'];
            $e['short_date'] = date('d M Y', strtotime($e['event_date']));
            $e['time'] = date('H:i', strtotime($e['event_date']));
            $e['image'] = !empty($e['thumbnail']) ? base_url('file/' . $e['thumbnail']) : 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop';
            $e['category'] = $e['type'];
            $e['hosted_by'] = $e['wpa_name'] ?? 'Almai Event';
            $e['city'] = $e['location'] ?? 'Online';

            if (isset($e['current_participants']) && isset($e['max_participants'])) {
                $e['participants'] = $e['current_participants'] . '/' . $e['max_participants'];
            }
            return $e;
        }, $allEvents);
    }

    public function submitReview()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu');
        }

        $eventId = $this->request->getPost('event_id');
        $rating = $this->request->getPost('rating');
        $ulasan = $this->request->getPost('ulasan');
        $eventTitle = $this->request->getPost('event_title');

        // Verify purchase
        $transaksiModel = new \App\Models\TransaksiModel();
        $purchase = $transaksiModel->where('user_id', session()->get('userId'))
            ->where('product_name', $eventTitle)
            ->where('status', 'confirmed')
            ->first();

        if (!$purchase) {
            return redirect()->back()->with('error', 'Anda belum membeli event ini.');
        }

        $ulasanModel = new \App\Models\LayananUlasanModel();

        // Check duplicate
        if ($ulasanModel->hasUserReviewed(session()->get('userId'), $eventId, 'event')) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan.');
        }

        $ulasanId = $ulasanModel->insert([
            'user_id' => session()->get('userId'),
            'layanan_id' => $eventId,
            'layanan_type' => 'event',
            'rating' => $rating,
            'ulasan' => $ulasan,
            'status' => 'approved'
        ]);

        if ($ulasanId) {
            // Notify WPA
            try {
                $db = \Config\Database::connect();
                $item = $db->table('layanan_event')->select('wpa_id')->where('id', $eventId)->get()->getRowArray();
                if ($item && !empty($item['wpa_id'])) {
                    $wpa = $db->table('wpa')->select('user_id')->where('id', $item['wpa_id'])->get()->getRowArray();
                    if ($wpa && $wpa['user_id']) {
                        $notifModel = new \App\Models\NotificationModel();
                        $notifModel->createNotification(
                            $wpa['user_id'],
                            "Ulasan Baru Diterima!",
                            session()->get('userName') . " memberikan rating $rating bintang untuk event '$eventTitle' Anda.",
                            'success',
                            base_url('wpa/dashboard')
                        );
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Event Review Notification Failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
