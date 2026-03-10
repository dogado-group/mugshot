<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\FileInterface;
use App\Contracts\ResponsableInterface;
use App\Exceptions\GenericBrowsershotException;
use App\Http\Requests\PdfRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PdfController extends Controller implements ResponsableInterface
{
    public function __construct(protected BrowsershotService $service)
    {
    }

    public function generate(PdfRequest $request): Response
    {
        try {
            $content = $this->service->execute(BrowsershotService::TYPE_PDF, $request->content(), Collection::make());
        } catch (ProcessFailedException $exception) {
            throw new GenericBrowsershotException('Generating pdf failed', $exception);
        }

        return $this->makeResponse($content, $request->responseType());
    }

    protected function makeResponse(FileInterface $resource, string $type = ResponsableInterface::INLINE): Response
    {
        return match ($type) {
            ResponsableInterface::DOWNLOAD => $this->responseDownload($resource),
            default => $this->responseInline($resource),
        };
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
