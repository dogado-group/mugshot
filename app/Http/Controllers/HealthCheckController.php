<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\ResultStores\ResultStore;

class HealthCheckController extends Controller
{
    public function __invoke(Request $request, ResultStore $resultStore): Response
    {
        if ($request->has('fresh') || config('health.oh_dear_endpoint.always_send_fresh_results')) {
            Artisan::call(RunHealthChecksCommand::class);
        }

        $checkResults = $resultStore->latestResults();
        $status = $checkResults->allChecksOk()
            ? Response::HTTP_OK
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $headers = [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
        ];
        return new Response($checkResults->allChecksOk(), $status, $headers);
    }
}
