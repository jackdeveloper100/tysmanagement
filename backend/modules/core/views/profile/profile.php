<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\core\models\ProfileForm
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Update profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'slack_username') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'phone') ?>
                        <?= $form->field($model, 'cell') ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'new_password')
                            ->passwordInput(['placeholder' => 'Leave empty if you don\'t want to change']) ?>
                    </div>
                </div>

                <?= Html::submitButton('Save', ['class' => 'btn btn green-haze']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
