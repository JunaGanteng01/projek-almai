<?php

namespace App\Libraries;

/**
 * Telegram Service for sending notifications
 */
class TelegramService
{
    protected $token;
    protected $apiUrl = "https://api.telegram.org/bot";

    public function __construct()
    {
        $this->token = getenv('TELEGRAM_TOKEN');
    }

    /**
     * Send message to a specific chat ID or default from .env
     * 
     * @param string $message
     * @param string|null $chatId
     * @param string $parseMode (HTML or MarkdownV2)
     * @return array|bool
     */
    public function sendMessage($message, $chatId = null, $parseMode = 'HTML')
    {
        if (!$this->token) {
            log_message('error', 'Telegram Token not set in .env');
            return false;
        }

        $chatId = $chatId ?: getenv('TELEGRAM_CHAT_ID');

        if (!$chatId) {
            log_message('error', 'Telegram Chat ID not set in .env or provided');
            return false;
        }

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => true
        ];

        return $this->sendRequest('sendMessage', $data);
    }

    /**
     * Send request to Telegram API
     */
    protected function sendRequest($method, $data)
    {
        $url = $this->apiUrl . $this->token . "/" . $method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Telegram API Error: ' . $error);
            return false;
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['ok']) || !$result['ok']) {
            log_message('error', 'Telegram API Response Error: ' . $response);
            return $result;
        }

        return $result;
    }

    /**
     * Centralized Admin Notification Handler
     */
    public function notifyAdmin($type, $data)
    {
        $msg = "";
        
        switch ($type) {
            case 'transaction':
                $msg = "<b>🔔 Transaksi Baru!</b>\n\n";
                $msg .= "Invoice: #{$data['invoice_number']}\n";
                $msg .= "Produk: " . ($data['product_name'] ?? 'Layanan') . "\n";
                $msg .= "Total: Rp " . number_format($data['total'], 0, ',', '.') . "\n";
                $msg .= "Metode: " . strtoupper($data['payment_method'] ?? '-') . "\n";
                $msg .= "Status: " . strtoupper($data['status'] ?? 'PENDING') . "\n\n";
                $msg .= "Cek Detail: " . base_url('admin/transaksi');
                break;


            case 'advocacy':
                $msg = "<b>⚖️ Advokasi Baru!</b>\n\n";
                $msg .= "Nama: {$data['name']}\n";
                $msg .= "Email: {$data['email']}\n";
                $msg .= "WhatsApp: {$data['whatsapp']}\n\n";
                $msg .= "Cek Detail: " . base_url('admin/advokasi');
                break;

            case 'kyc':
                $msg = "<b>🆔 KYC Baru!</b>\n\n";
                $msg .= "Nama: {$data['name']}\n";
                $msg .= "Status: " . strtoupper($data['kyc_status'] ?? 'PENDING') . "\n\n";
                $msg .= "Cek Detail: " . base_url('admin/kyc');
                break;

            case 'register':
                $msg = "<b>👤 User Baru Terdaftar!</b>\n\n";
                $msg .= "Nama: {$data['name']}\n";
                $msg .= "Email: {$data['email']}\n";
                $msg .= "WhatsApp: " . ($data['phone'] ?? '-') . "\n";
                
                // Referral & Affiliate Info
                $affCode = $data['affiliator_code'] ?? null;
                if (!empty($affCode)) {
                    $userModel = new \App\Models\UserModel();
                    $affiliator = $userModel->findByReferralCode($affCode);
                    
                    // If not found by code, try by name as fallback (platform standard)
                    if (!$affiliator) {
                        $affiliator = $userModel->where('name', $affCode)->first();
                    }
                    
                    $affName = $affiliator ? $affiliator['name'] : 'Unknown';
                    $msg .= "Referral: <code>{$affCode}</code> ({$affName})\n";
                    $msg .= "Sumber: Affiliate (Non-Organic)\n\n";
                } else {
                    $msg .= "Sumber: Organic\n\n";
                }

                $msg .= "Cek Detail: " . base_url('admin/users');
                break;
        }

        if ($msg) {
            return $this->sendMessage($msg);
        }

        return false;
    }
}
