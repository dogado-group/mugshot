<?php

declare(strict_types=1);

namespace App\Support;

final class Utils
{
    public static function sanitizeUrl(string $url): string
    {
        $parts = parse_url($url);

        if ($parts === false) {
            return '';
        }

        unset($parts['scheme']);

        return implode('', $parts);
    }
}
