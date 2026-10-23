<?php
namespace backend\modules\core\controllers;

use yii\base\Controller;
use backend\modules\core\models\LoginForm;

class OutAuthController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionSiteLogin()
    {
        $this->layout = false;
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('../auth/login', [
                'model' => $model
            ]);
        }
    }
}
