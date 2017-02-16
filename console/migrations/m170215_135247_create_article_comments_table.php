<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_comments`.
 */
class m170215_135247_create_article_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_comments', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'body' => $this->text()->notNull(),
            'status' => $this->smallInteger()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_comments');
    }
}
