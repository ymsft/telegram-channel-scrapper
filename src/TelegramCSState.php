<?php

namespace Ymsoft\TelegramChannelScrapper;

use Ymsoft\TelegramChannelScrapper\Entity\Channel;
use Ymsoft\TelegramChannelScrapper\Entity\Message\Message;
use Illuminate\Support\Collection;

class TelegramCSState
{
    /** @var Collection<Message> */
    public Collection $messages;

    /**
     * @param  Message[]  $messages
     */
    public function __construct(
        public ?Channel $channel = null,
        array $messages = []
    ) {
        $this->messages = new Collection($messages);
    }
}
