<?php

namespace app\domains\Author;

use app\models\Author;

/**
 * Интерфейс сервиса для бизнес-логики авторов
 * 
 * Определяет контракт для операций с бизнес-логикой авторов.
 * Реализует паттерн Service для инкапсуляции сложной бизнес-логики
 * и координации между различными компонентами системы.
 * 
 * @package app\domains\Author
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
interface AuthorServiceInterface
{
    /**
     * Создание нового автора
     * 
     * Обрабатывает создание нового автора с валидацией данных,
     * применением бизнес-правил и сохранением в хранилище.
     * 
     * @param Author $author Объект автора для создания
     * @param array $data Массив данных для заполнения модели
     * @return Author|null Созданный объект автора или null в случае ошибки
     */
    public function create(Author $author, array $data): ?Author;

    /**
     * Обновление существующего автора
     * 
     * Обрабатывает обновление данных автора с валидацией,
     * применением бизнес-правил и сохранением изменений.
     * 
     * @param Author $author Объект автора для обновления
     * @param array $data Массив новых данных для обновления
     * @return Author|null Обновленный объект автора или null в случае ошибки
     */
    public function update(Author $author, array $data): ?Author;

    /**
     * Удаление автора
     * 
     * Обрабатывает удаление автора с проверкой бизнес-правил
     * и выполнением связанных операций (например, обработка связанных сущностей).
     * 
     * @param Author $author Объект автора для удаления
     * @return bool Результат операции удаления
     */
    public function delete(Author $author): bool;
}
