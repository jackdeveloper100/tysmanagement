<?php
namespace backend\modules\accounting\controllers;

use backend\components\Controller;
use common\models\User;
use yii\web\NotFoundHttpException;

class UserReportsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['accountant']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['my'],
                        'roles' => ['employee']
                    ]
                ],
            ]
        ];
    }

    public function actionView($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionMy()
    {
        return $this->render('view_user', ['model' => \Yii::$app->user->identity]);
    }
}
