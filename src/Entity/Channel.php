<?php

namespace Ymsoft\TelegramChannelScrapper\Entity;

class Channel
{
    public function __construct(
        public readonly string $title,
        public readonly string $image,
        public readonly ?string $lineDescription,
        public readonly ?string $description,
        public readonly int $subscribersCount = 0,
        public readonly int $photosCount = 0,
        public readonly int $videosCount = 0,
        public readonly int $filesCount = 0,
        public readonly int $linksCount = 0,
    ) {}
}
