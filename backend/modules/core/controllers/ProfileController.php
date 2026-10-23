<?php
namespace backend\modules\core\controllers;

use backend\components\Controller;
use backend\modules\core\models\ProfileForm;
use common\models\User;

class ProfileController extends Controller
{
    public function actionEdit()
    {
        /**
         * @var $user User
         */
        $user = \Yii::$app->user->identity;

        $form = new ProfileForm();
        $form->setModel($user);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', 'Profile updated');
                return $this->redirect(['edit']);
            }
        }

        return $this->render('profile', ['model' => $form]);
    }
}
