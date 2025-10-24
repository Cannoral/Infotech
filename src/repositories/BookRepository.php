<?php

namespace app\repositories;

use app\models\Book;
use app\domains\Book\BookRepositoryInterface;

/**
 * Репозиторий для работы с книгами
 * 
 * Реализация интерфейса BookRepositoryInterface для работы с данными книг.
 * Обеспечивает доступ к данным книг с использованием ActiveRecord модели.
 * Включает предзагрузку связанных авторов и сортировку по году выпуска.
 * 
 * @package app\repositories
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class BookRepository implements BookRepositoryInterface
{
    /**
     * @inheritdoc
     * 
     * Загружает все книги с предзагрузкой авторов, отсортированные по году выпуска (убывание).
     */
    public function getAll(): array|bool
    {
        return Book::find()->with('authors')->orderBy(['year' => SORT_DESC])->all();
    }

    /**
     * @inheritdoc
     * 
     * Загружает книгу по идентификатору с предзагрузкой связанных авторов.
     */
    public function getById(int $id): ?Book
    {
        return Book::find()->with('authors')->where(['id' => $id])->one();
    }

    /**
     * @inheritdoc
     */
    public function save(Book $book): bool
    {
        return $book->save();
    }

    /**
     * @inheritdoc
     * 
     * Выполняет удаление книги из базы данных.
     */
    public function delete(Book $book): bool
    {
        return (bool)$book->delete();
    }
}
