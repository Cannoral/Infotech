<?php

namespace app\services;

use app\domains\Report\ReportRepositoryInterface;
use app\domains\Report\ReportServiceInterface;

class ReportService implements ReportServiceInterface
{
    private ReportRepositoryInterface $repo;

    public function __construct(ReportRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getTopAuthors(int $year): array
    {
        return $this->repo->getTopAuthorsByYear($year);
    }
}
