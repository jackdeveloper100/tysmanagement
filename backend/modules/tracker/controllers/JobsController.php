<?php
namespace backend\modules\tracker\controllers;

use backend\components\Controller;
use backend\modules\tracker\models\LinkForm;
use common\models\ArtworkComp;
use common\models\Job;
use backend\modules\tracker\models\JobForm;
use backend\modules\tracker\models\JobsSearch;
use common\models\JobLink;
use common\models\User;
use yii\web\NotFoundHttpException;

class JobsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['employee']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        if (isset(\Yii::$app->request->get('JobsSearch', [])['query'])) {
            /**
             * @var $job Job
             */
            $job = Job::find()->where(['internal_id' => \Yii::$app->request->get('JobsSearch', [])['query']])->one();
            if ($job) {
                return $this->redirect($job->getUrl());
            }
        }
        $searchModel = new JobsSearch();
        $searchModel->soft_deleted = 0;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        if (\Yii::$app->request->get('list_view', false)) {
            return $this->render('list', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        }
        return $this->render('list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionToggleFavorite($id)
    {
        $model = Job::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        /**
         * @var $user User
         */
        $user = \Yii::$app->user->identity;
        if (collect($user->favorites)->where('id', $model->id)->count() > 0) {
            $user->unlink('favorites', $model, true);
        } else {
            $user->link('favorites', $model);
        }
        \Yii::$app->response->setStatusCode(201);
        return;
    }

    public function actionView($id)
    {
        $model = Job::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        return $this->actionUpdate(0, true);
    }

    public function actionUpdate($id, $new = false)
    {
        if ($new) {
            $model = new Job();
        } else {
            $model = Job::findOne($id);
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $form = new JobForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Object created' : 'Object updated');
                return $this->redirect(['update', 'id' => $form->model->id]);
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    public function actionDelete($id)
    {
        /* @var $model Job */
        $model = Job::findOne($id);
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

    public function actionCreateLink($id)
    {
        $model = Job::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $form = new LinkForm();
        $form->setModel(new JobLink(['job_id' => $model->id]));
        $form->load(\Yii::$app->request->post());
        if ($form->save()) {
            \Yii::$app->session->setFlash('success', 'Object created');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDeleteLink($id)
    {
        /* @var $model JobLink */
        $model = JobLink::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Object removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['view', 'id' => $model->job->id]);
    }
}
