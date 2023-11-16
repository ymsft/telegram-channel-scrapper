<?php

namespace Ymsoft\TelegramChannelScrapper\Crawler;

use Ymsoft\TelegramChannelScrapper\Entity\Channel;
use Ymsoft\TelegramChannelScrapper\Helper\StringHelper;
use Ymsoft\TelegramChannelScrapper\TelegramCSState;
use Symfony\Component\DomCrawler\Crawler;

class ChannelCrawler implements CrawlerInterface
{
    public function __construct(
        private readonly TelegramCSState $state,
    ) {}

    public function process(string $html): self
    {
        $crawler = new Crawler($html);

        $description = $crawler->filter('.tgme_channel_info_description')->html();
        $description = $description ? StringHelper::replaceBRWithNativeLineBreakAndRemoveHtmlAttributes($description) : null;

        $subscribersCount = 0;
        $photosCount = 0;
        $videosCount = 0;
        $filesCount = 0;
        $linksCount = 0;

        $crawler->filter('.tgme_channel_info_counters .tgme_channel_info_counter')
            ->each(function (Crawler $parentCrawler) use (&$subscribersCount, &$photosCount, &$videosCount, &$filesCount, &$linksCount) {
                $count = StringHelper::convertStringNumberToInteger($parentCrawler->filter('.counter_value')->text());

                $type = $parentCrawler->filter('.counter_type')->text();

                match ($type) {
                    'subscribers' => $subscribersCount = $count,
                    'photos' => $photosCount = $count,
                    'videos' => $videosCount = $count,
                    'files' => $filesCount = $count,
                    'links' => $linksCount = $count,
                    default => null,
                };
            });

        $this->state->channel = new Channel(
            title: $crawler->filter('.tgme_channel_info_header_title span')->text(),
            image: $crawler->filter('.tgme_page_photo_image img')->attr('src'),
            lineDescription: $crawler->filter('.tgme_channel_info_description')->text(),
            description: $description,
            subscribersCount: $subscribersCount,
            photosCount: $photosCount,
            videosCount: $videosCount,
            filesCount: $filesCount,
            linksCount: $linksCount,
        );

        return $this;
    }

    public function getResult(): Channel
    {
        return $this->state->channel;
    }
}
