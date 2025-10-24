<?php

namespace app\services;

use app\domains\Report\ReportRepositoryInterface;
use app\domains\Report\ReportServiceInterface;

/**
 * Сервис для бизнес-логики отчетов
 * 
 * Реализация интерфейса ReportServiceInterface для обработки запросов на формирование отчетов.
 * Координирует работу с репозиторием отчетов и применяет дополнительную бизнес-логику
 * при необходимости. Служит прослойкой между контроллерами и слоем данных.
 * 
 * @package app\services
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class ReportService implements ReportServiceInterface
{
    /**
     * @var ReportRepositoryInterface Репозиторий для получения данных отчетов
     */
    private ReportRepositoryInterface $repo;

    /**
     * Конструктор сервиса
     * 
     * @param ReportRepositoryInterface $repo Репозиторий отчетов
     */
    public function __construct(ReportRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @inheritdoc
     * 
     * Делегирует получение данных репозиторию без дополнительной обработки.
     * В будущем здесь может быть добавлена дополнительная бизнес-логика
     * (кеширование, фильтрация, форматирование данных).
     */
    public function getTopAuthors(int $year): array
    {
        return $this->repo->getTopAuthorsByYear($year);
    }
}
