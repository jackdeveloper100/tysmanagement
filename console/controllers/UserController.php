<?php
namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class UserController extends Controller
{
    public function actionCreate()
    {
        $model = new User();
        $model->title = $this->prompt("Title: ");
        $model->email = $this->prompt("Email: ");
        $model->first_name = $this->prompt("First Name: ");
        $model->last_name = $this->prompt("Last Name: ");
        $model->slack_username = $this->prompt("Slack username: ");
        $model->setPassword($this->prompt("Password: "));
        if ($model->save()) {
            echo 'Admin created!' . "\n";
        } else {
            var_dump($model->getErrors());
        }
        return 0;
    }
}
