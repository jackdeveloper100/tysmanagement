<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ArtworkItem
 */
$this->title = 'History';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->comp->project->name . ' for ' . $model->comp->project->client->name,
    'url' => ['jobs/view', 'id' => $model->comp->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->comp->name,
    'url' => ['comp', 'id' => $model->comp->id]
];
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\tracker\assets\ProjectViewAsset::register($this);
\backend\modules\tracker\assets\ArtcompAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
    </div>
</div>
<div class="row artcomp">
    <div class="col-md-12">
        <?php
        $i = 0;
        $items = collect($model->artworkItemStates)
            ->filter(function ($item) use ($version) {
                if ($version) {
                    return $item->id <= $version->artwork_item_state_id;
                }
                return true;
            });
        foreach ($items->sortBy('updated_at', SORT_REGULAR, true)
                     ->chunk(4) as $pack) { ?>
            <div class="row">
                <?php
                /**
                 * @var $item  \common\models\ArtworkItemState
                 */
                foreach ($pack as $item) {
                    $i++;
                    ?>
                    <div class="col-md-3">
                        <div class="item <?= $i == 1 ? 'latest' : '' ?>">
                            <div class="portlet light">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-10">
                                                <h4 class="no_edit"><?= $item->original_file_name ?></h4>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <div class="revision_count">
                                                    <?= $i > 1 ? $items->count() - $i + 1 : '' ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="image">
                                        <img src="<?= $item->getPreview(400, 300) ?>" style="width: 100%;">
                                        <div class="hover">
                                            <div class="row actions">
                                                <div class="col-md-6">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'download',
                                                        'id' => $item->id
                                                    ]) ?>" rel="tooltip" title="Download"><i class="fa fa-download"></i></a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'delete-state',
                                                        'id' => $item->id
                                                    ]) ?>"
                                                       rel="tooltip" title="Delete"
                                                       onclick="return confirm('You sure?')"
                                                    ><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                            <a href="<?= \yii\helpers\Url::to([
                                                'preview',
                                                'id' => $item->id,
                                                'ext' => $item->getExt()
                                            ]) ?>" data-toggle="fancybox" class="btn green btn-lg">View</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= Yii::$app->formatter->asDatetime($item->updated_at, 'short') ?>
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
