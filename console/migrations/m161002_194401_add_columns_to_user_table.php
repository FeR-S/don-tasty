<?php

use yii\db\Migration;

class m161002_194401_add_columns_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'first_name', $this->string(255));
        $this->addColumn('users', 'last_name', $this->string(255));
        $this->addColumn('users', 'age', $this->integer(2));
        $this->addColumn('users', 'work_experience', $this->string(255));
        $this->addColumn('users', 'city', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('users', 'first_name');
        $this->dropColumn('users', 'last_name');
        $this->dropColumn('users', 'age');
        $this->dropColumn('users', 'work_experience');
        $this->dropColumn('users', 'city');
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
