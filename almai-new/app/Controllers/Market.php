<?php

namespace App\Controllers;

class Market extends BaseController
{
    private $apiBaseUrl = 'https://api-mt5.moneymallfutures.com';

    public function index()
    {
        $targetSymbols = ['XAUUSD', 'JP255', 'USOIL', 'KNGUSD', 'EURUSD', 'AUDUSD'];
        
        $token = $this->getMt5Token();
        $prices = [];
        $symbolsList = [];
        
        if ($token) {
            $symbolsList = $this->getSymbolsList($token);
            
            foreach ($targetSymbols as $baseSymbol) {
                // Find matching symbol in the list
                $actualSymbol = $this->findMatchingSymbol($baseSymbol, $symbolsList);
                
                if ($actualSymbol) {
                    $quote = $this->getQuotePrice($token, $actualSymbol);
                    if ($quote) {
                        $prices[] = [
                            'base_symbol' => $baseSymbol,
                            'actual_symbol' => $actualSymbol,
                            'bid' => $quote['Bid'] ?? '-',
                            'ask' => $quote['Ask'] ?? '-',
                            'last' => $quote['Last'] ?? '-',
                        ];
                    }
                } else {
                    $prices[] = [
                        'base_symbol' => $baseSymbol,
                        'actual_symbol' => 'N/A',
                        'bid' => '-',
                        'ask' => '-',
                        'last' => '-',
                    ];
                }
            }
        }

        $data = [
            'pageTitle' => 'Market Prices',
            'pageSubtitle' => 'Live MT5 Prices',
            'prices' => $prices,
            'apiError' => $token ? null : 'Failed to authenticate with MT5 API',
        ];

        return view('pages/market', $data);
    }
    
    private function findMatchingSymbol($baseSymbol, $symbolsList)
    {
        // For GOLD/XAU mapping if needed
        if ($baseSymbol === 'XAUUSD') {
            foreach ($symbolsList as $sym) {
                if (stripos($sym, 'GOLD') !== false || stripos($sym, 'XAUUSD') !== false) {
                    return $sym;
                }
            }
        }
        
        foreach ($symbolsList as $sym) {
            if (stripos($sym, $baseSymbol) !== false) {
                return $sym;
            }
        }
        
        return null;
    }

    private function getMt5Token()
    {
        $cache = \Config\Services::cache();
        $token = $cache->get('mt5_api_token');
        
        if ($token) {
            return $token;
        }

        $email = getenv('MT5_API_EMAIL') ?: 'propfirm@almai.id';
        $password = getenv('MT5_API_PASSWORD') ?: 'xxxxxxxxx';

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->request('POST', $this->apiBaseUrl . '/auth/login', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                if (isset($body['token']['access_token'])) {
                    $token = $body['token']['access_token'];
                    // Cache the token for 25 minutes (to be safe within 30 min limit)
                    $cache->save('mt5_api_token', $token, 25 * 60);
                    return $token;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'MT5 Login Error: ' . $e->getMessage());
        }
        
        return null;
    }

    private function getSymbolsList($token)
    {
        $cache = \Config\Services::cache();
        $symbols = $cache->get('mt5_symbols_list');
        
        if ($symbols) {
            return $symbols;
        }
        
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->request('GET', $this->apiBaseUrl . '/symbol/symbol_list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ],
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                if (isset($body['status']) && $body['status'] === 'success' && isset($body['response'])) {
                    $symbols = $body['response'];
                    $cache->save('mt5_symbols_list', $symbols, 3600); // cache 1 hour
                    return $symbols;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'MT5 Symbols Error: ' . $e->getMessage());
        }
        
        return [];
    }

    private function getQuotePrice($token, $symbol)
    {
        $client = \Config\Services::curlrequest();
        try {
            $transId = time() . rand(100, 999);
            $response = $client->request('GET', $this->apiBaseUrl . '/symbol/quote_prices', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ],
                'query' => [
                    'symbol' => $symbol,
                    'trans_id' => $transId
                ],
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                if (isset($body['status']) && $body['status'] === 'success' && isset($body['response'][0])) {
                    return $body['response'][0];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'MT5 Quote Error for ' . $symbol . ': ' . $e->getMessage());
        }
        
        return null;
    }
}
