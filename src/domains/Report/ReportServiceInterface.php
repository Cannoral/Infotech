<?php

namespace app\domains\Report;

interface ReportServiceInterface
{
    public function getTopAuthors(int $year): array;
}