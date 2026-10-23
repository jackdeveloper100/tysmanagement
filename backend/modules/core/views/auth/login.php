<?php
/**
 * Created by Alex Bond at UPDG.
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \backend\modules\core\models\LoginForm
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

\backend\modules\core\assets\LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>TYS :: Enter</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="UPDG" name="author"/>

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body class=" login">
<?php $this->beginBody() ?>
<div class="user-login-5">
    <div class="row bs-reset">
        <div class="col-md-6 bs-reset">
            <div class="login-bg" style="background-image:url(/static/images/pages/login/bg1.jpg)">
                <img class="login-logo" src="/static/images/logo_light.svg" width="150"/></div>
        </div>
        <div class="col-md-6 login-container bs-reset">
            <div class="login-content">
                <h1>TYS Management Login</h1>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'login-form']
                ]); ?>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'login', ['template' => "{input}\n{error}"])->textInput([
                            'class' => 'form-control form-control-solid placeholder-no-fix',
                            'autocomplete' => 'off',
                            'placeholder' => 'Email',
                            'required'
                        ]) ?></div>
                    <div class="col-xs-6">
                        <?= $form->field($model, 'password', ['template' => "{input}\n{error}"])->passwordInput([
                            'class' => 'form-control form-control-solid placeholder-no-fix',
                            'autocomplete' => 'off',
                            'placeholder' => 'Password',
                            'required'
                        ]) ?></div>
                </div>

                <?= \common\widgets\Alert::widget() ?>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="rem-password">
                            <p>Remember me
                                <?= \yii\helpers\Html::activeCheckbox($model, 'rememberMe',
                                    ['label' => false, 'class' => 'rem-checkbox']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-8 text-right">
                        <button class="btn blue" type="submit">Enter</button>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="login-footer">
                <div class="row bs-reset">
                    <div class="col-xs-4 bs-reset">
                    </div>
                    <div class="col-xs-8 bs-reset">
                        <div class="login-copyright text-right">
                            <p>Copyright &copy; TYS <?= date('Y') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
