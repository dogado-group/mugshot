<?php

declare(strict_types=1);

namespace App\Browsershot\Modes;

use App\Contracts\CapturableInterface;
use App\Entity\Screenshot;
use App\Browsershot\BrowsershotFactory;
use Illuminate\Support\Str;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class Browser extends BrowsershotFactory implements CapturableInterface
{
    /** @var string */
    public string $url = 'https://localhost';

    /**
     * @return Screenshot
     * @throws \Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    public function execute(): Screenshot
    {
        $hash = hash('sha256', Str::slug($this->sanitizeUrl($this->url), '_'));
        $filename = "{$hash}.{$this->fileExtension}";

        if ($this->checkFile($filename)) {
            $tempFile = (new TemporaryDirectory())->create();
            $tempFilePath = $tempFile->path($filename);

            // do the puppeteer magic
            $this->callPuppeteer($tempFile->path($filename));

            $mimeType = (new GeneratedExtensionToMimeTypeMap())->lookupMimeType($this->fileExtension);
            $publicUrl = $this->fileManager->save($tempFilePath, $hash, $this->fileExtension);

            $content = file_get_contents($tempFilePath);

            $tempFile->delete();
        } else {
            $publicUrl = $this->fileManager->url($filename);
            $content = $this->fileManager->get($filename);
            $mimeType = $this->fileManager->mimeType($filename);
        }

        $size = $this->fileManager->size($filename);

        return (new Screenshot)->fill([
            Screenshot::ATTRIBUTE_ID => $hash,
            Screenshot::ATTRIBUTE_URL => $publicUrl,
            Screenshot::ATTRIBUTE_SIZE => $size,
            Screenshot::ATTRIBUTE_MIMETYPE => $mimeType,
            Screenshot::ATTRIBUTE_CONTENT => $content,
            Screenshot::ATTRIBUTE_CREATED_AT => $this->fileManager->lastModified($filename)
        ]);
    }

    /**
     * @throws \Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot
     */
    protected function callPuppeteer(string $tempFile): void
    {
        $this->browsershot
            // make Puppeteer hopefully usable for every page
            ->ignoreHttpsErrors()
            ->dismissDialogs()
            ->waitUntilNetworkIdle()
            ->timeout(30)
            ->save($tempFile);
    }

    /**
     * @param string $url
     * @return string
     */
    protected function sanitizeUrl(string $url): string
    {
        $urlArr = parse_url($url);
        return implode('', array_splice($urlArr, 1));
    }

    /**
     * @param string $url
     * @return Browser
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        $this->browsershot->setUrl($url);
        return $this;
    }

    public function checkFile(string $filename): bool
    {
        return $this->fileManager->isExpired($filename) || !$this->fileManager->exists($filename);
    }
}
