<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel UserSearch
 *
 */

use backend\modules\tys\models\UserSearch;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<div class="btn-group btn-theme-panel">
    <a href="<?= \yii\helpers\Url::to(['create']) ?>" class="btn">
        <i class="icon-plus"></i>
    </a>
</div>
<?php $this->endBlock() ?>
<div class="portlet light">
    <div class="portlet-body">
        <?= common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                'email',
                'first_name',
                'last_name',
                [
                    'attribute' => 'status',
                    'filter' => \yii\helpers\Html::activeDropDownList($searchModel, 'status',
                        \common\models\enum\UserStatus::getDataList(),
                        ['prompt' => '', 'class' => 'form-control input-sm']),
                    'format' => 'html',
                    'value' => function ($model) {
                        return \common\models\enum\UserStatus::getObjectValue($model->status);
                    }
                ],
                [
                    'header' => 'Actions',
                    'format' => 'raw',
                    'mergeHeader' => true,
                    'content' => function ($item) {
                        return \yii\bootstrap\Html::a('Monthly Hours',
                            ['/accounting/user-reports/view', 'id' => $item->id],
                            ['class' => 'btn default btn-xs blue-stripe']) . '<br>' .
                        \yii\bootstrap\Html::a('Update',
                            ['update', 'id' => $item->id],
                            ['class' => 'btn default btn-xs red-stripe']);
                    }
                ]
            ]
        ]); ?>
    </div>
</div>
