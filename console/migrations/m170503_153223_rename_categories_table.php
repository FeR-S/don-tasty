<?php

use yii\db\Migration;

class m170503_153223_rename_categories_table extends Migration
{
    public function up()
    {
        $this->renameTable('categories', 'category');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->renameTable('category', 'categories');
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
