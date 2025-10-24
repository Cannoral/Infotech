<?php

namespace app\events;

use app\models\Book;
use yii\base\Event;

class BookAuthorsAddedEvent extends Event
{
    public Book $book;
    public array $newAuthorIds;

    public function __construct(Book $book, array $newAuthorIds, $config = [])
    {
        $this->book = $book;
        $this->newAuthorIds = $newAuthorIds;
        parent::__construct($config);
    }
}