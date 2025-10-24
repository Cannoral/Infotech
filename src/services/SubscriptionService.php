<?php

namespace app\services;

use app\models\Subscription;
use app\models\Author;
use yii\queue\db\Queue;
use app\jobs\SmsNotificationJob;
use app\domains\Subscription\SubscriptionServiceInterface;

/**
 * Сервис для управления подписками
 * 
 * Реализация интерфейса SubscriptionServiceInterface для обработки подписок на обновления авторов.
 * Обеспечивает создание подписок с валидацией данных и асинхронную отправку SMS уведомлений
 * через систему очередей. Координирует работу с моделями и фоновыми задачами.
 * 
 * @package app\services
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * @var Queue Очередь для асинхронной обработки задач
     */
    private Queue $queue;

    /**
     * Конструктор сервиса
     * 
     * @param Queue $queue Очередь для фоновых задач
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @inheritdoc
     * 
     * Создает новую подписку с валидацией данных автора и телефона.
     * При валидации используется проверка уникальности телефона и существования автора.
     * Сохраняет подписку в базу данных без повторной валидации для оптимизации.
     */
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

    /**
     * @inheritdoc
     * 
     * Отправляет SMS уведомления всем подписчикам автора через систему очередей.
     * Создает задачи SmsNotificationJob для каждого подписчика и добавляет их в очередь
     * для асинхронной обработки. Формирует персонализированное сообщение с названием книги.
     */
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
