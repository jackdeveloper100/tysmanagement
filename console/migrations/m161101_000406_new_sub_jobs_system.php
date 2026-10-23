<?php

use yii\db\Migration;

class m161101_000406_new_sub_jobs_system extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('sub_job', 'job_description');
        $this->addColumn('sub_job', 'sce_job_number', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('sub_job', 'sce_job_number');
        $this->addColumn('sub_job', 'job_description', $this->text()->notNull());
    }

}
