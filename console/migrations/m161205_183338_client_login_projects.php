<?php

use yii\db\Migration;

class m161205_183338_client_login_projects extends Migration
{
    public function safeUp()
    {
        $this->addColumn('client_login', 'all_project_access', $this->boolean()->defaultValue(false));
        $this->createTable('client_login_project', [
            'client_login_id' => $this->integer()->notNull(),
            'project_id' => $this->integer()->notNull(),
            'PRIMARY KEY(client_login_id,project_id)'
        ]);

        $this->addForeignKey(
            'fk-client_login_project-client_login_id',
            'client_login_project',
            'client_login_id',
            'client_login',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-client_login_project-project_id',
            'client_login_project',
            'project_id',
            'job',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('client_login_project');
        $this->dropColumn('client_login', 'all_project_access');
    }
}
