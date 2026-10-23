<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tys\models\ClientForm
 * @var $parent \common\models\Job
 *
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' sub-job';
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => ['jobs/index']];
$this->params['breadcrumbs'][] = [
    'label' => 'Sub Jobs for ' . $parent->internal_id,
    'url' => ['index', 'job' => $parent->id]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<?= \yii\bootstrap\Button::widget([
    'tagName' => 'a',
    'label' => 'Cancel',
    'options' => [
        'class' => 'btn red-flamingo',
        'href' => \yii\helpers\Url::to(['index', 'job' => $parent->id])
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
                <?= $form->field($model, 'job_number') ?>
                <?= $form->field($model, 'sce_job_number')
                    ->widget(\nex\chosen\Chosen::class, [
                        'items' => collect(\common\models\SCEProjectTracker::getJobs())->map(function ($item) {
                            return $item->legacy_id . ' - ' . $item->name;
                        })
                    ]) ?>
                <?= $form->field($model, 'status')->dropDownList(\common\models\enum\SubJobStatus::getDataList()) ?>

                <?= Html::submitButton('Save', ['class' => 'btn btn green-haze']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
