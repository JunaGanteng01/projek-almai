<?php

namespace App\Controllers;

class TradingView extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Trading View - Real-time Market Analysis',
            'description' => 'Monitor pasar crypto dan forex secara real-time dengan analisis mendalam'
        ];

        return view('pages/trading_view', $data);
    }

    /**
     * API endpoint untuk mendapatkan data trading
     */
    public function getMarketData()
    {
        // Data dummy untuk demo - bisa diganti dengan API real seperti Binance/CoinGecko
        $cryptoData = [
            [
                'symbol' => 'BTC/USDT',
                'name' => 'Bitcoin',
                'price' => 67845.32,
                'change_24h' => 2.45,
                'volume' => '28.5B',
                'market_cap' => '1.32T',
                'high_24h' => 68200.00,
                'low_24h' => 65800.00,
                'icon' => '₿'
            ],
            [
                'symbol' => 'ETH/USDT',
                'name' => 'Ethereum',
                'price' => 3456.78,
                'change_24h' => -1.23,
                'volume' => '15.2B',
                'market_cap' => '415B',
                'high_24h' => 3512.00,
                'low_24h' => 3401.50,
                'icon' => 'Ξ'
            ],
            [
                'symbol' => 'BNB/USDT',
                'name' => 'Binance Coin',
                'price' => 589.23,
                'change_24h' => 3.87,
                'volume' => '2.1B',
                'market_cap' => '88B',
                'high_24h' => 595.00,
                'low_24h' => 565.00,
                'icon' => '🔶'
            ],
            [
                'symbol' => 'SOL/USDT',
                'name' => 'Solana',
                'price' => 145.67,
                'change_24h' => 5.32,
                'volume' => '3.8B',
                'market_cap' => '65B',
                'high_24h' => 148.90,
                'low_24h' => 138.20,
                'icon' => '◎'
            ],
            [
                'symbol' => 'ADA/USDT',
                'name' => 'Cardano',
                'price' => 0.5678,
                'change_24h' => -2.14,
                'volume' => '856M',
                'market_cap' => '20B',
                'high_24h' => 0.5901,
                'low_24h' => 0.5565,
                'icon' => '₳'
            ],
            [
                'symbol' => 'XRP/USDT',
                'name' => 'Ripple',
                'price' => 0.6234,
                'change_24h' => 1.76,
                'volume' => '1.2B',
                'market_cap' => '34B',
                'high_24h' => 0.6345,
                'low_24h' => 0.6123,
                'icon' => '✕'
            ]
        ];

        $forexData = [
            [
                'symbol' => 'EUR/USD',
                'name' => 'Euro / US Dollar',
                'price' => 1.0856,
                'change_24h' => 0.15,
                'volume' => 'N/A',
                'high_24h' => 1.0872,
                'low_24h' => 1.0841
            ],
            [
                'symbol' => 'GBP/USD',
                'name' => 'British Pound / US Dollar',
                'price' => 1.2734,
                'change_24h' => -0.23,
                'volume' => 'N/A',
                'high_24h' => 1.2756,
                'low_24h' => 1.2718
            ],
            [
                'symbol' => 'USD/JPY',
                'name' => 'US Dollar / Japanese Yen',
                'price' => 149.87,
                'change_24h' => 0.42,
                'volume' => 'N/A',
                'high_24h' => 150.12,
                'low_24h' => 149.34
            ]
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'timestamp' => time(),
            'data' => [
                'crypto' => $cryptoData,
                'forex' => $forexData
            ]
        ]);
    }

    /**
     * API endpoint untuk mendapatkan historical chart data
     */
    public function getChartData()
    {
        $symbol = $this->request->getGet('symbol') ?? 'BTC/USDT';
        $interval = $this->request->getGet('interval') ?? '1h';

        // Generate dummy chart data - dalam production bisa gunakan API Binance/TradingView
        $dataPoints = 50;
        $basePrice = 67000;
        $chartData = [];

        for ($i = 0; $i < $dataPoints; $i++) {
            $timestamp = strtotime("-{$dataPoints} hours") + ($i * 3600);
            $randomChange = (rand(-300, 300) / 100);
            $basePrice += $randomChange;

            $chartData[] = [
                'time' => $timestamp * 1000, // milliseconds for chart
                'open' => $basePrice,
                'high' => $basePrice + rand(50, 300),
                'low' => $basePrice - rand(50, 300),
                'close' => $basePrice + rand(-100, 100),
                'volume' => rand(1000000, 5000000)
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'symbol' => $symbol,
            'interval' => $interval,
            'data' => $chartData
        ]);
    }
}
