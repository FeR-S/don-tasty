<?php

use yii\db\Migration;

/**
 * Handles adding source to table `recipe`.
 */
class m170515_144332_add_source_column_to_recipe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('recipe', 'source', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('recipe', 'source');
    }
}
