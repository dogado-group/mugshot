<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\FileInterface;
use App\Contracts\ResponsableInterface;
use App\Exceptions\GenericBrowsershotException;
use App\Http\Requests\GenerateScreenshotRequest;
use App\Http\Resources\Screenshot as ScreenshotResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ScreenshotController extends Controller implements ResponsableInterface
{
    public function __construct(protected BrowsershotService $service)
    {
    }

    /**
     * @return ScreenshotResource|Response
     */
    public function generate(GenerateScreenshotRequest $request): Response|ScreenshotResource
    {
        $response = $request->get('response', ResponsableInterface::INLINE);
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
    protected function makeResponse(FileInterface $resource, string $type = ResponsableInterface::INLINE): Response|ScreenshotResource
    {
        return match ($type) {
            ResponsableInterface::INLINE => $this->responseInline($resource),
            ResponsableInterface::DOWNLOAD => $this->responseDownload($resource),
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

    protected function responseDownload(FileInterface $resource): Response
    {
        $headers = [
            'Content-Type' => $resource->getMimeType(),
            'Content-Length' => $resource->getSize(),
            'Content-Disposition' => 'attachment; filename="'. $resource->getFilename() .'"'
        ];

        return new Response($resource->getContent(), Response::HTTP_OK, $headers);
    }
}
