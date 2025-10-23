<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_subscription}}`.
 */
class m251023_101651_create_book_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(20)->notNull()->unique(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk_subscription_author', 'subscription', 'author_id', 'author', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_subscription}}');
    }
}
