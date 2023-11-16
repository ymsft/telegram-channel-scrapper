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
}
