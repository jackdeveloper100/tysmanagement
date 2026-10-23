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
\backend\modules\tracker\assets\ProjectViewAsset::register($this);
\backend\modules\tracker\assets\ArtcompAsset::register($this);
?>
<?php \yii\bootstrap\Modal::begin(['id' => 'updateComp', 'header' => 'Update Artwork Comp']); ?>
<?php
$modelForm = new \backend\modules\tracker\models\ArtworkCompForm();
$modelForm->setModel($model);
$form = \yii\bootstrap\ActiveForm::begin([
    'action' => ['update-comp', 'id' => $model->id],
    'options' => ['enctype' => 'multipart/form-data']
]);
?>
<?= $form->field($modelForm, 'name') ?>
<?= $form->field($modelForm, 'description')->textarea() ?>
<?= $form->field($modelForm, 'image')->fileInput() ?>
<div class="text-center">
    <input type="submit" value="Save" class="btn btn-primary">
</div>
<?php \yii\bootstrap\ActiveForm::end() ?>
<?php \yii\bootstrap\Modal::end() ?>

<?php $this->beginBlock('page-toolbar') ?>
<a href="#updateComp" data-toggle="modal" class="btn green-haze">
    Settings
</a>
<a href="<?= \yii\helpers\Url::to(['delete-comp', 'id' => $model->id]) ?>"
   data-method="post" class="remove btn red-mint">
    Remove folder
</a>
<?php $this->registerJs("$('a.remove').click(function (e) {if (!confirm('You sure want to remove entire folder?')) {e.preventDefault(); e.stopPropagation(); } })"); ?>
<?php $this->endBlock() ?>

<?php
\yii\bootstrap\Modal::begin(['id' => 'uploadRevision', 'header' => 'Upload item revision']);
$modelForm = new \backend\modules\tracker\models\ArtworkCompRevisionForm();
$modelForm->setModel(new \common\models\ArtworkItemState());
$form = \yii\bootstrap\ActiveForm::begin([
    'action' => ['upload-revision'],
    'options' => [
        'id' => 'revisionUploadForm',
        'enctype' => 'multipart/form-data',
        'class' => 'dropzone dropzone-file-area'
    ]
]);
?>
<?= $form->field($modelForm, 'item_id')->hiddenInput()->label(false) ?>
<?php \yii\bootstrap\ActiveForm::end() ?>
<?php \yii\bootstrap\Modal::end() ?>


<?php
\yii\bootstrap\Modal::begin(['id' => 'createVersion', 'header' => 'Create version']);
$modelForm = new \backend\modules\tracker\models\ArtcompVersionCreate();
$form = \yii\bootstrap\ActiveForm::begin(['action' => ['create-version', 'id' => $model->id]]);
?>
<?= $form->field($modelForm, 'name') ?>

<button class="btn btn-primary">Create version</button>
<?php \yii\bootstrap\ActiveForm::end() ?>
<?php \yii\bootstrap\Modal::end() ?>

<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
    </div>
</div>
<div class="row artcomp">
    <div class="col-md-12">
        <div class="row" style="margin-bottom: 25px">
            <div class="col-md-3">
                <div class="form-group">
                    <select onchange="window.location = $(this).val();" class="form-control">
                        <option value="">Show version</option>
                        <?php
                        foreach (\common\models\ArtcompVersion::find()->where(['artcomp_id' => $model->id])->orderBy(['id' => SORT_DESC])->all() as $item) {
                            echo '<option value="' . \yii\helpers\Url::to([
                                    'comp',
                                    'id' => $model->id,
                                    'version' => $item->id
                                ]) . '">' . $item->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <a href="#createVersion" data-toggle="modal" class="btn btn-block btn-primary">Create version</a>
            </div>
        </div>
        <?php
        $items = collect($model->artworkItems)->sortBy('updated_at', SORT_REGULAR, true);
        $i1 = 0;
        $part_one = $items->filter(function ($item) use (&$i1) {
            $i1++;
            return $i1 <= 3;
        });
        $i1 = 0;
        $part_two = $items->filter(function ($item) use (&$i1) {
            $i1++;
            return $i1 > 3;
        });
        $row = 1;
        foreach (array_merge([$part_one], $part_two->chunk(4)->all()) as $pack) { ?>
            <div class="row" style="display: flex; align-items: stretch">
                <?php if ($row == 1) {
                    ?>
                    <div class="col-md-3" style="padding-bottom: 25px">
                        <?php
                        $formModel = new \backend\modules\tracker\models\ArtworkItemNewForm();
                        $formModel->comp_id = $model->id;
                        $form = \yii\bootstrap\ActiveForm::begin([
                            'action' => ['upload', 'id' => $model->id],
                            'options' => [
                                'id' => 'compForm',
                                'enctype' => 'multipart/form-data',
                                'class' => 'dropzone dropzone-file-area',
                                'style' => 'height: 100%'
                            ]
                        ]); ?>
                        <?= $form->field($formModel, 'comp_id')->hiddenInput()->label(false) ?>
                        <?php \yii\bootstrap\ActiveForm::end() ?>
                    </div>
                    <?php
                }
                $row++;
                ?>
                <?php
                /**
                 * @var $item \common\models\ArtworkItem
                 */
                foreach ($pack as $item) { ?>
                    <div class="col-md-3">
                        <div class="item">
                            <div class="portlet light artitem" data-id="<?= $item->id ?>">
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
                                                    data-title="Enter name"><?= $item->name ?></h4>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <div class="revision_count"><?= count($item->artworkItemStates) ?></div>
                                        </div>
                                    </div>

                                    <div class="image">
                                        <img src="<?= $item->getPreview(400, 300) ?>" style="width: 100%;">
                                        <div class="hover">
                                            <div class="row actions">
                                                <div class="col-md-3">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'download',
                                                        'id' => $item->getLatestState()->id
                                                    ]) ?>" rel="tooltip" title="Download latest"><i
                                                                class="fa fa-download"></i></a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= \yii\helpers\Url::to([
                                                        'history',
                                                        'id' => $item->id
                                                    ]) ?>" rel="tooltip" title="Show history"><i
                                                                class="fa fa-history"></i></a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="#uploadRevision" data-toggle="modal"
                                                       class="upload_revision"
                                                       data-item="<?= $item->id ?>"
                                                       rel="tooltip" title="Upload new revision"><i
                                                                class="fa fa-upload"></i></a>
                                                </div>
                                                <div class="col-md-3">
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
                                                'id' => $item->getLatestState()->id,
                                                'ext' => $item->getLatestState()->getExt()
                                            ]) ?>" data-toggle="fancybox" class="btn green btn-lg">View</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= Yii::$app->formatter->asDatetime($item->updated_at, 'short') ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $modelUploadRevisionForm = new \backend\modules\tracker\models\ArtworkCompRevisionForm();
                                $tmp = new \common\models\ArtworkItemState();
                                $tmp->item_id = $item->id;
                                $modelUploadRevisionForm->setModel($tmp);
                                $form = \yii\bootstrap\ActiveForm::begin([
                                    'action' => ['upload-revision'],
                                    'options' => [
                                        'id' => 'artupload_' . $item->id,
                                        'enctype' => 'multipart/form-data',
                                        'class' => 'upload-container dropzone dropzone-file-area'
                                    ]
                                ]);
                                ?>
                                <?= $form->field($modelUploadRevisionForm, 'item_id')->hiddenInput()->label(false) ?>
                                <?php \yii\bootstrap\ActiveForm::end() ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
