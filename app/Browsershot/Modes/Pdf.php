<?php

declare(strict_types=1);

namespace App\Browsershot\Modes;

use App\Browsershot\BrowsershotFactory;
use App\Entity\Pdf as PdfEntity;
use App\Support\Utils;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class Pdf extends BrowsershotFactory
{
    public HtmlString $content;

    public function execute(): PdfEntity
    {
        $hash = hash('sha256', Str::random());
        $filename = "{$hash}.pdf";

        [$publicUrl, $content] = $this->doPdf($filename, $hash);

        $size = $this->storageManager->size($filename);

        return (new PdfEntity())->fill([
            PdfEntity::ATTRIBUTE_ID => $hash,
            PdfEntity::ATTRIBUTE_URL => $publicUrl,
            PdfEntity::ATTRIBUTE_SIZE => $size,
            PdfEntity::ATTRIBUTE_MIMETYPE => 'application/pdf',
            PdfEntity::ATTRIBUTE_CONTENT => $content,
            PdfEntity::ATTRIBUTE_CREATED_AT => $this->storageManager->lastModified($filename)
        ]);
    }

    protected function doPdf(string $filename, string $hash): array
    {
        $tempFile = (new TemporaryDirectory())->create();
        $tempFilePath = $tempFile->path($filename);

        // do the puppeteer magic
        $this->callPuppeteer($tempFile->path($filename));

        $content = file_get_contents($tempFilePath);
        $publicUrl = $this->storageManager->save($tempFilePath, $hash, 'pdf');

        $tempFile->delete();
        return [$publicUrl, $content];
    }

    protected function callPuppeteer(string $tempFile): void
    {
        $this->browsershot->savePdf($tempFile);
    }

    public function setContent(string $content): self
    {
        $this->content = new HtmlString($content);
        $this->browsershot->setHtml($this->content->toHtml());

        return $this;
    }
}
