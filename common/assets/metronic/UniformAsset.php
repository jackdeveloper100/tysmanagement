<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class UniformAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.uniform';
    public $css = [
        'themes/default/css/uniform.default.css',
    ];
    public $js = [
        'jquery.uniform.min.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}