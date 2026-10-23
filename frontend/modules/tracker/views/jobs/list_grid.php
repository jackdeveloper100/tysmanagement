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

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
\frontend\modules\tracker\assets\ProjectListAsset::register($this);

$models = $dataProvider->getModels();
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
                        <div class="col-md-12 text-right">
                            <h5 style="display: inline-block; margin: 0;"><?= $model->internal_id ?></h5>
                        </div>
                    </div>

                    <h3 style="margin-top: 11px;"><?= $model->name ?></h3>
                    <div class="portlet-body">
                        <p>
                            <?= $model->manager->getFullName() ?><br>
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