<?php

use yii\db\Migration;

/**
 * Handles adding announcement to table `article`.
 */
class m170206_155759_add_announcement_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('articles', 'announcement', $this->string(1024));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('articles', 'announcement');
    }
}
