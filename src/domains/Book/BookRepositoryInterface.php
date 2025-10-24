<?php

namespace app\domains\Book;

use app\models\Book;

/**
 * Интерфейс репозитория для работы с книгами
 * 
 * Определяет контракт для операций с данными книг в слое доступа к данным.
 * Реализует паттерн Repository для абстрагирования от конкретной реализации хранилища данных.
 * 
 * @package app\domains\Book
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
interface BookRepositoryInterface
{
    /**
     * Получение всех книг
     * 
     * Возвращает список всех книг из хранилища данных.
     * 
     * @return array|bool Массив объектов Book или false в случае ошибки
     */
    public function getAll(): array|bool;

    /**
     * Получение книги по идентификатору
     * 
     * Возвращает конкретную книгу по её уникальному идентификатору.
     * 
     * @param int $id Идентификатор книги
     * @return Book|null Объект книги или null если не найдена
     */
    public function getById(int $id): ?Book;

    /**
     * Сохранение книги
     * 
     * Сохраняет книгу в хранилище данных (создание или обновление).
     * 
     * @param Book $book Объект книги для сохранения
     * @return bool Результат операции сохранения
     */
    public function save(Book $book): bool;

    /**
     * Удаление книги
     * 
     * Удаляет книгу из хранилища данных.
     * 
     * @param Book $book Объект книги для удаления
     * @return bool Результат операции удаления
     */
    public function delete(Book $book): bool;
}
