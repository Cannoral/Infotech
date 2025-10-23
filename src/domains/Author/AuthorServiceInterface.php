<?php

namespace app\domains\Author;

use app\models\Author;

interface AuthorServiceInterface
{
    public function create(Author $author, array $data): ?Author;

    public function update(Author $author, array $data): ?Author;

    public function delete(Author $author): bool;
}
