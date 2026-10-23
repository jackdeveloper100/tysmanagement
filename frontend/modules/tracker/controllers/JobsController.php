<?php
namespace frontend\modules\tracker\controllers;

use common\models\Job;
use frontend\components\Controller;
use frontend\modules\tracker\models\JobsSearch;
use yii\web\NotFoundHttpException;

class JobsController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new JobsSearch();
        $searchModel->soft_deleted = 0;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $model = Job::findOne($id);
        if (!$model || $model->client_id != \Yii::$app->user->identity->client_id) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['model' => $model]);
    }
}
