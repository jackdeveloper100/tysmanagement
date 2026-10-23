<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel \backend\modules\tracker\models\TasksSearch
 * @var $parent \common\models\Job
 *
 */

use common\widgets\DatePicker;

$this->title = 'Manage Hours';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['add']) ?>" class="btn sbold uppercase btn-outline grey-mint">
    Add
</a>
<a href="<?= \yii\helpers\Url::to(['add-weekly']) ?>" class="btn sbold uppercase btn-outline grey-mint">
    Add Weekly
</a>
<a href="<?= \yii\helpers\Url::to(['/accounting/user-reports/my']) ?>"
   class="btn sbold uppercase btn-outline green-dark">
    Calendar view
</a>
<?php $this->endBlock() ?>
<div class="portlet light">
    <div class="portlet-body">
        <?php

        $columns = [
            [
                'attribute' => 'job.internal_id',
                'header' => 'Job ID'
            ],
            [
                'attribute' => 'job.name',
                'header' => 'Job Name'
            ],
            [
                'attribute' => 'subJob.job_number',
                'header' => 'Sub Job Number'
            ]

        ];
        if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager')) {
            $columns[] = [
                'attribute' => 'user.first_name',
                'header' => 'User',
                'value' => function ($model) {
                    return $model->user->getShortName();
                },
                'filter' => \yii\bootstrap\Html::activeDropDownList($searchModel, 'user',
                    \common\models\User::getDataList(true), ['class' => 'form-control input-sm'])
            ];
        }

        $columns = \yii\helpers\ArrayHelper::merge($columns, [
            [
                'attribute' => 'task_item_id',
                'header' => 'Task Item',
                'filter' => \common\models\enum\TaskItem::getDataList(),
                'value' => function ($model) {
                    return \common\models\enum\TaskItem::getByValue($model->task_item_id)->text();
                }
            ],
            'description',
            [
                'attribute' => 'date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->date);
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'value' => !empty($searchModel->date) ? date('m/d/Y',
                        strtotime($searchModel->date)) : '',
                    'clientOptions' => [
                        'clearBtn' => true
                    ]
                ]),
                'options' => [
                    'width' => '200'
                ]
            ],
            'hours',
            [
                'class' => \common\widgets\grid\ActionColumn::class
            ]
        ]);

        echo common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => $columns
        ]); ?>
    </div>
</div>
