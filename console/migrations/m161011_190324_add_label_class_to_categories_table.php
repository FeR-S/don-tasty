<?php

use yii\db\Migration;

class m161011_190324_add_label_class_to_categories_table extends Migration
{
    public function up()
    {
        $this->addColumn('categories', 'label_class', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('categories', 'label_class');
    }
}
