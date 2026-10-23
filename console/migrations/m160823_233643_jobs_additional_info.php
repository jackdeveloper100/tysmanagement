<?php

use yii\db\Migration;

class m160823_233643_jobs_additional_info extends Migration
{
    public function up()
    {
        $this->addColumn('job', 'slack_channel', $this->string());
        $this->addColumn('job', 'docs_link', $this->string());
        $this->addColumn('job', 'jira_link', $this->string());
    }

    public function down()
    {
        $this->dropColumn('job', 'slack_channel');
        $this->dropColumn('job', 'docs_link');
        $this->dropColumn('job', 'jira_link');
    }
}
