<?php

use yii\db\Migration;

class m161006_072835_add_colums_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'created_at', $this->dateTime());
        $this->addColumn('users', 'updated_at', $this->dateTime());
    }

    public function down()
    {
        echo "m161006_072835_add_colums_to_user_table cannot be reverted.\n";

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
