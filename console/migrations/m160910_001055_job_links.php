<?php

use yii\db\Migration;

class m160910_001055_job_links extends Migration
{
    public function up()
    {
        $this->createTable('job_link', [
            'id' => $this->primaryKey(),
            'job_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);


        $this->addForeignKey(
            'fk-job_link-comp_id',
            'job_link',
            'job_id',
            'job',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('job_link');
    }
}
