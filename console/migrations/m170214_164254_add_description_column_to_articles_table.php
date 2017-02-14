<?php

use yii\db\Migration;

/**
 * Handles adding description to table `articles`.
 */
class m170214_164254_add_description_column_to_articles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('articles', 'description', $this->string(200));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('articles', 'description');
    }
}
