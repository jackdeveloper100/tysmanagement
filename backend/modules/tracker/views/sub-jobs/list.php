<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel \backend\modules\tracker\models\JobsSearch
 * @var $parent \common\models\Job
 *
 */

$this->title = 'Sub Jobs for ' . $parent->internal_id;
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => ['jobs/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['export', 'job' => $parent->id]) ?>"
   class="btn sbold uppercase btn-outline green">
    Export
</a>
<a href="<?= \yii\helpers\Url::to(['create', 'job' => $parent->id]) ?>"
   class="btn sbold uppercase btn-outline grey-mint">
    Create subjob
</a>
<?php $this->endBlock() ?>
<div class="portlet light">
    <div class="portlet-body">
        <?= common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => [
                'id',
                'job_number',
                [
                    'attribute' => 'status',
                    'filter' => \common\models\enum\SubJobStatus::getDataList(),
                    'value' => function ($model) {
                        return \common\models\enum\SubJobStatus::getByValue($model->status)->text();
                    }
                ],
                [
                    'attribute' => 'soft_deleted',
                    'format' => 'raw',
                    'filter' => \skinka\php\TypeEnum\enums\YesNo::getDataList(),
                    'value' => function ($model) {
                        return $model->soft_deleted ? 'Yes' : 'No';
                    }
                ],
                'created_at:datetime',
                [
                    'class' => \common\widgets\grid\ActionColumn::class
                ],
            ]
        ]); ?>
    </div>
</div>
