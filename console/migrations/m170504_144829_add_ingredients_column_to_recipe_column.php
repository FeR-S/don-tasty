<?php

use yii\db\Migration;

class m170504_144829_add_ingredients_column_to_recipe_column extends Migration
{
    public function up()
    {
        $this->addColumn('recipe', 'ingredients', 'jsonb');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('recipe', 'ingredients');
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
