<?php

namespace App\Libraries;

class XenditService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = env('XENDIT_SECRET_KEY', '');
    }

    /**
     * Create Invoice
     */
    public function createInvoice(array $data): array
    {
        // Validate required fields
        if (empty($data['external_id']) || empty($data['amount']) || empty($data['email']) || empty($data['customer_name'])) {
            return [
                'success' => false,
                'error' => 'Missing required fields: external_id, amount, email, customer_name',
            ];
        }

        // Ensure amount is integer
        $amount = (int) $data['amount'];
        if ($amount <= 0) {
            return [
                'success' => false,
                'error' => 'Amount must be greater than 0',
            ];
        }

        $customer = [
            'given_names' => (string) $data['customer_name'],
            'email' => (string) $data['email'],
        ];

        if (!empty($data['phone'])) {
            $customer['mobile_number'] = preg_replace('/[^0-9+]/', '', $data['phone']);
        }

        $payload = [
            'external_id' => (string) $data['external_id'],
            'amount' => $amount,
            'payer_email' => (string) $data['email'],
            'description' => (string) $data['description'],
            'invoice_duration' => 86400, // 24 hours
            'customer' => $customer,
            'success_redirect_url' => $data['success_url'] ?? base_url('user/invoice/' . $data['external_id']),
            'failure_redirect_url' => $data['failure_url'] ?? base_url('user/invoice/' . $data['external_id']),
            'currency' => 'IDR',
            'items' => [
                [
                    'name' => (string) $data['item_name'],
                    'quantity' => 1,
                    'price' => $amount,
                ]
            ],
        ];

        if (isset($data['payment_methods']) && !empty($data['payment_methods'])) {
            $payload['payment_methods'] = $data['payment_methods'];
        }

        return $this->request('POST', '/v2/invoices', $payload);
    }

    /**
     * Get Invoice by ID
     */
    public function getInvoice(string $invoiceId): array
    {
        return $this->request('GET', '/v2/invoices/' . $invoiceId);
    }

    /**
     * Get Invoice by External ID
     */
    public function getInvoiceByExternalId(string $externalId): array
    {
        return $this->request('GET', '/v2/invoices?external_id=' . $externalId);
    }

    /**
     * Get Balance
     */
    public function getBalance(string $accountType = 'CASH'): array
    {
        return $this->request('GET', '/balance?account_type=' . $accountType);
    }

    /**
     * Get Transactions
     */
    public function getTransactions(array $filters = []): array
    {
        $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
        return $this->request('GET', '/transactions' . $queryString);
    }

    /**
     * Get Transaction Detail
     */
    public function getTransaction(string $transactionId): array
    {
        return $this->request('GET', '/transactions/' . $transactionId);
    }

    /**
     * Create Report
     */
    public function createReport(string $type, string $filter_date_from, string $filter_date_to, string $format = 'CSV'): array
    {
        $payload = [
            'type' => $type,
            'filter_date_from' => $filter_date_from,
            'filter_date_to' => $filter_date_to,
            'format' => $format
        ];
        return $this->request('POST', '/reports', $payload);
    }

    /**
     * Get Report by ID
     */
    public function getReport(string $reportId): array
    {
        return $this->request('GET', '/reports/' . $reportId);
    }

    /**
     * Get Disbursements (Withdrawals)
     */
    public function getDisbursements(array $filters = []): array
    {
        $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
        return $this->request('GET', '/disbursements' . $queryString);
    }

    /**
     * Make HTTP request to Xendit API
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->secretKey . ':'),
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // 15 seconds timeout

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        // Log request and response for debugging
        log_message('debug', 'Xendit Request: ' . $method . ' ' . $endpoint);
        log_message('debug', 'Xendit Payload: ' . json_encode($data));
        log_message('debug', 'Xendit Response Code: ' . $httpCode);
        log_message('debug', 'Xendit Response: ' . substr($response, 0, 500));

        if ($error) {
            log_message('error', 'Xendit CURL Error: ' . $error);
            return [
                'success' => false,
                'error' => $error,
                'http_code' => $httpCode,
            ];
        }

        $result = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && $result) {
            return [
                'success' => true,
                'data' => $result,
                'http_code' => $httpCode,
            ];
        }

        return [
            'success' => false,
            'error' => $result['message'] ?? ($error ?: 'API Error: ' . $httpCode),
            'data' => $result,
            'http_code' => $httpCode,
        ];
    }

    /**
     * Verify callback token
     */
    public function verifyCallbackToken(string $token): bool
    {
        return $token === env('XENDIT_CALLBACK_TOKEN', '');
    }
}
