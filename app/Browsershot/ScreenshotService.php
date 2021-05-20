<?php

declare(strict_types=1);

namespace App\Browsershot;

use App\Contracts\FileInterface;
use App\Entity\Screenshot;
use App\Browsershot\Modes\Browser;
use Illuminate\Support\Collection;
use Spatie\Browsershot\Browsershot;

class ScreenshotService
{
    public function __construct(protected Browsershot $browsershot)
    {
    }

    public function execute(string $type, string $url, Collection $parameters): ?FileInterface
    {
        if ($type === 'screenshot') {
            return $this->screenshot($url, $parameters);
        }

        // TODO: if needed we can implement this to
        // if ($type === 'html') {
        //     return $this->html($content, $parameters);
        // }

        throw new \RuntimeException('Type method "' . $type . '" not implemented');
    }

    protected function screenshot(string $url, Collection $parameters): Screenshot
    {
        /** @var Browser $factory */
        $factory = app(Browser::class);
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
}
