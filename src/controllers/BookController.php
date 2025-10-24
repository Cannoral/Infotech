<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\domains\Book\BookRepositoryInterface;
use app\domains\Book\BookServiceInterface;
use app\domains\Cover\CoverServiceInterface;
use app\domains\Subscription\SubscriptionServiceInterface;
use yii\helpers\ArrayHelper;

use app\jobs\SmsNotificationJob;

class BookController extends Controller
{
    private BookServiceInterface $service;
    private BookRepositoryInterface $repo;
    private CoverServiceInterface $coverService;
    private SubscriptionServiceInterface $subscriptionService;

    public function __construct(
        $id, 
        $module, 
        BookServiceInterface $service,
        BookRepositoryInterface $repo,
        CoverServiceInterface $coverService,
        SubscriptionServiceInterface $subscriptionService,
        $config = []
    )
    {
        $this->repo = $repo;
        $this->service = $service;
        $this->coverService = $coverService;
        $this->subscriptionService = $subscriptionService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $books = $this->repo->getAll();
        return $this->render('index', ['books' => $books]);
    }

    public function actionView($id)
    {
        $book = $this->repo->getById($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }
        return $this->render('view', ['book' => $book]);
    }

    public function actionCreate()
    {
        $book = new Book();

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $created = $this->service->create($book, $data);

            if ($created) {
                Yii::$app->session->setFlash('success', 'Книга сохранена');
                return $this->redirect(['view', 'id' => $created->id]);
            }

            if (!$created && $book->hasErrors()) {
                Yii::$app->session->setFlash('error', 'Исправьте ошибки в форме');
            }
        }

        return $this->render('create', [
            'book' => $book,
        ]);
    }

    public function actionUpdate($id)
    {
        $book = $this->repo->getById($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $updated = $this->service->update($book, $data);

            if ($updated) {
                Yii::$app->session->setFlash('success', 'Книга обновлена');
                return $this->redirect(['view', 'id' => $updated->id]);
            }

            if (!$updated && $book->hasErrors()) {
                Yii::$app->session->setFlash('error', 'Исправьте ошибки в форме');
            }
        }

        $book->authorIds = ArrayHelper::getColumn($book->authors, 'id');

        return $this->render('update', [
            'book' => $book,
        ]);
    }

    public function actionDelete($id)
    {
        $book = $this->repo->getById($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        $this->service->delete($book);
        Yii::$app->session->setFlash('success', 'Книга удалена');
        return $this->redirect(['index']);
    }

    public function actionTest()
    {
        
        $job = new SmsNotificationJob([
            'phone' => '1234567890',
            'message' => 'Test SMS message',
        ]);

        dd($job);
    }
}
