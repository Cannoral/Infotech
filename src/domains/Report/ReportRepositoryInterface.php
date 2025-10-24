<?php

namespace app\domains\Report;

/**
 * Интерфейс репозитория для работы с отчетами
 * 
 * Определяет контракт для операций получения аналитических данных и отчетов.
 * Реализует паттерн Repository для абстрагирования от конкретной реализации
 * запросов к хранилищу данных для формирования отчетности.
 * 
 * @package app\domains\Report
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
interface ReportRepositoryInterface
{
    /**
     * Получение топ авторов по количеству книг за указанный год
     * 
     * Возвращает список самых продуктивных авторов (топ-10) 
     * отсортированных по количеству опубликованных книг за заданный год.
     *
     * @param int $year Год для формирования отчета
     * @return array<int, array{name: string, total: int}> Массив с данными авторов:
     *                                                     - name: имя автора
     *                                                     - total: количество книг
     */
    public function getTopAuthorsByYear(int $year): array;
}