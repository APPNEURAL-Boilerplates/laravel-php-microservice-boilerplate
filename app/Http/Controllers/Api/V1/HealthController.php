<?php

namespace App\Http\Controllers\Api\V1;

use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

final class HealthController
{
    public function health(): JsonResponse
    {
        return ApiResponse::success([
            'service' => config('microservice.name'),
            'status' => 'healthy',
            'uptime_seconds' => (int) floor(microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : microtime(true))),
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function ready(): JsonResponse
    {
        $checks = [
            'storage_writable' => is_writable(storage_path()),
            'cache_path_writable' => is_writable(storage_path('framework/cache')),
        ];

        $ready = ! in_array(false, $checks, true);

        return ApiResponse::success([
            'service' => config('microservice.name'),
            'status' => $ready ? 'ready' : 'not_ready',
            'checks' => $checks,
            'timestamp' => now()->toISOString(),
        ], status: $ready ? 200 : 503);
    }
}
