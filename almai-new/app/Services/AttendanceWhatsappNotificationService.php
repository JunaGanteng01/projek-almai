<?php

namespace App\Services;

use Throwable;

class AttendanceWhatsappNotificationService
{
    private const SENT_CACHE_TTL = 86400;

    public static function renderMessage(string $template, array $event, array $user, string $kegiatanType): string
    {
        $timestamp = strtotime((string) ($event['tanggal'] ?? $event['created_at'] ?? 'now')) ?: time();
        $checkinTimestamp = time();

        $values = [
            'nama' => (string) ($user['name'] ?? 'Peserta'),
            'name' => (string) ($user['name'] ?? 'Peserta'),
            'event' => self::eventName($event, $kegiatanType),
            'acara' => self::eventName($event, $kegiatanType),
            'tanggal' => self::indonesianDate($timestamp),
            'waktu_absen' => date('d-m-Y H:i', $checkinTimestamp),
            'poin' => '100',
        ];

        $search = [];
        $replace = [];
        foreach ($values as $key => $value) {
            $search[] = '{{' . $key . '}}';
            $replace[] = $value;
            $search[] = '{' . $key . '}';
            $replace[] = $value;
        }

        return str_replace($search, $replace, $template);
    }

    public static function cacheKey(int $checkinId): string
    {
        return 'attendance_wa_sent_' . $checkinId;
    }

    public static function wasSent(int $checkinId): bool
    {
        if ($checkinId <= 0) {
            return false;
        }

        try {
            return cache()->get(self::cacheKey($checkinId)) !== null;
        } catch (Throwable $exception) {
            log_message('warning', 'Unable to read attendance WhatsApp deduplication cache: ' . $exception->getMessage());

            return false;
        }
    }

    public static function markAsSent(int $checkinId): void
    {
        if ($checkinId <= 0) {
            return;
        }

        try {
            cache()->save(self::cacheKey($checkinId), '1', self::SENT_CACHE_TTL);
        } catch (Throwable $exception) {
            log_message('warning', 'Unable to save attendance WhatsApp deduplication cache: ' . $exception->getMessage());
        }
    }

    private static function eventName(array $event, string $kegiatanType): string
    {
        return match ($kegiatanType) {
            'seminar_fgd', 'pelatihan_simulasi' => (string) ($event['judul'] ?? 'Kegiatan Almai'),
            'kegiatan_lainnya' => (string) ($event['nama_kegiatan'] ?? 'Kegiatan Almai'),
            'expert_advisor' => (string) ($event['nama_layanan'] ?? 'Kegiatan Almai'),
            default => (string) ($event['keterangan'] ?? 'Kegiatan Almai'),
        };
    }

    private static function indonesianDate(int $timestamp): string
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return $days[(int) date('w', $timestamp)] . ', ' . date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
    }
}
