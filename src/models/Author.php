<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель автора
 * 
 * Представляет автора в системе управления книгами.
 * Содержит информацию об авторе и связи с его книгами и подписчиками.
 * 
 * @package app\models
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 * 
 * @property int $id Идентификатор автора
 * @property string $name Полное имя автора
 * @property string $created_at Дата создания записи
 * @property string $updated_at Дата последнего обновления записи
 * 
 * @property-read Book[] $books Книги автора
 * @property-read Subscription[] $subscribers Подписчики автора
 */
class Author extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * 
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * Правила валидации атрибутов модели
     * 
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'ФИО автора',
        ];
    }

    /**
     * Связь с книгами автора (многие ко многим)
     * 
     * Возвращает все книги, которые написал данный автор.
     * Связь реализована через промежуточную таблицу book_author.
     * 
     * @return \yii\db\ActiveQuery Запрос для получения книг автора
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('book_author', ['author_id' => 'id']);
    }

    /**
     * Связь с подписчиками автора (один ко многим)
     * 
     * Возвращает всех пользователей, подписанных на обновления данного автора.
     * 
     * @return \yii\db\ActiveQuery Запрос для получения подписчиков автора
     */
    public function getSubscribers()
    {
        return $this->hasMany(Subscription::class, ['author_id' => 'id']);
    }
}
