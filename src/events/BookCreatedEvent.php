<?php

namespace app\events;

use app\models\Book;
use yii\base\Event;

/**
 * Событие создания новой книги
 * 
 * Генерируется при успешном создании новой книги в системе.
 * Используется для уведомления системы о необходимости выполнения
 * связанных операций (например, уведомление подписчиков авторов).
 * 
 * @package app\events
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class BookCreatedEvent extends Event
{
    /**
     * @var Book Созданная книга
     */
    public Book $book;

    /**
     * Конструктор события
     * 
     * @param Book $book Созданная книга
     * @param array $config Дополнительная конфигурация события
     */
    public function __construct(Book $book, $config = [])
    {
        $this->book = $book;
        parent::__construct($config);
    }
}
