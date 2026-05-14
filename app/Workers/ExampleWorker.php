<?php

declare(strict_types=1);

namespace App\Workers;

use Illuminate\Support\Facades\Log;

final class ExampleWorker
{
    public function __invoke(): void
    {
        Log::info('Example worker executed. Replace this with scheduled or queue work.');
    }
}
