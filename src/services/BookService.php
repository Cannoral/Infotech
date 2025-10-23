<?php

namespace app\services;

use app\models\Book;
use app\repositories\BookRepository;
use yii\db\Exception;
use app\domains\Book\BookServiceInterface;

class BookService implements BookServiceInterface
{
    private BookRepository $repo;

    public function __construct(BookRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data): ?Book
    {
        $book = new Book();
        $book->load($data, '');

        if (!$book->validate()) {
            return null;
        }

        if (!$this->repo->save($book)) {
            throw new Exception('Не удалось сохранить книгу');
        }

        return $book;
    }

    public function update(Book $book, array $data): ?Book
    {
        $book->load($data, '');
        if (!$book->validate()) {
            return null;
        }

        if (!$this->repo->save($book)) {
            throw new Exception('Ошибка обновления');
        }

        return $book;
    }

    public function delete(Book $book): bool
    {
        return $this->repo->delete($book);
    }
}
