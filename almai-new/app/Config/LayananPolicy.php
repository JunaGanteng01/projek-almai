<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class LayananPolicy extends BaseConfig
{
    /**
     * ID layanan Membership Advokasi pada tabel transaksi.
     */
    public int $advokasiMembershipLayananId = 9999;

    /**
     * Slug layanan yang boleh dibeli tanpa membership advokasi.
     */
    public array $advokasiExemptSlugs = [
        'live-trading-mentoring-1772770083',
        'live-trading-mentoring-1772787151',
        'live-trading-mentoring-1773302763',
        'live-trading-mentoring-1772770523',
        'belajar-Investasi-aset-digital',
    ];

    /**
     * Nama kategori yang dianggap layanan advokasi.
     */
    public string $advokasiCategoryName = 'advokasi';
}


