<?php

namespace Tests\Feature;

use Tests\TestCase;

final class HealthTest extends TestCase
{
    public function test_health_endpoint_returns_healthy_status(): void
    {
        $this->getJson('/api/v1/health')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.status', 'healthy');
    }

    public function test_readiness_endpoint_returns_checks(): void
    {
        $this->getJson('/api/v1/ready')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonStructure([
                'data' => [
                    'checks' => ['storage_writable', 'cache_path_writable'],
                ],
            ]);
    }

    public function test_laravel_builtin_health_route_is_available(): void
    {
        $this->get('/up')->assertOk();
    }
}
