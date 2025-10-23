<?php

namespace app\controllers;

use app\models\Author;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\domains\Author\AuthorRepositoryInterface;
use app\domains\Author\AuthorServiceInterface;
use yii\filters\AccessControl;

class AuthorController extends Controller
{
    private AuthorRepositoryInterface $repo;
    private AuthorServiceInterface $service;

    public function __construct(
        $id, 
        $module,
        AuthorRepositoryInterface $repo,
        AuthorServiceInterface $service,
        $config = []
    )
    {
        $this->repo = $repo;
        $this->service = $service;
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
        $authors = $this->repo->getAll();
        return $this->render('index', ['authors' => $authors]);
    }

    public function actionView($id)
    {
        $author = $this->repo->getById($id);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }
        return $this->render('view', ['author' => $author]);
    }

    public function actionCreate()
    {
        $author = new Author();
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $created = $this->service->create($author, $data);

            if ($created) {
                Yii::$app->session->setFlash('success', 'Автор сохранен');
                return $this->redirect(['view', 'id' => $created->id]);
            }

            if (!$created && $author->hasErrors()) {
                Yii::$app->session->setFlash('error', 'Исправьте ошибки в форме');
            }
        }

        return $this->render('create', [
            'author' => $author,
        ]);
    }

    public function actionUpdate($id)
    {
        $author = $this->repo->getById($id);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $updated = $this->service->update($author, $data);

            if ($updated) {
                Yii::$app->session->setFlash('success', 'Автор обновлен');
                return $this->redirect(['view', 'id' => $updated->id]);
            }

            if (!$updated && $author->hasErrors()) {
                Yii::$app->session->setFlash('error', 'Исправьте ошибки в форме');
            }
        }

        return $this->render('update', [
            'author' => $author,
        ]);
    }

    public function actionDelete($id)
    {
        $author = $this->repo->getById($id);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        $this->repo->delete($author);
        Yii::$app->session->setFlash('success', 'Автор удален');
        return $this->redirect(['index']);
    }
}