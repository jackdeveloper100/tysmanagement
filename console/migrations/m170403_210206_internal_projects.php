<?php

use yii\db\Migration;

class m170403_210206_internal_projects extends Migration
{
    public function safeUp()
    {
        $this->addColumn('job', 'internal', $this->boolean()->defaultValue(false)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('job', 'internal');
    }
}
