<?php

use yii\db\Migration;

class m170530_091302_alter_title_column_in_category_table extends Migration
{
    public function up()
    {
        $this->alterColumn('category', 'title', $this->string(1024));
    }

    public function down()
    {
        echo "m170530_091302_alter_title_column_in_category_table cannot be reverted.\n";

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
