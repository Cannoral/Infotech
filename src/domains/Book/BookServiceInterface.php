<?php

namespace app\domains\Book;

use app\models\Book;

interface BookServiceInterface
{
    public function create(array $data): ?Book;

    public function update(Book $book, array $data): ?Book;

    public function delete(Book $book): bool;
}
