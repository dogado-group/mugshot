<?php

declare(strict_types=1);

namespace App\Browsershot\Modes;

use App\Browsershot\BrowsershotFactory;
use App\Contracts\CapturableInterface;
use App\DataTransferObject\ScreenshotData;
use App\Support\Utils;
use Illuminate\Support\Str;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;
use RuntimeException;
use Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot;
use Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists;

class Screenshot extends BrowsershotFactory implements CapturableInterface
{
    private string $url = 'https://localhost';

    /**
     * @throws CouldNotTakeBrowsershot
     * @throws PathAlreadyExists
     */
    public function execute(): ScreenshotData
    {
        $hash = $this->identifier();
        $filename = "{$hash}.{$this->fileExtension}";

        if ($this->needsCapture($filename)) {
            [$mimeType, $publicUrl, $content] = $this->capture($filename, $hash);
        } else {
            $publicUrl = $this->storageManager->url($filename);
            $content = $this->storageManager->get($filename);
            $mimeType = $this->storageManager->mimeType($filename);
        }

        return ScreenshotData::fillFromArray([
            ScreenshotData::ATTRIBUTE_ID => $hash,
            ScreenshotData::ATTRIBUTE_URL => $publicUrl,
            ScreenshotData::ATTRIBUTE_SIZE => $this->storageManager->size($filename),
            ScreenshotData::ATTRIBUTE_MIMETYPE => $mimeType,
            ScreenshotData::ATTRIBUTE_CONTENT => $content,
            ScreenshotData::ATTRIBUTE_CREATED_AT => $this->storageManager->lastModified($filename),
        ]);
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        $this->browsershot->setUrl($url);

        return $this;
    }

    protected function identifier(): string
    {
        return hash('sha256', Str::slug(Utils::sanitizeUrl($this->url), '_'));
    }

    private function needsCapture(string $filename): bool
    {
        return !$this->storageManager->exists($filename) || $this->storageManager->isExpired($filename);
    }

    /**
     * @return array{string|null, string, string}
     *
     * @throws CouldNotTakeBrowsershot
     * @throws PathAlreadyExists
     */
    private function capture(string $filename, string $hash): array
    {
        return $this->withTempFile($filename, function (string $tempFilePath) use ($hash): array {
            $this->browsershot->ignoreHttpsErrors()->save($tempFilePath);

            $mimeType = (new GeneratedExtensionToMimeTypeMap())->lookupMimeType($this->fileExtension);
            $publicUrl = $this->storageManager->save($tempFilePath, $hash, $this->fileExtension);
            $content = file_get_contents($tempFilePath);

            if ($content === false) {
                throw new RuntimeException("Failed to read captured screenshot: {$tempFilePath}");
            }

            return [$mimeType, $publicUrl, $content];
        });
    }
}
