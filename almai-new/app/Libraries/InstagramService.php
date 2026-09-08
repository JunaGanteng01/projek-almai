<?php

namespace App\Libraries;

class InstagramService
{
    private const CACHE_TTL = 604800;
    private const STALE_CACHE_TTL = 2592000;

    private string $apiKey;
    private string $apiHost;
    private string $graphApiVersion;
    private string $graphUserId;
    private string $graphAccessToken;
    private bool $publicProfileEnabled;

    public function __construct()
    {
        // Keep the current credential as a compatibility fallback, while allowing
        // production to move it to .env without another code deployment.
        $this->apiKey = trim((string) env('RAPIDAPI_KEY', '1fb7098361mshe2ade6bf5ad466cp1d0fd5jsn60b4ebaa414e'));
        $this->apiHost = trim((string) env('INSTAGRAM_RAPIDAPI_HOST', 'instagram120.p.rapidapi.com'));
        $this->graphApiVersion = trim((string) env('INSTAGRAM_GRAPH_API_VERSION', 'v26.0'));
        $this->graphUserId = trim((string) env('INSTAGRAM_GRAPH_USER_ID', ''));
        $this->graphAccessToken = trim((string) env('INSTAGRAM_GRAPH_ACCESS_TOKEN', ''));
        $this->publicProfileEnabled = filter_var(
            env('INSTAGRAM_PUBLIC_PROFILE_ENABLED', true),
            FILTER_VALIDATE_BOOL
        );
    }

    /**
     * Get a normalized Instagram profile. When the provider is temporarily
     * unavailable, use the last successful snapshot so follower statistics do
     * not disappear from the profile page.
     */
    public function getProfile($username)
    {
        $username = $this->normalizeUsername((string) $username);
        if ($username === '') {
            return null;
        }

        $cacheKey = 'instagram_profile_' . md5($username);
        $staleKey = 'instagram_profile_stale_' . md5($username);
        $cachedData = cache()->get($cacheKey);
        if (is_array($cachedData)) {
            return $cachedData;
        }

        $providerErrors = [];
        $providers = [];

        if ($this->graphUserId !== '' && $this->graphAccessToken !== '') {
            $providers['meta_graph'] = fn () => $this->requestGraphProfile($username);
        }
        if ($this->publicProfileEnabled) {
            $providers['instagram_public'] = fn () => $this->requestPublicProfile($username);
        }
        $providers['rapidapi'] = fn () => $this->requestProfile($username);

        foreach ($providers as $providerName => $request) {
            $maxAttempts = $providerName === 'rapidapi' ? 2 : 1;
            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                $response = $request();

                if ($response['success']) {
                    $profile = self::normalizeProfileData($response['data'], $username);
                    if ($profile !== null && ($profile['edge_followed_by']['count'] ?? null) !== null) {
                        $profile['data_source'] = $providerName;
                        cache()->save($cacheKey, $profile, self::CACHE_TTL);
                        cache()->save($staleKey, $profile, self::STALE_CACHE_TTL);

                        return $profile;
                    }

                    $response['error'] = 'Response did not contain follower statistics.';
                }

                $providerErrors[$providerName] = $response['error'];
                if (!$response['retryable'] || $attempt === $maxAttempts) {
                    break;
                }
                usleep(250000);
            }
        }

        log_message(
            'error',
            'Instagram profile unavailable for ' . $username . ': ' . json_encode($providerErrors)
        );

        $staleData = cache()->get($staleKey);
        if (is_array($staleData)) {
            $staleData['is_stale'] = true;

            return $staleData;
        }

        $fallbackData = $this->getFallbackProfile($username);
        if ($fallbackData !== null) {
            return $fallbackData;
        }

        // Keep the card visible and honest. Views render a dash for unknown
        // counts instead of showing a misleading zero.
        return [
            'username' => $username,
            'full_name' => 'Instagram',
            'profile_pic_url' => '',
            'profile_pic_url_hd' => '',
            'is_verified' => false,
            'edge_followed_by' => ['count' => null],
            'edge_follow' => ['count' => null],
            'edge_owner_to_timeline_media' => ['count' => null],
            'api_unavailable' => true,
        ];
    }

    /**
     * Return an admin-maintained snapshot when the external provider cannot
     * read a known public account. API and stale API cache always take priority.
     */
    public function getFallbackProfile(string $username): ?array
    {
        $username = strtolower($this->normalizeUsername($username));
        $profiles = config('InstagramProfiles')->fallbackProfiles ?? [];
        $fallback = $profiles[$username] ?? null;

        if (!is_array($fallback)) {
            return null;
        }

        return [
            'username' => $username,
            'full_name' => (string) ($fallback['full_name'] ?? 'Instagram'),
            'profile_pic_url' => (string) ($fallback['profile_pic_url'] ?? ''),
            'profile_pic_url_hd' => (string) ($fallback['profile_pic_url'] ?? ''),
            'is_verified' => (bool) ($fallback['is_verified'] ?? false),
            'edge_followed_by' => ['count' => isset($fallback['followers']) ? (int) $fallback['followers'] : null],
            'edge_follow' => ['count' => isset($fallback['following']) ? (int) $fallback['following'] : null],
            'edge_owner_to_timeline_media' => ['count' => null],
            'is_manual_fallback' => true,
            'api_unavailable' => true,
        ];
    }

    /**
     * Convert provider-specific payloads into the shape used by every view.
     */
    public static function normalizeProfileData(array $payload, string $username = ''): ?array
    {
        if (isset($payload['success']) && $payload['success'] === false) {
            return null;
        }

        $profile = $payload['result']
            ?? $payload['business_discovery']
            ?? $payload['data']['user']
            ?? $payload['data']['profile']
            ?? $payload['data']
            ?? $payload['user']
            ?? $payload;

        if (!is_array($profile)) {
            return null;
        }

        $resolvedUsername = (string) ($profile['username'] ?? $profile['user_name'] ?? $username);
        $followers = self::firstNumericValue($profile, [
            ['edge_followed_by', 'count'],
            ['followers', 'count'],
            ['follower_count'],
            ['followers_count'],
            ['followerCount'],
            ['followers'],
        ]);
        $following = self::firstNumericValue($profile, [
            ['edge_follow', 'count'],
            ['following', 'count'],
            ['following_count'],
            ['follows_count'],
            ['followingCount'],
            ['following'],
        ]);
        $posts = self::firstNumericValue($profile, [
            ['edge_owner_to_timeline_media', 'count'],
            ['media', 'count'],
            ['media_count'],
            ['posts_count'],
            ['post_count'],
        ]);

        if ($resolvedUsername === '' && $followers === null && $following === null) {
            return null;
        }

        $profile['username'] = $resolvedUsername;
        $profile['full_name'] = (string) ($profile['full_name'] ?? $profile['name'] ?? '');
        $profile['profile_pic_url'] = (string) ($profile['profile_pic_url'] ?? $profile['profile_picture_url'] ?? $profile['avatar_url'] ?? '');
        $profile['profile_pic_url_hd'] = (string) ($profile['profile_pic_url_hd'] ?? $profile['profile_picture_url_hd'] ?? $profile['profile_pic_url']);
        $profile['is_verified'] = (bool) ($profile['is_verified'] ?? $profile['verified'] ?? false);
        $profile['edge_followed_by'] = ['count' => $followers];
        $profile['edge_follow'] = ['count' => $following];
        $profile['edge_owner_to_timeline_media'] = ['count' => $posts];

        return $profile;
    }

    public function getMedia($username, $limit = 9)
    {
        return [];
    }

    public function formatFollowerCount($count): string
    {
        if ($count === null || $count === '') {
            return '–';
        }

        $count = (int) $count;
        if ($count >= 1000000) {
            return rtrim(rtrim(number_format($count / 1000000, 1, '.', ''), '0'), '.') . 'M';
        }
        if ($count >= 1000) {
            return rtrim(rtrim(number_format($count / 1000, 1, '.', ''), '0'), '.') . 'K';
        }

        return number_format($count);
    }

    private function normalizeUsername(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $candidate = preg_match('~^https?://~i', $value) ? $value : 'https://' . ltrim($value, '/');
        $host = strtolower((string) parse_url($candidate, PHP_URL_HOST));
        if ($host === 'instagram.com' || $host === 'www.instagram.com') {
            $path = trim((string) parse_url($candidate, PHP_URL_PATH), '/');
            $value = explode('/', $path)[0] ?? '';
        }

        return ltrim(trim($value, " \t\n\r\0\x0B/"), '@');
    }

    /**
     * @return array{success: bool, data: array, error: string, retryable: bool}
     */
    private function requestProfile(string $username): array
    {
        if ($this->apiKey === '' || $this->apiHost === '') {
            return ['success' => false, 'data' => [], 'error' => 'RapidAPI is not configured.', 'retryable' => false];
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://' . $this->apiHost . '/api/instagram/profile',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(['username' => $username]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-rapidapi-host: ' . $this->apiHost,
                'x-rapidapi-key: ' . $this->apiKey,
            ],
        ]);

        $body = curl_exec($curl);
        $curlError = curl_error($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($body === false) {
            return ['success' => false, 'data' => [], 'error' => 'Curl error: ' . $curlError, 'retryable' => true];
        }

        $data = json_decode($body, true);
        if ($httpCode < 200 || $httpCode >= 300) {
            $message = is_array($data) ? ($data['message'] ?? $data['response_type'] ?? null) : null;

            return [
                'success' => false,
                'data' => is_array($data) ? $data : [],
                'error' => 'HTTP ' . $httpCode . ($message ? ': ' . $message : ''),
                'retryable' => $httpCode === 429 || $httpCode >= 500,
            ];
        }

        if (!is_array($data)) {
            return ['success' => false, 'data' => [], 'error' => 'Invalid JSON response.', 'retryable' => true];
        }

        return ['success' => true, 'data' => $data, 'error' => '', 'retryable' => false];
    }

    /**
     * Meta Business Discovery is the stable, official source for public
     * Instagram Business/Creator accounts.
     *
     * @return array{success: bool, data: array, error: string, retryable: bool}
     */
    private function requestGraphProfile(string $username): array
    {
        $fields = sprintf(
            'business_discovery.username(%s){username,name,profile_picture_url,followers_count,follows_count,media_count}',
            $username
        );
        $url = 'https://graph.facebook.com/' . rawurlencode($this->graphApiVersion)
            . '/' . rawurlencode($this->graphUserId)
            . '?' . http_build_query([
                'fields' => $fields,
                'access_token' => $this->graphAccessToken,
            ]);

        return $this->requestJson($url, [
            'Accept: application/json',
        ]);
    }

    /**
     * Best-effort public lookup for public profiles. Instagram can rate-limit
     * this endpoint, therefore Graph API and cached data remain preferred.
     *
     * @return array{success: bool, data: array, error: string, retryable: bool}
     */
    private function requestPublicProfile(string $username): array
    {
        $url = 'https://www.instagram.com/api/v1/users/web_profile_info/?username=' . rawurlencode($username);

        return $this->requestJson($url, [
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/139.0 Safari/537.36',
            'X-IG-App-ID: 936619743392459',
            'Referer: https://www.instagram.com/',
        ]);
    }

    /**
     * @param list<string> $headers
     * @return array{success: bool, data: array, error: string, retryable: bool}
     */
    private function requestJson(string $url, array $headers): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $body = curl_exec($curl);
        $curlError = curl_error($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($body === false) {
            return ['success' => false, 'data' => [], 'error' => 'Curl error: ' . $curlError, 'retryable' => true];
        }

        $data = json_decode($body, true);
        if ($httpCode < 200 || $httpCode >= 300) {
            $message = is_array($data)
                ? ($data['error']['message'] ?? $data['message'] ?? null)
                : null;

            return [
                'success' => false,
                'data' => is_array($data) ? $data : [],
                'error' => 'HTTP ' . $httpCode . ($message ? ': ' . $message : ''),
                'retryable' => $httpCode === 429 || $httpCode >= 500,
            ];
        }

        if (!is_array($data)) {
            return ['success' => false, 'data' => [], 'error' => 'Invalid JSON response.', 'retryable' => false];
        }

        if (isset($data['error'])) {
            return [
                'success' => false,
                'data' => $data,
                'error' => (string) ($data['error']['message'] ?? 'Provider returned an error.'),
                'retryable' => false,
            ];
        }

        return ['success' => true, 'data' => $data, 'error' => '', 'retryable' => false];
    }

    private static function firstNumericValue(array $data, array $paths): ?int
    {
        foreach ($paths as $path) {
            $value = $data;
            foreach ($path as $segment) {
                if (!is_array($value) || !array_key_exists($segment, $value)) {
                    $value = null;
                    break;
                }
                $value = $value[$segment];
            }

            if (is_numeric($value)) {
                return (int) $value;
            }

            if (is_string($value) && preg_match('/([\d,.]+)\s*([KMB])?/i', $value, $matches)) {
                $number = (float) str_replace(',', '', $matches[1]);
                $multiplier = match (strtoupper($matches[2] ?? '')) {
                    'K' => 1000,
                    'M' => 1000000,
                    'B' => 1000000000,
                    default => 1,
                };

                return (int) round($number * $multiplier);
            }
        }

        return null;
    }
}
