<?php

use yii\db\Migration;

/**
 * Handles the creation for table `articles`.
 */
class m160930_174116_create_articles_table extends Migration
{
    /**
     *
     */
    public function up()
    {
        $this->createTable('articles', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'body' => $this->string(1024),
            'user_id' => $this->integer(),
            'category_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'source' => $this->string(512),
            'rating' => $this->integer(),
            'views' => $this->integer(),
            'status' => $this->smallInteger()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('articles');
    }
}
