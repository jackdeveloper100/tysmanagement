<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\backend\assets\BackendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-container-bg-solid page-header-fixed">
<?php $this->beginBody() ?>

<?= $this->render('@partial/_header') ?>

<div class="clearfix"></div>

<div class="page-container">
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-head">
                <div class="page-title">
                    <h1><?= $this->title ?></h1>
                </div>
                <div class="page-toolbar">
                    <?php
                    if (isset($this->blocks['page-toolbar'])) {
                        echo $this->blocks['page-toolbar'];
                    }
                    ?>
                </div>
            </div>
            <?= \common\widgets\Breadcrumbs::widget([
                'links' => \yii\helpers\ArrayHelper::getValue($this->params, 'breadcrumbs', [])
            ]) ?>

            <?= \common\widgets\Alert::widget() ?>
            
            <?= $content ?>

        </div>
    </div>
</div>
<?= $this->render('@partial/_footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
