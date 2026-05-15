# Laravel PHP Microservice

A production-minded Laravel REST microservice boilerplate with versioned API routes, consistent JSON responses, health/readiness endpoints, request IDs, validation, centralized error handling, Docker, and tests.

## Stack

- PHP 8.3+
- Laravel 13
- PHPUnit 12
- Laravel Pint
- Docker / Docker Compose

## Project structure

```txt
laravel-php-microservice/
├─ app/
│  ├─ Clients/
│  ├─ Events/
│  ├─ Exceptions/
│  ├─ Http/
│  │  ├─ Controllers/
│  │  ├─ Middleware/
│  │  └─ Requests/
│  ├─ Jobs/
│  ├─ Providers/
│  ├─ Repositories/
│  ├─ Services/
│  └─ Support/
├─ bootstrap/
├─ config/
├─ database/
├─ public/
├─ routes/
│  ├─ api.php
│  ├─ console.php
│  └─ web.php
├─ tests/
├─ Dockerfile
├─ docker-compose.yml
├─ Makefile
├─ composer.json
└─ phpunit.xml
```

## Setup

```bash
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
```

Or run the included setup script:

```bash
composer setup
```

## Run locally

```bash
composer dev
```

The service will start at:

```txt
http://localhost:8000
```

## Main endpoints

```txt
GET  /                     Service metadata
GET  /up                   Laravel built-in health route
GET  /api/v1/health        Application health
GET  /api/v1/ready         Readiness check
GET  /api/v1/items         List items
POST /api/v1/items         Create item
GET  /api/v1/items/{id}    Get item by ID
```

## Example requests

```bash
curl http://localhost:8000/api/v1/health
```

```bash
curl -X POST http://localhost:8000/api/v1/items \
  -H "Content-Type: application/json" \
  -H "X-Request-Id: demo-request-1" \
  -d '{"name":"Coffee","description":"Cold brew","price":5.5}'
```

## Test

```bash
composer test
```

Run formatting check and tests:

```bash
composer check
```

Format code:

```bash
composer format
```

## Docker

```bash
cp .env.example .env
docker compose up --build
```

Then open:

```txt
http://localhost:8000
http://localhost:8000/api/v1/health
```

## Configuration

Environment variables live in `.env` and should be copied from `.env.example`.

Important defaults:

```txt
SERVICE_NAME=laravel-microservice
SERVICE_VERSION=1.0.0
CACHE_STORE=array
QUEUE_CONNECTION=sync
DB_CONNECTION=sqlite
```

Never commit real secrets. Keep `.env` out of Git and use your deployment platform's secret manager for production values.

## Error response shape

```json
{
  "ok": false,
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "The given data was invalid.",
    "details": {}
  }
}
```

## Success response shape

```json
{
  "ok": true,
  "data": {}
}
```

## Replace the in-memory repository

The example items module uses `InMemoryItemRepository` to keep the boilerplate simple. Replace the binding in `app/Providers/AppServiceProvider.php` when you add a database or external service:

```php
$this->app->bind(ItemRepository::class, DatabaseItemRepository::class);
```

## Production notes

Before production deployment:

```bash
php artisan optimize
php artisan route:cache
php artisan config:cache
```

Also set:

```txt
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=info
```

The included Dockerfile is intentionally simple. For high-traffic production workloads, use a hardened PHP-FPM + Nginx image, Octane, RoadRunner, or FrankenPHP based on your infrastructure.
