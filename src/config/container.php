<?php

/**
 * Зависимости для каталога книг внедрения через контейнер
 */
use app\domains\Book\BookRepositoryInterface;
use app\domains\Book\BookServiceInterface;
use app\repositories\BookRepository;
use app\services\BookService;

Yii::$container->set(BookRepositoryInterface::class, BookRepository::class);
Yii::$container->set(BookServiceInterface::class, BookService::class);


/**
 * Зависимости для отчетов для внедрения через контейнер
 */
use app\repositories\ReportRepository;
use app\services\ReportService;
use app\domains\Report\ReportRepositoryInterface;
use app\domains\Report\ReportServiceInterface;

Yii::$container->set(ReportRepositoryInterface::class, ReportRepository::class);
Yii::$container->set(ReportServiceInterface::class, ReportService::class);

/**
 * Зависимости для авторов для внедрения через контейнер
 */
use app\domains\Author\AuthorRepositoryInterface;
use app\domains\Author\AuthorServiceInterface;
use app\repositories\AuthorRepository;
use app\services\AuthorService;

Yii::$container->set(AuthorRepositoryInterface::class, AuthorRepository::class);
Yii::$container->set(AuthorServiceInterface::class, AuthorService::class);

/**
 * Зависимости для обложек книг для внедрения через контейнер
 */
use app\domains\Cover\CoverServiceInterface;
use app\services\CoverService;

Yii::$container->set(CoverServiceInterface::class, CoverService::class);

/**
 * Зависимости для подписок для внедрения через контейнер
 */
use app\domains\Subscription\SubscriptionServiceInterface;
use app\services\SubscriptionService;

Yii::$container->set(SubscriptionServiceInterface::class, SubscriptionService::class);