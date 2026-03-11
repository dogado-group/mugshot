<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Contracts\FileInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;

class ScreenshotData implements FileInterface
{
    public const ATTRIBUTE_ID = 'id';
    public const ATTRIBUTE_URL = 'url';
    public const ATTRIBUTE_SIZE = 'size';
    public const ATTRIBUTE_MIMETYPE = 'mimetype';
    public const ATTRIBUTE_CONTENT = 'content';
    public const ATTRIBUTE_CREATED_AT = 'createdAt';

    public function __construct(
        public readonly string $id,
        public readonly string $url,
        public readonly int $size,
        public readonly string $mimeType,
        public readonly string $content,
        public readonly Carbon $createdAt,
    ) {
    }

    /** @param array<string, mixed> $parameters */
    public static function fillFromArray(array $parameters): self
    {
        return new self(
            id: Arr::get($parameters, self::ATTRIBUTE_ID),
            url: Arr::get($parameters, self::ATTRIBUTE_URL),
            size: Arr::get($parameters, self::ATTRIBUTE_SIZE),
            mimeType: Arr::get($parameters, self::ATTRIBUTE_MIMETYPE),
            content: Arr::get($parameters, self::ATTRIBUTE_CONTENT),
            createdAt: Arr::get($parameters, self::ATTRIBUTE_CREATED_AT),
        );
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getExtension(): ?string
    {
        return MimeTypes::getDefault()->getExtensions($this->mimeType)[0] ?? null;
    }

    public function getFilename(): string
    {
        return "{$this->id}.{$this->getExtension()}";
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
