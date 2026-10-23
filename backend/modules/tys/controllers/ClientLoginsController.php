<?php
namespace backend\modules\tys\controllers;

use backend\components\Controller;
use backend\modules\tys\models\ClientLoginForm;
use common\models\Client;
use common\models\ClientLogin;
use yii\web\NotFoundHttpException;

class ClientLoginsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex($client)
    {
        $model = Client::findOne($client);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $this->render('list', ['client' => $model]);
    }

    public function actionCreate($client)
    {
        return $this->actionUpdate(0, $client, true);
    }

    public function actionUpdate($id, $client = 0, $new = false)
    {
        if ($new) {
            $client = Client::findOne($client);
            if (!$client) {
                throw new NotFoundHttpException();
            }

            $model = new ClientLogin();
            $model->client_id = $client->id;
        } else {
            $model = ClientLogin::findOne($id);
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $client = $model->client;
        $form = new ClientLoginForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Object created' : 'Object updated');
                return $this->redirect(['index', 'client' => $client->id]);
            }
        }
        return $this->render('update', ['model' => $form, 'client' => $client]);
    }

    public function actionDelete($id)
    {
        /* @var $model ClientLogin */
        $model = ClientLogin::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Object removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['index', 'client' => $model->client_id]);
    }
}
