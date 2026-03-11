<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\FileInterface;
use App\Contracts\ResponsableInterface;
use App\Exceptions\GenericBrowsershotException;
use App\Http\Requests\ScreenshotRequest;
use App\Http\Resources\Screenshot as ScreenshotResource;
use Illuminate\Http\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ScreenshotController extends Controller implements ResponsableInterface
{
    public function __construct(protected BrowsershotService $service)
    {
    }

    public function generate(ScreenshotRequest $request): Response|ScreenshotResource
    {
        try {
            $content = $this->service->screenshot($request->url(), $request->parameters());
        } catch (ProcessFailedException $exception) {
            throw new GenericBrowsershotException("Generating a screenshot of {$request->url()} failed", $exception);
        }

        return $this->makeResponse($content, $request->responseType());
    }

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
            'Content-Disposition' => self::INLINE,
        ];

        return new Response($resource->getContent(), Response::HTTP_OK, $headers);
    }

    protected function responseDownload(FileInterface $resource): Response
    {
        $headers = [
            'Content-Type' => $resource->getMimeType(),
            'Content-Length' => $resource->getSize(),
            'Content-Disposition' => 'attachment; filename="'.$resource->getFilename().'"',
        ];

        return new Response($resource->getContent(), Response::HTTP_OK, $headers);
    }
}
