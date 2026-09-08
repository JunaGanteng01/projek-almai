<?php

namespace App\Libraries;

/**
 * SDK for Balesotomatis Send Message Template & Create Template
 */
final class Balesotomatis
{
    private static string $baseUrl = 'https://api.balesotomatis.id/public/v1';

    private static ?string $secretKey   = null;
    private static ?string $licensesKey = null;

    public static function configure(string $secretKey, string $licensesKey, ?string $baseUrl = null): void
    {
        self::$secretKey   = $secretKey;
        self::$licensesKey = $licensesKey;

        if (!empty($baseUrl)) {
            self::$baseUrl = rtrim($baseUrl, '/');
        }
    }

    public static function sendTemplateMessage(
        string $recipient,
        string $templateId,
        array $variables = [],
        string $platform = 'whatsapp_bisnis_api',
        string $methodSend = 'async',
        array $extraPayload = []
    ): array {
        if (empty(self::$secretKey) || empty(self::$licensesKey)) {
            return [
                'success'       => false,
                'error'         => 'SDK not configured.',
            ];
        }

        $normalizedVars = array_map('strval', $variables);

        $payload = [
            'secret_key'   => self::$secretKey,
            'licenses_key' => self::$licensesKey,
            'recipients'   => $recipient,
            'platform'     => $platform,
            'method_send'  => $methodSend,
            'template'     => $templateId,
            'variables'    => $normalizedVars,
        ];

        if (!empty($extraPayload)) {
            $payload = array_merge($payload, $extraPayload);
        }

        return self::post('/send_message_template', $payload);
    }

    public static function sendPersonalMessage(
        string $recipient,
        string $message,
        string $platform = 'whatsapp',
        string $methodSend = 'async'
    ): array {
        if (empty(self::$secretKey) || empty(self::$licensesKey)) {
            return [
                'success'       => false,
                'error'         => 'SDK not configured.',
            ];
        }

        $payload = [
            'secret_key'   => self::$secretKey,
            'licenses_key' => self::$licensesKey,
            'reciptient'   => $recipient,
            'platform'     => $platform,
            'message'      => $message,
            'method_send'  => $methodSend,
        ];

        return self::post('/send_meta_personal_message', $payload);
    }

    public static function createTemplate(array $payload): array
    {
        if (empty(self::$secretKey) || empty(self::$licensesKey)) {
            return [
                'success'       => false,
                'error'         => 'SDK not configured.',
            ];
        }

        $payload['secret_key'] = self::$secretKey;
        $payload['licensesKey'] = self::$licensesKey; // API requires licensesKey not licenses_key here based on docs

        return self::post('/create-template', $payload);
    }

    public static function post(string $path, array $payload, int $timeout = 30): array
    {
        $url = self::$baseUrl . $path;

        $ch   = curl_init();
        $json = json_encode($payload);

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json),
            ],
        ]);

        $responseBody = curl_exec($ch);
        $curlError    = curl_error($ch);
        $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($responseBody === false) {
            return ['success' => false, 'error' => 'Curl error: ' . $curlError];
        }

        $decoded = json_decode($responseBody, true);
        $success = ($httpCode >= 200 && $httpCode < 300);
        
        if ($success && is_array($decoded) && isset($decoded['code'])) {
            $success = ((string) $decoded['code'] === '200');
        }

        $error = null;
        if (!$success) {
            $rawMsg = $decoded['message'] ?? ('Request not successful. HTTP ' . $httpCode);
            $error = is_array($rawMsg) ? json_encode($rawMsg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string) $rawMsg;
        }

        return [
            'success'       => $success,
            'error'         => $error,
            'response_json' => $decoded,
        ];
    }
}
