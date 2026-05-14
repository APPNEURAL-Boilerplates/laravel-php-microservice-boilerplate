<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class HttpServiceClient
{
    public function get(string $url): Response
    {
        return $this->client()->get($url)->throw();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function post(string $url, array $payload): Response
    {
        return $this->client()->post($url, $payload)->throw();
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->timeout((int) config('microservice.http.timeout_seconds'))
            ->retry(
                times: (int) config('microservice.http.retry_times'),
                sleepMilliseconds: (int) config('microservice.http.retry_sleep_ms'),
            )
            ->withHeaders([
                'X-Service-Name' => (string) config('microservice.name'),
            ]);
    }
}
