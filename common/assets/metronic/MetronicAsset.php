<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class MetronicAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/static';
    public $css = [
        'layouts/layout4/css/layout.min.css',
        'layouts/layout4/css/themes/default.min.css'
    ];
    public $js = [
        'layouts/layout4/scripts/layout.js',
    ];
    public $depends = [
        MetronicCoreAsset::class,
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
