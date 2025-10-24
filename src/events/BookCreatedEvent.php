<?php

namespace app\events;

use app\models\Book;
use yii\base\Event;

class BookCreatedEvent extends Event
{
    public Book $book;

    public function __construct(Book $book, $config = [])
    {
        $this->book = $book;
        parent::__construct($config);
    }
}
