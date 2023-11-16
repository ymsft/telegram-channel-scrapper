<?php

namespace Ymsoft\TelegramChannelScrapper\Entity\Message;

class Message
{
    /**
     * @param  bool  $service for example pinned message is true
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $lineText,
        public readonly ?string $text,
        public readonly string $html,
        public readonly \DateTime $date,
        public readonly User $user,
        public readonly ?string $author,
        public array $photos = [],
        public readonly int $viewsCount = 0,
        public bool $edited = false,
        public bool $service = false,
    ) {}
}
