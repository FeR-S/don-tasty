<?php

use yii\db\Migration;

/**
 * Handles adding persons_count to table `recipe`.
 */
class m170529_085600_add_persons_count_columns_to_recipe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('recipe', 'persons_count', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('recipe', 'persons_count');
    }
}
