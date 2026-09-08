<?php

use App\Libraries\YoutubeService;
use CodeIgniter\Test\CIUnitTestCase;

final class YoutubeServiceTest extends CIUnitTestCase
{
    public function testAkademiBitorexProfileOverrideKeepsAvatar(): void
    {
        $service = new YoutubeService();
        $method = new ReflectionMethod($service, 'applyProfileOverride');

        $result = $method->invoke($service, 'UCIdO6pNx-6ppVeYaqeUdIGA', [
            'subscriber_count' => '6.22K subscribers',
            'video_count'      => 'respons API yang salah',
            'avatar'           => [['url' => 'https://example.com/avatar.jpg', 'width' => 120]],
        ]);

        $this->assertSame('Akademi Bitorex', $result['title']);
        $this->assertSame('6.26K subscribers', $result['subscriber_count']);
        $this->assertSame('634 videos', $result['video_count']);
        $this->assertSame('https://example.com/avatar.jpg', $result['avatar'][0]['url']);
    }
}
