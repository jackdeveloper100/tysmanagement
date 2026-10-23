<?php
/**
 * Created by PhpStorm.
 * User: skink
 * Date: 19.08.2016
 * Time: 0:09
 */
/**
 * @var $model \frontend\modules\ak\models\ContactForm
 */
?>
<strong>Name</strong>: <?= $model->name ?><br>
<strong>Phone</strong>: <?= $model->phone ?><br>
<strong>Email</strong>: <?= $model->email ?><br>
<strong>Message</strong>: <p><?= $model->message ?></p>
