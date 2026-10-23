<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tracker\models\WeeklyTaskForm
 * @var $parent \common\models\Job
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Add' : 'Update') . ' hours';
$this->params['breadcrumbs'][] = ['label' => 'Manage Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\backend\modules\tracker\assets\UpdateHoursAsset::register($this);
$times = \yii\helpers\ArrayHelper::map(
    Yii::$app->db->createCommand('SELECT date, SUM(hours) as hours FROM task WHERE user_id=:user GROUP BY date',
        ['user' => Yii::$app->user->id])->queryAll(),
    'date', 'hours'
);
?>
<script>
    times = <?=json_encode($times)?>;
</script>

<?php $form = ActiveForm::begin(); ?>
<div class="row add-weekly">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>

                <div class="row">
                    <div class="col-md-1 text-center">
                        <a href="#" class="btn btn-default prev">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </div>
                    <div class="col-md-10">
                        <div class="week">
                            <?php
                            $days = [
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday',
                                'Sunday'
                            ];
                            for ($i = 1; $i <= 7; $i++) {
                                ?>
                                <div class="day <?= $days[$i - 1] ?>" data-id="<?= $i ?>">
                                    <h2><?= $days[$i - 1] ?></h2>
                                    <h3></h3>
                                    <h5 class="logged"><span>0</span> hours</h5>
                                    <?= Html::hiddenInput('WeeklyTaskForm[hours][' . $i . '][date]', '',
                                        ['class' => 'hidden_date']) ?>
                                    <?= Html::textInput('WeeklyTaskForm[hours][' . $i . '][hours]', 0,
                                        [
                                            'class' => 'form-control text-center',
                                            'type' => 'number',
                                            'min' => 0,
                                            'max' => 24,
                                            'step' => 0.25
                                        ]) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        <a href="#" class="btn btn-default next">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <hr>

                <?php
                \yii\bootstrap\Modal::begin(['id' => 'advancedSearch', 'header' => 'Advanced search']);
                ?>

                <div class="form-group">
                    <label class="control-label">Client</label>
                    <?= \nex\chosen\Chosen::widget([
                        'name' => 'Client',
                        'id' => 'advanced_client',
                        'items' => ArrayHelper::map(\common\models\Client::find()->orderBy(['name' => SORT_ASC])->all(),
                            'id', 'name')
                    ]) ?>
                </div>

                <div class="form-group">
                    <label class="control-label">Job</label>
                    <?= \nex\chosen\Chosen::widget([
                        'name' => 'Job',
                        'id' => 'advanced_job',
                        'items' => \common\models\Job::getDataList(false, true,
                            [\common\models\enum\JobStatus::CURRENT])
                    ]) ?>
                </div>

                <?php
                $items = [];
                foreach (\common\models\Job::find()->orderBy(['internal_id' => SORT_DESC])->where(['status' => \common\models\enum\JobStatus::CURRENT])->all() as $item) {
                    /**
                     * @var $item \common\models\Job
                     */
                    $items[] = [
                        'id' => $item->id,
                        'name' => $item->internal_id . ' - ' . $item->name,
                        'client' => $item->client_id
                    ];
                }
                $this->registerJs('window.jobs = ' . json_encode($items));
                ?>

                <p class="text-center">
                    <a href="#" id="advanced_choose" class="btn btn-primary">Choose job</a>
                </p>

                <?php \yii\bootstrap\Modal::end() ?>

                <table width="100%">
                    <tr>
                        <td>
                            <?= $form->field($model, 'job_id')->widget(\nex\chosen\Chosen::class, [
                                'items' => \common\models\Job::getDataList(false, true,
                                    [\common\models\enum\JobStatus::CURRENT]),
                                'clientOptions' => ['search_contains' => true]
                            ]) ?>
                        </td>
                        <td width="200" valign="center" class="text-center">
                            <a href="#advancedSearch" data-toggle="modal">Advanced search</a>
                        </td>
                    </tr>
                </table>

                <?= $form->field($model, 'sub_job_id')->widget(\nex\chosen\Chosen::class, [
                    'items' => $model->sub_job_id > 0 ? \yii\helpers\ArrayHelper::map(\common\models\SubJob::find()->where(['job_id' => $model->job_id])->all(),
                        'id', 'job_number') : []
                ]) ?>
                <?= $form->field($model, 'task_item_id')->dropDownList(\common\models\enum\TaskItem::getDataList()) ?>

                <?= $form->field($model, 'description')->textarea() ?>
                <ul class="description-templates">
                    <?php foreach (Yii::$app->user->identity->userDescriptionTemplates as $item) { ?>
                        <li><a href="#" data-content="<?= $item->content ?>"><?= $item->name ?></a></li>
                    <?php } ?>
                </ul>

                <?= Html::submitButton('Save', ['class' => 'btn green-haze', 'style' => 'width:150px']) ?>

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
