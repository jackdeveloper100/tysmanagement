<?php

use yii\db\Migration;

class m160912_170401_rbac_init extends Migration
{
    public function up()
    {
        $this->proxy->migrateUp('m140506_102106_rbac_init');
    }

    public function down()
    {
        $this->proxy->migrateDown('m140506_102106_rbac_init');
    }

    protected function getProxy()
    {
        return new console\components\MigrationProxy(['migrationPath' => '@yii/rbac/migrations']);
    }
}
