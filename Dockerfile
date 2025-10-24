FROM php:8.3-fpm

ARG UID
ARG GID

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip mbstring opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID:-1000} appuser \
    && useradd -u ${UID:-1000} -g ${GID:-1000} -m -s /bin/bash appuser

WORKDIR /var/www/html

RUN mkdir -p /var/www/html && chown -R appuser:appuser /var/www/html

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

USER appuser

CMD ["php-fpm"]