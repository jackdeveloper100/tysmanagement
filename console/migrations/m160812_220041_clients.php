<?php

use yii\db\Migration;

class m160812_220041_clients extends Migration
{
    public function up()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'is_default' => $this->boolean()->defaultValue(false),
            'soft_deleted' => $this->boolean()->defaultValue(false)
        ]);
    }

    public function down()
    {
        $this->dropTable('client');
    }
}
