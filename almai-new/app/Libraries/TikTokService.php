<?php

namespace App\Libraries;

class TikTokService
{
    private $apiKey = '1fb7098361mshe2ade6bf5ad466cp1d0fd5jsn60b4ebaa414e';
    private $apiHost = 'tiktok-api23.p.rapidapi.com';
    
    /**
     * Get TikTok profile data using secUid
     * 
     * @param string $secUid TikTok secUid
     * @return array|null Profile data or null on error
     */
    public function getProfileBySecUid($secUid)
    {
        if (empty($secUid)) {
            return null;
        }
        
        $secUid = trim($secUid);
        
        // Caching logic (Match YoutubeService)
        $cacheKey = 'tiktok_profile_v3_' . md5($secUid);
        if ($cachedData = cache($cacheKey)) {
            return $cachedData;
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://tiktok-api23.p.rapidapi.com/api/user/info?secUid=" . urlencode($secUid),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: {$this->apiHost}",
                "x-rapidapi-key: {$this->apiKey}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($err) {
            if (function_exists('log_message')) {
                log_message('error', 'TikTok API Error: ' . $err);
            }
            return null;
        }
        
        if ($httpCode !== 200) {
            if (function_exists('log_message')) {
                log_message('error', 'TikTok API HTTP Error: ' . $httpCode . ' Response: ' . substr($response, 0, 500));
            }
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['userInfo'])) {
            if (function_exists('log_message')) {
                log_message('error', 'TikTok API Invalid Response for SecUid ' . $secUid . ': ' . substr($response, 0, 500));
            }
            return null;
        }
        
        // Extract userInfo to simplify view logic
        $profileData = $data['userInfo'];
        
        // Save to cache for 1 week (604800 seconds)
        cache()->save($cacheKey, $profileData, 604800);
        
        return $profileData;
    }

    /**
     * Get TikTok profile data using uniqueId (Username)
     * 
     * @param string $username TikTok username (without @)
     * @return array|null Profile data or null on error
     */
    public function getProfileByUsername($username)
    {
        if (empty($username)) {
            return null;
        }
        
        // Remove @ and whitespace
        $username = trim(ltrim($username, '@'));
        
        // Caching logic (Match YoutubeService)
        $cacheKey = 'tiktok_profile_v3_' . md5($username);
        if ($cachedData = cache($cacheKey)) {
            return $cachedData;
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://tiktok-api23.p.rapidapi.com/api/user/info?uniqueId=" . urlencode($username),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: {$this->apiHost}",
                "x-rapidapi-key: {$this->apiKey}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($err) {
            if (function_exists('log_message')) {
                log_message('error', 'TikTok API Error: ' . $err);
            }
            return null;
        }
        
        if ($httpCode !== 200) {
            if (function_exists('log_message')) {
                log_message('error', 'TikTok API HTTP Error: ' . $httpCode . ' Response: ' . substr($response, 0, 500));
            }
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['userInfo'])) {
            if (function_exists('log_message')) {
                log_message('error', 'TikTok API Invalid Response for ' . $username . ': ' . substr($response, 0, 500));
            }
            return null;
        }
        
        // Extract userInfo to simplify view logic (same as we did for IG/YT)
        $profileData = $data['userInfo'];
        
        // Save to cache for 1 week (604800 seconds)
        cache()->save($cacheKey, $profileData, 604800);
        
        return $profileData;
    }

    /**
     * Get TikTok posts using secUid
     * 
     * @param string $secUid TikTok secUid
     * @param int $count Number of posts
     * @return array|null Posts data or null on error
     */
    public function getPosts($secUid, $count = 10)
    {
        if (empty($secUid)) {
            return null;
        }
        
        $cacheKey = 'tiktok_posts_' . md5($secUid);
        if ($cachedData = cache($cacheKey)) {
            return $cachedData;
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://tiktok-api23.p.rapidapi.com/api/user/posts?secUid=" . urlencode($secUid) . "&count=" . $count . "&cursor=0",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: {$this->apiHost}",
                "x-rapidapi-key: {$this->apiKey}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($err) {
            return null;
        }
        
        $data = json_decode($response, true);
        
        if ($data) {
            cache()->save($cacheKey, $data, 604800);
        }
        
        return $data;
    }
}
