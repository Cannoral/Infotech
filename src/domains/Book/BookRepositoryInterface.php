<?php

namespace app\domains\Book;

use app\models\Book;

interface BookRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): ?Book;

    public function save(Book $book): bool;

    public function delete(Book $book): bool;
}
