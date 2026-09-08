<?php

namespace Tests\Unit;

use App\Services\AttendanceWhatsappNotificationService;
use CodeIgniter\Test\CIUnitTestCase;

final class AttendanceWhatsappNotificationServiceTest extends CIUnitTestCase
{
    public function testItRendersAttendanceTemplateVariables(): void
    {
        $message = AttendanceWhatsappNotificationService::renderMessage(
            'Halo {nama}, hadir di {{event}} pada {tanggal}. Poin: {poin}.',
            [
                'judul' => 'Seminar Trading Aman',
                'tanggal' => '2026-08-26 09:00:00',
            ],
            ['name' => 'Budi'],
            'seminar_fgd'
        );

        $this->assertStringContainsString('Halo Budi', $message);
        $this->assertStringContainsString('Seminar Trading Aman', $message);
        $this->assertStringContainsString('Rabu, 26 Agustus 2026', $message);
        $this->assertStringContainsString('Poin: 100', $message);
        $this->assertStringNotContainsString('{nama}', $message);
        $this->assertStringNotContainsString('{{event}}', $message);
    }

    public function testItUsesTheCorrectEventNameForOtherActivity(): void
    {
        $message = AttendanceWhatsappNotificationService::renderMessage(
            '{acara}',
            ['nama_kegiatan' => 'Gathering ALMAI'],
            ['name' => 'Siti'],
            'kegiatan_lainnya'
        );

        $this->assertSame('Gathering ALMAI', $message);
    }

    public function testCacheKeyIsStablePerCheckin(): void
    {
        $this->assertSame('attendance_wa_sent_42', AttendanceWhatsappNotificationService::cacheKey(42));
    }

    public function testAttendanceWaitsForUserWhatsappMessage(): void
    {
        $controller = file_get_contents(APPPATH . 'Controllers/Absensi.php');
        $webhook = file_get_contents(APPPATH . 'Controllers/Webhook/BalesotomatisWebhook.php');

        $this->assertStringNotContainsString('AttendanceWhatsappNotificationService())->send(', $controller);
        $this->assertStringContainsString('ingin mengonfirmasi kehadiran', $controller);
        $this->assertStringNotContainsString('👋', $controller);
        $this->assertStringContainsString('isAttendanceConfirmation', $webhook);
        $this->assertStringContainsString('AttendanceWhatsappNotificationService::renderMessage', $webhook);
    }
}
