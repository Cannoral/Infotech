<?php

namespace app\controllers;

use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\domains\Author\AuthorRepositoryInterface;
use app\domains\Author\AuthorServiceInterface;
use yii\filters\AccessControl;

/**
 * Контроллер для управления авторами
 * 
 * Обеспечивает CRUD операции для авторов и функционал подписки на их обновления.
 * Использует паттерн Repository и Service для разделения бизнес-логики.
 * 
 * @package app\controllers
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class AuthorController extends Controller
{
    /**
     * @var AuthorRepositoryInterface Репозиторий для работы с авторами
     */
    private AuthorRepositoryInterface $repo;
    
    /**
     * @var AuthorServiceInterface Сервис для бизнес-логики авторов
     */
    private AuthorServiceInterface $service;

    /**
     * Конструктор контроллера
     * 
     * @param string $id Идентификатор контроллера
     * @param \yii\base\Module $module Модуль контроллера
     * @param AuthorRepositoryInterface $repo Репозиторий авторов
     * @param AuthorServiceInterface $service Сервис авторов
     * @param array $config Конфигурация контроллера
     */
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

    /**
     * Настройка поведений контроллера
     * 
     * Настраивает контроль доступа для операций создания, обновления и удаления.
     * Разрешает доступ только авторизованным пользователям.
     * 
     * @return array Массив конфигураций поведений
     */
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

    /**
     * Отображение списка всех авторов
     * 
     * Получает всех авторов из репозитория и отображает их в представлении index.
     * 
     * @return string Результат рендеринга представления
     */
    public function actionIndex()
    {
        $authors = $this->repo->getAll();
        return $this->render('index', ['authors' => $authors]);
    }

    /**
     * Отображение детальной информации об авторе
     * 
     * Получает автора по идентификатору и отображает его данные.
     * Если автор не найден, выбрасывается исключение 404.
     * 
     * @param int $id Идентификатор автора
     * @return string Результат рендеринга представления
     * @throws NotFoundHttpException Если автор не найден
     */
    public function actionView($id)
    {
        $author = $this->repo->getById($id);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }
        return $this->render('view', ['author' => $author]);
    }

    /**
     * Создание нового автора
     * 
     * Обрабатывает GET запрос для отображения формы создания и POST запрос для сохранения нового автора.
     * При успешном создании перенаправляет на страницу просмотра автора.
     * При ошибках отображает форму с сообщениями об ошибках.
     * 
     * @return string|\yii\web\Response Результат рендеринга представления или редирект
     */
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

    /**
     * Обновление данных существующего автора
     * 
     * Получает автора по идентификатору и обрабатывает его обновление.
     * Обрабатывает GET запрос для отображения формы и POST запрос для сохранения изменений.
     * При успешном обновлении перенаправляет на страницу просмотра автора.
     * 
     * @param int $id Идентификатор автора
     * @return string|\yii\web\Response Результат рендеринга представления или редирект
     * @throws NotFoundHttpException Если автор не найден
     */
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

    /**
     * Удаление автора
     * 
     * Получает автора по идентификатору и удаляет его из системы.
     * После успешного удаления перенаправляет на список авторов с сообщением об успехе.
     * 
     * @param int $id Идентификатор автора
     * @return \yii\web\Response Редирект на список авторов
     * @throws NotFoundHttpException Если автор не найден
     */
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

    /**
     * Подписка на обновления автора
     * 
     * Позволяет пользователям подписываться на получение уведомлений об обновлениях автора.
     * Обрабатывает GET запрос для отображения формы подписки и POST запрос для создания подписки.
     * При успешной подписке перенаправляет на страницу автора с сообщением об успехе.
     * 
     * @param int $id Идентификатор автора
     * @return string|\yii\web\Response Результат рендеринга представления или редирект
     * @throws NotFoundHttpException Если автор не найден
     */
    public function actionSubscribe($id)
    {
        $author = $this->repo->getById($id);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        $subscription = new Subscription();

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $subscription->load($data);
            $subscription->author_id = $author->id;

            if ($subscription->validate() && $subscription->save()) {
                Yii::$app->session->setFlash('success', 'Вы подписались на обновления автора.');
                return $this->redirect(['author/view', 'id' => $author->id]);
            }
        }

        return $this->render('subscribe', [
            'author' => $author,
            'subscription' => $subscription,
        ]);
    }
}