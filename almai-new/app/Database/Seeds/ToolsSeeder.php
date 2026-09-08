<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ToolsSeeder extends Seeder
{
    public function run()
    {
        $tools = [
            [
                'name' => 'Gold Scalper EA Pro',
                'slug' => 'gold-scalper-ea-pro',
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600',
                'price' => 5000000,
                'original_price' => 8000000,
                'category' => 'Expert Advisor',
                'platform' => 'MT5',
                'rating' => 4.9,
                'sales' => 234,
                'description' => 'Expert Advisor untuk scalping gold dengan algoritma AI. Cocok untuk trader yang ingin trading otomatis 24/7.',
                'features' => json_encode(['Auto Trading 24/7', 'Risk Management Built-in', 'Multi Timeframe Analysis', 'News Filter', 'Telegram Notification']),
                'compatibility' => json_encode(['MT5', 'Windows', 'VPS Ready']),
                'status' => 'active'
            ],
            [
                'name' => 'Forex Trend Master EA',
                'slug' => 'forex-trend-master-ea',
                'thumbnail' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=600',
                'price' => 3500000,
                'original_price' => 5000000,
                'category' => 'Expert Advisor',
                'platform' => 'MT4/MT5',
                'rating' => 4.8,
                'sales' => 456,
                'description' => 'EA trend following untuk major pairs forex. Menggunakan kombinasi moving average dan price action.',
                'features' => json_encode(['Trend Detection', 'Auto Lot Sizing', 'Trailing Stop', 'Break Even', 'Multiple Pairs']),
                'compatibility' => json_encode(['MT4', 'MT5', 'Windows', 'VPS Ready']),
                'status' => 'active'
            ],
            [
                'name' => 'Trade Copier Pro',
                'slug' => 'trade-copier-pro',
                'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600',
                'price' => 2500000,
                'original_price' => 4000000,
                'category' => 'Copier',
                'platform' => 'MT4/MT5',
                'rating' => 4.9,
                'sales' => 789,
                'description' => 'Copy trade dari master account ke multiple slave accounts secara real-time. Latency rendah dan reliable.',
                'features' => json_encode(['Real-time Copy', 'Multi Account Support', 'Lot Multiplier', 'Symbol Mapping', 'Reverse Copy']),
                'compatibility' => json_encode(['MT4', 'MT5', 'Windows', 'VPS Ready']),
                'status' => 'active'
            ],
            [
                'name' => 'TradingView Signal Bot',
                'slug' => 'tradingview-signal-bot',
                'thumbnail' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
                'price' => 1500000,
                'original_price' => 2500000,
                'category' => 'Signal',
                'platform' => 'TradingView',
                'rating' => 4.7,
                'sales' => 567,
                'description' => 'Bot untuk mengirim signal dari TradingView ke MT4/MT5 secara otomatis. Support webhook dan alert.',
                'features' => json_encode(['Webhook Integration', 'Auto Execute', 'Telegram Alert', 'Custom Risk', 'Multi Symbol']),
                'compatibility' => json_encode(['TradingView', 'MT4', 'MT5']),
                'status' => 'active'
            ],
            [
                'name' => 'Smart Money Indicator',
                'slug' => 'smart-money-indicator',
                'thumbnail' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=600',
                'price' => 1000000,
                'original_price' => 1500000,
                'category' => 'Indicator',
                'platform' => 'MT4/MT5',
                'rating' => 4.8,
                'sales' => 1234,
                'description' => 'Indicator untuk mendeteksi pergerakan smart money dan institutional order flow.',
                'features' => json_encode(['Order Block Detection', 'Fair Value Gap', 'Liquidity Zones', 'Break of Structure', 'Alert System']),
                'compatibility' => json_encode(['MT4', 'MT5', 'TradingView']),
                'status' => 'active'
            ],
            [
                'name' => 'Risk Calculator Toolkit',
                'slug' => 'risk-calculator-toolkit',
                'thumbnail' => 'https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=600',
                'price' => 500000,
                'original_price' => 800000,
                'category' => 'Toolkit',
                'platform' => 'MT4/MT5',
                'rating' => 4.9,
                'sales' => 2345,
                'description' => 'Toolkit lengkap untuk menghitung lot size, risk per trade, dan position sizing secara otomatis.',
                'features' => json_encode(['Auto Lot Calculator', 'Risk Percentage', 'Pip Value', 'Margin Calculator', 'One-Click Trading']),
                'compatibility' => json_encode(['MT4', 'MT5']),
                'status' => 'active'
            ],
        ];

        foreach ($tools as $tool) {
            $this->db->table('tools')->insert($tool);
        }
    }
}
