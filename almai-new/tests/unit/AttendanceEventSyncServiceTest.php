<?php

use App\Services\AttendanceEventSyncService;
use CodeIgniter\Test\CIUnitTestCase;

final class AttendanceEventSyncServiceTest extends CIUnitTestCase
{
    public function testItMapsAttendanceToAFreePublicEvent(): void
    {
        $event = AttendanceEventSyncService::mapActivity('seminar_fgd', 42, [
            'judul' => 'Seminar Pasar Modal',
            'banner_image' => 'uploads/banners/seminar.jpg',
            'tanggal' => '2026-09-10',
            'event_time' => '09:30:00',
            'kode_qr' => 'ABS-SEMINAR-42',
            'lokasi' => 'Denpasar',
            'jml_peserta' => 100,
            'wpa_id' => 7,
            'registration_roles' => 'cwpa,wpa',
        ]);

        $this->assertSame('seminar_fgd', $event['attendance_type']);
        $this->assertSame(42, $event['attendance_id']);
        $this->assertSame('ABS-SEMINAR-42', $event['attendance_code']);
        $this->assertSame('Seminar Pasar Modal', $event['title']);
        $this->assertSame('uploads/banners/seminar.jpg', $event['thumbnail']);
        $this->assertSame('Seminar / FGD', $event['specialist']);
        $this->assertSame('2026-09-10 09:30:00', $event['event_date']);
        $this->assertSame('Denpasar', $event['location']);
        $this->assertSame(100, $event['max_participants']);
        $this->assertSame(0, $event['is_paid']);
        $this->assertSame(0, $event['price']);
        $this->assertSame('cwpa,wpa', $event['registration_roles']);
        $this->assertSame('aktif', $event['status']);
    }

    public function testItNormalizesAliasesToPreventDuplicateSourceKeys(): void
    {
        $this->assertSame('seminar_fgd', AttendanceEventSyncService::canonicalType('Seminar FGD'));
        $this->assertSame('seminar_fgd', AttendanceEventSyncService::canonicalType('seminar'));
        $this->assertSame('pelatihan_simulasi', AttendanceEventSyncService::canonicalType('Pelatihan Simulasi'));
        $this->assertSame('signals', AttendanceEventSyncService::canonicalType('Signal'));
    }

    public function testItMapsEverySupportedAttendanceTitleAndLocationShape(): void
    {
        $event = AttendanceEventSyncService::mapActivity('kegiatan_lainnya', 9, [
            'nama_kegiatan' => 'Gathering Komunitas',
            'media' => 'Google Meet',
            'tanggal' => '2026-10-01 19:00:00',
            'jml_klien' => '25',
        ]);

        $this->assertSame('Gathering Komunitas', $event['title']);
        $this->assertSame('Google Meet', $event['location']);
        $this->assertSame('2026-10-01 19:00:00', $event['event_date']);
        $this->assertSame(25, $event['max_participants']);
    }
}
