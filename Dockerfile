FROM php:8.3-fpm

ARG UID
ARG GID

RUN apt-get update && apt-get install -y \
    git unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev curl \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql gd zip mbstring opcache \
 && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# создаём пользователя
RUN groupadd -g ${GID:-1000} appuser \
 && useradd -u ${UID:-1000} -g ${GID:-1000} -m -s /bin/bash appuser

WORKDIR /var/www/html

# конфиг PHP
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

# копируем исходники
COPY ./src /var/www/html

# ставим зависимости
RUN composer install --no-interaction --no-progress --prefer-dist \
 && chown -R appuser:appuser /var/www/html

# entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER appuser

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
