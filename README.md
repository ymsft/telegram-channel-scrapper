[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://vshymanskyy.github.io/StandWithUkraine/)
# Public telegram channels scrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ymsoft/telegram-channel-scrapper.svg?style=for-the-badge)](https://packagist.org/packages/ymsoft/telegram-channel-scrapper)
[![License](https://img.shields.io/github/license/yarmat/telegram-channel-scrapper?style=for-the-badge)](https://github.com/yarmat/telegram-channel-scrapper/blob/master/LICENSE.md)

[![PHP from Packagist](https://img.shields.io/packagist/php-v/ymsoft/telegram-channel-scrapper?style=flat-square)](https://packagist.org/packages/ymsoft/telegram-channel-scrapper)
[![PHP Composer](https://github.com/yarmat/telegram-channel-scrapper/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/yarmat/telegram-channel-scrapper/actions/workflows/php.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ymsoft/telegram-channel-scrapper.svg?style=flat-square)](https://packagist.org/packages/ymsoft/telegram-channel-scrapper)

This package is intended for those who need to efficiently scrape a public telegram channel.
This is done quite easily:
```php
use Ymsoft\TelegramChannelScrapper\TelegramCS;

$scrapper = new TelegramCS('channel_name');

/** @var \Ymsoft\TelegramChannelScrapper\Entity\Channel $channel */
$channel = $scrapper->getChannel();

/** 
 * By default, you will scrap the latest 20 messages.
 * @var \Illuminate\Support\Collection<\Ymsoft\TelegramChannelScrapper\Entity\Message\Message> $messages 
 */
$messages = $scrapper->getMessages();
$messages->count(); // will return 20

// In order to download 20 more messages you need
$scrapper->loadPrevMessages();

$scrapper->getMessages()->count() // will return 40

// You can download old messages endlessly until you download everything.
```

Since this package uses [illuminate/collections](https://github.com/illuminate/collections) you can use all [the methods described here](https://laravel.com/docs/10.x/collections#available-methods) to work with a collection of messages. 

```php
/** 
 * @var \Illuminate\Support\Collection<\Ymsoft\TelegramChannelScrapper\Entity\Message\Message> $messages 
 */
$messages = $service->getMessage();
$messages->all();
$messages->count();
$messages->toArray();
$messages->firstWhere('id', 1);
$messages->last();
$messages->first();

// and much more https://laravel.com/docs/10.x/collections#available-methods
```

### Channel
View all entity attributes [Channel](src/Entity/Channel.php). 

### Message
View all entity attributes [Message](src/Entity/Message/Message.php).

## Installation

You can install the package via composer:

``` bash
composer require ymsoft/telegram-channel-scrapper
```

## Advanced usage

### Scrap special message by concrete id
If you know the message ID you can get it:
```php
use Ymsoft\TelegramChannelScrapper\TelegramCS;

$service = new TelegramCS('channel_name');

/** @var \Ymsoft\TelegramChannelScrapper\Entity\Message\Message $message */
$message = $service->getMessageById(1);
```

### Http client (CUSTOM HEADERS & PROXY)
If you want to add your own headers or make requests through a proxy, you can pass your http client instance as the second parameter:
```php
use Ymsoft\TelegramChannelScrapper\TelegramCS;

$client = new \GuzzleHttp\Client([
    'headers' => [
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept' => 'text/html',
    ],
    'proxy' => 'http://localhost:8125',
]);

$service = new TelegramCS('channel_name', $client);
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](https://github.com/yarmat/telegram-channel-scrapper/releases) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to the TelegramCS Package!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.