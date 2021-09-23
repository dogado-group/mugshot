<?php

declare(strict_types=1);

namespace App\Browsershot\Modes;

use App\Browsershot\BrowsershotFactory;
use App\Contracts\CapturableInterface;
use App\Entity\Screenshot as ScreenshotEntity;
use App\Support\Utils;
use Illuminate\Support\Str;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class Screenshot extends BrowsershotFactory implements CapturableInterface
{
    public string $url = 'https://localhost';

    /**
     * @throws \Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    public function execute(): ScreenshotEntity
    {
        $hash = hash('sha256', Str::slug(Utils::sanitizeUrl($this->url), '_'));
        $filename = "{$hash}.{$this->fileExtension}";

        if ($this->checkFile($filename)) {
            [$mimeType, $publicUrl, $content] = $this->doScreenshot($filename, $hash);
        } else {
            $publicUrl = $this->storageManager->url($filename);
            $content = $this->storageManager->get($filename);
            $mimeType = $this->storageManager->mimeType($filename);
        }

        $size = $this->storageManager->size($filename);

        return (new ScreenshotEntity)->fill([
            ScreenshotEntity::ATTRIBUTE_ID => $hash,
            ScreenshotEntity::ATTRIBUTE_URL => $publicUrl,
            ScreenshotEntity::ATTRIBUTE_SIZE => $size,
            ScreenshotEntity::ATTRIBUTE_MIMETYPE => $mimeType,
            ScreenshotEntity::ATTRIBUTE_CONTENT => $content,
            ScreenshotEntity::ATTRIBUTE_CREATED_AT => $this->storageManager->lastModified($filename)
        ]);
    }

    /**
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     * @throws \Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot
     */
    protected function doScreenshot(string $filename, string $hash): array
    {
        $tempFile = (new TemporaryDirectory())->create();
        $tempFilePath = $tempFile->path($filename);

        // do the puppeteer magic
        $this->callPuppeteer($tempFile->path($filename));

        $mimeType = (new GeneratedExtensionToMimeTypeMap())->lookupMimeType($this->fileExtension);
        $publicUrl = $this->storageManager->save($tempFilePath, $hash, $this->fileExtension);

        $content = file_get_contents($tempFilePath);

        $tempFile->delete();
        return [$mimeType, $publicUrl, $content];
    }

    /**
     * @throws \Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot
     */
    protected function callPuppeteer(string $tempFile): void
    {
        $this->browsershot
            ->ignoreHttpsErrors()
            ->save($tempFile);
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        $this->browsershot->setUrl($url);
        return $this;
    }

    protected function checkFile(string $filename): bool
    {
        return $this->storageManager->isExpired($filename) || !$this->storageManager->exists($filename);
    }
}
