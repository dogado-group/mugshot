<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\FileInterface;
use App\Contracts\ResponableInterface;
use App\Exceptions\GenericBrowsershotException;
use App\Http\Requests\GeneratePdfRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PdfController extends Controller implements ResponableInterface
{
    public function __construct(protected BrowsershotService $service)
    {
    }

    public function generate(GeneratePdfRequest $request): Response
    {
        $response = $request->get('response', ResponableInterface::INLINE);
        $content = $request->get('content');
        $parameters = Collection::make();

        try {
            $content = $this->service->execute(BrowsershotService::TYPE_PDF, $content, $parameters);
        } catch (ProcessFailedException $exception) {
            throw new GenericBrowsershotException('Generating pdf failed', $exception);
        }

        return $this->makeResponse($content, $response);
    }

    protected function makeResponse(FileInterface $resource, string $type = ResponableInterface::INLINE): Response
    {
        return match ($type) {
            ResponableInterface::DOWNLOAD => $this->responseDownload($resource),
            default => $this->responseInline($resource)
        };
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
