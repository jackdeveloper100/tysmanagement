<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel \backend\modules\tys\models\ClientSearch
 *
 */

$this->title = 'Clients';
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
        <?= \common\widgets\Alert::widget() ?>

        <?= common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => [
                'id',
                'name',
                [
                    'attribute' => 'is_default',
                    'format' => 'raw',
                    'filter' => \skinka\php\TypeEnum\enums\YesNo::getDataList(),
                    'value' => function ($model) {
                        return $model->is_default ? 'Default' :
                            \yii\bootstrap\Html::a('Use as default', ['set-default', 'id' => $model->id]);
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
                [
                    'header' => '',
                    'format' => 'raw',
                    'mergeHeader' => true,
                    'value' => function ($item) {
                        return
                            \yii\bootstrap\Html::a('Logins', ['client-logins/index', 'client' => $item->id],
                                ['class' => 'btn default btn-xs green-stripe']) . '<br>' .
                            \yii\bootstrap\Html::a('Hosting', ['hostings/index', 'client' => $item->id],
                                ['class' => 'btn default btn-xs purple-stripe']) . '<br>' .
                            \yii\bootstrap\Html::a('Domains', ['domains/index', 'client' => $item->id],
                                ['class' => 'btn default btn-xs purple-stripe']) . '<br>';
                    }
                ]
            ]
        ]); ?>
    </div>
</div>
