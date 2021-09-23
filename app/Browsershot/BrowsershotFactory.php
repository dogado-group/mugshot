<?php

declare(strict_types=1);

namespace App\Browsershot;

use App\Contracts\FileInterface;
use Spatie\Browsershot\Browsershot;

abstract class BrowsershotFactory
{
    protected int $width = 800;

    protected int $height = 600;

    protected string $fileExtension = FileInterface::IMAGE_JPEG;

    protected int $imageQuality = 70;

    protected int $deviceScale = 1;

    protected bool $fullPage = false;

    protected int $delay = 0;

    /**
     * @param Browsershot $browsershot
     * @param StorageManager $storageManager
     */
    public function __construct(protected Browsershot $browsershot, protected StorageManager $storageManager)
    {
    }

    public function setSize(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;
        $this->browsershot->windowSize($width, $height);
        return $this;
    }

    public function setFileExtension(string $extension = FileInterface::IMAGE_PNG): self
    {
        if ($extension === 'jpg') {
            $extension = FileInterface::IMAGE_JPEG;
        }

        $this->fileExtension = $extension;
        $this->browsershot->setScreenshotType($extension);
        return $this;
    }

    public function setQuality(int $quality = 70): self
    {
        if ($this->fileExtension === FileInterface::IMAGE_PNG) {
            return $this;
        }

        $this->imageQuality = $quality;
        $this->browsershot->setScreenshotType($this->fileExtension, $quality);
        return $this;
    }

    public function setDeviceScale(int $deviceScale): self
    {
        $this->deviceScale = $deviceScale;
        $this->browsershot->deviceScaleFactor($deviceScale);
        return $this;
    }

    public function setFullPage(bool $fullPage): self
    {
        $this->fullPage = $fullPage;
        $this->browsershot->setOption('fullPage', $fullPage);
        return $this;
    }

    public function setDelay(int $seconds): self
    {
        if ($seconds > 10) {
            $seconds = 10;
        }

        $this->delay = $seconds;
        $this->browsershot->setDelay($seconds * 1000);
        return $this;
    }

    abstract protected function execute();
}
