<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel \backend\modules\tracker\models\JobsSearch
 *
 */

use common\widgets\DatePicker;

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\tracker\assets\ProjectListAsset::register($this);
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn sbold uppercase btn-outline blue-steel">
    Grid view
</a>
<?php if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager')) { ?>
    <a href="<?= \yii\helpers\Url::to(['create']) ?>" class="btn sbold uppercase btn-outline grey-mint">
        Create project
    </a>
<?php } ?>
<?php $this->endBlock() ?>
<?php
$favs = \yii\helpers\ArrayHelper::map(Yii::$app->user->identity->favorites, 'id', 'name');
?>
<div class="portlet light">
    <div class="portlet-body">
        <?= common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => [
                'id',
                'internal_id',
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($item) use ($favs) {
                        return \yii\helpers\Html::a($item->name,
                                [
                                    'view',
                                    'id' => $item->id
                                ]) . ' <a href="#" data-id="' . $item->id . '" class="favorite ' . (isset($favs[$item->id]) ? 'active' : '') . '"><i class="fa fa-star"></i></a>';
                    }

                ],
                [
                    'attribute' => 'client_id',
                    'value' => function ($model) {
                        return $model->client->name;
                    },
                    'filter' => \common\models\Client::getDataList(),
                    'header' => 'Client'
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->created_at);
                    },
                    'format' => 'html',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'value' => !empty($searchModel->created_at) ? date('m/d/Y',
                            strtotime($searchModel->created_at)) : '',
                        'clientOptions' => [
                            'clearBtn' => true
                        ]
                    ])
                ],
                [
                    'attribute' => 'status',
                    'filter' => \common\models\enum\JobStatus::getDataList(),
                    'value' => function ($model) {
                        return \common\models\enum\JobStatus::getByValue($model->status)->text();
                    }
                ],
                [
                    'attribute' => 'manager_id',
                    'filter' => \nex\chosen\Chosen::widget([
                        'model' => $searchModel,
                        'attribute' => 'manager_id',
                        'items' => \common\models\User::getDataList()
                    ]),
                    'value' => function ($model) {
                        return $model->manager->getShortName();
                    },
                    'header' => 'Manager'
                ],
                [
                    'attribute' => 'budget_hours',
                    'value' => function ($item) {
                        $hours = \common\models\Task::find()->where(['job_id' => $item->id])->sum('hours');
                        return $item->budget_hours ? ($hours > 0 ? $hours : 0) . ' / ' . $item->budget_hours : $item->budget_hours;
                    }
                ],
                [
                    'attribute' => 'internal',
                    'format' => 'raw',
                    'filter' => \skinka\php\TypeEnum\enums\YesNo::getDataList(),
                    'value' => function ($model) {
                        return $model->internal ? 'Yes' : 'No';
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
                [
                    'class' => \common\widgets\grid\ActionColumn::class
                ],
            ]
        ]); ?>
    </div>
</div>
