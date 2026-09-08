<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserExchangeKeyModel;
use App\Models\BotModel;
use App\Models\BotOrderModel;
use App\Models\BotLogModel;

class RwaBot extends BaseController
{
    protected $exchangeModel;
    protected $botModel;
    protected $orderModel;
    protected $logModel;

    public function __construct()
    {
        $this->exchangeModel = new UserExchangeKeyModel();
        $this->botModel      = new BotModel();
        $this->orderModel    = new BotOrderModel();
        $this->logModel      = new BotLogModel();
    }

    public function exchangeApi()
    {
        $userId = session()->get('userId');
        $data = [
            'title' => 'Exchange API',
            'exchangeKeys' => $this->exchangeModel->where('user_id', $userId)->findAll(),
            'supportedExchanges' => [
                ['slug' => 'indodax', 'name' => 'Indodax', 'logo' => '/images/indodax.png'],
                ['slug' => 'tokocrypto', 'name' => 'Tokocrypto', 'logo' => '/images/Tokocrypto.png'],
                ['slug' => 'mobee', 'name' => 'Mobee', 'logo' => '/images/mobee.jpeg'],
            ]
        ];
        return view('user/rwa/exchange_api', $data);
    }

        public function bots()
    {
        $userId = session()->get('userId');
        $tradeMode = session()->get('rwa_trade_mode') ?? 'demo';
        
        $bots = $this->botModel->where('user_id', $userId)->findAll();
        
        // Prepare botsData exactly like Laravel template expects
        $botsData = [];
        foreach ($bots as $b) {
            $config = json_decode($b->config ?? '{}', true);
            $botsData[] = [
                'icon' => '<circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>',
                'name' => $b->name,
                'symbol' => $b->symbol,
                'status' => $b->status,
                'description' => $b->strategy . ' Strategy',
                'pair' => $b->symbol,
                'timeframe' => $config['timeframe'] ?? '1h',
                'allocation_percent' => $config['allocation_percent'] ?? 10,
                'stop_loss_percent' => $config['stopLossPercent'] ?? 0,
                'total_profit' => $b->total_profit ?? 0,
                'total_trades' => $config['total_trades'] ?? 0,
                'win_rate' => $config['win_rate'] ?? 0,
                'uptime' => $config['uptime'] ?? '0h'
            ];
        }

        // Provide dummy data if no bots exist, so the user sees the UI properly
        if (empty($botsData)) {
            $defaultBots = [
                [
                    'user_id' => $userId,
                    'user_exchange_key_id' => null,
                    'name' => 'Alpha Gold',
                    'symbol' => 'SLVON_IDR',
                    'strategy' => 'EMA 9 & 21 Cross + VWAP pullback entry',
                    'status' => 'stopped',
                    'config' => json_encode(['timeframe' => 'M5', 'allocation_percent' => 20]),
                    'total_profit' => 0,
                ],
                [
                    'user_id' => $userId,
                    'user_exchange_key_id' => null,
                    'name' => 'Silver Trend',
                    'symbol' => 'XAUT_IDR',
                    'strategy' => 'EMA 10 / 30 Trend following breakout',
                    'status' => 'stopped',
                    'config' => json_encode(['timeframe' => 'M15', 'allocation_percent' => 20]),
                    'total_profit' => 0,
                ],
                [
                    'user_id' => $userId,
                    'user_exchange_key_id' => null,
                    'name' => 'Swap Grid',
                    'symbol' => 'PAXG_IDR',
                    'strategy' => 'Grid Trading based on Average True Range',
                    'status' => 'stopped',
                    'config' => json_encode(['timeframe' => 'M30', 'allocation_percent' => 30]),
                    'total_profit' => 0,
                ]
            ];
            
            $this->botModel->insertBatch($defaultBots);
            
            // Re-fetch after insert
            return redirect()->to(current_url());
        }
        // Get bots for this user
        $botIds = array_column($bots, 'id');
        $recentOrders = [];
        
        $globalTotalTrades = 0;
        $globalWinTrades = 0;
        $globalAvgWinRate = 0;
        $globalTotalProfit = 0;

        if (!empty($botIds)) {
            // Setup builder for aggregate stats (count sell orders as trades)
            $db = \Config\Database::connect();
            $statsBuilder = $db->table('bot_orders')->whereIn('bot_id', $botIds)->where('side', 'sell');
            if ($tradeMode === 'live') {
                $statsBuilder->notLike('exchange_order_id', 'MOCK_', 'after');
            } else {
                $statsBuilder->like('exchange_order_id', 'MOCK_', 'after');
            }
            $allSells = $statsBuilder->get()->getResultArray();
            $globalTotalTrades = count($allSells);
            
            foreach($allSells as $s) {
                if (isset($s['pnl'])) {
                    $globalTotalProfit += $s['pnl'];
                    if ($s['pnl'] > 0) {
                        $globalWinTrades++;
                    }
                }
            }
            if ($globalTotalTrades > 0) {
                $globalAvgWinRate = round(($globalWinTrades / $globalTotalTrades) * 100, 1);
            }

            // Setup builder for recent orders
            $this->orderModel->whereIn('bot_id', $botIds);
            if ($tradeMode === 'live') {
                $this->orderModel->notLike('exchange_order_id', 'MOCK_', 'after');
            } else {
                $this->orderModel->like('exchange_order_id', 'MOCK_', 'after');
            }
            $recentOrders = $this->orderModel->orderBy('created_at', 'DESC')->limit(10)->findAll();
        }

        // Check bot license status
        $db = \Config\Database::connect();
        $transaksi = $db->table('transaksi')
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->like('product_name', 'AIWE')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();
            
        $botStatusData = [
            'expires_at' => null,
            'days_left' => null,
            'is_lifetime' => false
        ];

        if ($transaksi) {
            if (!empty($transaksi['expires_at'])) {
                $botStatusData['expires_at'] = $transaksi['expires_at'];
                $now = new \DateTime();
                $expires = new \DateTime($transaksi['expires_at']);
                if ($expires > $now) {
                    $diff = $now->diff($expires);
                    $botStatusData['days_left'] = $diff->days;
                } else {
                    $botStatusData['days_left'] = 0;
                }
            } else {
                $botStatusData['is_lifetime'] = true;
            }
        }

        $data = [
            'title' => 'Trading Bots RWA',
            'tradeMode' => $tradeMode,
            'botsData' => $botsData,
            'recentOrders' => $recentOrders,
            'exchangeKeys' => $this->exchangeModel->where('user_id', $userId)->where('is_active', 1)->findAll(),
            'botStatusData' => $botStatusData,
            'globalTotalTrades' => $globalTotalTrades,
            'globalAvgWinRate' => $globalAvgWinRate,
            'globalTotalProfit' => $globalTotalProfit
        ];
        return view('user/rwa/bots', $data);
    }

    public function orders()
    {
        $userId = session()->get('userId');
        // Get bots for this user first
        $bots = $this->botModel->where('user_id', $userId)->findColumn('id');
        
        $orders = [];
        if (!empty($bots)) {
            $orders = $this->orderModel->whereIn('bot_id', $bots)->orderBy('created_at', 'DESC')->findAll();
        }

        $data = [
            'title' => 'Bot Orders',
            'orders' => $orders
        ];
        return view('user/rwa/orders', $data);
    }
    public function marketSummaryProxy()
    {
        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get(rtrim($tradeEngineUrl, '/') . '/api/market/summary', [
                'timeout' => 5,
                'http_errors' => false,
                'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
            ]);
            return $this->response->setJSON(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function market()
    {
        $userId = session()->get('userId');
        $tradeMode = session()->get('rwa_trade_mode') ?? 'demo';
        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';

        // Fetch real prices from trade engine
        $marketPairs = [];
        $marketStats = ['volume_idr' => 0, 'spread' => 0, 'high' => 0, 'low' => 0];

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get($tradeEngineUrl . '/api/ws/prices', [
                'timeout' => 10,
                'http_errors' => false,
                'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody(), true);
                if (!empty($res['success']) && !empty($res['data'])) {
                    $nameMap = [
                        'PAXG/IDR' => 'Pax Gold',
                        'XAUT/IDR' => 'Tether Gold',
                        'SLVON/IDR' => 'Silver On-chain',
                    ];
                    foreach ($res['data'] as $item) {
                        $symbol = $item['symbol'] ?? '';
                        if (isset($nameMap[$symbol])) {
                            $marketPairs[] = [
                                'symbol' => $symbol,
                                'name' => $nameMap[$symbol],
                                'price' => (float)($item['price'] ?? 0),
                                'change_percent' => (float)($item['changePercent'] ?? 0),
                                'available' => ((float)($item['price'] ?? 0)) > 0,
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Trade engine down
        }

        // If no pairs fetched, create empty placeholders
        if (empty($marketPairs)) {
            $marketPairs = [
                ['symbol' => 'PAXG/IDR', 'name' => 'Pax Gold', 'price' => 0, 'change_percent' => 0, 'available' => false],
                ['symbol' => 'XAUT/IDR', 'name' => 'Tether Gold', 'price' => 0, 'change_percent' => 0, 'available' => false],
                ['symbol' => 'SLVON/IDR', 'name' => 'Silver On-chain', 'price' => 0, 'change_percent' => 0, 'available' => false],
            ];
        }

        $activePair = $marketPairs[0] ?? $marketPairs[0];

        $data = [
            'title' => 'Market - AIWE RWA',
            'tradeMode' => $tradeMode,
            'marketPairs' => $marketPairs,
            'marketStats' => $marketStats,
            'activePair' => $activePair,
        ];

        return view('user/rwa/market', $data);
    }

    public function placeOrder()
    {
        $userId = session()->get('userId');
        $tradeMode = session()->get('rwa_trade_mode') ?? 'demo';
        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';

        $symbol = $this->request->getPost('symbol');
        $side = $this->request->getPost('side');
        $type = $this->request->getPost('type');
        $price = $this->request->getPost('price');
        $amount = $this->request->getPost('amount');

        if (!$symbol || !$side || !$amount) {
            return redirect()->to('user/dashboard/rwa/market')->with('error', 'Data order tidak lengkap.');
        }

        $liveMode = ($tradeMode === 'live');

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post(rtrim($tradeEngineUrl, '/') . '/api/order/place', [
                'json' => [
                    'userId' => $userId,
                    'symbol' => $symbol,
                    'side' => $side,
                    'type' => $type ?? 'limit',
                    'amount' => (float)$amount,
                    'price' => $price ? (float)$price : null,
                    'liveMode' => $liveMode,
                ],
                'timeout' => 10,
                'http_errors' => false,
                'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
            ]);

            $res = json_decode($response->getBody(), true);
            if (!empty($res['success'])) {
                $modeLabel = $liveMode ? 'LIVE' : 'DEMO';
                return redirect()->to('user/dashboard/rwa/market')->with('success', "Order {$side} {$amount} {$symbol} berhasil di-submit ({$modeLabel}).");
            } else {
                return redirect()->to('user/dashboard/rwa/market')->with('error', 'Gagal: ' . ($res['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            return redirect()->to('user/dashboard/rwa/market')->with('error', 'Trade engine error: ' . $e->getMessage());
        }
    }

    public function chartCandles($symbol)
    {
        $tf = $this->request->getGet('tf') ?? 'H1';
        $limit = (int)($this->request->getGet('limit') ?? 200);

        // Normalize: PAXGIDR -> PAXG/IDR
        $normalized = strtoupper($symbol);
        $pairMap = [
            'PAXGIDR' => 'PAXG/IDR',
            'XAUTIDR' => 'XAUT/IDR',
            'SLVONIDR' => 'SLVON/IDR',
        ];
        $pair = $pairMap[$normalized] ?? $normalized;
        
        // Map UI timeframe to ccxt timeframe
        $tfMap = [
            'H1' => '1h',
            'H4' => '4h',
            'D1' => '1d'
        ];
        $mappedTf = $tfMap[$tf] ?? '1h';

        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';

        try {
            $client = \Config\Services::curlrequest();
            $url = rtrim($tradeEngineUrl, '/') . '/api/candles?symbol=' . urlencode($pair) . '&timeframe=' . $mappedTf . '&limit=' . $limit;
            $response = $client->get($url, [
                'timeout' => 10,
                'http_errors' => false,
                'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody(), true);
                if (!empty($res['success']) && !empty($res['data'])) {
                    return $this->response->setJSON($res['data']);
                }
            }
        } catch (\Exception $e) {
            // fallback failed
        }

        return $this->response->setJSON([]);
    }

    public function chartPrice($symbol)
    {
        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';

        // Normalize: PAXGIDR -> PAXG/IDR
        $normalized = strtoupper($symbol);
        $pairMap = [
            'PAXGIDR' => 'PAXG/IDR',
            'XAUTIDR' => 'XAUT/IDR',
            'SLVONIDR' => 'SLVON/IDR',
        ];
        $targetSymbol = $pairMap[$normalized] ?? $normalized;

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get(rtrim($tradeEngineUrl, '/') . '/api/ws/prices', [
                'timeout' => 5,
                'http_errors' => false,
                'headers' => ['x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!']
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody(), true);
                if (!empty($res['success']) && !empty($res['data'])) {
                    foreach ($res['data'] as $item) {
                        if (($item['symbol'] ?? '') === $targetSymbol) {
                            return $this->response->setJSON([
                                'price' => (float)($item['price'] ?? 0),
                                'changePercent' => (float)($item['changePercent'] ?? 0),
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // price fetch failed
        }

        return $this->response->setJSON(['price' => 0, 'changePercent' => 0]);
    }

    public function backtest()
    {
        $data = [
            'title'  => 'Strategy Backtesting',
            'params' => [],
        ];
        return view('user/rwa/backtest', $data);
    }

    public function runBacktest()
    {
        $params = [
            'bot'              => $this->request->getPost('bot'),
            'symbol'           => $this->request->getPost('symbol'),
            'timeframe'        => $this->request->getPost('timeframe'),
            'date_range'       => $this->request->getPost('date_range'),
            'starting_balance' => $this->request->getPost('starting_balance'),
            'risk_per_trade'   => $this->request->getPost('risk_per_trade'),
        ];

        // Normalise timeframe: form sends "5m", BacktestService expects "5m" → OK
        // but older bot options may use "M5" — map to ccxt format
        $tfRaw = strtolower($params['timeframe'] ?? '5m');
        $tfMap = ['m5' => '5m', 'm15' => '15m', 'm30' => '30m', 'h1' => '1h', 'h4' => '4h', 'd1' => '1d'];
        $timeframe = $tfMap[$tfRaw] ?? $tfRaw; // already "5m"/"15m" → pass through

        $payload = [
            'bot'              => $params['bot']              ?? 'alpha_gold',
            'symbol'           => $params['symbol']           ?? 'XAUT/IDR',
            'timeframe'        => $timeframe,
            'date_range'       => $params['date_range']        ?? 'last_month',
            'starting_balance' => (float)($params['starting_balance'] ?? 1000000),
            'risk_per_trade'   => $params['risk_per_trade']    ?? '20%',
        ];

        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
        $url = rtrim($tradeEngineUrl, '/') . '/api/backtest';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-trade-engine-secret: ' . (getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!')
            ],
            CURLOPT_TIMEOUT        => 90,           // backtest can take a moment
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        $result      = null;
        $errorMsg    = null;

        if ($curlErr) {
            $errorMsg = 'Trade Engine tidak dapat dijangkau. Server sedang dalam proses penyambungan, mohon coba lagi.';
        } elseif ($httpCode === 200 && $response) {
            $decoded = json_decode($response, true);
            if (!empty($decoded['success'])) {
                // BacktestService returns summary/trades/equityCurve at top level
                $result = [
                    'summary'     => $decoded['summary']     ?? [],
                    'trades'      => $decoded['trades']      ?? [],
                    'equityCurve' => $decoded['equityCurve'] ?? [],
                ];
            } else {
                $errorMsg = 'Backtest gagal: ' . ($decoded['message'] ?? 'Unknown error dari trade engine.');
            }
        } else {
            $errorMsg = "Trade Engine merespons dengan HTTP $httpCode. Cek apakah server berjalan di $tradeEngineUrl.";
        }

        if ($errorMsg) {
            session()->setFlashdata('error', $errorMsg);
        }

        $data = [
            'title'  => 'Strategy Backtesting',
            'params' => $params,
            'result' => $result,
        ];
        return view('user/rwa/backtest', $data);
    }



    private function encryptForLaravel($value)
    {
        if (empty($value)) return $value;

        // Try to read APP_KEY from current CI4 env
        $appKeyStr = env('APP_KEY');
        if (empty($appKeyStr) || !preg_match('/^base64:(.+)$/', $appKeyStr, $matches)) {
            throw new \Exception("APP_KEY is missing or invalid in .env");
        }
        $appKey = base64_decode($matches[1]);

        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($value, 'aes-256-cbc', $appKey, 0, $iv);
        $ivBase64 = base64_encode($iv);
        $mac = hash_hmac('sha256', $ivBase64 . $encrypted, $appKey);
        
        $json = json_encode([
            'iv' => $ivBase64,
            'value' => $encrypted,
            'mac' => $mac
        ]);
        
        return base64_encode($json);
    }

    public function saveExchangeApi()
    {
        $userId = session()->get('userId');
        $id     = (int) $this->request->getPost('id');

        try {
            $exchange = trim($this->request->getPost('exchange') ?? '');
            if (empty($exchange)) {
                return redirect()->to('user/dashboard/rwa/exchange-api')
                    ->with('error', 'Exchange harus dipilih.');
            }

            $apiKey    = $this->request->getPost('api_key')    ?? '';
            $apiSecret = $this->request->getPost('api_secret') ?? '';

            $data = [
                'user_id'      => $userId,
                'exchange'     => $exchange,
                'account_name' => $this->request->getPost('account_name'),
                'is_active'    => (int) $this->request->getPost('is_active'),
            ];

            // Passphrase: simpan apa adanya (opsional)
            $passphrase = $this->request->getPost('passphrase');
            if ($passphrase !== null) {
                $data['passphrase'] = $passphrase;
            }

            if ($id) {
                // UPDATE — pastikan milik user ini
                $existing = $this->exchangeModel
                    ->where('id', $id)
                    ->where('user_id', $userId)
                    ->first();

                if (!$existing) {
                    return redirect()->to('user/dashboard/rwa/exchange-api')
                        ->with('error', 'Kunci API tidak ditemukan atau akses ditolak.');
                }

                // Hanya update API key/secret jika diisi
                if (!empty($apiKey)) {
                    $data['api_key'] = $this->encryptForLaravel($apiKey);
                }
                if (!empty($apiSecret)) {
                    $data['api_secret'] = $this->encryptForLaravel($apiSecret);
                }

                $this->exchangeModel->update($id, $data);
                return redirect()->to('user/dashboard/rwa/exchange-api')
                    ->with('success', 'Kunci API berhasil diperbarui.');
            } else {
                // INSERT — api_key & api_secret wajib
                if (empty($apiKey) || empty($apiSecret)) {
                    return redirect()->to('user/dashboard/rwa/exchange-api')
                        ->with('error', 'API Key dan API Secret wajib diisi untuk menambah exchange baru.');
                }

                $data['api_key']    = $this->encryptForLaravel($apiKey);
                $data['api_secret'] = $this->encryptForLaravel($apiSecret);

                $this->exchangeModel->insert($data);
                return redirect()->to('user/dashboard/rwa/exchange-api')
                    ->with('success', 'Kunci API berhasil ditambahkan.');
            }
        } catch (\Exception $e) {
            log_message('error', '[saveExchangeApi] ' . $e->getMessage());
            return redirect()->to('user/dashboard/rwa/exchange-api')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteExchangeApi()
    {
        $userId = session()->get('userId');
        $id = $this->request->getPost('id');
        
        $existing = $this->exchangeModel->where('id', $id)->where('user_id', $userId)->first();
        if ($existing) {
            $this->exchangeModel->delete($id);
            return redirect()->to('user/dashboard/rwa/exchange-api')->with('success', 'Kunci API berhasil dihapus.');
        }
        return redirect()->to('user/dashboard/rwa/exchange-api')->with('error', 'Kunci API tidak ditemukan.');
    }

    public function testExchangeApi()
    {
        $exchange  = $this->request->getPost('exchange');
        $apiKey    = $this->request->getPost('api_key');
        $apiSecret = $this->request->getPost('api_secret');

        if (empty($exchange) || empty($apiKey) || empty($apiSecret)) {
            return $this->response
                ->setHeader('X-CSRF-TOKEN', csrf_hash())
                ->setJSON(['status' => 'error', 'message' => 'Lengkapi data Exchange, API Key, dan Secret terlebih dahulu.']);
        }

        $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';

        try {
            $client   = \Config\Services::curlrequest();
            $response = $client->post(rtrim($tradeEngineUrl, '/') . '/api/test-connection', [
                'json' => [
                    'exchange'  => $exchange,
                    'apiKey'    => $apiKey,
                    'apiSecret' => $apiSecret,
                ],
                'timeout'     => 15,
                'http_errors' => false,
                'headers'     => [
                    'x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!'
                ]
            ]);

            $res = json_decode($response->getBody(), true);

            if (!empty($res['success'])) {
                return $this->response
                    ->setHeader('X-CSRF-TOKEN', csrf_hash())
                    ->setJSON([
                        'status'  => 'success',
                        'message' => 'Koneksi ke ' . ucfirst($exchange) . ' berhasil! API Key valid.',
                    ]);
            } else {
                return $this->response
                    ->setHeader('X-CSRF-TOKEN', csrf_hash())
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'Koneksi gagal: ' . ($res['message'] ?? 'Unknown error'),
                    ]);
            }
        } catch (\Exception $e) {
            return $this->response
                ->setHeader('X-CSRF-TOKEN', csrf_hash())
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Trade Engine tidak dapat dijangkau. Mohon periksa kembali koneksi server.',
                ]);
        }
    }
    public function startBot($symbol)
    {
        $userId = session()->get('userId');
        
        $tradeMode = session()->get('rwa_trade_mode') ?? 'demo';
        $isLive = ($tradeMode === 'live');

        if ($isLive) {
            $db = \Config\Database::connect();

            // Check exchange API key exists
            $hasExchangeKey = $this->exchangeModel->where('user_id', $userId)->where('is_active', 1)->first();
            if (!$hasExchangeKey) {
                return redirect()->to('user/dashboard/rwa/bots')->with('error', 'Anda belum mengisi Exchange API Key. Silakan <a href="'.base_url('user/dashboard/rwa/exchange-api').'" style="text-decoration:underline; font-weight:bold; color:#00e5ff;">Atur Exchange API</a> terlebih dahulu untuk menjalankan bot di mode LIVE.');
            }

            $pointsRow = $db->query("SELECT COALESCE(SUM(point), 0) as balance FROM points WHERE user_id = ?", [$userId])->getRow();
            $pointBalance = (int)($pointsRow->balance ?? 0);
            
            if ($pointBalance < 1) {
                return redirect()->to('user/dashboard/rwa/bots')->with('error', 'Almai Poin Anda kurang. Silakan <a href="'.base_url('user/poin/buy').'" style="text-decoration:underline; font-weight:bold; color:#FFB800;">Top Up Almai Poin</a> terlebih dahulu untuk menjalankan bot di mode LIVE.');
            }
        }

        $bot = $this->botModel->where('user_id', $userId)->where('symbol', $symbol)->first();
        if ($bot) {
            $config = json_decode($bot->config, true) ?? [];
            $config['liveMode'] = $isLive;

            $this->botModel->update($bot->id, [
                'status' => 'active',
                'config' => json_encode($config)
            ]);
            try {
                $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
                $client = \Config\Services::curlrequest();
                $client->post(rtrim($tradeEngineUrl, '/') . '/api/bots/start', [
                    'json' => ['userId' => $userId, 'symbol' => $symbol],
                    'timeout' => 10,
                    'http_errors' => false,
                    'headers' => [
                        'x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!'
                    ]
                ]);
            } catch (\Exception $e) {
                log_message('error', '[Bot Start Error] ' . $e->getMessage());
            }
        }
        return redirect()->to('user/dashboard/rwa/bots')->with('success', 'Bot trading ' . esc($symbol) . ' berhasil diaktifkan.');
    }

    public function stopBot($symbol)
    {
        $userId = session()->get('userId');
        $bot = $this->botModel->where('user_id', $userId)->where('symbol', $symbol)->first();
        if ($bot) {
            $this->botModel->update($bot->id, ['status' => 'stopped']);
            try {
                $tradeEngineUrl = getenv('TRADE_ENGINE_URL') ?: 'http://127.0.0.1:1818';
                $client = \Config\Services::curlrequest();
                $client->post(rtrim($tradeEngineUrl, '/') . '/api/bots/stop', [
                    'json' => ['userId' => $userId, 'symbol' => $symbol],
                    'timeout' => 10,
                    'http_errors' => false,
                    'headers' => [
                        'x-trade-engine-secret' => getenv('TRADE_ENGINE_SECRET') ?: 'RAHASIA_SUPER_KUAT_123!'
                    ]
                ]);
            } catch (\Exception $e) {
                log_message('error', '[Bot Stop Error] ' . $e->getMessage());
            }
        }
        return redirect()->to('user/dashboard/rwa/bots')->with('success', 'Bot trading ' . esc($symbol) . ' berhasil dihentikan.');
    }

    public function settingsBot($symbol)
    {
        $userId = session()->get('userId');
        $allocation = $this->request->getPost('allocation_percent');
        $stopLoss = $this->request->getPost('stop_loss_percent');
        
        $bot = $this->botModel->where('user_id', $userId)->where('symbol', $symbol)->first();
        if ($bot) {
            $config = json_decode($bot->config, true) ?? [];
            $config['allocation_percent'] = (int) $allocation;
            $config['stopLossPercent'] = (float) $stopLoss;
            
            $this->botModel->update($bot->id, [
                'config' => json_encode($config)
            ]);
        }
        
        $slText = (float)$stopLoss > 0 ? " dan Stop Loss {$stopLoss}%" : ' tanpa Stop Loss';
        return redirect()->to('user/dashboard/rwa/bots')->with('success', 'Pengaturan bot ' . esc($symbol) . ' berhasil diperbarui dengan alokasi ' . esc($allocation) . '%' . $slText . '.');
    }

    public function generateInsight()
    {
        $symbol = $this->request->getJsonVar('symbol');
        $tf = $this->request->getJsonVar('tf');
        $candles = $this->request->getJsonVar('candles') ?? [];

        if (empty($symbol) || empty($candles)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Insufficient data']);
        }

        $groqApiKey = getenv('GROQ_API_KEY');
        if (empty($groqApiKey)) {
            return $this->response->setJSON(['success' => false, 'message' => 'GROQ API Key is not configured']);
        }

        // Prepare data summary for prompt
        $latest = end($candles);
        $oldest = reset($candles);
        $priceChange = $latest->close - $oldest->open;
        $high = max(array_column($candles, 'high'));
        $low = min(array_column($candles, 'low'));
        
        $percentChange = ($oldest->open > 0) ? ($priceChange / $oldest->open) * 100 : 0;
        if ($percentChange > 0.3) {
            $trend = 'Bullish';
        } elseif ($percentChange < -0.3) {
            $trend = 'Bearish';
        } else {
            $trend = 'Sideways';
        }

        $prompt = "Anda adalah seorang Analis Teknikal Kripto Profesional tingkat dunia. Berikan analisis pasar yang memukau, akurat, dan sangat profesional dalam BAHASA INDONESIA untuk pasangan {$symbol} pada timeframe {$tf}.\n\n";
        $prompt .= "Data Pergerakan Harga (berdasarkan " . count($candles) . " periode terakhir):\n";
        $prompt .= "- Harga Saat Ini: Rp " . number_format($latest->close, 0, ',', '.') . "\n";
        $prompt .= "- Harga Tertinggi: Rp " . number_format($high, 0, ',', '.') . "\n";
        $prompt .= "- Harga Terendah: Rp " . number_format($low, 0, ',', '.') . "\n";
        $prompt .= "- Indikasi Tren: " . $trend . " (Perubahan: " . number_format($percentChange, 2) . "%)\n\n";
        $prompt .= "ATURAN WAJIB:\n";
        $prompt .= "1. Tulis tepat dalam 2 paragraf singkat tanpa nomor urut, bullet, atau judul (langsung ke paragraf teks).\n";
        $prompt .= "2. Paragraf 1: Bahas Kondisi Pasar Saat Ini & Analisis Tren (Pastikan menyebut tren {$trend} dengan percaya diri).\n";
        $prompt .= "3. Paragraf 2: Bahas Level Support/Resistance Kunci & Prediksi Arah selanjutnya.\n";
        $prompt .= "4. Gunakan gaya bahasa Indonesia yang elegan dan profesional seperti laporan investasi bank besar.\n";
        $prompt .= "5. Wajib gunakan format **Teks** untuk menebalkan kata kunci (tren, harga, dan arah).";

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $groqApiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.6,
                    'max_tokens' => 300
                ],
                'timeout' => 15,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody(), true);
                $insight = $res['choices'][0]['message']['content'] ?? 'Analysis unavailable.';
                return $this->response->setJSON(['success' => true, 'insight' => $insight]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Groq API Error: ' . $response->getStatusCode()]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Throwable: ' . $e->getMessage() . ' Line: ' . $e->getLine()]);
        }
    }
}
