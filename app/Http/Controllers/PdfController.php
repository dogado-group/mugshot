<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Browsershot\BrowsershotService;
use App\Contracts\ResponableInterface;
use App\Entity\Pdf;
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

    public function generate(GeneratePdfRequest $request)
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

    protected function makeResponse(Pdf $content, string $type = 'inline')
    {
        $headers = [
            'Content-Type' => $content->getMimeType(),
            'Content-Disposition' => self::INLINE
        ];

        return new Response($content->getContent(), Response::HTTP_OK, $headers);
    }
}
