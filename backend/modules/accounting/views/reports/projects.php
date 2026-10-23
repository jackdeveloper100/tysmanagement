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
        <li><a href="<?= \yii\helpers\Url::to(['index']) ?>">Hours reports</a></li>
        <li class="active"><a href="<?= \yii\helpers\Url::to(['projects']) ?>">Projects reports</a></li>
    </ul>
</div>
<div class="portlet light">
    <div class="portlet-body">
        <form method="get">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-8 col-md-offset-2">
                    <?= \nex\chosen\Chosen::widget([
                        'name' => 'client',
                        'items' => \common\models\Client::getDataList(true),
                        'value' => Yii::$app->request->get('client')
                    ]) ?>
                </div>
            </div>
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-8 col-md-offset-2">
                    <?= \nex\chosen\Chosen::widget([
                        'name' => 'manager',
                        'items' => \common\models\User::getDataList(true),
                        'value' => Yii::$app->request->get('manager')
                    ]) ?>
                </div>
            </div>
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-8 col-md-offset-2">
                    <?= \nex\chosen\Chosen::widget([
                        'name' => 'status',
                        'items' => \common\models\enum\JobStatus::getDataList(),
                        'value' => Yii::$app->request->get('status')
                    ]) ?>
                </div>
            </div>
            <div class="text-center">
                <input type="submit" class="btn blue-dark" value="Search">
                <button class="btn green-meadow export-projects">Export</button>
            </div>
        </form>

        <?php
        $q = \common\models\Job::find()
            ->andFilterWhere(['client_id' => Yii::$app->request->get('client')])
            ->andFilterWhere(['manager_id' => Yii::$app->request->get('manager')])
            ->andFilterWhere(['status' => Yii::$app->request->get('status')])
            ->orderBy(['internal_id' => SORT_DESC]);


        echo common\widgets\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $q]),
            'pjax' => false,
            'columns' => [
                'internal_id',
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($item) {
                        return \yii\helpers\Html::a($item->name,
                            [
                                'view',
                                'id' => $item->id
                            ]);
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
                    'format' => 'html'
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
                ]
            ]
        ]); ?>
    </div>
</div>