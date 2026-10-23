<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/10/15 7:01 PM
 *
 * @var $this yii\web\View
 * @var $client \common\models\Client
 *
 */

$this->title = $client->name . ' :: Hostings';
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<div class="btn-group btn-theme-panel">
    <a href="<?= \yii\helpers\Url::to(['create', 'client' => $client->id]) ?>" class="btn">
        <i class="icon-plus"></i>
    </a>
</div>
<?php $this->endBlock() ?>
<div class="portlet light">
    <div class="portlet-body">
        <?= common\widgets\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider(['query' => \common\models\Hosting::find()->where(['client_id' => $client->id])]),
            'pjax' => false,
            'columns' => [
                'id',
                'provider',
                'expiration_date:date',
                [
                    'class' => \common\widgets\grid\ActionColumn::class,
                    'showActions' => ['update', 'delete']
                ]
            ]
        ]); ?>
    </div>
</div>
