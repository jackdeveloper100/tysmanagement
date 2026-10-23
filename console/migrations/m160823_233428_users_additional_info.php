<?php

use yii\db\Migration;

class m160823_233428_users_additional_info extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'slack_username', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('user', 'slack_username');
    }
}
