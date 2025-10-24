<?php

use app\listeners\BookEventListener;
use app\events\BookCreatedEvent;
use app\events\BookAuthorsAddedEvent;

$listener = Yii::$container->get(BookEventListener::class);
Yii::$app->on(BookCreatedEvent::class, [$listener, 'onBookCreated']);
Yii::$app->on(BookAuthorsAddedEvent::class, [$listener, 'onBookAuthorsAdded']);


$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1)); // html/.env
$dotenv->load();

