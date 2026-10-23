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

$this->title = 'Projects' . (isset(Yii::$app->request->get('JobsSearch',
            [])['query']) ? ' (search for ' . \yii\helpers\Html::encode(Yii::$app->request->get('JobsSearch',
            [])['query']) . ')' : '');
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\tracker\assets\ProjectListAsset::register($this);
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['index', 'list_view' => 1]) ?>" class="btn sbold uppercase btn-outline blue-steel">
    List view
</a>
<a href="<?= \yii\helpers\Url::to(['create']) ?>" class="btn sbold uppercase btn-outline grey-mint">
    Create project
</a>
<?php $this->endBlock() ?>
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    'action' => ['index'],
    'options' => ['class' => 'status_form']
]); ?>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $form->field($searchModel, 'client_id')
                ->label(false)
                ->dropDownList(\common\models\Client::getDataList(true, 'All clients'),
                    ['class' => 'form-control input-block']) ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $form->field($searchModel, 'status')
                ->label(false)
                ->dropDownList(\yii\helpers\ArrayHelper::merge(['' => 'Any status'],
                    \common\models\enum\JobStatus::getDataList()),
                    ['class' => 'form-control input-block']) ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $form->field($searchModel, 'manager_id')
                ->label(false)
                ->widget(\nex\chosen\Chosen::class, [
                    'items' => \common\models\User::getDataList()
                ]) ?>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end() ?>
<?php
$models = $dataProvider->getModels();
echo \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination]);
$favs = \yii\helpers\ArrayHelper::map(Yii::$app->user->identity->favorites, 'id', 'name');
foreach (collect($models)->chunk(4) as $item) {
    ?>
    <div class="row">
        <?php
        foreach ($item as $model) {
            /**
             * @var $model \common\models\Job
             */
            ?>
            <div class="col-md-3">
                <div class="portlet light">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!($image = $model->client->getLogo())) {
                                echo '<h4 style="margin: 0;">' . $model->client->name . '</h4>';
                            } else {
                                echo \yii\bootstrap\Html::img($image, ['style' => 'max-height: 15px']);
                            } ?>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5 style="display: inline-block; margin: 0;"><?= $model->internal_id ?></h5>
                            <a href="#" data-id="<?= $model->id ?>"
                               class="favorite <?= isset($favs[$model->id]) ? 'active' : '' ?>"><i
                                        class="fa fa-star"></i></a>
                        </div>
                    </div>

                    <h3 style="margin-top: 11px;"><?= $model->name ?></h3>
                    <div class="portlet-body">
                        <p>
                            <?= $model->manager->getFullName() ?> <a
                                    href="https://team-tys.slack.com/messages/@<?= $model->manager->slack_username ?>/"
                                    target="_blank"><i class="fa fa-slack"></i></a><br>
                            <span
                                    class="font-yellow-gold">•</span> <?= \common\models\enum\JobStatus::getByValue($model->status)->text() ?>
                        </p>

                        <div class="text-center">
                            <a href="<?= \yii\helpers\Url::to(['view', 'id' => $model->id]) ?>" class="btn uppercase">To
                                project</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
<?= \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
