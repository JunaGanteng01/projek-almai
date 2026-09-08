<?php

namespace App\Services;

class MT4Service
{
    protected $baseUrl;
    protected $client;

    public function __construct()
    {
        // URL Flask Server (Python 2.7) yang berjalan di lokal
        $this->baseUrl = 'http://127.0.0.1:5000/api/';
        $this->client = \Config\Services::curlrequest();
    }

    /**
     * Mengambil daftar total user (Trader) dari MT4 Manager.
     */
    public function getUsers()
    {
        try {
            $response = $this->client->get($this->baseUrl . 'users', [
                'timeout' => 10,
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Mengambil daftar trades terbuka/histori (tergantung limitasi wrapper) dari MT4 Manager.
     */
    public function getTrades()
    {
        try {
            $response = $this->client->get($this->baseUrl . 'trades', [
                'timeout' => 10,
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
