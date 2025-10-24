<?php

namespace app\listeners;

use app\events\BookCreatedEvent;
use app\events\BookAuthorsAddedEvent;
use app\models\Author;
use app\domains\Subscription\SubscriptionServiceInterface;
use Yii;

/**
 * Слушатель событий книг
 * 
 * Обрабатывает события, связанные с книгами, и выполняет соответствующие действия.
 * Отвечает за уведомление подписчиков о новых книгах и изменениях авторского состава.
 * Реализует событийно-ориентированную архитектуру для разделения ответственности.
 * 
 * @package app\listeners
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class BookEventListener
{
    /**
     * @var SubscriptionServiceInterface Сервис для управления подписками и уведомлениями
     */
    private SubscriptionServiceInterface $subscriptionService;

    /**
     * Конструктор слушателя событий
     * 
     * @param SubscriptionServiceInterface $subscriptionService Сервис подписок для отправки уведомлений
     */
    public function __construct(SubscriptionServiceInterface $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Обработчик события создания новой книги
     * 
     * Уведомляет всех подписчиков авторов книги о её публикации.
     * Перебирает всех авторов книги и отправляет уведомления их подписчикам.
     * 
     * @param BookCreatedEvent $event Событие создания книги
     * @return void
     */
    public function onBookCreated(BookCreatedEvent $event): void
    {
        foreach ($event->book->authors as $author) {
            $this->subscriptionService->notifySubscribers($author, $event->book->title);
        }
    }

    /**
     * Обработчик события добавления авторов к книге
     * 
     * Уведомляет подписчиков новых авторов о том, что к ним добавлена книга.
     * Получает авторов по их идентификаторам и отправляет уведомления подписчикам.
     * 
     * @param BookAuthorsAddedEvent $event Событие добавления авторов к книге
     * @return void
     */
    public function onBookAuthorsAdded(BookAuthorsAddedEvent $event): void
    {
        $authors = Author::find()->where(['id' => $event->newAuthorIds])->all();
        foreach ($authors as $author) {
            $this->subscriptionService->notifySubscribers($author, $event->book->title);
        }
    }
}
