<?php

namespace app\services;

use app\models\Author;
use app\domains\Author\AuthorServiceInterface;
use app\domains\Author\AuthorRepositoryInterface;
use Yii;

class AuthorService implements AuthorServiceInterface
{
    private AuthorRepositoryInterface $repo;

    public function __construct(AuthorRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create(Author $author, array $data): ?Author
    {
        if (!$author->load($data) || !$author->validate()) {
            return null;
        }

        if (!$this->repo->save($author)) {
            throw new \RuntimeException('Не удалось сохранить автора');
        }

        return $author;
    }

    public function update(Author $author, array $data): ?Author
    {
        if (!$author->load($data) || !$author->validate()) {
            return null;
        }

        if (!$this->repo->save($author)) {
            throw new \RuntimeException('Не удалось обновить автора');
        }

        return $author;
    }

    public function delete(Author $author): bool
    {
        return $this->repo->delete($author);
    }
}