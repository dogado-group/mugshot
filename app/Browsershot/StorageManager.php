<?php

declare(strict_types=1);

namespace App\Browsershot;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\StorageAttributes;

/**
 * @method exists(string $file)
 * @method get(string $filename)
 * @method size(string $filename)
 * @method url(string $fileName)
 * @method delete(string|array<int, string> $path)
 * @method readStream(string $path)
 */
class StorageManager
{
    protected FilesystemContract $storage;

    public const DISKNAME = 'screenshot';

    public function __construct()
    {
        $this->storage = Storage::disk(self::DISKNAME);
    }

    public function save(string $tempFilePath, string $name, string $fileExtension): string
    {
        $file = new File($tempFilePath);
        $fileName = $name.'.'.$fileExtension;

        $this->storage->putFileAs('', $file, $fileName);

        return $this->url($fileName);
    }

    public function lastModified(string $file): ?Carbon
    {
        if (!$this->exists($file)) {
            return null;
        }

        return Carbon::createFromTimestamp($this->storage->lastModified($file));
    }

    public function mimeType(string $file): ?string
    {
        if (!$this->exists($file)) {
            return null;
        }

        return $this->storage->mimeType($file) ?: null;
    }

    public function isExpired(string $file): bool
    {
        return $this->exists($file)
            && $this->lastModified($file)->diffInMinutes(new Carbon()) > config('mugshot.cache');
    }

    /** @return Collection<int, StorageAttributes> */
    public function listContent(): Collection
    {
        $driver = $this->storage->getDriver();

        return Collection::make($driver->listContents('', false)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->toArray());
    }

    /**
     * @param array<mixed> $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->storage->{$method}(...array_values($parameters));
    }
}
