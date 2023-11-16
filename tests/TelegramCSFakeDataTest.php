<?php

namespace Ymsoft\TelegramChannelScrapper\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Ymsoft\TelegramChannelScrapper\Entity\Message\Message;
use Ymsoft\TelegramChannelScrapper\TelegramCS;

class TelegramCSFakeDataTest extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function test_fake_channel1(): void
    {
        $mockClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockClient->method('get')
            ->willReturn(new Response(200, [], file_get_contents(__DIR__ . '/data/channel1')));

        $service = new TelegramCS('spotify', $mockClient);

        $channel = $service->getChannel();

        $this->assertEquals('¶Spotify Team™', $channel->title);
        $this->assertEquals('https://cdn5.cdn-telegram.org/file/YVbFRnV4i_EQi9x5Zi41S9s5sqP5C9GLmsJm20_2SGsh6ZXMz5YdTOPAqPDuzUbQCA9Tu79lkbvypDO669z9poGtVrX7GUJVD0EGkuRYsTgJMxIbjynWoUfcto9OY5ic1HPSo5uozCEXSZHCYYOb_N1KpoJhy_byPfCmZOtiW8P-xThS6V9OYYVpqJNN1Mm1GB3ltB2jCfXq1zg8hd14ytjriKvrE6LP8i59uQiIFD2zOVtbPWsxCgUIaZvNEqkkphh4JwAr8Bsa2SpqLOu2yGgqyLs01GyMAuXnKOGu81pNekJnup7iJ5GZxeWYPbZbyK3bk8fBd82XOBkCLlsCVA.jpg', $channel->image);
        $this->assertEquals('THIS IS OFFICIAL CHANNEL OF @Spotify_downloa_botHERE WE POST UPDATES AND QUERIES ABOUT OUR PROJECTSFOR MUSIC GROUP VIST @Spotify_downloaCEO/FOUNDER IS @MasterolicBuy ads: @Masterolic', $channel->lineDescription);
        $this->assertEquals(10100, $channel->subscribersCount);
        $this->assertEquals(15, $channel->photosCount);
        $this->assertEquals(4, $channel->videosCount);
        $this->assertEquals(31, $channel->linksCount);

        $messages = $service->getMessages();
        $this->assertEquals(20, $messages->count());

        /** @var Message $firstMessage */
        $firstMessage = $messages->first();
        $this->assertEquals(308, $firstMessage->id);
        $this->assertEquals('¶Spotify Team™ pinned a photo', $firstMessage->lineText);
        $this->assertTrue($firstMessage->service);

        /** @var Message $secondMessage */
        $secondMessage = $messages->get(307);
        $this->assertEquals(307, $secondMessage->id);
        $this->assertFalse($secondMessage->service);

        $this->assertEquals('▄︻デE̷t̷h̷i̷c̷a̷l̷ ̷ H̷a̷c̷k̷e̷r̷══━一 ⁪⁬⁮⁮', $secondMessage->author);
        $this->assertCount(1, $secondMessage->photos);
        $this->assertEquals('https://cdn5.cdn-telegram.org/file/XuGRXwN6tOvE49fm0Iyks01snTlGBmcyVr_7vAhY8k4xj8yPIlcLNumQEO_pNDTRl0GwY8Qd_BbF3B_Q-YYKzj7JfwG591qDNw1KK6zBPKupVjpv54Sd4P5tTe5c-lNR7eqNXGQWwiE9oh4jZYvbS1W8m3Xltr91mx95Uujwns9aT8JJ4kZ9S6tjpzHkbT8wIPLnYxrn0DspaTgNdqm3MLKKi-_6OTkzvWG089RWOkm4iGUJ-LsP3unexl5tPoyS-bpcWdeV1tM5TmBVep1jzszRYhPlpmTye25pbAbbtw6aV_YngrUs-yzFMffcsOrINRUBGcVoUUvuusPkAAF8Ww.jpg', $secondMessage->photos[0]);
        $this->assertFalse($secondMessage->edited);

        /** @var Message $thirdMessage */
        $thirdMessage = $messages->get(302);
        $this->assertEquals(302, $thirdMessage->id);
        $this->assertEquals('▄︻デE̷t̷h̷i̷c̷a̷l̷ ̷ H̷a̷c̷k̷e̷r̷══━一 ⁪⁬⁮⁮', $thirdMessage->author);
        $this->assertTrue($thirdMessage->edited);
    }
}
