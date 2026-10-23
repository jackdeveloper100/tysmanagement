<?php

use yii\db\Migration;

class m161208_192132_user_descriptions extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_description_template', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'content' => $this->text()->notNull()
        ]);

        $this->addForeignKey(
            'fk-user_description_template-user_id',
            'user_description_template',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('user_description_template');
    }
}
