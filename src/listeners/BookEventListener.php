<?php

namespace app\listeners;

use app\events\BookCreatedEvent;
use app\events\BookAuthorsAddedEvent;
use app\models\Author;
use app\domains\Subscription\SubscriptionServiceInterface;
use Yii;

class BookEventListener
{
    private SubscriptionServiceInterface $subscriptionService;

    public function __construct(SubscriptionServiceInterface $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function onBookCreated(BookCreatedEvent $event): void
    {
        foreach ($event->book->authors as $author) {
            $this->subscriptionService->notifySubscribers($author, $event->book->title);
        }
    }

    public function onBookAuthorsAdded(BookAuthorsAddedEvent $event): void
    {
        $authors = Author::find()->where(['id' => $event->newAuthorIds])->all();
        foreach ($authors as $author) {
            $this->subscriptionService->notifySubscribers($author, $event->book->title);
        }
    }
}
