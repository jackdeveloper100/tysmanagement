<?php

use yii\db\Migration;

class m160912_194037_user_token extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'access_token', $this->string());
    }

    public function down()
    {
        $this->dropColumn('user', 'access_token');
    }
}
