<?php

namespace Tests\Feature;

use Tests\TestCase;

final class ErrorHandlingTest extends TestCase
{
    public function test_unknown_api_route_returns_json_404(): void
    {
        $this->getJson('/api/v1/unknown')
            ->assertNotFound()
            ->assertJsonPath('ok', false)
            ->assertJsonPath('error.code', 'NOT_FOUND');
    }

    public function test_unsupported_method_returns_json_405(): void
    {
        $this->deleteJson('/api/v1/health')
            ->assertStatus(405)
            ->assertJsonPath('ok', false)
            ->assertJsonPath('error.code', 'METHOD_NOT_ALLOWED');
    }

    public function test_invalid_json_returns_400(): void
    {
        $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->call(
            'POST',
            '/api/v1/items',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            '{"name":'
        )
            ->assertStatus(400)
            ->assertJsonPath('ok', false)
            ->assertJsonPath('error.code', 'INVALID_JSON');
    }
}
