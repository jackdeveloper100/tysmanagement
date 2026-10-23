<?php

namespace backend\modules\tys\controllers;

use backend\components\Controller;
use backend\modules\tys\models\UserForm;
use backend\modules\tys\models\UserSearch;
use common\models\User;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superuser']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('list', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        return $this->actionUpdate(0, true);
    }

    public function actionUpdate($id, $new = false)
    {
        if ($new) {
            $model = new User();
        } else {
            $model = User::findOne($id);
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $form = new UserForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Object created' : 'Object updated');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $form]);
    }
}
