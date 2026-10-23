<?php
/**
 * @var $this \yii\web\View
 */
use yii\db\Expression;
use yii\helpers\ArrayHelper;

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\accounting\assets\JobReportsAsset::register($this);
?>
<div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed" style="margin-bottom: 0;">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?= \yii\helpers\Url::to(['index']) ?>">Hours reports</a></li>
        <li><a href="<?= \yii\helpers\Url::to(['projects']) ?>">Projects reports</a></li>
    </ul>
</div>
<div class="portlet light">
    <div class="portlet-body">
        <form method="get">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <?= \nex\chosen\Chosen::widget([
                                'id' => 'job',
                                'name' => 'job',
                                'items' => \common\models\Job::getDataList(true),
                                'value' => Yii::$app->request->get('job'),
                                'placeholder' => 'Filter by project'
                            ]) ?>
                        </div>
                    </div>
                    <div class="row sub_job_container" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <?= \nex\chosen\Chosen::widget([
                                'id' => 'sub_job_id',
                                'name' => 'sub_job_id',
                                'items' => Yii::$app->request->get('job') > 0 ? \yii\helpers\ArrayHelper::map(\common\models\SubJob::find()->where(['job_id' => Yii::$app->request->get('job')])->all(),
                                    'id', 'job_number') : [],
                                'value' => Yii::$app->request->get('sub_job_id'),
                                'placeholder' => 'Filter by sub job'
                            ]) ?>
                        </div>
                    </div>
                    <h5 class="text-center">Date of work</h5>
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-6">
                            <?= \common\widgets\DatePicker::widget([
                                'name' => 'from',
                                'value' => Yii::$app->request->get('from')
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= \common\widgets\DatePicker::widget([
                                'name' => 'to',
                                'value' => Yii::$app->request->get('to')
                            ]) ?>
                        </div>
                    </div>
                    <h5 class="text-center">Creation date</h5>
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-6">
                            <?= \common\widgets\DatePicker::widget([
                                'name' => 'entry_from',
                                'value' => Yii::$app->request->get('entry_from')
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= \common\widgets\DatePicker::widget([
                                'name' => 'entry_to',
                                'value' => Yii::$app->request->get('entry_to')
                            ]) ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn blue-dark" value="Search">
                        <button class="btn green-meadow export">Export</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="margin-bottom: 10px;">
                        <h5 class="text-center" style="margin-top: 0;">Only clients</h5>
                        <?= \nex\chosen\Chosen::widget([
                            'name' => 'clients',
                            'multiple' => true,
                            'items' => \common\models\Client::getDataList(true),
                            'value' => Yii::$app->request->get('clients', []),
                            'placeholder' => 'Filter by client'
                        ]) ?>
                    </div>
                    <h5 class="text-center">Exclude clients</h5>
                    <?= \nex\chosen\Chosen::widget([
                        'name' => 'clientsExclude',
                        'multiple' => true,
                        'items' => \common\models\Client::getDataList(true),
                        'value' => Yii::$app->request->get('clientsExclude', []),
                        'placeholder' => 'Exclude client'
                    ]) ?>
                </div>
            </div>
        </form>

        <?php


        echo "<h4 class='text-center'>Total hours found: " . round($q->sum('hours'), 2) . "</h4>";


        echo common\widgets\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $q,
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ]),
            'pjax' => false,
            'columns' => [
                'date:date',
                [
                    'attribute' => 'user.first_name',
                    'header' => 'Employee',
                    'value' => function ($model) {
                        return $model->user->getShortName();
                    }
                ],
                ['attribute' => 'user.title'],
                [
                    'attribute' => 'job.name',
                    'header' => 'Job',
                    'value' => function ($model) {
                        return $model->job->internal_id . ' - ' . $model->job->name;
                    }
                ],
                [
                    'attribute' => 'job.client.name',
                    'header' => 'Client'
                ],
                [
                    'attribute' => 'task_item_id',
                    'value' => function ($model) {
                        return \common\models\enum\TaskItem::getByValue($model->task_item_id)->text();
                    }
                ],
                ['attribute' => 'description'],
                /*[
                    'attribute' => 'job.status',
                    'filter' => \common\models\enum\JobStatus::getDataList(),
                    'value' => function ($model) {
                        return \common\models\enum\JobStatus::getByValue($model->job->status)->text();
                    }
                ],*/
                /* [
                     'attribute' => 'job.manager_id',
                     'value' => function ($model) {
                         return $model->job->manager->getShortName();
                     }
                 ],*/
                [
                    'header' => 'SCE Job Name',
                    'value' => function ($model) use ($sce_jobs) {
                        if ($model->subJob) {
                            if ($model->subJob->sce_job_number && isset($sce_jobs[$model->subJob->sce_job_number])) {
                                return $sce_jobs[$model->subJob->sce_job_number]->legacy_id . ' ' . $sce_jobs[$model->subJob->sce_job_number]->name;
                            }
                        } elseif ($model->job->sce_job_number && isset($sce_jobs[$model->job->sce_job_number])) {
                            return $sce_jobs[$model->job->sce_job_number]->legacy_id . ' ' . $sce_jobs[$model->job->sce_job_number]->name;
                        }
                        return '';
                    }
                ],
                [
                    'header' => 'SCE CC',
                    'format' => 'html',
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'value' => function ($model) use ($sce_jobs) {
                        if ($model->subJob) {
                            if ($model->subJob->sce_job_number && isset($sce_jobs[$model->subJob->sce_job_number])) {
                                return implode('<br>',
                                    \common\models\SCEProjectTracker::getCostCenters($model->subJob->sce_job_number));
                            }
                        } elseif ($model->job->sce_job_number && isset($sce_jobs[$model->job->sce_job_number])) {
                            return implode('<br>',
                                \common\models\SCEProjectTracker::getCostCenters($model->job->sce_job_number));
                        }
                        return '';
                    }
                ],
                [
                    'header' => 'SCE Requestor',
                    'format' => 'html',
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'value' => function ($model) use ($sce_jobs) {
                        if ($model->subJob) {
                            if ($model->subJob->sce_job_number && isset($sce_jobs[$model->subJob->sce_job_number])) {
                                return \common\models\SCEProjectTracker::getJobs()[$model->subJob->sce_job_number]->creator->first_name . ' ' . \common\models\SCEProjectTracker::getJobs()[$model->subJob->sce_job_number]->creator->last_name;

                            }
                        } elseif ($model->job->sce_job_number && array_key_exists($model->job->sce_job_number,
                                \common\models\SCEProjectTracker::getJobs())
                        ) {
                            return \common\models\SCEProjectTracker::getJobs()[$model->job->sce_job_number]->creator->first_name . ' ' . \common\models\SCEProjectTracker::getJobs()[$model->job->sce_job_number]->creator->last_name;
                        }
                        return '';
                    }
                ],
                [
                    'header' => 'Total Hours',
                    'format' => 'html',
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'value' => function ($model) {
                        return $model->sum;
                    }
                ]
            ]
        ]); ?>
    </div>
</div>