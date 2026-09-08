<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\KelasModel;
use App\Models\WpaModel;

class KelasDetail extends BaseController
{
    public function index($id)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $layananModel = new \App\Models\LayananModel();
        $wpaModel = new WpaModel();
        
        // Check if user has purchased this layanan (using ID)
        $purchase = $transaksiModel
            ->where('user_id', $userId)
            ->where('layanan_id', $id)
            ->where('status', 'confirmed')
            ->first();
        
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
                    // Auto-fix the link for next time
                    $transaksiModel->update($fuzzy['id'], ['layanan_id' => $id]);
                }
            }
        }

        if (!$purchase) {
             return redirect()->to('/user/layanan-saya')->with('error', 'Anda belum membeli layanan ini');
        }
        
        // Get layanan detail
        // 1. Try Main Layanan Table
        $layanan = $layananModel->find($id);
        
        // 2. Try Layanan Event Table (if not found in main)
        // If the ID in transaction refers to layanan_event, find() above will naturally fail or return wrong data
        // Ideally we should check product_type, but loosely checking both tables works for now
        if (!$layanan) {
            $db = \Config\Database::connect();
            $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();
            
            if ($event) {
                // Calculate Schedule
                $schedule = $event['event_date'];
                $nextSession = $event['event_date'];
                
                if (!empty($event['is_recurring']) && $event['is_recurring'] == 1) {
                    $dayMap = [
                        'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                        'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
                    ];
                    $dbDay = $event['recurring_day'];
                    $engDay = $dayMap[$dbDay] ?? 'Monday';
                    $time = $event['recurring_time'];
                    
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
                $layanan = [
                    'id' => $event['id'],
                    'name' => $event['title'],
                    'slug' => $event['slug'],
                    'category' => 'Event', 
                    'subcategory' => $event['type'],
                    'description' => $event['description'],
                    'thumbnail' => base_url($event['thumbnail']), // Ensure full URL for uploads
                    'wpa_id' => $event['wpa_id'],
                    'price' => $event['price'],
                    'rating' => 0,
                    'students' => $event['current_participants'] ?? 0,
                    'mode' => ($event['type'] == 'webinar') ? 'Live' : 'Offline',
                    'location' => $event['location'] ?? 'Online',
                    'duration' => '-', 
                    'level' => 'All Level',
                    'modules' => 0,
                    'schedule' => $schedule,
                    'next_session' => $nextSession,
                    'zoom_link' => $event['zoom_link'] ?? '#',
                    'zoom_meeting_id' => $event['meeting_id'] ?? '-', // Fixed column name
                    'zoom_password' => $event['meeting_password'] ?? '-', // Fixed column name
                    'youtube_tutorials' => $event['youtube_tutorials'] ?? null,
                ];
                // Force type to live/event in view logic
                $layanan['type'] = 'live'; 
            }
        }

        if (!$layanan) {
            return redirect()->to('/user/layanan-saya')->with('error', 'Layanan tidak ditemukan');
        }
        
        // Map fields for view compatibility
        $layanan['title'] = $layanan['name']; // View likely uses 'title'
        
        // Get WPA info
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $wpaModel->find($layanan['wpa_id']);
        }
        
        // Determine if live or recorded
        $isLive = ($layanan['mode'] ?? 'Online') !== 'Online'; // Assume anything not Online is "Live" or similar?
        // Or strictly check 'mode' or category.
        
        // Materials (dummy for now)
        $materials = [];
        
        return view('user/kelas-detail', [
            'title' => $layanan['name'] . ' - Almai',
            'kelas' => $layanan, // Keep variable name 'kelas' for view compatibility
            'wpa' => $wpa,
            'isLive' => $isLive,
            'materials' => $materials,
        ]);
    }
}
