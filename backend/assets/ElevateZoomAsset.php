<?php
namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ElevateZoomAsset extends AssetBundle
{
    public $sourcePath = '@bower/ez-plus';
    public $css = [
        'css/jquery.ez-plus.css'
    ];
    public $js = [
        'src/jquery.ez-plus.js'
    ];
    public $depends = [
        JqueryAsset::class
    ];
}
