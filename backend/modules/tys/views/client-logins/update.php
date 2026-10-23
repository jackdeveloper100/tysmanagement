<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\tys\models\ClientLoginForm
 * @var $client \common\models\Client
 *
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' client login';
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = ['label' => $client->name . ' logins', 'url' => ['index', 'client' => $client->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-12">
        <?= \common\widgets\Alert::widget() ?>
        <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

        <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
            <?php
            echo \yii\bootstrap\Tabs::widget([
                'items' => [
                    [
                        'label' => 'Main',
                        'content' => $this->render('partial/update/_main', ['form' => $form, 'model' => $model]),
                        'active' => true
                    ],
                    [
                        'label' => 'Projects',
                        'content' => $this->render('partial/update/_projects',
                            ['form' => $form, 'model' => $model, 'client' => $client])
                    ]
                ]
            ]);
            ?>
        </div>

        <div class="portlet light">
            <div class="portlet-body">
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
