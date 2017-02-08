<?php

use yii\db\Migration;

/**
 * Handles adding slug to table `articles`.
 */
class m170207_182639_add_slug_column_to_articles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('articles', 'slug', $this->string(255)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('articles', 'slug');
    }
}
