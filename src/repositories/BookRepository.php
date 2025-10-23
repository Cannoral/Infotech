<?php

namespace app\repositories;

use app\models\Book;
use app\domains\Book\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getAll(): array
    {
        return Book::find()->with('authors')->orderBy(['year' => SORT_DESC])->all();
    }

    public function getById(int $id): ?Book
    {
        return Book::find()->with('authors')->where(['id' => $id])->one();
    }

    public function save(Book $book): bool
    {
        return $book->save();
    }

    public function delete(Book $book): bool
    {
        return (bool)$book->delete();
    }
}
