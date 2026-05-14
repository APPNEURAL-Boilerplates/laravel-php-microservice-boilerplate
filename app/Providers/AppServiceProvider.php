<?php

namespace App\Providers;

use App\Repositories\Items\InMemoryItemRepository;
use App\Repositories\Items\ItemRepository;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ItemRepository::class, InMemoryItemRepository::class);
    }

    public function boot(): void
    {
        // Register model observers, macros, or production-only guards here.
    }
}
