<?php

declare(strict_types=1);

namespace App\Browsershot;

use App\Browsershot\Modes\Pdf as PdfFactory;
use App\Browsershot\Modes\Screenshot as ScreenshotFactory;
use App\DataTransferObject\PdfData;
use App\DataTransferObject\ScreenshotData;
use Illuminate\Support\Collection;

class BrowsershotService
{
    public function __construct(
        protected PdfFactory $pdfFactory,
        protected ScreenshotFactory $screenshotFactory,
    ) {
    }

    /** @param Collection<string, mixed> $parameters */
    public function screenshot(string $url, Collection $parameters): ScreenshotData
    {
        $this->screenshotFactory
            ->setUrl($url)
            ->setSize(
                (int) $parameters->get('width', config('mugshot.defaults.width')),
                (int) $parameters->get('height', config('mugshot.defaults.height'))
            )
            ->setFileExtension($parameters->get('fileExtension', config('mugshot.defaults.fileExtension')))
            ->setQuality((int) $parameters->get('quality', config('mugshot.defaults.quality')))
            ->setDeviceScale((int) $parameters->get('deviceScale', config('mugshot.defaults.deviceScale')));

        if ($parameters->has('delay')) {
            $this->screenshotFactory->setDelay((int) $parameters->get('delay'));
        }

        if ($parameters->has('fullPage')) {
            $this->screenshotFactory->setFullPage((bool) $parameters->get('fullPage'));
        }

        return $this->screenshotFactory->execute();
    }

    public function pdf(string $content): PdfData
    {
        return $this->pdfFactory->setContent($content)->execute();
    }
}
