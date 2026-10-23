<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class BackstretchAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-backstretch/src';
    public $css = [];
    public $js = [
        'jquery.backstretch.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}