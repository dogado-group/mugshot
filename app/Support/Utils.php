<?php

declare(strict_types=1);

namespace App\Support;

final class Utils
{
    public static function sanitizeUrl(string $url): string
    {
        $parts = parse_url($url);

        if ($parts === false || $parts === []) {
            return '';
        }

        $result = '';

        if (isset($parts['user'])) {
            $result .= $parts['user'];
            if (isset($parts['pass'])) {
                $result .= ':'.$parts['pass'];
            }
            $result .= '@';
        }

        $result .= $parts['host'] ?? '';

        if (isset($parts['port'])) {
            $result .= ':'.$parts['port'];
        }

        $result .= $parts['path'] ?? '';

        if (isset($parts['query'])) {
            $result .= '?'.$parts['query'];
        }

        if (isset($parts['fragment'])) {
            $result .= '#'.$parts['fragment'];
        }

        return $result;
    }
}
