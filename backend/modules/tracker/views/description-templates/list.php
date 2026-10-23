<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel \common\models\UserDescriptionTemplate
 *
 */

$this->title = 'Description Templates';
$this->params['breadcrumbs'][] = ['label' => 'Manage Hours', 'url' => ['tasks/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['create']) ?>"
   class="btn sbold uppercase btn-outline grey-mint">
    Create template
</a>
<?php $this->endBlock() ?>
<div class="portlet light">
    <div class="portlet-body">
        <?= common\widgets\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => \common\models\UserDescriptionTemplate::find()->where(['user_id' => Yii::$app->user->id])
            ]),
            'columns' => [
                'id',
                'name',
                'content',
                [
                    'class' => \common\widgets\grid\ActionColumn::class
                ],
            ]
        ]); ?>
    </div>
</div>
