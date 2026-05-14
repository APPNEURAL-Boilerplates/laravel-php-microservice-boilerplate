<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

final class PublishDomainEventJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        private readonly string $topic,
        private readonly array $payload,
    ) {}

    public function handle(): void
    {
        // Replace this placeholder with Kafka, RabbitMQ, SNS/SQS, Redis streams, etc.
        Log::info('Domain event published', [
            'topic' => $this->topic,
            'payload' => $this->payload,
        ]);
    }
}
