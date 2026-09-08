<?php

use App\Models\LevelModel;
use CodeIgniter\Test\CIUnitTestCase;

/** @internal */
final class CeoFoundationTest extends CIUnitTestCase
{
    public function testCeoRoleMappingsUseLevelTen(): void
    {
        $this->assertSame(10, LevelModel::LEVEL_CEO);
        $this->assertSame('ceo', LevelModel::getDashboardSegment(LevelModel::LEVEL_CEO));
        $this->assertSame('/ceo/dashboard', LevelModel::getDashboardPath(LevelModel::LEVEL_CEO));
        $this->assertSame('CEO', LevelModel::getRoleName(LevelModel::LEVEL_CEO));
        $this->assertSame(LevelModel::LEVEL_CEO, LevelModel::levelFromRoleString('ceo'));
        $this->assertSame('ceo', LevelModel::roleStringFromLevel(LevelModel::LEVEL_CEO));
        $this->assertTrue(LevelModel::isCeoLevel(LevelModel::LEVEL_CEO));
        $this->assertFalse(LevelModel::isCeoLevel(LevelModel::LEVEL_SUPER_ADMIN));
    }
}
