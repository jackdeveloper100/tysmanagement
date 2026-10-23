<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class DatePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-datepicker/dist';
    public $css = [
        'css/bootstrap-datepicker3.min.css'
    ];
    public $js = [
        'js/bootstrap-datepicker.min.js',
        'locales/bootstrap-datepicker.ru.min.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}
