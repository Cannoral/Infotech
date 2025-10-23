<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class Book extends ActiveRecord
{
    public $coverFile;
    public $authorIds = [];

    public static function tableName()
    {
        return 'book';
    }

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

    // Связь многие-ко-многим
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }
}
