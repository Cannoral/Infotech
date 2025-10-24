# Тестовое задание (Infotech)

## Установка и запуск

1. Клонировать репозиторий и перейти в директорию проекта.  
2. Создать файлы окружения:
```bash
cp .env.example .env           # для docker-compose
cp src/.env.example src/.env   # для приложения
```
3. Собрать и поднять контейнеры:
```bash
docker-compose build
docker-compose up -d
```
4. Установка зависимостей и запуск миграций
```bash
docker-compose exec php composer install
docker-compose exec php php yii migrate/up --interactive=0
```

Приложение будет доступно по адресу
http://localhost:8080

## Команды
### Очередь
1. Проверка состояния:
```bash
docker exec -it app php yii queue/info
```
2. Запуск обработки задач:
```bash
docker exec -it app php yii queue/listen
```