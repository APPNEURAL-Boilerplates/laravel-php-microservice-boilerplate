<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class RootTest extends TestCase
{
    public function test_root_returns_service_metadata(): void
    {
        $this->getJson('/')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.service', config('microservice.name'));
    }
}
