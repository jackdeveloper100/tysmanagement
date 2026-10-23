<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tys\models\UserForm
 * @var $client \common\models\Client
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' client domain';
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = ['label' => $client->name . ' logins', 'url' => ['index', 'client' => $client->id]];
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
                        <?= $form->field($model, 'domain') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'registrar') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'auto_renew')->dropDownList([false => 'No', true => 'Yes']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'expiration_date')->widget(\common\widgets\DatePicker::class, [
                            'options' => !empty($model->expiration_date) ? [
                                'value' => date('m/d/Y', strtotime($model->expiration_date))
                            ] : [
                                'value' => date('m/d/Y')
                            ]
                        ]) ?>
                    </div>
                </div>

                <?= Html::submitButton('Save', ['class' => 'btn btn green-haze']) ?>

                <?= \yii\bootstrap\Button::widget([
                    'tagName' => 'a',
                    'label' => 'Cancel',
                    'options' => [
                        'class' => 'btn red-flamingo',
                        'href' => \yii\helpers\Url::to(['index', 'client' => $client->id])
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
