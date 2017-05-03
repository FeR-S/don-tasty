<?php

use yii\db\Migration;

/**
 * Handles the creation of table `recipe`.
 */
class m170503_152821_create_recipe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('recipe', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'user_id' => $this->integer(),
            'category_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'status' => $this->integer(),
            'slug' => $this->string(255),
            'description' => $this->string(255),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('recipe');
    }
}
