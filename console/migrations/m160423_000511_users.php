<?php

use yii\db\Migration;

class m160423_000511_users extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey()->unique(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
            'password' => $this->string(65)->notNull(),
            'password_reset_token' => $this->string(50)->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'phone' => $this->string(64),
            'cell' => $this->string(10),
            'hourly_rate' => $this->double(2),
            'title' => $this->string()->notNull(),
            'admin' => $this->boolean()->defaultValue(false),
            'status' => $this->smallInteger(1)->defaultValue(1),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
