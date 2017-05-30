<?php

use yii\db\Migration;

/**
 * Handles adding tags to table `recipe`.
 */
class m170526_142347_add_tags_columns_to_recipe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('recipe', 'tags', 'jsonb');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('recipe', 'tags');
    }
}
