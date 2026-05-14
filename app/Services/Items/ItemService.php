<?php

namespace App\Services\Items;

use App\Exceptions\AppException;
use App\Repositories\Items\ItemRepository;

final class ItemService
{
    public function __construct(private readonly ItemRepository $items) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function list(): array
    {
        return $this->items->all();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->items->create([
            'name' => (string) $data['name'],
            'description' => $data['description'] ?? null,
            'price' => (float) $data['price'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function find(string $id): array
    {
        $item = $this->items->find($id);

        if ($item === null) {
            throw new AppException(
                errorCode: 'ITEM_NOT_FOUND',
                message: "Item [{$id}] was not found.",
                status: 404,
            );
        }

        return $item;
    }
}
