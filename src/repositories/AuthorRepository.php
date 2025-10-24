<?php

namespace app\repositories;

use app\models\Author;
use app\domains\Author\AuthorRepositoryInterface;

/**
 * Репозиторий для работы с авторами
 * 
 * Реализация интерфейса AuthorRepositoryInterface для работы с данными авторов.
 * Обеспечивает доступ к данным авторов с использованием ActiveRecord модели.
 * Включает предзагрузку связанных книг для оптимизации запросов.
 * 
 * @package app\repositories
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * @inheritdoc
     * 
     * Загружает всех авторов с предзагрузкой связанных книг.
     */
    public function getAll(): array|bool
    {
        return Author::find()->with('books')->all();
    }

    /**
     * @inheritdoc
     * 
     * Загружает автора по идентификатору с предзагрузкой связанных книг.
     */
    public function getById(int $id): ?Author
    {
        return Author::find()->with('books')->where(['id' => $id])->one();
    }

    /**
     * @inheritdoc
     */
    public function save(Author $author): bool
    {
        return $author->save();
    }

    /**
     * @inheritdoc
     * 
     * Выполняет мягкое удаление автора из базы данных.
     */
    public function delete(Author $author): bool
    {
        return (bool)$author->delete();
    }
}