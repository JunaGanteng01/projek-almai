<?php

namespace Tests\Unit;

use App\Libraries\InstagramService;
use CodeIgniter\Test\CIUnitTestCase;

final class InstagramServiceTest extends CIUnitTestCase
{
    public function testItNormalizesLegacyInstagramPayload(): void
    {
        $profile = InstagramService::normalizeProfileData([
            'result' => [
                'username' => 'almai_id',
                'full_name' => 'ALMAI',
                'edge_followed_by' => ['count' => 12540],
                'edge_follow' => ['count' => 321],
            ],
        ]);

        $this->assertSame('almai_id', $profile['username']);
        $this->assertSame(12540, $profile['edge_followed_by']['count']);
        $this->assertSame(321, $profile['edge_follow']['count']);
    }

    public function testItNormalizesAlternativeFollowerFields(): void
    {
        $profile = InstagramService::normalizeProfileData([
            'data' => [
                'user' => [
                    'user_name' => 'trader_id',
                    'followers_count' => '46.1K followers',
                    'following_count' => 180,
                    'profile_picture_url' => 'https://example.com/avatar.jpg',
                ],
            ],
        ]);

        $this->assertSame('trader_id', $profile['username']);
        $this->assertSame(46100, $profile['edge_followed_by']['count']);
        $this->assertSame(180, $profile['edge_follow']['count']);
        $this->assertSame('https://example.com/avatar.jpg', $profile['profile_pic_url']);
    }

    public function testItNormalizesMetaBusinessDiscoveryPayload(): void
    {
        $profile = InstagramService::normalizeProfileData([
            'business_discovery' => [
                'username' => 'bitorexpost',
                'name' => 'Bitorex Post',
                'followers_count' => 206000,
                'follows_count' => 3,
                'media_count' => 1200,
                'profile_picture_url' => 'https://example.com/bitorex.jpg',
            ],
        ]);

        $this->assertSame('bitorexpost', $profile['username']);
        $this->assertSame('Bitorex Post', $profile['full_name']);
        $this->assertSame(206000, $profile['edge_followed_by']['count']);
        $this->assertSame(3, $profile['edge_follow']['count']);
        $this->assertSame(1200, $profile['edge_owner_to_timeline_media']['count']);
    }

    public function testFollowerFormattingMatchesYoutubeStyle(): void
    {
        $service = new InstagramService();

        $this->assertSame('–', $service->formatFollowerCount(null));
        $this->assertSame('887', $service->formatFollowerCount(887));
        $this->assertSame('12.5K', $service->formatFollowerCount(12540));
        $this->assertSame('1.2M', $service->formatFollowerCount(1200000));
    }

    public function testKnownProfileUsesConfiguredManualFallback(): void
    {
        $service = new InstagramService();
        $profile = $service->getFallbackProfile('@bitorexpost');

        $this->assertNotNull($profile);
        $this->assertSame('bitorexpost', $profile['username']);
        $this->assertSame(206000, $profile['edge_followed_by']['count']);
        $this->assertSame(3, $profile['edge_follow']['count']);
        $this->assertTrue($profile['is_manual_fallback']);
    }
}
