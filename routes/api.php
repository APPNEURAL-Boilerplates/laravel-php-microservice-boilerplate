<?php

use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\ItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function (): void {
        Route::get('/health', [HealthController::class, 'health'])->name('health');
        Route::get('/ready', [HealthController::class, 'ready'])->name('ready');

        Route::apiResource('items', ItemController::class)
            ->only(['index', 'store', 'show']);
    });
