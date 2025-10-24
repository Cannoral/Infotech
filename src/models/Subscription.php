<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель подписки на обновления автора
 * 
 * Представляет подписку пользователя на получение уведомлений о новых книгах автора.
 * Связывает номер телефона подписчика с конкретным автором для отправки SMS уведомлений.
 * 
 * @package app\models
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 * 
 * @property int $id Идентификатор подписки
 * @property string $phone Номер телефона подписчика
 * @property int $author_id Идентификатор автора на которого оформлена подписка
 * @property string $created_at Дата создания подписки
 * @property string $updated_at Дата последнего обновления записи
 * 
 * @property-read Author $author Автор на которого оформлена подписка
 */
class Subscription extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * 
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * Правила валидации атрибутов модели
     * 
     * Определяет правила валидации для подписки,
     * включая проверку обязательных полей и уникальности номера телефона.
     * 
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            [['phone', 'author_id'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'unique'],
        ];
    }

    /**
     * Метки атрибутов для отображения в формах и представлениях
     * 
     * @return array Массив меток атрибутов
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'author_id' => 'Автор',
        ];
    }

    /**
     * Связь с автором (многие к одному)
     * 
     * Возвращает автора, на которого оформлена данная подписка.
     * 
     * @return \yii\db\ActiveQuery Запрос для получения автора подписки
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
