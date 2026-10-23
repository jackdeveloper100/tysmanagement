<?php

namespace frontend\assets;

use backend\assets\ElevateZoomAsset;
use backend\assets\FancyboxAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [];
    public $js = [];
    public $depends = [
        \yii\web\YiiAsset::class,
        \yii\bootstrap\BootstrapAsset::class,
        \common\assets\metronic\UniformAsset::class,
        ElevateZoomAsset::class,
        FancyboxAsset::class
    ];
}