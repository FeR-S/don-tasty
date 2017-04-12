<?php

use yii\db\Migration;

class m170411_140615_alter_body_column_in_article_table extends Migration
{
    public function up()
    {
        $this->alterColumn('articles', 'body', $this->text());
    }

    public function down()
    {
        echo "m170411_140615_alter_body_column_in_article_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
