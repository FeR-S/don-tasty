<?php

use yii\db\Migration;

class m170530_084154_alter_description_table_in_recipe_table extends Migration
{
    public function up()
    {
        $this->alterColumn('recipe', 'description', $this->string(1024));
    }

    public function down()
    {
        echo "m170530_084154_alter_description_table_in_recipe_table cannot be reverted.\n";

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
