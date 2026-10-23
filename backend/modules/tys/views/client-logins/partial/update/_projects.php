<?php
/**
 * @var $this \yii\web\View
 * @var $client \common\models\Client
 * @var $projects \common\models\Job[]
 * @var $from \common\widgets\ActiveForm
 * @var $model \backend\modules\tys\models\ClientLoginForm
 */
$projects = \common\models\Job::find()->where(['client_id' => $client->id])->orderBy(['id' => SORT_DESC])->all();
?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-condenced table-stripped">
            <?php foreach ($projects as $project) { ?>
                <tr>
                    <td width="30"><?= \yii\bootstrap\Html::checkbox('ClientLoginForm[projects][' . $project->id . ']',
                            in_array($project->id, $model->projects),
                            ['id' => 'project_' . $project->id]) ?></td>
                    <td><label for="project_<?= $project->id ?>"><?= $project->name ?></label></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>