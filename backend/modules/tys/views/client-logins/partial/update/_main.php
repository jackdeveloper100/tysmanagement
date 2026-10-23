<?php
/**
 * @var $this \yii\web\View
 * @var $form \common\widgets\ActiveForm
 * @var $model \backend\modules\tys\models\ClientLoginForm
 */
?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'first_name') ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'last_name') ?>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')
            ->passwordInput(['placeholder' => $model->isNewRecord ? '' : 'Leave empty if you don\'t want to change']) ?>

    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12 text-center">
        <?= $form->field($model, 'all_project_access')->checkbox() ?>
    </div>
</div>
