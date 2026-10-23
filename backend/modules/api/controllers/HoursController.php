<?php
namespace backend\modules\api\controllers;

use backend\modules\api\components\ApiController;
use backend\modules\tracker\models\TaskForm;
use common\models\Task;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class HoursController extends ApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'save-hours' => ['post']
                ],
            ]
        ]);
    }

    public function actionGetTodayHours()
    {
        return Task::find()->joinWith('job', 'subJob')
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere(['date' => new Expression('DATE(NOW())')])
            ->asArray()
            ->all();
    }

    public function actionSaveHours()
    {
        $model = new Task();
        $model->user_id = \Yii::$app->user->id;

        $form = new TaskForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->setAttributes(\Yii::$app->request->getBodyParams());
            if ($form->save()) {
                \Yii::$app->response->setStatusCode(201);
                return $form->model;
            }
        }
        \Yii::$app->response->setStatusCode(400);
        return $form->getErrors();
    }
}
