<?php

namespace app\controllers;

use app\models\Book;
use app\services\BookService;
use app\repositories\BookRepository;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\domains\Book\BookRepositoryInterface;
use app\domains\Book\BookServiceInterface;

class BookController extends Controller
{
    private BookServiceInterface $service;
    private BookRepositoryInterface $repo;

    public function __construct($id, $module, $config = [])
    {
        $this->repo = new BookRepository();
        $this->service = new BookService($this->repo);
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
                        'roles' => ['@'], // только авторизованные
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
        $data = Yii::$app->request->post();
        $book = $this->service->create($data);
        if ($book) {
            return $this->redirect(['view', 'id' => $book->id]);
        }
        return $this->render('create');
    }

    public function actionUpdate($id)
    {
        $book = $this->repo->getById($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        $data = Yii::$app->request->post();
        $updated = $this->service->update($book, $data);
        if ($updated) {
            return $this->redirect(['view', 'id' => $book->id]);
        }

        return $this->render('update', ['book' => $book]);
    }

    public function actionDelete($id)
    {
        $book = $this->repo->getById($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        $this->service->delete($book);
        return $this->redirect(['index']);
    }
}
