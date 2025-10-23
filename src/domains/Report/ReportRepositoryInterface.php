<?php

namespace app\domains\Report;

interface ReportRepositoryInterface
{
    /**
     * Возвращает топ-10 авторов по количеству книг за указанный год.
     *
     * @param int $year
     * @return array<int, array{name: string, total: int}>
     */
    public function getTopAuthorsByYear(int $year): array;
}