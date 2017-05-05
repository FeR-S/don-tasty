<?php

use yii\db\Migration;

/**
 * Handles adding instructions to table `recipe`.
 */
class m170504_153358_add_instructions_column_to_recipe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('recipe', 'instructions', 'json');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('recipe', 'instructions');
    }
}
