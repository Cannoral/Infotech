<?php

namespace app\controllers;

use app\services\ReportService;
use app\repositories\ReportRepository;
use app\domains\Report\ReportRepositoryInterface;
use app\domains\Report\ReportServiceInterface;
use Yii;
use yii\web\Controller;

class ReportController extends Controller
{
    private ReportServiceInterface $service;
    private ReportRepositoryInterface $repo;

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
