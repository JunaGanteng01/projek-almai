<?php

namespace App\Services;

use App\Models\LayananEventModel;
use RuntimeException;

/**
 * Keeps public free events in sync with records created in Admin > Absensi Kegiatan.
 * Paid-event creation and update flows deliberately do not use this service.
 */
class AttendanceEventSyncService
{
    private LayananEventModel $events;

    public function __construct(?LayananEventModel $events = null)
    {
        $this->events = $events ?? new LayananEventModel();
    }

    public function sync(string $attendanceType, int $attendanceId, array $activity): int
    {
        $type = self::canonicalType($attendanceType);
        $existing = $this->events
            ->where('attendance_type', $type)
            ->where('attendance_id', $attendanceId)
            ->first();

        $event = self::mapActivity($type, $attendanceId, $activity);

        if ($existing) {
            // Keep public URLs stable when an attendance title is edited.
            $event['slug'] = $existing['slug'];
            $event['current_participants'] = (int) ($existing['current_participants'] ?? 0);
            if (!$this->events->update((int) $existing['id'], $event)) {
                throw new RuntimeException('Gagal memperbarui Event Gratis dari kegiatan absensi.');
            }

            return (int) $existing['id'];
        }

        $id = $this->events->insert($event, true);
        if (!$id) {
            throw new RuntimeException('Gagal membuat Event Gratis dari kegiatan absensi.');
        }

        return (int) $id;
    }

    public function delete(string $attendanceType, int $attendanceId): void
    {
        $this->events
            ->where('attendance_type', self::canonicalType($attendanceType))
            ->where('attendance_id', $attendanceId)
            ->delete();
    }

    public static function mapActivity(string $attendanceType, int $attendanceId, array $activity): array
    {
        $type = self::canonicalType($attendanceType);
        $title = self::first($activity, ['event_title', 'judul', 'nama_layanan', 'nama_kegiatan', 'keterangan', 'produk'])
            ?: 'Kegiatan ALMAI';
        $date = self::first($activity, ['tanggal']) ?: date('Y-m-d');
        $time = self::first($activity, ['event_time', 'jam']);
        if ($time && strlen($date) <= 10) {
            $date .= ' ' . $time;
        } elseif (strlen($date) <= 10) {
            $date .= ' 00:00:00';
        }

        $categories = [
            'seminar_fgd' => 'Seminar / FGD',
            'pelatihan_simulasi' => 'Pelatihan Simulasi',
            'signals' => 'Signal',
            'konsultasi' => 'Konsultasi',
            'expert_advisor' => 'Expert Advisor',
            'kegiatan_lainnya' => 'Kegiatan Lainnya',
        ];

        return [
            'attendance_type' => $type,
            'attendance_id' => $attendanceId,
            'attendance_code' => $activity['kode_qr'] ?? null,
            'wpa_id' => $activity['wpa_id'] ?? null,
            'cwpa_id' => $activity['cwpa_id'] ?? null,
            'type' => in_array($type, ['seminar_fgd', 'signals', 'konsultasi'], true) ? 'webinar' : 'workshop',
            'title' => $title,
            'description' => self::first($activity, ['topik', 'penjelasan_layanan', 'keterangan']) ?: $title,
            'thumbnail' => self::first($activity, ['thumbnail', 'banner_image']),
            'specialist' => $activity['event_category'] ?? ($categories[$type] ?? 'Kegiatan'),
            'event_date' => $date,
            'location' => self::first($activity, ['lokasi', 'media']) ?: 'Online',
            'max_participants' => (int) (self::first($activity, ['event_quota', 'jml_peserta', 'jml_klien']) ?: 0),
            'current_participants' => 0,
            'price' => 0,
            'poin_price' => 0,
            'is_paid' => 0,
            'is_pro_only' => 0,
            'registration_roles' => $activity['registration_roles'] ?? null,
            'is_featured' => 0,
            'is_recurring' => 0,
            'status' => 'aktif',
        ];
    }

    public static function canonicalType(string $type): string
    {
        $type = strtolower(str_replace([' ', '-'], '_', trim($type)));

        return match ($type) {
            'seminar', 'seminar__fgd' => 'seminar_fgd',
            'pelatihan', 'pelatihan__simulasi' => 'pelatihan_simulasi',
            'signal' => 'signals',
            default => $type,
        };
    }

    private static function first(array $data, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (isset($data[$key]) && $data[$key] !== '') {
                return $data[$key];
            }
        }

        return null;
    }
}
