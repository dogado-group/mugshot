<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\GenericBrowsershotException;
use App\Http\Requests\CaptureScreenshotRequest;
use App\Http\Resources\Screenshot as ScreenshotResource;
use App\Entity\Screenshot;
use App\Browsershot\ScreenshotService;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ScreenshotController extends Controller
{
    public function __construct(protected ScreenshotService $screenshotService)
    {
    }

    /**
     * @return ScreenshotResource|Response
     */
    public function capture(CaptureScreenshotRequest $request)
    {
        $response = $request->get('response', 'inline');
        $url = $request->get('url');
        $parameters = Collection::make(
            $request->only(['width', 'height', 'fullPage', 'deviceScale', 'quality', 'delay', 'fileExtension'])
        );

        try {
            $screenshot = $this->screenshotService->execute('screenshot', $url, $parameters);
        } catch (ProcessFailedException $exception) {
            throw new GenericBrowsershotException("Generating a screenshot of {$url} failed", $exception);
        }

        return $this->makeResponse($screenshot, $response);
    }

    /**
     * @return ScreenshotResource|Response
     */
    protected function makeResponse(Screenshot $content, string $type = 'inline')
    {
        return match ($type) {
            'inline' => $this->responseInline($content),
            'download' => $this->responseDownload($content),
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
            'Content-Disposition' => 'inline'
        ];

        return new Response($screenshot->getContent(), Response::HTTP_OK, $headers);
    }

    protected function responseDownload(Screenshot $screenshot)
    {
        $filename = "{$screenshot->getId()}.{$screenshot->getExtension()}";

        $headers = [
            'Content-Type' => $screenshot->getMimeType(),
            'Content-Length' => $screenshot->getSize(),
            'Content-Disposition' => 'attachment; filename="'. $filename .'"'
        ];

        return new Response($screenshot->getContent(), Response::HTTP_OK, $headers);
    }
}
