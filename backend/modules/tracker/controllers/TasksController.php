<?php

namespace backend\modules\tracker\controllers;

use backend\components\Controller;
use common\models\enum\SubJobStatus;
use common\models\Job;
use common\models\Task;
use backend\modules\tracker\models\TaskForm;
use backend\modules\tracker\models\TasksSearch;
use backend\modules\tracker\models\WeeklyTaskForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TasksController extends Controller
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
        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('list', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);

    }

    public function actionAdd()
    {
        return $this->actionUpdate(0, true);
    }

    public function actionAddWeekly()
    {
        $model = new Task();
        $model->user_id = \Yii::$app->user->id;

        $form = new WeeklyTaskForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', 'Times Added');
                return $this->redirect(['add-weekly']);
            }
        }
        return $this->render('add_weekly', ['model' => $form]);
    }

    public function actionUpdate($id, $new = false)
    {
        if ($new) {
            $model = new Task();
            $model->user_id = \Yii::$app->user->id;
        } else {
            $model = Task::findOne($id);
        }
        if (!$model || (!\Yii::$app->authManager->checkAccess(\Yii::$app->user->id,
                    'manager') && $model->user_id != \Yii::$app->user->id)
        ) {
            throw new NotFoundHttpException();
        }
        $form = new TaskForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Time Added' : 'Time Updated');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    public function actionDelete($id)
    {
        /* @var $model Task */
        $model = Task::findOne($id);
        if (!$model || (!\Yii::$app->authManager->checkAccess(\Yii::$app->user->id,
                    'manager') && $model->user_id != \Yii::$app->user->id)
        ) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Time Removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['index']);
    }

    public function actionGetSubJobs($job, $only_active = false)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Job::findOne($job);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if (!empty($model->subJobs)) {
            return ArrayHelper::map(
                collect($model->subJobs)
                    ->filter(function ($item) use ($only_active) {
                        if (!$only_active) {
                            return true;
                        }
                        return $item->status != SubJobStatus::COMPLETED;
                    })
                    ->sortBy('created_at', SORT_REGULAR, true)
                    ->all(),
                'id',
                'job_number'
            );
        }
        return [];
    }
}
