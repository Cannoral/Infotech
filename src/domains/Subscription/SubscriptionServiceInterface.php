<?php

namespace app\domains\Subscription;

use app\models\Author;
use app\models\Subscription;

/**
 * Интерфейс сервиса для управления подписками
 * 
 * Определяет контракт для операций с подписками на обновления авторов.
 * Реализует паттерн Service для инкапсуляции бизнес-логики подписок,
 * включая создание подписок и отправку уведомлений подписчикам.
 * 
 * @package app\domains\Subscription
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
interface SubscriptionServiceInterface
{
    /**
     * Создание подписки на обновления автора
     * 
     * Создает новую подписку пользователя на получение уведомлений
     * о новых книгах указанного автора на заданный номер телефона.
     * 
     * @param int $authorId Идентификатор автора для подписки
     * @param string $phone Номер телефона для отправки уведомлений
     * @return Subscription Созданная подписка
     * @throws \Exception В случае ошибки создания подписки
     */
    public function subscribe(int $authorId, string $phone): Subscription;

    /**
     * Уведомление подписчиков о новой книге автора
     * 
     * Отправляет SMS уведомления всем подписчикам автора
     * о публикации новой книги с указанием названия.
     * 
     * @param Author $author Автор, чья книга была опубликована
     * @param string $bookTitle Название новой книги
     * @return void
     */
    public function notifySubscribers(Author $author, string $bookTitle): void;
}