<?php

namespace app\services;

use app\models\Book;
use app\domains\Book\BookRepositoryInterface;
use yii\db\Exception;
use app\domains\Book\BookServiceInterface;
use yii\web\UploadedFile;
use app\domains\Cover\CoverServiceInterface;
use app\domains\Subscription\SubscriptionServiceInterface;
use app\events\BookCreatedEvent;
use app\events\BookAuthorsAddedEvent;
use Yii;

/**
 * Сервис для бизнес-логики книг
 * 
 * Реализация интерфейса BookServiceInterface для обработки сложных бизнес-операций с книгами.
 * Координирует работу с репозиторием, сервисами обложек и подписок.
 * Обрабатывает загрузку файлов, управление связями с авторами и отправку событий.
 * 
 * @package app\services
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class BookService implements BookServiceInterface
{
    /**
     * @var BookRepositoryInterface Репозиторий для работы с данными книг
     */
    private BookRepositoryInterface $repo;
    
    /**
     * @var CoverServiceInterface Сервис для обработки обложек книг
     */
    private CoverServiceInterface $coverService;
    
    /**
     * @var SubscriptionServiceInterface Сервис для управления подписками и уведомлениями
     */
    private SubscriptionServiceInterface $subscriptionService;

    /**
     * Конструктор сервиса
     * 
     * @param BookRepositoryInterface $repo Репозиторий книг
     * @param CoverServiceInterface $coverService Сервис обложек
     * @param SubscriptionServiceInterface $subscriptionService Сервис подписок
     */
    public function __construct(
        BookRepositoryInterface $repo, 
        CoverServiceInterface $coverService,
        SubscriptionServiceInterface $subscriptionService
    )
    {
        $this->repo = $repo;
        $this->coverService = $coverService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @inheritdoc
     * 
     * Создает новую книгу с обработкой загрузки обложки, привязкой авторов и уведомлением подписчиков.
     * Выполняет валидацию данных, загрузку файла обложки, сохранение в репозиторий,
     * создание связей с авторами, генерацию события и отправку уведомлений.
     */
    public function create(Book $book, array $data): ?Book
    {
        $book = $this->prepareBookData(new Book(), $data);
        if (!$book) {
            return null;
        }

        $this->saveBookWithRelations($book);
        $this->processBookCreationEvents($book);

        return $book;
    }

    /**
     * @inheritdoc
     * 
     * Обновляет существующую книгу с пересозданием связей с авторами и обработкой обложки.
     * Удаляет старые связи с авторами, создает новые, генерирует события для добавленных авторов.
     * Обрабатывает загрузку новой обложки при наличии файла.
     */
    public function update(Book $book, array $data): ?Book
    {
        $oldAuthorIds = array_column($book->authors, 'id');
        
        $book = $this->prepareBookData($book, $data);
        if (!$book) {
            return null;
        }

        $this->saveBookWithRelations($book);
        $this->processBookUpdateEvents($book, $oldAuthorIds);

        return $book;
    }

    /**
     * @inheritdoc
     * 
     * Делегирует операцию удаления репозиторию без дополнительной бизнес-логики.
     */
    public function delete(Book $book): bool
    {
        return $this->repo->delete($book);
    }

    /**
     * Подготавливает данные книги с валидацией и обработкой обложки
     * 
     * @param Book $book Объект книги для заполнения
     * @param array $data Данные для загрузки в модель
     * @return Book|null Подготовленная книга или null при ошибках валидации
     */
    private function prepareBookData(Book $book, array $data): ?Book
    {
        if (!$book->load($data)) {
            return null;
        }

        $book->coverFile = UploadedFile::getInstance($book, 'coverFile');

        if (!$book->validate()) {
            return null;
        }

        $this->processCoverUpload($book);

        return $book;
    }

    /**
     * Обрабатывает загрузку файла обложки
     * 
     * @param Book $book Книга с потенциальным файлом обложки
     * @throws \RuntimeException При ошибке загрузки файла
     */
    private function processCoverUpload(Book $book): void
    {
        if ($book->coverFile) {
            $path = $this->coverService->upload($book->coverFile);
            if (!$path) {
                throw new \RuntimeException('Ошибка загрузки файла');
            }
            $book->cover = $path;
        }
    }

    /**
     * Сохраняет книгу и создает связи с авторами
     * 
     * @param Book $book Книга для сохранения
     * @throws \RuntimeException При ошибке сохранения
     */
    private function saveBookWithRelations(Book $book): void
    {
        if (!$this->repo->save($book)) {
            throw new \RuntimeException('Не удалось сохранить книгу');
        }

        $this->syncBookAuthors($book);
    }

    /**
     * Синхронизирует связи книги с авторами
     * 
     * @param Book $book Книга для синхронизации авторов
     */
    private function syncBookAuthors(Book $book): void
    {
        // Удаляем старые связи (для update)
        Yii::$app->db->createCommand()
            ->delete('book_author', ['book_id' => $book->id])
            ->execute();

        // Создаем новые связи
        if (!empty($book->authorIds)) {
            Yii::$app->db->createCommand()
                ->batchInsert(
                    'book_author', 
                    ['book_id', 'author_id'], 
                    array_map(fn($id) => [$book->id, $id], $book->authorIds)
                )
                ->execute();
        }
    }

    /**
     * Обрабатывает события при создании новой книги
     * 
     * @param Book $book Созданная книга
     */
    private function processBookCreationEvents(Book $book): void
    {
        Yii::$app->trigger(BookCreatedEvent::class, new BookCreatedEvent($book));

        foreach ($book->authors as $author) {
            $this->subscriptionService->notifySubscribers($author, $book->title);
        }
    }

    /**
     * Обрабатывает события при обновлении книги
     * 
     * @param Book $book Обновленная книга
     * @param array $oldAuthorIds Массив ID старых авторов
     */
    private function processBookUpdateEvents(Book $book, array $oldAuthorIds): void
    {
        $newAuthorIds = $book->authorIds ?? [];
        $addedAuthorIds = array_diff($newAuthorIds, $oldAuthorIds);

        if (!empty($addedAuthorIds)) {
            Yii::$app->trigger(
                BookAuthorsAddedEvent::class, 
                new BookAuthorsAddedEvent($book, $addedAuthorIds)
            );
        }
    }
}
