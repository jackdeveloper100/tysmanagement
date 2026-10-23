<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tys\models\UserForm
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' user';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-8">
        <div class="portlet light">
            <div class="portlet-body">
                <?= \common\widgets\Alert::widget() ?>
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
                    <div class="col-md-6">
                        <?= $form->field($model, 'title') ?>
                        <?= $form->field($model, 'slack_username') ?>
                        <?= $form->field($model, 'phone') ?>
                        <?= $form->field($model, 'cell') ?>
                        <?= $form->field($model, 'hourly_rate') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'status')
                            ->dropDownList(\common\models\enum\UserStatus::getDataList()) ?>
                        <?= $form->field($model, 'email') ?>
                        <?= $form->field($model, 'password')
                            ->passwordInput(['placeholder' => $model->isNewRecord ? '' : 'Leave empty if you don\'t want to change']) ?>

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
    <div class="col-md-4">
        <div class="portlet light">
            <div class="portlet-body">
                <?php
                $roles = Yii::$app->getAuthManager()->getRoles();
                $rolesArray = \yii\helpers\ArrayHelper::map($roles, 'name', 'description');
                $userRoles = !empty($model->id) ? Yii::$app->getAuthManager()->getRolesByUser($model->id) : [];
                $userRolesArray = [];
                foreach ($userRoles as $role) {
                    $userRolesArray[] = $role->name;
                }
                $model->role = ($item = collect(Yii::$app->getAuthManager()->getRolesByUser($model->id))->first()) != null ? $item->name : '';
                ?>
                <?= $form->field($model, 'role')->radioList($rolesArray) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
