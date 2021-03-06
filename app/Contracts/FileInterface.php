<?php

declare(strict_types=1);

namespace App\Contracts;

interface FileInterface
{
    public const IMAGE_JPEG = 'jpeg';
    public const IMAGE_PNG = 'png';

    public const ALLOWED_SCREENSHOT_EXTENSIONS = [
        self::IMAGE_JPEG,
        self::IMAGE_PNG
    ];
    public function getMimeType(): string;

    public function getSize(): int;

    public function getFilename(): string;

    public function getContent(): string;
}
