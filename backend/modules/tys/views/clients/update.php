<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tys\models\ClientForm
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' client';
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>

                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'image')->fileInput() ?>

                <?= Html::submitButton('Save', ['class' => 'btn btn green-haze']) ?>

                <?= \yii\bootstrap\Button::widget([
                    'tagName' => 'a',
                    'label' => 'Cancel',
                    'options' => [
                        'class' => 'btn red-flamingo',
                        'href' => \yii\helpers\Url::to(['index'])
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
