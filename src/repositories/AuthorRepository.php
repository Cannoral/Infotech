<?php

namespace app\repositories;

use app\models\Author;
use app\domains\Author\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAll(): array|bool
    {
        return Author::find()->with('books')->all();
    }

    public function getById(int $id): ?Author
    {
        return Author::find()->with('books')->where(['id' => $id])->one();
    }

    public function save(Author $author): bool
    {
        return $author->save();
    }

    public function delete(Author $author): bool
    {
        return (bool)$author->delete();
    }
}