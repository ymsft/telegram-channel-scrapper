<?php

namespace Ymsoft\TelegramChannelScrapper\Tests;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Ymsoft\TelegramChannelScrapper\TelegramCS;

class TelegramCSTest extends TestCase
{
    public function test_init_with_invalid_channel_name(): void
    {
        $this->expectExceptionMessage('Invalid telegram channel name.');

        new TelegramCS('invalid_telegram_channel_name');
    }

    public function test_init(): void
    {
        $service = new TelegramCS('spotifynewss');

        $this->assertEquals(20, $service->getMessages()->count());
    }

    /**
     * @throws GuzzleException
     */
    public function test_load_prev_messages(): void
    {
        $service = new TelegramCS('spotifynewss');

        $service->loadPrevMessages();

        $this->assertEquals(40, $service->getMessages()->count());
    }

    /**
     * @throws GuzzleException
     */
    public function test_get_message_by_id(): void
    {
        $service = new TelegramCS('spotifynewss');

        $message = $service->getMessageById(308);

        $this->assertEquals(308, $message->id);
        $this->assertEquals('¶Spotify Team™ pinned a photo', $message->lineText);
        $this->assertTrue($message->service);

        $secondMessage = $service->getMessageById(307);
        $this->assertEquals(307, $secondMessage->id);
        $this->assertFalse($secondMessage->service);
        $this->assertEquals('▄︻デE̷t̷h̷i̷c̷a̷l̷ ̷ H̷a̷c̷k̷e̷r̷══━一 ⁪⁬⁮⁮', $secondMessage->author);
        $this->assertCount(1, $secondMessage->photos);
        $this->assertFalse($secondMessage->edited);
    }
}
