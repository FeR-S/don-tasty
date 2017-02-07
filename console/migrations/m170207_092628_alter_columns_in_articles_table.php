<?php

use yii\db\Migration;

class m170207_092628_alter_columns_in_articles_table extends Migration
{
    public function up()
    {
        $this->alterColumn('articles', 'body', $this->text());
        $this->alterColumn('articles', 'announcement', $this->text());
    }

    public function down()
    {
        $this->alterColumn('articles', 'body', $this->string(2048));
        $this->alterColumn('articles', 'announcement', $this->string(1024));
    }
}
