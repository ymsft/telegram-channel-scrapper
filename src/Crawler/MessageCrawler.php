<?php

namespace Ymsoft\TelegramChannelScrapper\Crawler;

use Ymsoft\TelegramChannelScrapper\Entity\Message\Message;
use Ymsoft\TelegramChannelScrapper\Entity\Message\User;
use Ymsoft\TelegramChannelScrapper\Helper\StringHelper;
use Symfony\Component\DomCrawler\Crawler;

class MessageCrawler implements CrawlerInterface
{
    private ?Message $message;

    /**
     * @throws \Exception
     */
    public function process(string $html): self
    {
        $crawler = new Crawler($html);

        if ($crawler->filter('.tgme_widget_message_error')->count() > 0) {
            throw new \Exception($crawler->filter('.tgme_widget_message_error')->text());
        }

        $id = (int) explode('/', $crawler->filter('.tgme_widget_message')->attr('data-post'))[1];

        $text = null;
        $lineText = null;

        $textContainer = $crawler->filter('.tgme_widget_message_text');
        if ($textContainer->count() > 0) {
            $text = StringHelper::replaceBRWithNativeLineBreakAndRemoveHtmlAttributes($textContainer->html());
            $lineText = $textContainer->text();
        }

        $date = new \DateTime($crawler->filter('.tgme_widget_message_date time')->attr('datetime'));

        $viewsCount = 0;
        $viewsContainer = $crawler->filter('.tgme_widget_message_views');
        if ($viewsContainer->count() > 0) {
            $viewsCount = StringHelper::convertStringNumberToInteger($viewsContainer->text());
        }

        $photos = [];

        $crawler->filter('.tgme_widget_message_photo_wrap')->each(function (Crawler $crawler) use (&$photos) {
            $cssStyle = $crawler->attr('style');

            $imageUrlRegex = '/url\(\'([^\']+)\'\)/';
            preg_match($imageUrlRegex, $cssStyle, $matches);

            if (isset($matches[1])) {
                $photos[] = $matches[1];
            }
        });

        $author = null;
        $authorContainer = $crawler->filter('.tgme_widget_message_from_author');
        if ($authorContainer->count() > 0) {
            $author = $authorContainer->text();
        }

        $this->message = new Message(
            id: $id,
            lineText: $lineText,
            text: $text,
            html: $crawler->html(),
            date: $date,
            user: new User(
                photo: $crawler->filter('.tgme_widget_message_user_photo img')->attr('src'),
                name: $crawler->filter('.tgme_widget_message_owner_name span')->text(),
            ),
            author: $author,
            photos: $photos,
            viewsCount: $viewsCount,
            edited: str_contains($crawler->filter('.tgme_widget_message_meta')->text(), 'edited'),
            service: str_contains($crawler->filter('.tgme_widget_message')->attr('class'), 'service_message'),
        );

        return $this;
    }

    public function getResult(): ?Message
    {
        return $this->message;
    }
}
