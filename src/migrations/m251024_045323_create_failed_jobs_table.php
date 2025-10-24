<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%failed_jobs}}`.
 */
class m251024_045323_create_failed_jobs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%failed_jobs}}', [
            'id' => $this->primaryKey(),
            'channel' => $this->string(255)->notNull(),
            'job' => $this->binary()->notNull(),
            'pushed_at' => $this->integer()->notNull(),
            'failed_at' => $this->integer()->notNull(),
            'error' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx-failed_jobs-channel', '{{%failed_jobs}}', 'channel');
        $this->createIndex('idx-failed_jobs-failed_at', '{{%failed_jobs}}', 'failed_at');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%failed_jobs}}');
    }
}
