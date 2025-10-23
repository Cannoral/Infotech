<?php

namespace app\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
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
            [['cover'], 'string', 'max' => 255],
            [['isbn'], 'unique'],
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
