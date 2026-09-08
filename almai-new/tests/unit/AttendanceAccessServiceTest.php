<?php

namespace Tests\Unit;

use App\Models\LevelModel;
use App\Services\AttendanceAccessService;
use CodeIgniter\Test\CIUnitTestCase;

final class AttendanceAccessServiceTest extends CIUnitTestCase
{
    public function testAudienceInputIsNormalized(): void
    {
        $this->assertNull(AttendanceAccessService::rolesFromAudience('public'));
        $this->assertNull(AttendanceAccessService::rolesFromAudience('invalid'));
        $this->assertSame('cwpa,wpa', AttendanceAccessService::rolesFromAudience('cwpa_wpa'));
    }

    public function testPublicActivityAllowsEveryLevelAndGuests(): void
    {
        $activity = ['registration_roles' => null];

        $this->assertTrue(AttendanceAccessService::canCheckIn($activity, null));
        $this->assertTrue(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_USER));
    }

    public function testRestrictedActivityAllowsCwpaAndWpa(): void
    {
        $activity = ['registration_roles' => 'cwpa,wpa'];

        $this->assertTrue(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_CWPA));
        $this->assertTrue(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_WPA));
    }

    public function testRestrictedActivityRejectsGuestsAndOtherRoles(): void
    {
        $activity = ['registration_roles' => 'cwpa,wpa'];

        $this->assertFalse(AttendanceAccessService::canCheckIn($activity, null));
        $this->assertFalse(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_USER));
        $this->assertFalse(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_PRO));
        $this->assertFalse(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_ADMIN));
        $this->assertFalse(AttendanceAccessService::canCheckIn($activity, LevelModel::LEVEL_SUPER_ADMIN));
    }
}
