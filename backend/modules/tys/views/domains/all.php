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

$this->title = 'Domains';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portlet light">
    <div class="portlet-body">
        <?php

        $columns = [
            'id',
            'domain',
            'client.name',
            [
                'attribute' => 'expiration_date',
                'format' => 'raw',
                'value' => function ($model) {
                    $left = strtotime($model->expiration_date) - time();
                    return '<span data-toggle="tooltip" title="' . Yii::$app->formatter->asDate($model->expiration_date) . '"' . ($left > 60 * 60 * 24 * 7 ? '' : ' style="font-weight:bold;" class="font-red-mint"') . '>' . Yii::$app->formatter->asRelativeTime($model->expiration_date) . '</span>';
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'expiration_date',
                    'value' => !empty($searchModel->expiration_date) ? date('m/d/Y',
                        strtotime($searchModel->expiration_date)) : '',
                    'clientOptions' => [
                        'clearBtn' => true
                    ]
                ])
            ],
            'auto_renew:boolean',
            'registrar',
            [
                'class' => \common\widgets\grid\ActionColumn::class
            ]
        ];

        echo common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => $columns
        ]); ?>
    </div>
</div>
