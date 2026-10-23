<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ArtworkComp
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['jobs/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->project->name . ' for ' . $model->project->client->name,
    'url' => ['jobs/view', 'id' => $model->project->id]
];
$this->params['breadcrumbs'][] = $this->title;
\frontend\modules\tracker\assets\ProjectViewAsset::register($this);
\frontend\modules\tracker\assets\ArtcompAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
    </div>
</div>
<div class="row artcomp">
    <div class="col-md-8">
        <h3>No versions found. Contact project manager.</h3>
    </div>
    <div class="col-md-4">
        <div class="portlet light">
            <div class="portlet-body">
                <p>
                    Project Manager: <?= $model->project->manager->getFullName() ?>
                </p>
                <p>
                    bus: <?= $model->project->manager->phone ?> <br>
                    fax: 323 604-0153 <br>
                    <?= \yii\bootstrap\Html::a($model->project->manager->email,
                        'mailto:' . $model->project->manager->email) ?>
                </p>
            </div>
        </div>
        <div class="portlet light links">
            <div class="portlet-body">
                <h4>Links</h4>

                <?php foreach ($model->project->links as $link) { ?>
                    <div class="item">
                        <a href="<?= $link->url ?>" target="_blank" class="link"><?= $link->name ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
