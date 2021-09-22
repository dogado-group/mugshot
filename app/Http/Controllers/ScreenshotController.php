<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\ResponableInterface;
use App\Entity\Screenshot;
use App\Exceptions\GenericBrowsershotException;
use App\Http\Requests\GenerateScreenshotRequest;
use App\Http\Resources\Screenshot as ScreenshotResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ScreenshotController extends Controller implements ResponableInterface
{

    public function __construct(protected BrowsershotService $service)
    {
    }

    /**
     * @return ScreenshotResource|Response
     */
    public function capture(GenerateScreenshotRequest $request)
    {
        $response = $request->get('response', ResponableInterface::INLINE);
        $url = $request->get('url');
        $parameters = Collection::make(
            $request->only('width', 'height', 'fullPage', 'deviceScale', 'quality', 'delay', 'fileExtension')
        );

        try {
            $content = $this->service->execute(BrowsershotService::TYPE_SCREENSHOT, $url, $parameters);
        } catch (ProcessFailedException $exception) {
            throw new GenericBrowsershotException("Generating a screenshot of {$url} failed", $exception);
        }

        return $this->makeResponse($content, $response);
    }

    /**
     * @return ScreenshotResource|Response
     */
    protected function makeResponse(Screenshot $content, string $type = ResponableInterface::INLINE)
    {
        return match ($type) {
            ResponableInterface::INLINE => $this->responseInline($content),
            ResponableInterface::DOWNLOAD => $this->responseDownload($content),
            default => $this->responseJson($content),
        };
    }

    protected function responseJson(Screenshot $screenshot): ScreenshotResource
    {
        return new ScreenshotResource($screenshot);
    }

    protected function responseInline(Screenshot $screenshot): Response
    {
        $headers = [
            'Content-Type' => $screenshot->getMimeType(),
            'Content-Disposition' => self::INLINE
        ];

        return new Response($screenshot->getContent(), Response::HTTP_OK, $headers);
    }

    protected function responseDownload(Screenshot $screenshot)
    {
        $headers = [
            'Content-Type' => $screenshot->getMimeType(),
            'Content-Length' => $screenshot->getSize(),
            'Content-Disposition' => 'attachment; filename="'. $screenshot->getFilename() .'"'
        ];

        return new Response($screenshot->getContent(), Response::HTTP_OK, $headers);
    }
}
