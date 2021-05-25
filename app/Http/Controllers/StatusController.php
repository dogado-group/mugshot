<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class StatusController extends Controller
{
    /**
     * Helper for Grafana or whatever to check system status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        return response()->json([
            'status' => 'OK'
        ]);
    }
}
