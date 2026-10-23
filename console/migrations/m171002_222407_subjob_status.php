<?php

use yii\db\Migration;

class m171002_222407_subjob_status extends Migration
{
    public function safeUp()
    {
        $this->addColumn('sub_job', 'status', $this->smallInteger(1)->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('sub_job', 'status');
    }
}
