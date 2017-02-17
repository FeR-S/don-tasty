<?php

use yii\db\Migration;

class m170217_132135_insert_questions_category_to_categories_table extends Migration
{
    public function up()
    {
        $this->insert('categories', [
            'id' => 0,
            'title' => 'Новые темы',
            'parent_category_id' => 0,
            'label_class' => 'label-inverse',
            'slug' => 'new-themes',
            'description' => 'Новые темы для статей',
        ]);
    }

    public function down()
    {
        echo "m170217_132135_insert_questions_category_to_categories_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
