<?php

use yii\db\Migration;

class m160812_220216_jobs extends Migration
{
    public function up()
    {
        $this->createTable('job', [
            'id' => $this->primaryKey(),
            'internal_id' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'manager_id' => $this->integer()->notNull(),
            'sce_job_number' => $this->integer(),
            'accounting_notes' => $this->text(),
            'budget_hours' => $this->double(),
            'client_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'soft_deleted' => $this->boolean()->defaultValue(false),

            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->addForeignKey(
            'fk-job-client_id',
            'job',
            'client_id',
            'client',
            'id'
        );

        $this->addForeignKey(
            'fk-job-manager_id',
            'job',
            'manager_id',
            'user',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('job');
    }
}
