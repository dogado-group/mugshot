<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\FileInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;

class Pdf implements FileInterface
{
    public const ATTRIBUTE_ID = 'id';
    public const ATTRIBUTE_URL = 'url';
    public const ATTRIBUTE_SIZE = 'size';
    public const ATTRIBUTE_MIMETYPE = 'mimetype';
    public const ATTRIBUTE_CONTENT = 'content';
    public const ATTRIBUTE_CREATED_AT = 'createdAt';

    public ?string $id = null;

    public ?string $url = null;

    public ?int $size = null;

    public ?string $mimeType = null;

    public ?string $content = null;

    public ?Carbon $createdAt = null;

    public function fill(array $parameters): self
    {
        $this->setId(Arr::get($parameters, self::ATTRIBUTE_ID));
        $this->setUrl(Arr::get($parameters, self::ATTRIBUTE_URL));
        $this->setSize(Arr::get($parameters, self::ATTRIBUTE_SIZE));
        $this->setMimeType(Arr::get($parameters, self::ATTRIBUTE_MIMETYPE));
        $this->setContent(Arr::get($parameters, self::ATTRIBUTE_CONTENT));
        $this->setCreatedAt(Arr::get($parameters, self::ATTRIBUTE_CREATED_AT));

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getExtension(): ?string
    {
        return MimeTypes::getDefault()->getExtensions($this->getMimeType())[0] ?? null;
    }

    public function getFilename(): string
    {
        return "{$this->getId()}.{$this->getExtension()}";
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
