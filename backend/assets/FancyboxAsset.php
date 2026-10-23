<?php
namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class FancyboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox';
    public $css = [
        'source/jquery.fancybox.css'
    ];
    public $js = [
        'source/jquery.fancybox.pack.js'
    ];
    public $depends = [
        JqueryAsset::class
    ];
}
