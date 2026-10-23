<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ArtworkComp
 * @var $version \common\models\ArtcompVersion
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
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <?= \common\widgets\Alert::widget() ?>

                <div class="form-group">
                    <select onchange="window.location = $(this).val();" class="form-control">
                        <?php
                        foreach (\common\models\ArtcompVersion::find()->where(['artcomp_id' => $model->id])->orderBy(['id' => SORT_DESC])->all() as $item) {
                            echo '<option value="' . \yii\helpers\Url::to([
                                    'comp',
                                    'id' => $model->id,
                                    'version' => $item->id
                                ]) . '" ' . ($version->id == $item->id ? 'selected' : '') . '>' . $item->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row artcomp">
            <div class="col-md-12">
                <?php foreach (collect($version->artcompVersionStates)
                                   ->sortBy('updated_at', SORT_REGULAR, true)
                                   ->chunk(4) as $pack) { ?>
                    <div class="row">
                        <?php
                        /**
                         * @var $item \common\models\ArtcompVersionState
                         */
                        foreach ($pack as $item) { ?>
                            <div class="col-md-3">
                                <div class="item">
                                    <div class="portlet light">
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h4><?= $item->artworkItem->name ?></h4>
                                                </div>
                                                <div class="col-md-2 text-right">
                                                    <div class="revision_count">
                                                        <?= \common\models\ArtworkItemState::find()
                                                            ->under($item->artworkItem, $item->artwork_item_state_id)
                                                            ->count() ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="image">
                                                <img src="<?= $item->artworkItemState->getPreview(400, 300) ?>"
                                                     style="width: 100%;">
                                                <div class="hover">
                                                    <div class="row actions">
                                                        <div class="col-md-6">
                                                            <a href="<?= \yii\helpers\Url::to([
                                                                'download',
                                                                'id' => $item->artworkItemState->id
                                                            ]) ?>" rel="tooltip" title="Download latest"><i
                                                                        class="fa fa-download"></i></a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a href="<?= \yii\helpers\Url::to([
                                                                'history',
                                                                'id' => $item->artworkItem->id,
                                                                'version' => $version->id
                                                            ]) ?>" rel="tooltip" title="Show history"><i
                                                                        class="fa fa-history"></i></a>
                                                        </div>
                                                    </div>
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'preview',
                                                        'id' => $item->artworkItemState->id,
                                                        'ext' => $item->artworkItemState->getExt()
                                                    ]) ?>" data-toggle="fancybox" class="btn green btn-lg">View</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= Yii::$app->formatter->asDatetime($item->artworkItemState->updated_at,
                                                        'short') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>

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