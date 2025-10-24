<?php

namespace app\events;

use app\models\Book;
use yii\base\Event;

/**
 * Событие добавления авторов к книге
 * 
 * Генерируется при добавлении новых авторов к существующей книге.
 * Используется для уведомления системы о необходимости выполнения
 * связанных операций (например, уведомление подписчиков).
 * 
 * @package app\events
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class BookAuthorsAddedEvent extends Event
{
    /**
     * @var Book Книга, к которой добавлены авторы
     */
    public Book $book;
    
    /**
     * @var array Массив идентификаторов новых авторов
     */
    public array $newAuthorIds;

    /**
     * Конструктор события
     * 
     * @param Book $book Книга, к которой добавлены авторы
     * @param array $newAuthorIds Массив идентификаторов добавленных авторов
     * @param array $config Дополнительная конфигурация события
     */
    public function __construct(Book $book, array $newAuthorIds, $config = [])
    {
        $this->book = $book;
        $this->newAuthorIds = $newAuthorIds;
        parent::__construct($config);
    }
}