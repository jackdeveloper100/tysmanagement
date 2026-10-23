<?php

use yii\db\Migration;

class m160812_221951_tasks extends Migration
{
    public function up()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'job_id' => $this->integer()->notNull(),
            'sub_job_id' => $this->integer(),
            'task_item_id' => $this->smallInteger(10)->notNull(),
            'description' => $this->text()->notNull(),
            'date' => $this->date()->notNull(),
            'hours' => $this->double(2)->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->addForeignKey(
            'fk-task-user_id',
            'task',
            'user_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-task-job_id',
            'task',
            'job_id',
            'job',
            'id'
        );

        $this->addForeignKey(
            'fk-task-sub_job_id',
            'task',
            'sub_job_id',
            'sub_job',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('task');
    }
}
