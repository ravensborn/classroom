FROM php:8.4-fpm AS php

ENV PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_ENABLE_CLI=0 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 \
    PHP_OPCACHE_REVALIDATE_FREQ=2

# Build arguments for flexible UID/GID matching host user
ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    libpq-dev \
    libcurl4-gnutls-dev \
    nginx \
    libonig-dev \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    ffmpeg \
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

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Configure www-data user to match host UID/GID for bind mount compatibility
RUN groupmod --gid ${GID} www-data \
    && usermod --uid ${UID} --gid ${GID} www-data \
    && chown -R www-data:www-data /var/www

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy configuration files
COPY ./documentation/be-docker/configuration-files/php/php.ini /usr/local/etc/php/php.ini
COPY ./documentation/be-docker/configuration-files/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./documentation/be-docker/configuration-files/nginx/nginx.conf /etc/nginx/nginx.conf

# Fix nginx to run as www-data and create required directories
RUN sed -i 's/user  nginx;/user  www-data;/' /etc/nginx/nginx.conf || true \
    && mkdir -p /var/cache/nginx /var/log/nginx /run /var/lib/nginx/body /var/lib/nginx/proxy /var/lib/nginx/fastcgi /var/lib/nginx/uwsgi /var/lib/nginx/scgi \
    && chown -R www-data:www-data /var/cache/nginx /var/log/nginx /run /var/lib/nginx

WORKDIR /var/www

# Switch to non-root user
USER www-data

ENTRYPOINT ["documentation/be-docker/configuration-files/entrypoint.sh"]
