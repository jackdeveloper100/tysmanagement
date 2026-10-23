<?php

use yii\db\Migration;

class m160912_170404_rbac_roles extends Migration
{
    public function up()
    {
        $auth = \Yii::$app->authManager;

        $auth->removeAll();

        $roles['employee'] = $auth->createRole('employee');
        $roles['employee']->description = 'General employee';
        $auth->add($roles['employee']);

        $roles['manager'] = $auth->createRole('manager');
        $roles['manager']->description = 'Manager';
        $auth->add($roles['manager']);
        $auth->addChild($roles['manager'], $roles['employee']);


        $roles['accountant'] = $auth->createRole('accountant');
        $roles['accountant']->description = 'Accountant';
        $auth->add($roles['accountant']);
        $auth->addChild($roles['accountant'], $roles['manager']);

        $superuser = $auth->createRole('superuser');
        $superuser->description = 'Super user';
        $auth->add($superuser);
        foreach ($roles as $role) {
            $auth->addChild($superuser, $role);
        }
    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
