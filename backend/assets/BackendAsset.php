<?php
namespace backend\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
        'css/global.css'
    ];
    public $js = [
        'js/app.js'
    ];
    public $depends = [
        \common\assets\metronic\MetronicAsset::class,
        AutosizeAsset::class,
        ElevateZoomAsset::class,
        FancyboxAsset::class
    ];
}
