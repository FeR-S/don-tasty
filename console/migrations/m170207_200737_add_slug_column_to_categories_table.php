<?php

use yii\db\Migration;

/**
 * Handles adding slug to table `categories`.
 */
class m170207_200737_add_slug_column_to_categories_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('categories', 'slug', $this->string(255)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('categories', 'slug');
    }
}
