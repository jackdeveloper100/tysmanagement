<?php
namespace backend\modules\tys\controllers;

use backend\components\Controller;
use backend\modules\tys\models\DomainForm;
use backend\modules\tys\models\DomainSearch;
use common\models\Client;
use common\models\Domains;
use yii\web\NotFoundHttpException;

class DomainsController extends Controller
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

    public function actionAll()
    {
        $searchModel = new DomainSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('all', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
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

            $model = new Domains();
            $model->client_id = $client->id;
        } else {
            $model = Domains::findOne($id);
            $client = $model->client;
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $form = new DomainForm();
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
        /* @var $model Domains */
        $model = Domains::findOne($id);
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
