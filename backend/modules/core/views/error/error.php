<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/8/15 7:33 PM
 */
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception|HttpException */

$this->title = $name;
backend\modules\core\assets\ErrorAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>

    <html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>TYS :: Error</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta content="" name="description"/>
        <meta content="UPDG" name="author"/>

        <?php $this->head() ?>
    </head>

    <body class="page-500-full-page">
    <?php $this->beginBody() ?>
    <div class="row">
        <div class="col-md-12 page-500">
            <div class=" number font-red"> <?php
                if ($exception instanceof \yii\web\HttpException) {
                    echo $exception->statusCode;
                } else {
                    echo $exception->getCode();
                }
                ?> </div>
            <div class=" details">
                <h3>Oops! Something went wrong. <small><?= $message ?></small></h3>

                <p> We are fixing it! Please come back in a while.
                    <br/>
                </p>

                <p>
                    <a href="/" class="btn red btn-outline"> Return home </a>
                    <br>
                </p>

            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>

    <!-- END BODY -->
    </html>
<?php $this->endPage() ?>