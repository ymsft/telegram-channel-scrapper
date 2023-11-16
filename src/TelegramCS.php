<?php

namespace Ymsoft\TelegramChannelScrapper;

use Ymsoft\TelegramChannelScrapper\Crawler\ChannelCrawler;
use Ymsoft\TelegramChannelScrapper\Crawler\CrawlerInterface;
use Ymsoft\TelegramChannelScrapper\Crawler\MessageCrawler;
use Ymsoft\TelegramChannelScrapper\Crawler\MessagesCrawler;
use Ymsoft\TelegramChannelScrapper\Entity\Channel;
use Ymsoft\TelegramChannelScrapper\Entity\Message\Message;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class TelegramCS
{
    private Client $client;

    private TelegramCSState $state;

    /** @var CrawlerInterface[] */
    private array $crawlers = [];

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function __construct(
        private readonly string $channelName,
        Client $client = null,
    ) {
        $this->setClient($client);

        $this->state = new TelegramCSState();

        $this->addCrawler(new ChannelCrawler(
            state: $this->state,
        ));

        $this->addCrawler(new MessagesCrawler(
            state: $this->state,
        ));

        $response = $this->client->get("https://t.me/s/$this->channelName");

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Telegram response does not correspond to what we expect.');
        }

        $html = $response->getBody();

        if ((new Crawler($html))->filter('.tgme_channel_info')->count() === 0) {
            throw new \InvalidArgumentException('Invalid telegram channel name.');
        }

        $this->processCrawlers($html);
    }

    public function getChannel(): ?Channel
    {
        return $this->state->channel;
    }

    /**
     * @return Collection<Message>
     */
    public function getMessages(): Collection
    {
        return $this->state->messages;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function getMessageById(int $id, bool $onlyStateMessages = false): Message
    {
        $messageFromState = $this->state->messages->firstWhere('id', $id);

        if ($messageFromState) {
            return $messageFromState;
        }

        if (is_null($messageFromState) && $onlyStateMessages) {
            throw new Exception('Undefined message in the state.');
        }
        $html = $this->client->get("https://t.me/$this->channelName/$id?embed=1&mode=tme")->getBody();

        $crawler = new MessageCrawler();

        $message = $crawler->process($html)->getResult();

        if (!$message) {
            throw new Exception('Undefined message.');
        }

        return $message;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function loadPrevMessages(): void
    {
        /** @var ?Message $message */
        $message = $this->state->messages->last();

        if (!$message) {
            throw new Exception('This channel has no more messages.');
        }

        $html = $this->client->get("https://t.me/s/$this->channelName?before=$message->id")->getBody();

        $this->getCrawler(MessagesCrawler::class)->process($html);
    }

    private function addCrawler(CrawlerInterface $crawler): void
    {
        $this->crawlers[$crawler::class] = $crawler;
    }

    private function processCrawlers(string $html): void
    {
        foreach ($this->crawlers as $crawler) {
            $crawler->process($html);
        }
    }

    private function getCrawler(string $name): CrawlerInterface
    {
        return $this->crawlers[$name];
    }

    private function setClient(?Client $client): void
    {
        $this->client = $client ?: new Client([
            'headers' => [
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept' => 'text/html',
            ],
        ]);
    }
}
