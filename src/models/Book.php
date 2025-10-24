<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Модель книги
 * 
 * Представляет книгу в системе управления библиотекой.
 * Содержит информацию о книге, её авторах и связанных данных.
 * Поддерживает загрузку обложек и управление связями с авторами.
 * 
 * @package app\models
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 * 
 * @property int $id Идентификатор книги
 * @property string $title Название книги
 * @property string|null $description Описание книги
 * @property int $year Год выпуска
 * @property string|null $isbn ISBN номер книги
 * @property string|null $cover Путь к файлу обложки
 * @property string $created_at Дата создания записи
 * @property string $updated_at Дата последнего обновления записи
 * 
 * @property-read Author[] $authors Авторы книги
 * 
 * @property mixed $coverFile Временное свойство для загрузки файла обложки
 * @property array $authorIds Массив идентификаторов авторов для привязки
 */
class Book extends ActiveRecord
{
    /**
     * @var mixed Временное свойство для загрузки файла обложки
     */
    public $coverFile;
    
    /**
     * @var array Массив идентификаторов авторов для привязки к книге
     */
    public $authorIds = [];

    /**
     * Возвращает имя таблицы в базе данных
     * 
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * Правила валидации атрибутов модели
     * 
     * Определяет правила валидации для всех атрибутов книги,
     * включая проверку обязательных полей, типов данных и загружаемых файлов.
     * 
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer', 'min' => 0],
            [['isbn'], 'string', 'max' => 32],
            [['coverFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'jpeg', 'png', 'webp']],
            [['isbn'], 'unique'],
            [['authorIds'], 'each', 'rule' => ['integer']],
            [['cover'], 'string', 'max' => 255],
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
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover' => 'Обложка',
        ];
    }

    /**
     * Связь с авторами книги (многие ко многим)
     * 
     * Возвращает всех авторов данной книги.
     * Связь реализована через промежуточную таблицу book_author.
     * 
     * @return \yii\db\ActiveQuery Запрос для получения авторов книги
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }
}
