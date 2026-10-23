<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ArtworkComp
 * @var $version \common\models\ArtcompVersion
 */
$this->title = $model->name . ' - version ' . $version->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['jobs/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->project->name . ' for ' . $model->project->client->name,
    'url' => ['jobs/view', 'id' => $model->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->name,
    'url' => ['comp', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\tracker\assets\ProjectViewAsset::register($this);
\backend\modules\tracker\assets\ArtcompAsset::register($this);
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['comp', 'id' => $model->id]) ?>" class="remove btn red-mint">
    Exit version
</a>
<?php $this->endBlock() ?>

<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
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
                                            <h4
                                                data-type="text"
                                                data-pk="<?= $item->id ?>"
                                                data-url="<?= \yii\helpers\Url::to([
                                                    'update-name',
                                                    'id' => $item->id
                                                ]) ?>"
                                                data-title="Enter name"><?= $item->artworkItem->name ?></h4>
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
                                                <div class="col-md-4">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'download',
                                                        'id' => $item->artworkItemState->id
                                                    ]) ?>" rel="tooltip" title="Download latest"><i
                                                            class="fa fa-download"></i></a>
                                                </div>
                                                <div class="col-md-4">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'history',
                                                        'id' => $item->id,
                                                        'version' => $version->id
                                                    ]) ?>" rel="tooltip" title="Show history"><i
                                                            class="fa fa-history"></i></a>
                                                </div>
                                                <div class="col-md-4">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'delete-item',
                                                        'id' => $item->id
                                                    ]) ?>"
                                                       rel="tooltip" title="Delete"
                                                       onclick="return confirm('You sure?')"
                                                    ><i class="fa fa-trash"></i></a>
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
