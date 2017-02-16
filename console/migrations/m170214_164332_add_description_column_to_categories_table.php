<?php

use yii\db\Migration;

/**
 * Handles adding description to table `categories`.
 */
class m170214_164332_add_description_column_to_categories_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('categories', 'description', $this->string(200));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('categories', 'description');
    }
}
