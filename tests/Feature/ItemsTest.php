<?php

namespace Tests\Feature;

use Tests\TestCase;

final class ItemsTest extends TestCase
{
    public function test_items_can_be_listed(): void
    {
        $this->getJson('/api/v1/items')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => ['id', 'name', 'description', 'price'],
                    ],
                ],
            ]);
    }

    public function test_item_can_be_created(): void
    {
        $this->postJson('/api/v1/items', [
            'name' => 'Coffee',
            'description' => 'Cold brew',
            'price' => 5.5,
        ])
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.item.name', 'Coffee')
            ->assertJsonPath('data.item.price', 5.5);
    }

    public function test_item_can_be_fetched_by_id(): void
    {
        $this->getJson('/api/v1/items/item_001')
            ->assertOk()
            ->assertJsonPath('data.item.id', 'item_001');
    }

    public function test_unknown_item_returns_404(): void
    {
        $this->getJson('/api/v1/items/missing')
            ->assertNotFound()
            ->assertJsonPath('ok', false)
            ->assertJsonPath('error.code', 'ITEM_NOT_FOUND');
    }

    public function test_validation_error_returns_json(): void
    {
        $this->postJson('/api/v1/items', [
            'name' => '',
            'price' => -1,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('ok', false)
            ->assertJsonPath('error.code', 'VALIDATION_FAILED');
    }
}
