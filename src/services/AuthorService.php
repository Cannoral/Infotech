<?php

namespace app\services;

use app\models\Author;
use app\domains\Author\AuthorServiceInterface;
use app\domains\Author\AuthorRepositoryInterface;
use Yii;

/**
 * Сервис для бизнес-логики авторов
 * 
 * Реализация интерфейса AuthorServiceInterface для обработки бизнес-операций с авторами.
 * Обеспечивает валидацию данных, координацию с репозиторием и обработку ошибок.
 * Инкапсулирует бизнес-правила создания, обновления и удаления авторов.
 * 
 * @package app\services
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class AuthorService implements AuthorServiceInterface
{
    /**
     * @var AuthorRepositoryInterface Репозиторий для работы с данными авторов
     */
    private AuthorRepositoryInterface $repo;

    /**
     * Конструктор сервиса
     * 
     * @param AuthorRepositoryInterface $repo Репозиторий авторов
     */
    public function __construct(AuthorRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @inheritdoc
     * 
     * Загружает данные в модель, выполняет валидацию и сохраняет через репозиторий.
     * В случае ошибки сохранения выбрасывает исключение.
     */
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

    /**
     * @inheritdoc
     * 
     * Загружает новые данные в модель, выполняет валидацию и обновляет через репозиторий.
     * В случае ошибки обновления выбрасывает исключение.
     */
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

    /**
     * @inheritdoc
     * 
     * Делегирует операцию удаления репозиторию без дополнительной бизнес-логики.
     */
    public function delete(Author $author): bool
    {
        return $this->repo->delete($author);
    }
}