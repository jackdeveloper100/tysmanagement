<?php
namespace backend\modules\api\controllers;

use backend\modules\api\components\ApiController;
use backend\modules\tracker\models\TaskForm;
use common\models\enum\JobStatus;
use common\models\Job;
use common\models\SubJob;
use common\models\Task;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class JobsController extends ApiController
{
    public function actionGetProjects()
    {
        return ArrayHelper::map(Job::find()->all(), 'id', function ($item) {
            return $item->internal_id . ' ' . $item->name;
        });
    }

    public function actionGetCurrentProjects()
    {
        return ArrayHelper::map(Job::find()->where(['status' => JobStatus::CURRENT])->all(),
            'id', function ($item) {
                return $item->internal_id . ' ' . $item->name;
            });
    }

    public function actionGetSubProjects($id)
    {
        return ArrayHelper::map(SubJob::find()->where(['job_id' => $id])->all(), 'id', 'job_number');
    }
}
