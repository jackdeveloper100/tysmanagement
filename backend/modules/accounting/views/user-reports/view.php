<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\User
 */

$this->title = $model->getShortName() . ' Hours';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['/tys/users/index']];
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\accounting\assets\UserReportsAsset::register($this);
$date = mb_split('/', Yii::$app->request->get('month', date('m/Y')));
$hours = \common\models\Task::find()
    ->where(['user_id' => $model->id])
    ->andWhere([
        '>=',
        'date',
        $date[1] . '-' . $date[0] . '-1'
    ])
    ->andWhere([
        '<=',
        'date',
        $date[1] . '-' . $date[0] . '-31'
    ])
    ->all();
$finalHours = [];
$hours = collect($hours)->groupBy('date')->each(function ($item, $key) use (&$finalHours) {
    $finalHours[$key] = collect($item)->sum(function ($item) {
        return $item->hours;
    });
});

function showMonth($month, $year, $hours, $user)
{
    $date = mktime(12, 0, 0, $month, 1, $year);
    $daysInMonth = date("t", $date);
    // calculate the position of the first day in the calendar (sunday = 1st column, etc)
    $offset = date("w", $date);
    $rows = 1;

    echo "<h3 class='text-center'>Displaying " . $user->getFullName() . " calendar for " . date("F Y",
            $date) . "</h3>\n";

    echo "<table class='calendar'>\n";
    echo "\t<tr><th>Su</th><th>M</th><th>Tu</th><th>W</th><th>Th</th><th>F</th><th>Sa</th></tr>";
    echo "\n\t<tr>";

    for ($i = 1; $i <= $offset; $i++) {
        echo "<td></td>";
    }

    $total = 0;
    for ($day = 1; $day <= $daysInMonth; $day++) {
        if (($day + $offset - 1) % 7 == 0 && $day != 1) {
            echo "<td><div class='day'>Total</div><div class='hours'>" . $total . "</div></td>";
            $total = 0;
            echo "</tr>\n\t<tr>";
            $rows++;
        }

        echo "<td><div class='day'>" . $day . "</div><div class='hours'>";
        $zeroDate = sprintf('%02d', $day);
        if (isset($hours[$year . '-' . $month . '-' . $zeroDate]) && $hours[$year . '-' . $month . '-' . $zeroDate] > 0) {
            echo $hours[$year . '-' . $month . '-' . $zeroDate];
            $total += $hours[$year . '-' . $month . '-' . $zeroDate];
        } else {
            echo '-';
        }
        echo "</div></td>";
    }

    while (($day + $offset) <= $rows * 7) {
        echo "<td></td>";
        $day++;
    }

    echo "<td><div class='day'>Total</div><div class='hours'>" . $total . "</div></td>";
    echo "</tr>\n";
    echo "</table>\n";
}

?>
<div class="user_hours">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-body">
                    <?= \common\widgets\Alert::widget() ?>

                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <form method="get">
                                <?= \yii\bootstrap\Html::hiddenInput('id', $model->id) ?>
                                <?= \common\widgets\DatePicker::widget([
                                    'name' => 'month',
                                    'value' => Yii::$app->request->get('month', date('m/Y')),
                                    'clientOptions' => [
                                        'format' => 'mm/yyyy',
                                        'startView' => 'months',
                                        'minViewMode' => 'months'
                                    ],
                                    'options' => [
                                        'onChange' => '$(this).closest(\'form\').submit();'
                                    ]
                                ]); ?>
                            </form>
                        </div>
                    </div>

                    <?php showMonth($date[0], $date[1], $finalHours, $model) ?>
                </div>
            </div>
        </div>
    </div>
</div>