<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class YoutubeProfiles extends BaseConfig
{
    /**
     * Nilai tampilan tetap untuk kanal yang digunakan pada profil CWPA.
     * Data ini juga menjadi fallback ketika RapidAPI gagal atau mengubah format respons.
     */
    public array $profiles = [
        'UCIdO6pNx-6ppVeYaqeUdIGA' => [
            'channel_id'       => 'UCIdO6pNx-6ppVeYaqeUdIGA',
            'title'            => 'Akademi Bitorex',
            'subscriber_count' => '6.26K subscribers',
            'video_count'      => '634 videos',
        ],
    ];
}
