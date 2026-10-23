<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tracker\models\JobForm
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' project';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('page-toolbar') ?>
<?php if (!$model->getIsNewRecord()) {
    echo \yii\bootstrap\Button::widget([
        'tagName' => 'a',
        'label' => 'Sub Jobs',
        'options' => [
            'class' => 'btn green-meadow',
            'href' => \yii\helpers\Url::to(['sub-jobs/index', 'job' => $model->id])
        ]
    ]);
} ?>
<?php $this->endBlock() ?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>
                <div class="row">
                    <div class="col-md-3">
                        <?php

                        if (!$model->model->isNewRecord) {
                            echo $form->field($model, 'internal_id');
                        } else {
                            echo $form->field($model, 'internal_id')->textInput([
                                'disabled' => true,
                                'placeholder' => 'Will be auto-filled'
                            ]);
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-8">
                                <?= $form->field($model, 'status')
                                    ->dropDownList(\common\models\enum\JobStatus::getDataList()) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'internal')->dropDownList(collect(['' => ''])
                                    ->union(\skinka\php\TypeEnum\enums\YesNo::getDataList())->all()) ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'budget_hours') ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model, 'name') ?>
                        <?= $form->field($model, 'manager_id')->widget(\nex\chosen\Chosen::class, [
                            'items' => \common\models\User::getDataList(false)
                        ]) ?>
                        <?= $form->field($model, 'client_id')->widget(\nex\chosen\Chosen::class, [
                            'items' => \common\models\Client::getDataList(false)
                        ]) ?>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'slack_channel') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'docs_link') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'jira_link') ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'sce_job_number')
                            ->widget(\nex\chosen\Chosen::class, [
                                'items' => collect(\common\models\SCEProjectTracker::getJobs())->map(function ($item) {
                                    return $item->legacy_id . ' - ' . $item->name;
                                })
                            ]) ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model, 'accounting_notes')->textarea(['data-autosize' => true]) ?>
                    </div>
                </div>

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
