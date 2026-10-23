<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Job
 */
$this->title = $model->name . ' for ' . $model->client->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\tracker\assets\ProjectViewAsset::register($this);
?>
<?php $this->beginBlock('page-toolbar') ?>
<a href="<?= \yii\helpers\Url::to(['update', 'id' => $model->id]) ?>" class="btn green-haze">
    Settings
</a>
<a href="<?= \yii\helpers\Url::to(['delete', 'id' => $model->id]) ?>" data-method="post" class="btn red-mint">
    Remove project
</a>
<?php $this->endBlock() ?>

<?php \yii\bootstrap\Modal::begin(['id' => 'createArtworkComp', 'header' => 'Create Artwork Comp']); ?>
<?php
$modelForm = new \backend\modules\tracker\models\ArtworkCompForm();
$form = \yii\bootstrap\ActiveForm::begin(['action' => ['artwork/create-comp', 'project' => $model->id]]);
?>
<?= $form->field($modelForm, 'name') ?>
<?= $form->field($modelForm, 'description')->textarea() ?>
<div class="text-center">
    <input type="submit" value="Create" class="btn btn-primary">
</div>
<?php \yii\bootstrap\ActiveForm::end() ?>
<?php \yii\bootstrap\Modal::end() ?>

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
                    <h2>Comps <a href="#createArtworkComp" data-toggle="modal" class="btn"><i
                                    class="icon-plus"></i></a></h2>
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
                            Client: <?= $model->client->name ?>
                        </p>
                        <p>
                            Project Manager: <?= $model->manager->getFullName() ?> <a
                                    href="https://team-tys.slack.com/messages/@<?= $model->manager->slack_username ?>/"
                                    target="_blank"><i class="fa fa-slack"></i></a>
                        </p>
                        <p class="text-center">
                            <?php if (!empty($model->slack_channel)) { ?>
                                <a href="https://team-tys.slack.com/messages/#<?= $model->slack_channel ?>"
                                   target="_blank" class="btn"><i
                                            class="fa fa-slack"></i> Slack
                                    channel</a>
                            <?php } ?>
                            <?php if (!empty($model->docs_link)) { ?>
                                <br>
                                <a href="<?= $model->docs_link ?>" target="_blank" class="btn"><i
                                            class="fa fa-google"></i>
                                    Docs folder</a>
                            <?php } ?>
                            <?php if (!empty($model->jira_link)) { ?>
                                <br>
                                <a href="<?= $model->jira_link ?>" target="_blank" class="btn"><i
                                            class="fa fa-product-hunt"></i> Jira Project</a>
                            <?php } ?>
                        </p>
                    </div>
                </div>
                <div class="portlet light links">
                    <div class="portlet-body">
                        <?php
                        \yii\bootstrap\Modal::begin(['id' => 'createLink', 'header' => 'Create Link']);
                        $modelForm = new \backend\modules\tracker\models\LinkForm();
                        $form = \yii\bootstrap\ActiveForm::begin(['action' => ['create-link', 'id' => $model->id]]);
                        ?>
                        <?= $form->field($modelForm, 'name') ?>
                        <?= $form->field($modelForm, 'url') ?>
                        <div class="text-center">
                            <input type="submit" value="Create" class="btn btn-primary">
                        </div>
                        <?php \yii\bootstrap\ActiveForm::end() ?>
                        <?php \yii\bootstrap\Modal::end() ?>

                        <h4>Links <a href="#createLink" data-toggle="modal" class="btn"><i
                                        class="icon-plus"></i></a></h4>

                        <?php foreach ($model->links as $link) { ?>
                            <div class="item">
                                <a href="<?= \yii\helpers\Url::to(['delete-link', 'id' => $link->id]) ?>"
                                   onclick="return confirm('You sure?')" class="delete"><i
                                            class="fa fa-trash"></i></a>
                                <a href="<?= $link->url ?>" target="_blank" class="link"><?= $link->name ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
