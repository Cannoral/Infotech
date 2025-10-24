<?php

namespace app\services;

use app\models\Subscription;
use app\models\Author;
use yii\queue\db\Queue;
use app\jobs\SmsNotificationJob;
use app\domains\Subscription\SubscriptionServiceInterface;

class SubscriptionService implements SubscriptionServiceInterface
{
    private Queue $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function subscribe(int $authorId, string $phone): Subscription
    {
        $subscription = new Subscription([
            'author_id' => $authorId,
            'phone' => $phone,
        ]);

        if (!$subscription->validate()) {
            throw new \DomainException('Невалидная подписка');
        }

        $subscription->save(false);

        return $subscription;
    }

    public function notifySubscribers(Author $author, string $bookTitle): void
    {
        foreach ($author->subscribers as $sub) {
            $this->queue->push(new SmsNotificationJob([
                'phone' => $sub->phone,
                'message' => "Новая книга автора {$author->name}: {$bookTitle}",
            ]));
        }
    }
}
