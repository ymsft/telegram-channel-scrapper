<?php

namespace Ymsoft\TelegramChannelScrapper\Helper;

class StringHelper
{
    public static function convertStringNumberToInteger(string $number): int
    {
        $numericPart = (float) $number;
        $suffix = substr($number, -1);

        $multiplier = 1;
        switch ($suffix) {
            case 'K':
                $multiplier = 1000;
                break;
            case 'M':
                $multiplier = 1000000;
                break;
            case 'B':
                $multiplier = 1000000000;
                break;
        }

        $result = $numericPart * $multiplier;

        return (int) $result;
    }

    public static function replaceBRWithNativeLineBreakAndRemoveHtmlAttributes(string $text): string
    {
        return strip_tags(str_replace('<br>', "\n", $text));
    }
}
