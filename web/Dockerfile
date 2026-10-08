FROM dunglas/frankenphp:1.12.7-builder-php8.4 AS builder

RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    gd \
    opcache \
    intl \
    exif \
    pcntl \
    bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y ca-certificates curl gnupg && \
    mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_24.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
    apt-get update && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /build

COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-scripts --no-autoloader

COPY . ./

RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views && \
    chmod -R 775 bootstrap/cache storage

RUN composer dump-autoload --optimize

RUN npm ci --prefer-offline --no-audit && npm run build

FROM dunglas/frankenphp:1.12.7-builder-php8.4

RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    gd \
    opcache \
    intl \
    exif \
    pcntl \
    bcmath

WORKDIR /app

COPY --from=builder /build ./

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]