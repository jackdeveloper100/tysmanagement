<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class SlimScrollAsset extends AssetBundle
{
    public $sourcePath = '@bower/slimscroll';
    public $css = [];
    public $js = [
        'jquery.slimscroll.min.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}