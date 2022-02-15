<?php

declare(strict_types=1);

namespace App\Browsershot;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use League\Flysystem\StorageAttributes;

/**
 * @method exists(string $file)
 * @method get(string $filename)
 * @method size(string $filename)
 * @method url(string $fileName)
 * @method delete(string|array $path)
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
        $fileName = $name . '.' . $fileExtension;

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

        return $this->storage->mimeType($file);
    }

    public function isExpired(string $file): bool
    {
        return $this->exists($file)
            && $this->lastModified($file)->diffInMinutes(new Carbon()) > config('mugshot.cache');
    }

    public function listContent(): Collection
    {
        $driver = $this->storage->getDriver();

        return Collection::make($driver->listContents('', false)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->toArray());
    }

    public function __call($method, array $parameters)
    {
        return $this->storage->{$method}(...array_values($parameters));
    }
}
