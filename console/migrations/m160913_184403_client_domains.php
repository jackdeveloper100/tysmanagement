<?php

use yii\db\Migration;

class m160913_184403_client_domains extends Migration
{
    public function up()
    {
        $this->createTable('domains', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'domain' => $this->string()->notNull(),
            'registrar' => $this->string()->notNull(),
            'auto_renew' => $this->boolean()->defaultValue(false)->notNull(),
            'expiration_date' => $this->date()->notNull()
        ]);

        $this->addForeignKey(
            'fk-domains-client_id',
            'domains',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('domains');
    }
}
