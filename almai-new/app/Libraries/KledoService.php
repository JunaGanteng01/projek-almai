<?php

namespace App\Libraries;

class KledoService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->token = env('KLEDO_API_TOKEN', '');
        // Default to the user's specific endpoint
        $this->baseUrl = env('KLEDO_API_URL', 'https://loafingcode.api.kledo.com/api/v1');
    }

    /**
     * Post transaction to Kledo
     * This is a wrapper function that orchestrates the process
     */
    public function postTransaction(array $transaksi, array $user)
    {
        if (empty($this->token)) {
            log_message('error', 'Kledo Service: API Token is missing in .env');
            return false;
        }

        try {
            // 1. Find or Create Contact in Kledo
            $contactId = $this->ensureContact($user);
            if (!$contactId) {
                log_message('error', 'Kledo Service: Failed to get contact ID');
                return false;
            }

            // 2. Create Invoice
            return $this->createInvoice($transaksi, $contactId);

        } catch (\Exception $e) {
            log_message('error', 'Kledo Service Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create Invoice in Kledo
     * Note: Adjust endpoint and payload according to specific Kledo API docs
     */
    public function createInvoice(array $transaksi, int $contactId)
    {
        $endpoint = '/finance/invoices';
        
        // Prepare tags or memos based on product type
        $memo = 'Invoice #' . $transaksi['invoice_number'] . ' - ' . $transaksi['product_name'];

        $payload = [
            'contact_id' => $contactId,
            'trans_date' => date('Y-m-d'), // Transaction date
            'due_date' => date('Y-m-d'),   // Due date (same for immediate payment)
            'memo' => $memo,
            'items' => [
                [
                    'name' => $transaksi['product_name'],
                    'desc' => $transaksi['product_type'],
                    'qty' => 1,
                    'price' => (float) $transaksi['amount'],
                    // 'account_id' => 100, // Optional: Revenue account ID in Kledo
                ]
            ],
            // Add discount as a negative line item or specific field if Kledo supports it
            // 'discount_amount' => $transaksi['discount'] ?? 0, 
        ];

        // If there's a discount, we might handle it as a separate item or field
        if (isset($transaksi['discount']) && $transaksi['discount'] > 0) {
             // Example strategy: Line item discount is usually per item, 
             // Global discount might be a field. 
             // For now simplified:
             $payload['items'][0]['discount_amount'] = (float) $transaksi['discount'];
        }
        
        // Payment info (Directly marked as paid?)
        // Some APIs require creating invoice first, then adding payment.
        // Assuming we can pass status or similar.
        //$payload['status'] = 'paid'; 

        $response = $this->request('POST', $endpoint, $payload);

        if ($response['success']) {
            log_message('info', 'Kledo Service: Invoice created successfully. ID: ' . ($response['data']['id'] ?? 'unknown'));
            
            // Should we add payment?
            if (isset($response['data']['id'])) {
                $this->addPayment($response['data']['id'], $transaksi['total'], date('Y-m-d'));
            }
            
            return true;
        } else {
            log_message('error', 'Kledo Service: Failed to create invoice. Result: ' . json_encode($response));
            return false;
        }
    }
    
    public function addPayment($invoiceId, $amount, $date) {
        $endpoint = '/finance/invoices/' . $invoiceId . '/payments';
        $payload = [
            'amount' => $amount,
            'date' => $date,
            'account_id' => env('KLEDO_BANK_ACCOUNT_ID', 1), // The account where money is received (Bank/Cash)
        ];
        
        return $this->request('POST', $endpoint, $payload);
    }

    /**
     * Ensure Contact exists in Kledo
     * Returns Contact ID
     */
    /**
     * Ensure Contact exists in Kledo
     * Returns Contact ID
     */
    public function ensureContact(array $user)
    {
        // Search for contact by email first
        $search = $this->request('GET', '/finance/contacts?search=' . urlencode($user['email']));
        
        if ($search['success'] && !empty($search['data']['data'])) {
            // Assuming search returns a list and we pick the first match
            return $search['data']['data'][0]['id'];
        }

        // Create new contact
        $payload = [
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'] ?? '',
            'type' => 'customer', // generic customer type
            'group_id' => null 
        ];

        $create = $this->request('POST', '/finance/contacts', $payload);

        if ($create['success'] && isset($create['data']['id'])) {
            return $create['data']['id'];
        }
        
        log_message('error', 'Kledo Service: Failed to create contact. ' . json_encode($create));
        return null;
    }

    protected function request(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error' => $error,
                'http_code' => $httpCode,
            ];
        }

        $result = json_decode($response, true);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'data' => $result,
                'http_code' => $httpCode,
            ];
        }

        return [
            'success' => false,
            'error' => $result['message'] ?? 'Unknown error',
            'data' => $result,
            'http_code' => $httpCode,
        ];
    }
    /**
     * Test Connection
     * Try to fetch 1 contact to verify token
     */
    public function testConnection()
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'Token belum diset di .env (KLEDO_API_TOKEN)'
            ];
        }

        // Try to fetch contacts (limit 1) to verify auth
        $contactUrl = '/finance/contacts?per_page=1'; // Correct endpoint is /finance/contacts

        $response = $this->request('GET', $contactUrl);
        
        if ($response['success']) {
            return [
                'success' => true,
                'message' => 'Koneksi Berhasil! API merespon dengan HTTP ' . $response['http_code'],
                'data' => $response['data']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Koneksi Gagal: ' . ($response['error'] ?? 'Unknown error'),
                'debug' => array_merge($response, ['base_url_used' => $this->baseUrl, 'full_url' => $this->baseUrl . $contactUrl])
            ];
        }
    }
}
