<?php

namespace App\Models;

use CodeIgniter\Model;

class CwpaModel extends Model
{
    protected $table = 'cwpa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'name',
        'slug',
        'photo',
        'university',
        'status',
        'batch',
        'current_phase',
        'phase_certificates',
        'is_verified',
        'verified_by',
        'verified_at',
        'verification_notes',
        'instagram',
        'youtube',
        'tiktok',
        'tiktok_secuid',
        'mql5_widget_url',
        'specialty',
        'experience',
        'rating',
        'bio',
        'certifications',
        'nik_wpa',
        'nomor_izin_wpa',
        'tanggal_izin_wpa',
        'no_sertifikat_aspebtindo',
        'no_sertifikat_bi',
        'no_sertifikat_bnsp',
        'masa_berlaku',
        'keterangan',
        'nik_cwpa',
        'almai_pendampingan',
        'bursa_icdx_sertifikasi_multilateral',
        'lpk_sertifikasi_pelatihan_pbk',
        'bnsp_sertifikasi_kompetensi',
        'almai_pendampingan_file',
        'bursa_icdx_sertifikasi_multilateral_file',
        'lpk_sertifikasi_pelatihan_pbk_file',
        'bnsp_sertifikasi_kompetensi_file',
        'phase_certificate_numbers'
    ];


    protected $beforeInsert = ['generateSlug', 'normalizeDigitalProfiles'];
    protected $beforeUpdate = ['generateSlug', 'normalizeDigitalProfiles'];

    // Phase constants (1-8 = fase bimbingan, 9 = selesai)
    public const PHASES = [
        1 => 'Pembekalan & Pendampingan',
        2 => 'Sertifikasi Multilateral',
        3 => 'Sertifikasi Pelatihan LPK',
        4 => 'Sertifikasi LSP PBK',
        5 => 'Sertifikasi TLUP',
        6 => 'Izin WPA',
        7 => 'Persetujuan Derivatif Keuangan',
        8 => 'Persetujuan Derivatif PUVA',
        9 => 'Selesai',
    ];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }
        return $data;
    }

    /**
     * Keep social profile values in one predictable format, regardless of
     * whether the form receives a username or a complete profile URL.
     */
    protected function normalizeDigitalProfiles(array $data)
    {
        if (array_key_exists('instagram', $data['data'])) {
            $data['data']['instagram'] = self::normalizeInstagram($data['data']['instagram']);
        }

        if (array_key_exists('mql5_widget_url', $data['data'])) {
            $data['data']['mql5_widget_url'] = self::normalizeMql5Url($data['data']['mql5_widget_url']);
        }

        return $data;
    }

    public static function normalizeInstagram(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        // Allow instagram.com/profile URLs with or without a scheme.
        $candidate = preg_match('~^https?://~i', $value) ? $value : 'https://' . ltrim($value, '/');
        $host = strtolower((string) parse_url($candidate, PHP_URL_HOST));
        if ($host === 'instagram.com' || $host === 'www.instagram.com') {
            $path = trim((string) parse_url($candidate, PHP_URL_PATH), '/');
            $value = explode('/', $path)[0] ?? '';
        }

        return ltrim(trim($value, " \t\n\r\0\x0B/"), '@');
    }

    public static function instagramUrl(?string $value): string
    {
        $username = self::normalizeInstagram($value);
        return $username === '' ? '' : 'https://www.instagram.com/' . rawurlencode($username) . '/';
    }

    public static function normalizeMql5Url(?string $value): string
    {
        return trim((string) $value);
    }

    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)
            ->where('status', 'active')
            ->first();
    }
    protected $useTimestamps = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    public function getActiveCwpa()
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public static function getPhases()
    {
        return self::PHASES;
    }

    public static function getPhaseLabel($id)
    {
        return self::PHASES[(int) $id] ?? 'Unknown Phase';
    }
}
