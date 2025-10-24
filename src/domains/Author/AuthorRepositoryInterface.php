<?php

namespace app\domains\Author;

use app\models\Author;

/**
 * Интерфейс репозитория для работы с авторами
 * 
 * Определяет контракт для операций с данными авторов в слое доступа к данным.
 * Реализует паттерн Repository для абстрагирования от конкретной реализации хранилища данных.
 * 
 * @package app\domains\Author
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
interface AuthorRepositoryInterface
{
    /**
     * Получение всех авторов
     * 
     * Возвращает список всех авторов из хранилища данных.
     * 
     * @return array|bool Массив объектов Author или false в случае ошибки
     */
    public function getAll(): array|bool;

    /**
     * Получение автора по идентификатору
     * 
     * Возвращает конкретного автора по его уникальному идентификатору.
     * 
     * @param int $id Идентификатор автора
     * @return Author|null Объект автора или null если не найден
     */
    public function getById(int $id): ?Author;

    /**
     * Сохранение автора
     * 
     * Сохраняет автора в хранилище данных (создание или обновление).
     * 
     * @param Author $author Объект автора для сохранения
     * @return bool Результат операции сохранения
     */
    public function save(Author $author): bool;

    /**
     * Удаление автора
     * 
     * Удаляет автора из хранилища данных.
     * 
     * @param Author $author Объект автора для удаления
     * @return bool Результат операции удаления
     */
    public function delete(Author $author): bool;
}