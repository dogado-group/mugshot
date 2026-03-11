<?php

declare(strict_types=1);

namespace App\Browsershot\Modes;

use App\Browsershot\BrowsershotFactory;
use App\DataTransferObject\PdfData;
use Illuminate\Support\Str;
use RuntimeException;

class Pdf extends BrowsershotFactory
{
    private string $htmlContent = '';

    public function execute(): PdfData
    {
        $hash = hash('sha256', Str::random());
        $filename = "{$hash}.pdf";

        [$publicUrl, $content] = $this->capture($filename, $hash);

        return PdfData::fillFromArray([
            PdfData::ATTRIBUTE_ID => $hash,
            PdfData::ATTRIBUTE_URL => $publicUrl,
            PdfData::ATTRIBUTE_SIZE => $this->storageManager->size($filename),
            PdfData::ATTRIBUTE_MIMETYPE => 'application/pdf',
            PdfData::ATTRIBUTE_CONTENT => $content,
            PdfData::ATTRIBUTE_CREATED_AT => $this->storageManager->lastModified($filename),
        ]);
    }

    public function setContent(string $content): static
    {
        $this->htmlContent = $content;
        $this->browsershot->setHtml($content);

        return $this;
    }

    /**
     * @return array{string, string}
     *
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    private function capture(string $filename, string $hash): array
    {
        return $this->withTempFile($filename, function (string $tempFilePath) use ($hash): array {
            $this->browsershot->savePdf($tempFilePath);

            $content = file_get_contents($tempFilePath);

            if ($content === false) {
                throw new RuntimeException("Failed to read generated PDF: {$tempFilePath}");
            }

            $publicUrl = $this->storageManager->save($tempFilePath, $hash, 'pdf');

            return [$publicUrl, $content];
        });
    }
}
