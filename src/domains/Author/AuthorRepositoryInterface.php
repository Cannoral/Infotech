<?php

namespace app\domains\Author;

use app\models\Author;

interface AuthorRepositoryInterface
{
    public function getAll(): array|bool;

    public function getById(int $id): ?Author;

    public function save(Author $author): bool;

    public function delete(Author $author): bool;
}