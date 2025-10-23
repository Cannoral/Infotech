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
 * Зависимости для отчетов внедрения через контейнер
 */
use app\repositories\ReportRepository;
use app\services\ReportService;
use app\domains\Report\ReportRepositoryInterface;
use app\domains\Report\ReportServiceInterface;

Yii::$container->set(ReportRepositoryInterface::class, ReportRepository::class);
Yii::$container->set(ReportServiceInterface::class, ReportService::class);
