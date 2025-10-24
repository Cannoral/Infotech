<?php

namespace app\controllers;

use app\services\ReportService;
use app\repositories\ReportRepository;
use app\domains\Report\ReportRepositoryInterface;
use app\domains\Report\ReportServiceInterface;
use Yii;
use yii\web\Controller;

/**
 * Контроллер для генерации отчетов
 * 
 * Предоставляет функционал для создания различных отчетов и аналитических данных.
 * Использует паттерны Repository и Service для разделения бизнес-логики.
 * 
 * @package app\controllers
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class ReportController extends Controller
{
    /**
     * @var ReportServiceInterface Сервис для бизнес-логики отчетов
     */
    private ReportServiceInterface $service;
    
    /**
     * @var ReportRepositoryInterface Репозиторий для работы с данными отчетов
     */
    private ReportRepositoryInterface $repo;

    /**
     * Конструктор контроллера
     * 
     * @param string $id Идентификатор контроллера
     * @param \yii\base\Module $module Модуль контроллера
     * @param ReportRepositoryInterface $repo Репозиторий отчетов
     * @param ReportServiceInterface $service Сервис отчетов
     * @param array $config Конфигурация контроллера
     */
    public function __construct(
        $id, 
        $module, 
        ReportRepositoryInterface $repo,
        ReportServiceInterface $service,
        $config = []
    )
    {
        $this->repo = $repo;
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Отчет по топ авторам за указанный год
     * 
     * Генерирует отчет о самых популярных авторах за заданный год.
     * Если год не указан, используется текущий год.
     * Возвращает список авторов с их статистикой.
     * 
     * @param int|null $year Год для формирования отчета (по умолчанию текущий год)
     * @return string Результат рендеринга представления с отчетом
     */
    public function actionTopAuthors($year = null)
    {
        $year = $year ?: date('Y');
        $data = $this->service->getTopAuthors((int)$year);
        return $this->render('top-authors', [
            'authors' => $data,
            'year' => $year,
        ]);
    }
}
