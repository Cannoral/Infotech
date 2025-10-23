<?php

use yii\db\Migration;
use Faker\Factory as Faker;

class m251023_121140_seed_faker_data extends Migration
{
    /**
     * Сидирование базы данных с использованием Faker
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $faker = Faker::create('ru_RU');

        // 10 авторов
        for ($i = 0; $i < 10; $i++) {
            $this->insert('author', [
                'name' => $faker->name,
            ]);
        }

        // 30 книг
        for ($i = 0; $i < 30; $i++) {
            $this->insert('book', [
                'title' => $faker->sentence(3),
                'year' => $faker->numberBetween(1800, 2025),
                'description' => $faker->text(200),
                'isbn' => $faker->isbn13,
                'cover' => null,
            ]);
        }

        // связи
        for ($i = 1; $i <= 30; $i++) {
            $authorIds = $faker->randomElements(range(1, 10), rand(1, 3));
            foreach ($authorIds as $authorId) {
                $this->insert('book_author', [
                    'book_id' => $i,
                    'author_id' => $authorId,
                ]);
            }
        }

        // подписки
        for ($i = 0; $i < 10; $i++) {
            $this->insert('subscription', [
                'phone' => $faker->unique()->e164PhoneNumber,
                'author_id' => $faker->numberBetween(1, 10),
            ]);
        }

        // пользователи
        $adminPassword = Yii::$app->security->generatePasswordHash('admin');
        $userPassword  = Yii::$app->security->generatePasswordHash('user');

        $this->batchInsert('user', ['username', 'password_hash', 'auth_key'], [
            ['admin', $adminPassword, Yii::$app->security->generateRandomString()],
            ['user',  $userPassword,  Yii::$app->security->generateRandomString()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Seed rollback is disabled.\n";
        return false;
    }
}