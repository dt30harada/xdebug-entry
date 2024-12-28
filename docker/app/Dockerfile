FROM php:8.0-apache AS base

ENV TZ=UTC \
    LANG=en_US.UTF-8 \
    LANGUAGE=en_US:en \
    LC_ALL=en_US.UTF-8

RUN a2enmod headers rewrite \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        libonig-dev \
        libzip-dev \
        locales \
        unzip \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=en_US.UTF-8 \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


FROM base AS local

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    # ローカル開発環境用のコンテナのみXdebugをインストールする
    && pecl install xdebug-3.4.0 \
    && docker-php-ext-enable xdebug


FROM base AS prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --chown=www-data:www-data . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --no-suggest \
    --optimize-autoloader \
    --prefer-dist \
    --quiet \
    && chmod -R 755 ./storage ./bootstrap/cache \
    && php artisan config:cache \
    && php artisan route:cache

