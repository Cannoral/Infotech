#!/bin/sh
set -e

echo "Waiting for database..."
until php -r "new PDO('mysql:host=db;dbname=${MYSQL_DATABASE}','${MYSQL_USER}','${MYSQL_PASSWORD}');" 2>/dev/null; do
  sleep 3
done
echo "Database is up."

echo "Running migrations..."
php yii migrate/up --interactive=0 || true

echo "Starting php-fpm..."
exec php-fpm
