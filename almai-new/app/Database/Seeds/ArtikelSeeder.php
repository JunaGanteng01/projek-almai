<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'wpa_id' => 1,
                'title' => '5 Kesalahan Fatal Trader Pemula yang Harus Dihindari',
                'slug' => '5-kesalahan-fatal-trader-pemula',
                'thumbnail' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
                'excerpt' => 'Banyak trader pemula kehilangan modal karena kesalahan yang sebenarnya bisa dihindari. Simak 5 kesalahan fatal ini.',
                'content' => '<p>Trading adalah aktivitas yang membutuhkan disiplin dan pengetahuan yang cukup. Banyak trader pemula yang terjebak dalam kesalahan-kesalahan umum yang sebenarnya bisa dihindari.</p><h2>1. Tidak Menggunakan Stop Loss</h2><p>Stop loss adalah alat penting untuk membatasi kerugian. Tanpa stop loss, satu trade yang salah bisa menghabiskan seluruh modal Anda.</p><h2>2. Overtrading</h2><p>Terlalu sering trading tanpa setup yang jelas adalah kesalahan umum. Quality over quantity adalah kunci sukses trading.</p>',
                'category' => 'Tips Trading',
                'read_time' => '5 min',
                'status' => 'published',
                'created_at' => '2025-01-15 10:00:00',
            ],
            [
                'wpa_id' => 2,
                'title' => 'Cara Membaca Berita Ekonomi untuk Trading Forex',
                'slug' => 'cara-membaca-berita-ekonomi-forex',
                'thumbnail' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=600',
                'excerpt' => 'Berita ekonomi sangat mempengaruhi pergerakan forex. Pelajari cara membaca dan memanfaatkannya.',
                'content' => '<p>Analisis fundamental adalah salah satu pendekatan penting dalam trading forex. Memahami bagaimana berita ekonomi mempengaruhi pergerakan mata uang adalah skill yang harus dikuasai.</p>',
                'category' => 'Fundamental',
                'read_time' => '7 min',
                'status' => 'published',
                'created_at' => '2025-01-10 10:00:00',
            ],
            [
                'wpa_id' => 7,
                'title' => 'Bitcoin Halving 2024: Dampak dan Peluang Trading',
                'slug' => 'bitcoin-halving-2024-dampak-peluang',
                'thumbnail' => 'https://images.unsplash.com/photo-1621761191319-c6fb62004040?w=600',
                'excerpt' => 'Bitcoin halving selalu membawa volatilitas tinggi. Bagaimana memanfaatkan momentum ini?',
                'content' => '<p>Bitcoin halving adalah event penting dalam ekosistem cryptocurrency yang terjadi setiap 4 tahun sekali. Event ini selalu membawa volatilitas tinggi dan peluang trading yang menarik.</p>',
                'category' => 'Crypto',
                'read_time' => '10 min',
                'status' => 'published',
                'created_at' => '2025-01-05 10:00:00',
            ],
            [
                'wpa_id' => 4,
                'title' => 'Panduan Lengkap Trading CPO di Bursa Berjangka',
                'slug' => 'panduan-trading-cpo-bursa-berjangka',
                'thumbnail' => 'https://images.unsplash.com/photo-1516937941344-00b4e0337589?w=600',
                'excerpt' => 'CPO adalah salah satu komoditas paling aktif di Indonesia. Pelajari cara tradingnya.',
                'content' => '<p>Crude Palm Oil (CPO) adalah salah satu komoditas unggulan Indonesia. Trading CPO di bursa berjangka menawarkan peluang profit yang menarik bagi trader yang memahami karakteristiknya.</p>',
                'category' => 'Commodity',
                'read_time' => '12 min',
                'status' => 'published',
                'created_at' => '2024-12-28 10:00:00',
            ],
            [
                'wpa_id' => 5,
                'title' => 'Strategi Trading Saat Market Sideways',
                'slug' => 'strategi-trading-market-sideways',
                'thumbnail' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=600',
                'excerpt' => 'Market tidak selalu trending. Bagaimana strategi yang tepat saat market sideways?',
                'content' => '<p>Market sideways atau ranging adalah kondisi dimana harga bergerak dalam range tertentu tanpa trend yang jelas. Banyak trader kesulitan profit di kondisi ini, padahal ada strategi khusus yang bisa digunakan.</p>',
                'category' => 'Strategy',
                'read_time' => '8 min',
                'status' => 'published',
                'created_at' => '2024-12-20 10:00:00',
            ],
            [
                'wpa_id' => 9,
                'title' => 'Pentingnya Risk Management dalam Trading',
                'slug' => 'pentingnya-risk-management-trading',
                'thumbnail' => 'https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=600',
                'excerpt' => 'Risk management adalah kunci sukses trading jangka panjang. Pelajari teknik yang tepat.',
                'content' => '<p>Risk management adalah fondasi dari trading yang sukses. Tanpa pengelolaan risiko yang baik, bahkan strategi trading terbaik pun bisa berakhir dengan kerugian.</p>',
                'category' => 'Tips Trading',
                'read_time' => '8 min',
                'status' => 'published',
                'created_at' => '2024-12-10 10:00:00',
            ],
        ];

        $this->db->table('artikel')->insertBatch($data);
    }
}
