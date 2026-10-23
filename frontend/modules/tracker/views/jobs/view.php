<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Job
 */
$this->title = $model->name . ' for ' . $model->client->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\frontend\modules\tracker\assets\ProjectViewAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div class="artwork">
                    <h2>Comps</h2>
                    <div class="row">
                        <?php foreach ($model->artworkComps as $item) { ?>
                            <div class="col-md-4">
                                <div class="item">
                                    <div class="portlet light">
                                        <div class="portlet-body">
                                            <h4><?= \yii\bootstrap\Html::encode($item->name) ?></h4>
                                            <a href="<?= \yii\helpers\Url::to(['artwork/comp', 'id' => $item->id]) ?>">
                                                <img src="<?= $item->getImage(400, 200) ?>" style="max-width: 100%;">
                                            </a>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <?= Yii::$app->formatter->asDatetime($item->updated_at, 'short') ?>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="portlet light">
                    <div class="portlet-body">
                        <p>
                            Project Manager: <?= $model->manager->getFullName() ?>
                        </p>
                        <p>
                            bus: <?= $model->manager->phone ?> <br>
                            fax: 323 604-0153 <br>
                            <?= \yii\bootstrap\Html::a($model->manager->email, 'mailto:' . $model->manager->email) ?>
                        </p>
                    </div>
                </div>
                <div class="portlet light links">
                    <div class="portlet-body">
                        <h4>Links</h4>

                        <?php foreach ($model->links as $link) { ?>
                            <div class="item">
                                <a href="<?= $link->url ?>" target="_blank" class="link"><?= $link->name ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
