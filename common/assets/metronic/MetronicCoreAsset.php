<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class MetronicCoreAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/static';
    public $css = [
        '//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'global/css/components.min.css',
        'global/css/plugins.css'
    ];
    public $js = [
        'global/scripts/app.js'
    ];
    public $depends = [
        \yii\web\YiiAsset::class,
        \yii\bootstrap\BootstrapAsset::class,
        \yii\bootstrap\BootstrapPluginAsset::class,
        \common\assets\metronic\FontAwesomeAsset::class,
        \common\assets\metronic\SimpleLineIconsAsset::class,
        //\common\assets\metronic\UniformAsset::class,
        \common\assets\metronic\BootstrapSwitchAsset::class,
        \common\assets\metronic\JsCookieAsset::class,
        \common\assets\metronic\BootstrapHoverDropdownAsset::class,
        \common\assets\metronic\JqueryBlockUIAsset::class
    ];
}
