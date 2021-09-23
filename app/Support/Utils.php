<?php

declare(strict_types=1);

namespace App\Support;

class Utils
{
    public static function sanitizeUrl(string $url): string
    {
        $urlArr = parse_url($url);
        return implode('', array_splice($urlArr, 1));
    }
}
