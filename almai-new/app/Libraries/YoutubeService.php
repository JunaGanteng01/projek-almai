<?php

namespace App\Libraries;

class YoutubeService
{
    private $apiKey = '1fb7098361mshe2ade6bf5ad466cp1d0fd5jsn60b4ebaa414e';
    private $apiHost = 'youtube-v2.p.rapidapi.com';
    
    /**
     * Get YouTube channel data by channel ID
     * 
     * @param string $channelId YouTube channel ID (e.g., UCXuqSBlHAE6Xw-yeJA0Tunw)
     * @return array|null Channel data or null on error
     */
    public function getChannelById($channelId)
    {
        if (empty($channelId)) {
            return null;
        }
        
        $channelId = trim($channelId);
        
        // Caching logic
        $cacheKey = 'youtube_channel_' . md5($channelId);
        if ($cachedData = cache($cacheKey)) {
            return $this->applyProfileOverride($channelId, $cachedData);
        }

        $profileFallback = $this->getProfileOverride($channelId);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://youtube-v2.p.rapidapi.com/channel/details?channel_id={$channelId}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
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
                log_message('error', 'YouTube API Error: ' . $err);
            }
            return $profileFallback;
        }
        
        if ($httpCode !== 200) {
            if (function_exists('log_message')) {
                log_message('error', 'YouTube API HTTP Error: ' . $httpCode . ' Response: ' . substr($response, 0, 500));
            }
            return $profileFallback;
        }
        
        $data = json_decode($response, true);
        
        if (function_exists('log_message')) {
            log_message('debug', 'YouTube API Response for ' . $channelId . ': ' . json_encode($data));
        }
        
        if (!$data) {
            if (function_exists('log_message')) {
                log_message('error', 'YouTube API Invalid Response: ' . substr($response, 0, 500));
            }
            return $profileFallback;
        }

        // RapidAPI beberapa kali mengubah nama/isi field. Nilai profil yang
        // dikonfigurasi tetap dipakai agar tampilan produksi sama dengan lokal.
        $data = $this->applyProfileOverride($channelId, $data);
        
        // Save to cache for 1 week (604800 seconds)
        cache()->save($cacheKey, $data, 604800);
        
        return $data;
    }

    /**
     * Ambil data tampilan tetap untuk kanal tertentu.
     */
    private function getProfileOverride(string $channelId): ?array
    {
        $config = config('YoutubeProfiles');
        $profile = $config->profiles[$channelId] ?? null;

        return is_array($profile) ? $profile : null;
    }

    /**
     * Timpa field statistik saja, tetapi pertahankan avatar dari API bila tersedia.
     */
    private function applyProfileOverride(string $channelId, array $apiData): array
    {
        $profile = $this->getProfileOverride($channelId);

        if ($profile === null) {
            return $apiData;
        }

        return array_replace($apiData, $profile);
    }
    
    /**
     * Format subscriber count from string
     * 
     * @param string $count Subscriber count string (e.g., "46.1K subscribers")
     * @return string Formatted count
     */
    public function formatSubscriberCount($count)
    {
        if (is_numeric($count)) {
            if ($count >= 1000000) {
                return round($count / 1000000, 1) . 'M';
            } elseif ($count >= 1000) {
                return round($count / 1000, 1) . 'K';
            }
            return number_format($count);
        }
        
        // Extract from string like "46.1K subscribers"
        preg_match('/([\d.]+[KMB]?)/', $count, $matches);
        return $matches[1] ?? $count;
    }
    
    /**
     * Get avatar URL by size
     * 
     * @param array $avatars Avatar array from API
     * @param int $preferredWidth Preferred width (72, 120, 160)
     * @return string Avatar URL
     */
    public function getAvatarUrl($avatars, $preferredWidth = 120)
    {
        if (empty($avatars) || !is_array($avatars)) {
            return 'https://via.placeholder.com/100';
        }
        
        // Find closest size
        foreach ($avatars as $avatar) {
            if (isset($avatar['width']) && $avatar['width'] >= $preferredWidth) {
                return $avatar['url'] ?? 'https://via.placeholder.com/100';
            }
        }
        
        // Return last (largest) if no match
        return end($avatars)['url'] ?? 'https://via.placeholder.com/100';
    }
}
