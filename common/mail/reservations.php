<?php
/**
 * @var $model \common\models\Reservations
 */
use yii\helpers\Html;

?>

<?php foreach ($model->getAttributes() as $field => $value) {
    switch ($field) {
        case 'event_type':
            echo '<strong>' . Html::encode($model->getAttributeLabel($field)) . '</strong>: '
                . \common\models\enum\EventType::getByValue($value)->text() . '<br>';
            break;
        case 'passengers_count':
            echo '<strong>' . Html::encode($model->getAttributeLabel($field)) . '</strong>: '
                . \common\models\enum\Passengers::getByValue($value)->text() . '<br>';
            break;
        case 'vehicle_id':
            echo '<strong>' . Html::encode($model->getAttributeLabel($field)) . '</strong>: '
                . $model->vehicle->name . '<br>';
            break;
        case 'created_at':
            echo '<strong>' . Html::encode($model->getAttributeLabel($field)) . '</strong>: '
                . Yii::$app->formatter->asDatetime($value) . '<br>';
            break;
        default:
            echo '<strong>' . Html::encode($model->getAttributeLabel($field)) . '</strong>: ' . $value . '<br>';
    }
} ?>
