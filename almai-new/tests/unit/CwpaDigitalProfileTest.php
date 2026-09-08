<?php

namespace Tests\Unit;

use App\Models\CwpaModel;
use CodeIgniter\Test\CIUnitTestCase;

final class CwpaDigitalProfileTest extends CIUnitTestCase
{
    public function testInstagramUsernameIsNormalized(): void
    {
        $this->assertSame('bitorexpost', CwpaModel::normalizeInstagram('@bitorexpost'));
    }

    public function testCompleteInstagramUrlIsNormalized(): void
    {
        $this->assertSame(
            'bitorexpost',
            CwpaModel::normalizeInstagram('https://www.instagram.com/bitorexpost/?utm_source=profile')
        );
    }

    public function testInstagramUrlIsBuiltFromEitherInputFormat(): void
    {
        $expected = 'https://www.instagram.com/bitorexpost/';

        $this->assertSame($expected, CwpaModel::instagramUrl('bitorexpost'));
        $this->assertSame($expected, CwpaModel::instagramUrl('instagram.com/bitorexpost/'));
    }

    public function testMql5SignalUrlAndQueryStringArePreserved(): void
    {
        $url = 'https://www.mql5.com/en/signals/2371939?source=Site+Profile+Seller';

        $this->assertSame($url, CwpaModel::normalizeMql5Url('  ' . $url . '  '));
    }
}
