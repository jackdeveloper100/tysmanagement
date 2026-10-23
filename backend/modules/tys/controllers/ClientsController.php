<?php

namespace backend\modules\tys\controllers;

use backend\components\Controller;
use common\models\Client;
use backend\modules\tys\models\ClientForm;
use backend\modules\tys\models\ClientSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ClientsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $searchModel->soft_deleted = 0;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('list', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionSetDefault($id)
    {
        $model = Client::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        Client::updateAll(['is_default' => 0]);
        $model->is_default = true;
        if ($model->save(false, ['is_default'])) {
            \Yii::$app->session->addFlash('success', 'Default object changed');
        } else {
            \Yii::$app->session->addFlash('error', 'Error');
        }
        return $this->redirect(['index']);
    }

    public function actionCreate()
    {
        return $this->actionUpdate(0, true);
    }

    public function actionUpdate($id, $new = false)
    {
        if ($new) {
            $model = new Client();
        } else {
            $model = Client::findOne($id);
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $form = new ClientForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            $form->image = UploadedFile::getInstance($form, 'image');
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Object created' : 'Object updated');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    public function actionDelete($id)
    {
        /* @var $model Client */
        $model = Client::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Object removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['index']);
    }
}
