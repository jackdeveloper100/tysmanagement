<?php

use yii\db\Migration;

class m160812_220226_sub_jobs extends Migration
{
    public function up()
    {
        $this->createTable('sub_job', [
            'id' => $this->primaryKey(),
            'job_id' => $this->integer()->notNull(),
            'job_number' => $this->string()->notNull(),
            'job_description' => $this->text()->notNull(),
            'soft_deleted' => $this->boolean()->defaultValue(false),

            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->addForeignKey(
            'fk-sub_job-job_id',
            'sub_job',
            'job_id',
            'job',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('sub_job');
    }
}
