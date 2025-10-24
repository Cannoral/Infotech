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

/**
 * Контроллер для управления книгами
 * 
 * Обеспечивает CRUD операции для книг с интеграцией обложек и уведомлений подписчиков.
 * Использует паттерны Repository и Service для разделения бизнес-логики.
 * 
 * @package app\controllers
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class BookController extends Controller
{
    /**
     * @var BookServiceInterface Сервис для бизнес-логики книг
     */
    private BookServiceInterface $service;
    
    /**
     * @var BookRepositoryInterface Репозиторий для работы с книгами
     */
    private BookRepositoryInterface $repo;
    
    /**
     * @var CoverServiceInterface Сервис для работы с обложками книг
     */
    private CoverServiceInterface $coverService;
    
    /**
     * @var SubscriptionServiceInterface Сервис для управления подписками
     */
    private SubscriptionServiceInterface $subscriptionService;

    /**
     * Конструктор контроллера
     * 
     * @param string $id Идентификатор контроллера
     * @param \yii\base\Module $module Модуль контроллера
     * @param BookServiceInterface $service Сервис книг
     * @param BookRepositoryInterface $repo Репозиторий книг
     * @param CoverServiceInterface $coverService Сервис обложек
     * @param SubscriptionServiceInterface $subscriptionService Сервис подписок
     * @param array $config Конфигурация контроллера
     */
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
     * Отображение списка всех книг
     * 
     * Получает все книги из репозитория и отображает их в представлении index.
     * 
     * @return string Результат рендеринга представления
     */
    public function actionIndex()
    {
        $books = $this->repo->getAll();
        return $this->render('index', ['books' => $books]);
    }

    /**
     * Отображение детальной информации о книге
     * 
     * Получает книгу по идентификатору и отображает её данные.
     * Если книга не найдена, выбрасывается исключение 404.
     * 
     * @param int $id Идентификатор книги
     * @return string Результат рендеринга представления
     * @throws NotFoundHttpException Если книга не найдена
     */
    public function actionView($id)
    {
        $book = $this->repo->getById($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }
        return $this->render('view', ['book' => $book]);
    }

    /**
     * Создание новой книги
     * 
     * Обрабатывает GET запрос для отображения формы создания и POST запрос для сохранения новой книги.
     * При успешном создании перенаправляет на страницу просмотра книги.
     * При ошибках отображает форму с сообщениями об ошибках.
     * 
     * @return string|\yii\web\Response Результат рендеринга представления или редирект
     */
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

    /**
     * Обновление данных существующей книги
     * 
     * Получает книгу по идентификатору и обрабатывает её обновление.
     * Обрабатывает GET запрос для отображения формы и POST запрос для сохранения изменений.
     * При успешном обновлении перенаправляет на страницу просмотра книги.
     * Подготавливает массив идентификаторов авторов для отображения в форме.
     * 
     * @param int $id Идентификатор книги
     * @return string|\yii\web\Response Результат рендеринга представления или редирект
     * @throws NotFoundHttpException Если книга не найдена
     */
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

    /**
     * Удаление книги
     * 
     * Получает книгу по идентификатору и удаляет её из системы.
     * После успешного удаления перенаправляет на список книг с сообщением об успехе.
     * 
     * @param int $id Идентификатор книги
     * @return \yii\web\Response Редирект на список книг
     * @throws NotFoundHttpException Если книга не найдена
     */
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
}
