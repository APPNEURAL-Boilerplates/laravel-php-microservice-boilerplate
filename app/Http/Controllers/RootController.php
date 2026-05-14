<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

final class RootController
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success([
            'service' => config('microservice.name'),
            'version' => config('microservice.version'),
            'environment' => app()->environment(),
            'api' => '/api/v1',
            'health' => '/api/v1/health',
            'ready' => '/api/v1/ready',
        ]);
    }
}
