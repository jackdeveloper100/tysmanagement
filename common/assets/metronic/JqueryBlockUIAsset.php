<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class JqueryBlockUIAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.blockui';
    public $css = [];
    public $js = [
        'jquery.blockUI.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}