<?php

declare(strict_types=1);

namespace App\Browsershot;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

/**
 * @method exists(string $file)
 * @method get(string $filename)
 * @method size(string $filename)
 * @method url(string $fileName)
 * @method delete(mixed $path)
 */
class FileManager
{
    public const DISKNAME = 'screenshot';

    protected FilesystemContract $storage;

    /**
     * FileManager constructor.
     */
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
            && $this->lastModified($file)->diffInMinutes(new Carbon()) > config('mugshot.defaults.cache');
    }

    public function listContentByLastModified(): Collection
    {
        $driver = $this->storage->getDriver();
        return Collection::make($driver->listContents('', false))
            ->sortBy('size', SORT_DESC)
            ->values();
    }

    public function __call($method, array $parameters)
    {
        return $this->storage->{$method}(...array_values($parameters));
    }
}
