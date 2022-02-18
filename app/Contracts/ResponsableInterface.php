<?php

declare(strict_types=1);

namespace App\Contracts;

interface ResponsableInterface
{
    public const JSON = 'json';
    public const INLINE = 'inline';
    public const DOWNLOAD = 'download';

    public const ALLOWED_RESPONSES = [
        self::JSON,
        self::INLINE,
        self::DOWNLOAD,
    ];
}
