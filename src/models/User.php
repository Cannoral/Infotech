<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель пользователя
 * 
 * Представляет пользователя системы с функциями аутентификации и авторизации.
 * Реализует интерфейс IdentityInterface для интеграции с системой безопасности Yii2.
 * Обеспечивает управление учетными записями пользователей и проверку паролей.
 * 
 * @package app\models
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 * 
 * @property int $id Идентификатор пользователя
 * @property string $username Имя пользователя (логин)
 * @property string $password_hash Хеш пароля
 * @property string $auth_key Ключ аутентификации для запоминания входа
 * @property string $created_at Дата создания записи
 * @property string $updated_at Дата последнего обновления записи
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * Возвращает имя таблицы в базе данных
     * 
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Поиск пользователя по идентификатору
     * 
     * Реализация метода интерфейса IdentityInterface.
     * 
     * @param int|string $id Идентификатор пользователя
     * @return static|null Объект пользователя или null если не найден
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Поиск пользователя по токену доступа
     * 
     * Реализация метода интерфейса IdentityInterface.
     * В данной реализации не используется (возвращает null).
     * 
     * @param mixed $token Токен доступа
     * @param mixed $type Тип токена
     * @return static|null Всегда возвращает null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Поиск пользователя по имени пользователя
     * 
     * @param string $username Имя пользователя для поиска
     * @return static|null Объект пользователя или null если не найден
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Получение идентификатора пользователя
     * 
     * Реализация метода интерфейса IdentityInterface.
     * 
     * @return int|string Идентификатор пользователя
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Получение ключа аутентификации
     * 
     * Реализация метода интерфейса IdentityInterface.
     * 
     * @return string Ключ аутентификации
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Проверка ключа аутентификации
     * 
     * Реализация метода интерфейса IdentityInterface.
     * 
     * @param string $authKey Ключ аутентификации для проверки
     * @return bool Результат проверки ключа
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Проверка пароля пользователя
     * 
     * Проверяет введенный пароль против сохраненного хеша пароля.
     * 
     * @param string $password Пароль для проверки
     * @return bool Результат проверки пароля
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Создание нового пользователя
     * 
     * Создает и сохраняет нового пользователя с хешированием пароля
     * и генерацией ключа аутентификации.
     * 
     * @param string $username Имя пользователя
     * @param string $password Пароль в открытом виде
     * @return static Созданный пользователь
     * @throws \Exception При ошибке сохранения в базу данных
     */
    public static function create(string $username, string $password): self
    {
        $user = new self();
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->save();
        return $user;
    }
}
