<?php

use App\Models\LevelModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/** @internal */
final class CeoRouteTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testGuestIsRedirectedFromCeoDashboard(): void
    {
        $result = $this->get('/ceo/dashboard');
        $result->assertRedirectTo('/ceo');
    }

    public function testNonCeoIsDeniedBeforeDashboardLoads(): void
    {
        $result = $this->withSession([
            'isLoggedIn' => true,
            'userId' => 1,
            'userRole' => 'admin',
            'level_id' => LevelModel::LEVEL_SUPER_ADMIN,
        ])->get('/ceo/dashboard');

        $result->assertRedirectTo('/login');
    }

    public function testCeoCanRenderExecutiveDashboard(): void
    {
        if (!extension_loaded('sqlite3')) {
            $this->markTestSkipped('Ekstensi sqlite3 untuk database test CI4 tidak tersedia di runtime lokal.');
        }
        $result = $this->withSession([
            'isLoggedIn' => true,
            'userId' => 1,
            'userName' => 'CEO Test',
            'userEmail' => 'ceo-test@example.invalid',
            'userRole' => 'ceo',
            'level_id' => LevelModel::LEVEL_CEO,
        ])->get('/ceo/dashboard');

        $result->assertStatus(200);
        $result->assertSee('Executive Pulse');
        $result->assertSee('Approval Center');
    }
}
