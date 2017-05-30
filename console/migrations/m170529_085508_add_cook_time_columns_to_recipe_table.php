<?php

use yii\db\Migration;

/**
 * Handles adding cook_time to table `recipe`.
 */
class m170529_085508_add_cook_time_columns_to_recipe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('recipe', 'cook_time', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('recipe', 'cook_time');
    }
}
