<?php

return [
    'name' => env('SERVICE_NAME', env('APP_NAME', 'laravel-microservice')),
    'version' => env('SERVICE_VERSION', '1.0.0'),
    'http_timeout_seconds' => (int) env('HTTP_TIMEOUT_SECONDS', 5),
];
