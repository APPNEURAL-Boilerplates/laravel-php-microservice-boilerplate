FROM php:8.4-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        curl \
        git \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        sqlite-dev \
        unzip \
    && docker-php-ext-install intl pdo pdo_mysql pdo_sqlite zip opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.json
COPY artisan artisan
COPY bootstrap bootstrap
COPY config config
COPY database database
COPY public public
COPY routes routes
COPY app app
COPY tests tests
COPY phpunit.xml phpunit.xml

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && mkdir -p storage/app/private storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
