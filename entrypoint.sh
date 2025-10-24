#!/bin/sh
set -e

# ждём, пока база станет доступна
echo "Waiting for database..."
until php -r "new PDO('mysql:host=db;dbname=${MYSQL_DATABASE}','${MYSQL_USER}','${MYSQL_PASSWORD}');"; do
  sleep 3
done

# миграции
php yii migrate/up --interactive=0 || true

# запускаем php-fpm
exec php-fpm