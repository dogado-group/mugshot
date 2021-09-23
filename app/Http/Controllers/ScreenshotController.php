<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\FileInterface;
use App\Contracts\ResponableInterface;
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
    protected function makeResponse(FileInterface $resource, string $type = ResponableInterface::INLINE)
    {
        return match ($type) {
            ResponableInterface::INLINE => $this->responseInline($resource),
            ResponableInterface::DOWNLOAD => $this->responseDownload($resource),
            default => $this->responseJson($resource),
        };
    }

    protected function responseJson(FileInterface $resource): ScreenshotResource
    {
        return new ScreenshotResource($resource);
    }

    protected function responseInline(FileInterface $resource): Response
    {
        $headers = [
            'Content-Type' => $resource->getMimeType(),
            'Content-Disposition' => self::INLINE
        ];

        return new Response($resource->getContent(), Response::HTTP_OK, $headers);
    }

    protected function responseDownload(FileInterface $resource)
    {
        $headers = [
            'Content-Type' => $resource->getMimeType(),
            'Content-Length' => $resource->getSize(),
            'Content-Disposition' => 'attachment; filename="'. $resource->getFilename() .'"'
        ];

        return new Response($resource->getContent(), Response::HTTP_OK, $headers);
    }
}
