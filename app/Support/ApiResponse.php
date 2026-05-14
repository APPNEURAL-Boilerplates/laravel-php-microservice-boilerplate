<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    /**
     * @param  array<string, mixed>  $meta
     * @param  array<string, string>  $headers
     */
    public static function success(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200,
        array $meta = [],
        array $headers = [],
    ): JsonResponse {
        $payload = [
            'ok' => true,
            'message' => $message,
            'data' => $data,
            'request_id' => self::requestId(),
        ];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status, $headers);
    }

    /**
     * @param  array<string, mixed>  $details
     * @param  array<string, string>  $headers
     */
    public static function error(
        string $code,
        string $message,
        int $status = 400,
        array $details = [],
        array $headers = [],
    ): JsonResponse {
        $payload = [
            'ok' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
            'request_id' => self::requestId(),
        ];

        if ($details !== []) {
            $payload['error']['details'] = $details;
        }

        return response()->json($payload, $status, $headers);
    }

    private static function requestId(): ?string
    {
        $request = request();

        return $request->headers->get('X-Request-Id')
            ?: $request->attributes->get('request_id');
    }
}
