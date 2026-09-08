<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run()
    {
        // 1. Clean up old/generic table to prevent confusion
        $this->db->table('layanan')->truncate();

        // 2. Truncate specific tables to ensure clean state
        $this->db->table('layanan_artikel')->truncate();
        $this->db->table('layanan_event')->truncate();
        $this->db->table('layanan_tools')->truncate();
        $this->db->table('layanan_subscription')->truncate();

        // --- SEED LAYANAN ARTIKEL ---
        $artikelData = [
            [
                'wpa_id' => 1,
                'title' => 'Analisis Teknikal Gold Harian',
                'slug' => 'analisis-teknikal-gold-harian',
                'excerpt' => 'Analisis mendalam pergerakan harga emas hari ini dengan indikator RSI dan MACD.',
                'content' => '<p>Harga emas diperkirakan akan mengalami kenaikan menuju level resisten 2050...</p>',
                'thumbnail' => null, 
                'poin_price' => 50, // Points
                'is_pro_only' => 0,
                'is_featured' => 1,
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'wpa_id' => 2,
                'title' => 'Strategi Scalping EURUSD',
                'slug' => 'strategi-scalping-eurusd',
                'excerpt' => 'Tips trading cepat menggunakan timeframe M5 untuk pair EURUSD.',
                'content' => '<p>Gunakan moving average 50 dan 200 untuk menentukan tren utama...</p>',
                'thumbnail' => null,
                'poin_price' => 100,
                'is_pro_only' => 1,
                'is_featured' => 0,
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('layanan_artikel')->insertBatch($artikelData);

        // --- SEED LAYANAN EVENT (Webinar & Workshop) ---
        $eventData = [
            [
                'wpa_id' => 1,
                'type' => 'webinar',
                'title' => 'Webinar Fundamental Trading',
                'slug' => 'webinar-fundamental-trading',
                'description' => 'Belajar cara membaca berita ekonomi dan dampaknya ke pasar forex.',
                'thumbnail' => null,
                'price' => 250000,
                'event_date' => date('Y-m-d H:i:s', strtotime('+1 week')),
                'event_end_date' => date('Y-m-d H:i:s', strtotime('+1 week +2 hours')),
                'location' => 'Zoom Meeting',
                'zoom_link' => 'https://zoom.us/j/1234567890',
                'meeting_id' => '123 456 7890',
                'meeting_password' => 'wpa123',
                'max_participants' => 100,
                'requires_agreement' => 0,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'wpa_id' => 3,
                'type' => 'workshop',
                'title' => 'Workshop Trading Intensif Bali',
                'slug' => 'workshop-trading-intensif-bali',
                'description' => 'Workshop offline 3 hari di Bali membahas psikologi trading dan money management.',
                'thumbnail' => null,
                'price' => 5000000,
                'event_date' => date('Y-m-d H:i:s', strtotime('+1 month')),
                'event_end_date' => date('Y-m-d H:i:s', strtotime('+1 month +3 days')),
                'location' => 'Hotel Aston Bali',
                'zoom_link' => null,
                'meeting_id' => null,
                'meeting_password' => null,
                'max_participants' => 20,
                'requires_agreement' => 1,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('layanan_event')->insertBatch($eventData);

        // --- SEED LAYANAN TOOLS (EA & Toolkit) ---
        $toolsData = [
            [
                'type' => 'ea',
                'name' => 'EA Angel Gold',
                'slug' => 'ea-angel-gold',
                'description' => 'Expert Advisor khusus Gold dengan strategi averaging martingale yang aman.',
                'thumbnail' => null,
                'price' => 12000000,
                'version' => '2.5',
                'features' => json_encode(['Auto Lot', 'News Filter', 'Trailing Stop']),
                'requirements' => json_encode(['MT4', 'Balance $1000', 'VPS']),
                'is_pro_only' => 0,
                'is_featured' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'toolkit',
                'name' => 'Almai Trading Journal',
                'slug' => 'almai-trading-journal',
                'description' => 'Spreadsheet lengkap untuk mencatat dan menganalisis performa trading Anda.',
                'thumbnail' => null,
                'price' => 150000,
                'version' => '1.0',
                'features' => json_encode(['Dashboard Analytics', 'Risk Calculator', 'Monthly Report']),
                'requirements' => null,
                'is_pro_only' => 0,
                'is_featured' => 0,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('layanan_tools')->insertBatch($toolsData);

        // --- SEED LAYANAN SUBSCRIPTION ---
        $subsData = [
            [
                'type' => 'pendampingan',
                'wpa_id' => 4,
                'name' => 'Pendampingan Private Gold',
                'slug' => 'pendampingan-private-gold',
                'description' => 'Mentoring private one-on-one selama 1 bulan.',
                'thumbnail' => null,
                'price' => 2500000,
                'duration_days' => 30,
                'benefits' => json_encode(['4x Zoom Call', 'Daily Signal', 'Review Floating']),
                'includes' => json_encode(['Ebook Strategy', 'Template MT4']),
                'max_slots' => 5,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'vip_member',
                'wpa_id' => null,
                'name' => 'VIP Member Tahunan',
                'slug' => 'vip-member-tahunan',
                'description' => 'Akses semua layanan premium selama 1 tahun.',
                'thumbnail' => null,
                'price' => 10000000,
                'duration_days' => 365,
                'benefits' => json_encode(['Free Access All EA', 'Free Webinar', 'Priority Support']),
                'includes' => null,
                'max_slots' => 0,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('layanan_subscription')->insertBatch($subsData);
    }
}
