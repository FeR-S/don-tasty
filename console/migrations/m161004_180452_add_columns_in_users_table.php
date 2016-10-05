<?php

use yii\db\Migration;

class m161004_180452_add_columns_in_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'created_at', 'datetime');
        $this->addColumn('users', 'updated_at', 'datetime');
    }

    public function down()
    {
        echo "m161004_180452_add_columns_in_users_table cannot be reverted.\n";

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
