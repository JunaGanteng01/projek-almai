<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class InstagramProfiles extends BaseConfig
{
    /**
     * Snapshot manual yang dipakai hanya ketika API Instagram gagal dan belum
     * ada cache hasil API. Perbarui angka di sini jika profil berubah.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $fallbackProfiles = [
        'bitorexpost' => [
            'full_name' => 'Bitorex Post',
            'followers' => 206000,
            'following' => 3,
            'is_verified' => true,
            'profile_pic_url' => '',
        ],
    ];
}
