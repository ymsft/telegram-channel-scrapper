<?php

namespace Ymsoft\TelegramChannelScrapper\Crawler;

use Ymsoft\TelegramChannelScrapper\TelegramCSState;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class MessagesCrawler implements CrawlerInterface
{
    public function __construct(private readonly TelegramCSState $state) {}

    /**
     * @throws \Exception
     */
    public function process(string $html): self
    {
        $crawler = new Crawler($html);

        $crawler->filter('.tgme_widget_message_wrap')->each(closure: function (Crawler $parentCrawler) {
            $messageCrawler = new MessageCrawler();

            $message = $messageCrawler->process($parentCrawler->html())->getResult();

            if ($message) {
                $this->state->messages->put($message->id, $message);
            }
        });

        $this->state->messages = $this->state->messages->sortKeys()->reverse();

        return $this;
    }

    public function getResult(): Collection
    {
        return $this->state->messages;
    }
}
