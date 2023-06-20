FROM php:8.1-fpm-alpine

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk update && apk add bash

# Extension zip needed by box/spout
RUN apk add --no-cache \
      libzip-dev \
      zip \
    && docker-php-ext-install zip

WORKDIR /app
