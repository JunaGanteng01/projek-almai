<?php

namespace App\Libraries;

class GoogleAuthService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId = getenv('GOOGLE_CLIENT_ID') ?: '';
        $this->clientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: '';
        $this->redirectUri = base_url('auth/google/callback');
    }

    /**
     * Get Google OAuth URL
     */
    public function getAuthUrl(): string
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Exchange code for access token
     */
    protected function getAccessToken(string $code): ?array
    {
        $url = 'https://oauth2.googleapis.com/token';
        
        $data = [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Google token error: ' . $error);
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Get user info from access token
     */
    protected function getUserInfo(string $accessToken): ?array
    {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken]
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Google userinfo error: ' . $error);
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Handle callback and get user info
     */
    public function getUserFromCode(string $code): ?array
    {
        // Get access token
        $tokenData = $this->getAccessToken($code);
        
        if (!$tokenData || isset($tokenData['error'])) {
            log_message('error', 'Google OAuth error: ' . ($tokenData['error'] ?? 'Unknown'));
            return null;
        }

        // Get user info
        $userInfo = $this->getUserInfo($tokenData['access_token']);
        
        if (!$userInfo || isset($userInfo['error'])) {
            log_message('error', 'Google userinfo error: ' . ($userInfo['error']['message'] ?? 'Unknown'));
            return null;
        }

        return [
            'google_id' => $userInfo['id'],
            'email' => $userInfo['email'],
            'name' => $userInfo['name'],
            'picture' => $userInfo['picture'] ?? null,
            'verified_email' => $userInfo['verified_email'] ?? false
        ];
    }

    /**
     * Check if Google Auth is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }
}
