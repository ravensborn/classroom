# Base PHP image
FROM php:8.4-fpm AS php

ENV PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_ENABLE_CLI=0 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 \
    PHP_OPCACHE_REVALIDATE_FREQ=2

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    libpq-dev \
    libcurl4-gnutls-dev \
    nginx \
    libonig-dev \
    zip \
    ffmpeg \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --enable-gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        bcmath \
        curl \
        mbstring \
        opcache \
        exif \
        gd \
        zip \
        pcntl

# Install Redis PHP extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy nginx + PHP configs
COPY ./documentation/be-docker/configuration-files/php/php.ini /usr/local/etc/php/php.ini
COPY ./documentation/be-docker/configuration-files/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./documentation/be-docker/configuration-files/nginx/nginx.conf /etc/nginx/nginx.conf

# Fix nginx user
RUN sed -i 's/user  nginx;/user  www-data;/' /etc/nginx/nginx.conf || true \
    && mkdir -p /var/cache/nginx /var/log/nginx /run \
    /var/lib/nginx/body /var/lib/nginx/proxy /var/lib/nginx/fastcgi \
    && chown -R www-data:www-data /var/cache/nginx /var/log/nginx /run /var/lib/nginx

# Set work directory
WORKDIR /var/www

# --- IMPORTANT FOR DOKPLOY ---
# Copy app code into the image
COPY --chown=www-data:www-data . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint for APP | HORIZON | SCHEDULER
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

USER www-data

ENTRYPOINT ["/entrypoint.sh"]
