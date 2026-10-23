<?php

use yii\db\Migration;

class m160822_232111_client_logins extends Migration
{
    public function up()
    {
        $this->createTable('client_login', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string(65)->notNull(),
            'password_reset_token' => $this->string(50),
            'auth_key' => $this->string()->unique()->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->addForeignKey(
            'fk-client_login-client_id',
            'client_login',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('client_login');
    }
}
