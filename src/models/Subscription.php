<?php

namespace app\models;

use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName()
    {
        return 'subscription';
    }

    public function rules()
    {
        return [
            [['phone', 'author_id'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'author_id' => 'Автор',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
