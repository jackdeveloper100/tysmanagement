<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \common\models\UserDescriptionTemplate
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' sub-job';
$this->params['breadcrumbs'][] = ['label' => 'Manage Hours', 'url' => ['tasks/index']];
$this->params['breadcrumbs'][] = ['label' => 'Description Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<?= \yii\bootstrap\Button::widget([
    'tagName' => 'a',
    'label' => 'Cancel',
    'options' => [
        'class' => 'btn red-flamingo',
        'href' => \yii\helpers\Url::to(['index'])
    ]
]) ?>
<?php $this->endBlock() ?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>
                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'content')->textarea() ?>

                <?= Html::submitButton('Save', ['class' => 'btn btn green-haze']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
