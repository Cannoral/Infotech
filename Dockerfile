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

# composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# отдельный пользователь
RUN groupadd -g ${GID:-1000} appuser \
    && useradd -u ${UID:-1000} -g ${GID:-1000} -m -s /bin/bash appuser

WORKDIR /var/www/html

# php.ini
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

# копируем исходники заранее, чтобы composer видел composer.json
COPY ./src /var/www/html

# ставим зависимости и прогоняем миграции
RUN composer install --no-interaction --no-progress --prefer-dist \
    && php yii migrate/up --interactive=0

USER appuser

CMD ["php-fpm"]
