<?php

use yii\db\Migration;

/**
 * Handles adding specialization to table `users`.
 */
class m161003_193111_add_specialization_column_to_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('users', 'specialization', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('users', 'specialization');
    }
}
