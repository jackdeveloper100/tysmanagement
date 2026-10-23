<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class BootstrapSwitchAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-switch/dist';
    public $css = [
        'css/bootstrap3/bootstrap-switch.min.css',
    ];
    public $js = [
        'js/bootstrap-switch.min.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}