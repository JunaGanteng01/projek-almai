<?php

namespace App\Libraries;

class TurnstileService
{
    private $secretKey;
    private $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct()
    {
        $this->secretKey = env('TURNSTILE_SECRET_KEY', '');
    }

    /**
     * Verify Turnstile token
     * 
     * @param string $token The Turnstile response token
     * @param string $remoteIp Optional: User's IP address
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function verify(string $token, string $remoteIp = null): array
    {
        if (empty($this->secretKey)) {
            return [
                'success' => false,
                'error' => 'Turnstile secret key not configured'
            ];
        }

        if (empty($token)) {
            return [
                'success' => false,
                'error' => 'Mohon verifikasi captcha dulu'
            ];
        }

        $data = [
            'secret' => $this->secretKey,
            'response' => $token
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        try {
            $ch = curl_init($this->verifyUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                log_message('error', 'Turnstile verification cURL error: ' . $curlError);
                return [
                    'success' => false,
                    'error' => 'Connection error: ' . $curlError
                ];
            }

            if ($httpCode !== 200) {
                log_message('error', 'Turnstile verification HTTP error: ' . $httpCode);
                return [
                    'success' => false,
                    'error' => 'HTTP error: ' . $httpCode
                ];
            }

            $result = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'Turnstile JSON decode error: ' . json_last_error_msg());
                return [
                    'success' => false,
                    'error' => 'Invalid response format'
                ];
            }

            if (isset($result['success']) && $result['success'] === true) {
                return [
                    'success' => true,
                    'error' => null,
                    'challenge_ts' => $result['challenge_ts'] ?? null,
                    'hostname' => $result['hostname'] ?? null
                ];
            }

            $errorCodes = $result['error-codes'] ?? ['unknown-error'];
            $errorMessage = $this->getErrorMessage($errorCodes);

            log_message('error', 'Turnstile verification failed: ' . implode(', ', $errorCodes));

            return [
                'success' => false,
                'error' => $errorMessage,
                'error_codes' => $errorCodes
            ];
        } catch (\Exception $e) {
            log_message('error', 'Turnstile verification exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Verification failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get user-friendly error message from error codes
     * 
     * @param array $errorCodes
     * @return string
     */
    private function getErrorMessage(array $errorCodes): string
    {
        $messages = [
            'missing-input-secret' => 'Konfigurasi tidak valid',
            'invalid-input-secret' => 'Konfigurasi tidak valid',
            'missing-input-response' => 'Harap selesaikan verifikasi CAPTCHA',
            'invalid-input-response' => 'Verifikasi CAPTCHA tidak valid atau sudah kadaluarsa',
            'bad-request' => 'Permintaan tidak valid',
            'timeout-or-duplicate' => 'Verifikasi CAPTCHA sudah kadaluarsa, silakan refresh halaman',
            'internal-error' => 'Terjadi kesalahan sistem, silakan coba lagi'
        ];

        foreach ($errorCodes as $code) {
            if (isset($messages[$code])) {
                return $messages[$code];
            }
        }

        return 'Verifikasi CAPTCHA gagal, silakan coba lagi';
    }

    /**
     * Check if Turnstile is enabled
     * 
     * @return bool
     */
    public function isEnabled(): bool
    {
        return !empty($this->secretKey);
    }
}
