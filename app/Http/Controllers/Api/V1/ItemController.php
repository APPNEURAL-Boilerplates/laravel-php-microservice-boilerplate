<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateItemRequest;
use App\Services\Items\ItemService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

final class ItemController
{
    public function __construct(private readonly ItemService $items) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success([
            'items' => $this->items->list(),
        ]);
    }

    public function store(CreateItemRequest $request): JsonResponse
    {
        $item = $this->items->create($request->validated());

        return ApiResponse::success([
            'item' => $item,
        ], 'Item created.', 201);
    }

    public function show(string $id): JsonResponse
    {
        return ApiResponse::success([
            'item' => $this->items->find($id),
        ]);
    }
}
