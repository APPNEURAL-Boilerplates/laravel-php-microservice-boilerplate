<?php

declare(strict_types=1);

namespace App\Repositories\Items;

use Illuminate\Support\Str;

final class InMemoryItemRepository implements ItemRepository
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $items = [
        'item_001' => [
            'id' => 'item_001',
            'name' => 'Starter item',
            'description' => 'Example item shipped with the boilerplate.',
            'price' => 9.99,
        ],
        'item_002' => [
            'id' => 'item_002',
            'name' => 'Demo item',
            'description' => 'Use this module as a pattern for real features.',
            'price' => 19.99,
        ],
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return array_values(self::$items);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $id): ?array
    {
        return self::$items[$id] ?? null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        $id = 'item_'.Str::lower((string) Str::ulid());

        $item = [
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => (float) $data['price'],
        ];

        self::$items[$id] = $item;

        return $item;
    }
}
