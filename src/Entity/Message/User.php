<?php

namespace Ymsoft\TelegramChannelScrapper\Entity\Message;

class User
{
    public function __construct(
        public readonly string $photo,
        public readonly string $name,
    ) {}
}
