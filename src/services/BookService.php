<?php

namespace app\services;

use app\models\Book;
use app\domains\Book\BookRepositoryInterface;
use yii\db\Exception;
use app\domains\Book\BookServiceInterface;
use yii\web\UploadedFile;
use app\domains\Cover\CoverServiceInterface;
use Yii;

class BookService implements BookServiceInterface
{
    private BookRepositoryInterface $repo;
    private CoverServiceInterface $coverService;

    public function __construct(BookRepositoryInterface $repo, CoverServiceInterface $coverService)
    {
        $this->repo = $repo;
        $this->coverService = $coverService;
    }

    public function create(Book $book, array $data): ?Book
    {
        $book = new Book();
        if (!$book->load($data)) {
            return null;
        }

        $book->coverFile = UploadedFile::getInstance($book, 'coverFile');

        if (!$book->validate()) {
            return null;
        }

        if ($book->coverFile) {
            $path = $this->coverService->upload($book->coverFile);
            if (!$path) {
                throw new \RuntimeException('Ошибка загрузки файла');
            }
            $book->cover = $path;
        }

        if (!$this->repo->save($book)) {
            throw new \RuntimeException('Не удалось сохранить книгу');
        }

        if (!empty($book->authorIds)) {
            Yii::$app->db->createCommand()
                ->batchInsert('book_author', ['book_id', 'author_id'], 
                    array_map(fn($id) => [$book->id, $id], $book->authorIds)
                )
                ->execute();
        }

        return $book;
    }

    public function update(Book $book, array $data): ?Book
    {
        if (!$book->load($data)) {
            return null;
        }
        $book->coverFile = UploadedFile::getInstance($book, 'coverFile');

        if (!$book->validate()) {
            return null;
        }

        if ($book->coverFile) {
            $path = $this->coverService->upload($book->coverFile);
            if (!$path) {
                throw new \RuntimeException('Ошибка загрузки файла');
            }
            $book->cover = $path;
        }

        if (!$this->repo->save($book)) {
            throw new \RuntimeException('Ошибка обновления');
        }

        Yii::$app->db->createCommand()->delete('book_author', ['book_id' => $book->id])->execute();

        if (!empty($book->authorIds)) {
            Yii::$app->db->createCommand()
                ->batchInsert('book_author', ['book_id', 'author_id'],
                    array_map(fn($id) => [$book->id, $id], $book->authorIds)
                )
            ->execute();
        }

        return $book;
    }

    public function delete(Book $book): bool
    {
        return $this->repo->delete($book);
    }
}
