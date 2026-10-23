<?php

use yii\db\Migration;

class m160913_191409_client_hosting extends Migration
{
    public function up()
    {
        $this->createTable('hosting', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'provider' => $this->string()->notNull(),
            'auto_renew' => $this->boolean()->defaultValue(false)->notNull(),
            'expiration_date' => $this->date()->notNull()
        ]);

        $this->addForeignKey(
            'fk-hosting-client_id',
            'hosting',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('hosting');
    }
}
