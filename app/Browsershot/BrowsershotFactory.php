<?php

declare(strict_types=1);

namespace App\Browsershot;

use App\Contracts\FileInterface;
use Closure;
use Spatie\Browsershot\Browsershot;
use Spatie\TemporaryDirectory\TemporaryDirectory;

abstract class BrowsershotFactory
{
    protected int $width = 800;

    protected int $height = 600;

    protected string $fileExtension = FileInterface::IMAGE_JPEG;

    protected int $imageQuality = 70;

    protected int $deviceScale = 1;

    protected bool $fullPage = false;

    protected int $delay = 0;

    public function __construct(
        protected readonly Browsershot $browsershot,
        protected readonly StorageManager $storageManager,
    ) {
    }

    public function setSize(int $width, int $height): static
    {
        $this->width = $width;
        $this->height = $height;
        $this->browsershot->windowSize($width, $height);

        return $this;
    }

    public function setFileExtension(string $extension): static
    {
        $this->fileExtension = $extension === 'jpg' ? FileInterface::IMAGE_JPEG : $extension;
        $this->browsershot->setScreenshotType($this->fileExtension);

        return $this;
    }

    public function setQuality(int $quality): static
    {
        if ($this->fileExtension === FileInterface::IMAGE_PNG) {
            return $this;
        }

        $this->imageQuality = $quality;
        $this->browsershot->setScreenshotType($this->fileExtension, $quality);

        return $this;
    }

    public function setDeviceScale(int $deviceScale): static
    {
        $this->deviceScale = $deviceScale;
        $this->browsershot->deviceScaleFactor($deviceScale);

        return $this;
    }

    public function setFullPage(bool $fullPage): static
    {
        $this->fullPage = $fullPage;
        $this->browsershot->setOption('fullPage', $fullPage);

        return $this;
    }

    public function setDelay(int $seconds): static
    {
        $this->delay = min($seconds, (int) config('mugshot.validation.maxDelay'));
        $this->browsershot->setDelay($this->delay * 1000);

        return $this;
    }

    /**
     * Executes the given callback with a managed temporary file path,
     * guaranteeing cleanup even when an exception is thrown.
     */
    protected function withTempFile(string $filename, Closure $callback): mixed
    {
        $tempDir = (new TemporaryDirectory())->create();

        try {
            return $callback($tempDir->path($filename));
        } finally {
            $tempDir->delete();
        }
    }

    abstract protected function identifier(): string;

    abstract public function execute(): FileInterface;
}
