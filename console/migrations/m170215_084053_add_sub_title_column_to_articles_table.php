<?php

use yii\db\Migration;

/**
 * Handles adding sub_title to table `articles`.
 */
class m170215_084053_add_sub_title_column_to_articles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('articles', 'sub_title', $this->string(512));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('articles', 'sub_title');
    }
}
