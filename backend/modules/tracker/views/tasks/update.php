<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tracker\models\TaskForm
 * @var $parent \common\models\Job
 *
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Add' : 'Update') . ' hours';
$this->params['breadcrumbs'][] = ['label' => 'Manage Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag(['name' => 'isNewRecord', 'content' => (int)$model->isNewRecord]);

\backend\modules\tracker\assets\UpdateHoursAsset::register($this);
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>
                <?php if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager')) {
                    echo $form->field($model, 'user_id')->widget(\nex\chosen\Chosen::class, [
                        'items' => collect(\common\models\User::getDataList())->map(function ($item, $key) {
                            return $key == Yii::$app->user->id ? $item . ' (me)' : $item;
                        })
                    ]);
                } ?>

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
                            $model->getIsNewRecord() ? [\common\models\enum\JobStatus::CURRENT] : null)
                    ]) ?>
                </div>

                <?php
                $items = [];
                $jobsQ = \common\models\Job::find()->orderBy(['internal_id' => SORT_DESC]);
                if ($model->getIsNewRecord()) {
                    $jobsQ->andWhere(['status' => \common\models\enum\JobStatus::CURRENT]);
                }
                foreach ($jobsQ->all() as $item) {
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
                                    $model->getIsNewRecord() ? [\common\models\enum\JobStatus::CURRENT] : null),
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
                        'id', 'job_number') : [],
                    'clientOptions' => ['search_contains' => true]
                ]) ?>

                <?= $form->field($model, 'date')->widget(\common\widgets\DatePicker::class, [
                    'options' => !empty($model->date) ? [
                        'value' => date('m/d/Y', strtotime($model->date))
                    ] : ['value' => date('m/d/Y')]
                ]) ?>
                <?= $form->field($model, 'hours') ?>

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
