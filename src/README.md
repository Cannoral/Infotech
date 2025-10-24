# Тестовое задание (Infotech)

## Установка и запуск

1. Клонировать репозиторий и перейти в директорию проекта.  
2. Создать файлы окружения:
```bash
cp .env.example .env           # для docker-compose
cp src/.env.example src/.env   # для приложения
```
3. Поднять контейнеры:
```bash
docker-compose up -d
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