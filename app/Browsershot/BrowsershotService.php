<?php

declare(strict_types=1);

namespace App\Browsershot;

use App\Browsershot\Modes\Pdf as PdfFactory;
use App\Browsershot\Modes\Screenshot as ScreenshotFactory;
use App\Contracts\FileInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class BrowsershotService
{
    public const TYPE_SCREENSHOT = 'screenshot';
    public const TYPE_PDF = 'pdf';

    public function __construct(
        protected Browsershot $browsershot,
        protected PdfFactory $pdfFactory,
        protected ScreenshotFactory $screenshotFactory
    ) {}

    public function execute(string $type, string $input, Collection $parameters): ?FileInterface
    {
        $type = Str::lower($type);

        return match ($type) {
            self::TYPE_SCREENSHOT => $this->screenshot($input, $parameters),
            self::TYPE_PDF => $this->pdf($input, $parameters),
            default => throw new \RuntimeException('Type method "' . $type . '" not implemented'),
        };
    }

    protected function screenshot(string $url, Collection $parameters): FileInterface
    {
        $factory = $this->screenshotFactory;
        $factory->setUrl($url);

        $factory->setSize(
            $parameters->get('width', config('mugshot.defaults.width')),
            $parameters->get('height', config('mugshot.defaults.height'))
        );

        $factory->setFileExtension($parameters->get('fileExtension', config('mugshot.defaults.fileExtension')));
        $factory->setQuality($parameters->get('quality', config('mugshot.defaults.quality')));
        $factory->setDeviceScale($parameters->get('deviceScale', config('mugshot.defaults.deviceScale')));

        if ($parameters->has('delay')) {
            $factory->setDelay($parameters->get('delay'));
        }
        if ($parameters->has('fullPage')) {
            $factory->setFullPage($parameters->get('fullPage'));
        }

        return $factory->execute();
    }

    protected function pdf(string $content, Collection $parameters)
    {
        $factory = $this->pdfFactory;
        $factory->setContent($content);

        return $factory->execute();
    }
}
