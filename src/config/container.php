<?php

use app\Domain\Book\BookRepositoryInterface;
use app\Domain\Book\BookRepository;
use app\Domain\Book\BookServiceInterface;
use app\Domain\Book\BookService;

Yii::$container->set(BookRepositoryInterface::class, BookRepository::class);
Yii::$container->set(BookServiceInterface::class, BookService::class);
