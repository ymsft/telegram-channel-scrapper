<?php

namespace Ymsoft\TelegramChannelScrapper\Crawler;

interface CrawlerInterface
{
    public function process(string $html): self;

    public function getResult(): mixed;
}
