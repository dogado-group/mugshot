<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    #[Test]
    #[DataProvider('urlProvider')]
    public function itStripsTheSchemeFromAUrl(string $input, string $expected): void
    {
        $this->assertSame($expected, Utils::sanitizeUrl($input));
    }

    public static function urlProvider(): array
    {
        return [
            'http url' => ['http://example.com/path?foo=bar', 'example.com/path?foo=bar'],
            'https url' => ['https://example.com', 'example.com'],
            'url with port' => ['https://example.com:8080/path', 'example.com:8080/path'],
            'url with fragment' => ['https://example.com/page#section', 'example.com/page#section'],
            'url with auth' => ['https://user:pass@example.com', 'user:pass@example.com'],
            'empty string' => ['', ''],
        ];
    }
}
