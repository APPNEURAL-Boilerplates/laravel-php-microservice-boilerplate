<?php

use App\Exceptions\AppException;
use App\Http\Middleware\EnsureJsonRequestIsValid;
use App\Http\Middleware\RequestIdMiddleware;
use App\Support\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(RequestIdMiddleware::class);
        $middleware->append(EnsureJsonRequestIsValid::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $shouldReturnJson = static fn (Request $request): bool => $request->is('api/*') || $request->expectsJson();

        $exceptions->render(function (AppException $exception, Request $request) use ($shouldReturnJson) {
            if (! $shouldReturnJson($request)) {
                return null;
            }

            return ApiResponse::error(
                code: $exception->errorCode(),
                message: $exception->getMessage(),
                status: $exception->status(),
                details: $exception->details(),
            );
        });

        $exceptions->render(function (ValidationException $exception, Request $request) use ($shouldReturnJson) {
            if (! $shouldReturnJson($request)) {
                return null;
            }

            return ApiResponse::error(
                code: 'VALIDATION_FAILED',
                message: 'The given data was invalid.',
                status: 422,
                details: $exception->errors(),
            );
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) use ($shouldReturnJson) {
            if (! $shouldReturnJson($request)) {
                return null;
            }

            return ApiResponse::error(
                code: 'NOT_FOUND',
                message: 'The requested resource was not found.',
                status: 404,
            );
        });

        $exceptions->render(function (MethodNotAllowedHttpException $exception, Request $request) use ($shouldReturnJson) {
            if (! $shouldReturnJson($request)) {
                return null;
            }

            return ApiResponse::error(
                code: 'METHOD_NOT_ALLOWED',
                message: 'This HTTP method is not allowed for the requested resource.',
                status: 405,
                details: ['allowed_methods' => $exception->getHeaders()['Allow'] ?? null],
            );
        });

        $exceptions->render(function (Throwable $exception, Request $request) use ($shouldReturnJson) {
            if (! $shouldReturnJson($request)) {
                return null;
            }

            $message = config('app.debug')
                ? $exception->getMessage()
                : 'An unexpected server error occurred.';

            return ApiResponse::error(
                code: 'INTERNAL_SERVER_ERROR',
                message: $message,
                status: 500,
            );
        });
    })
    ->create();
