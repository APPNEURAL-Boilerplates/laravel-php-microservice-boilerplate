<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureJsonRequestIsValid
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        if ($this->shouldValidateJsonBody($request)) {
            json_decode($request->getContent());

            if (json_last_error() !== JSON_ERROR_NONE) {
                return ApiResponse::error(
                    code: 'INVALID_JSON',
                    message: 'Malformed JSON request body.',
                    status: 400,
                    details: ['json_error' => json_last_error_msg()],
                );
            }
        }

        return $next($request);
    }

    private function shouldValidateJsonBody(Request $request): bool
    {
        return $request->isJson()
            && in_array($request->method(), ['POST', 'PUT', 'PATCH'], true)
            && trim($request->getContent()) !== '';
    }
}
