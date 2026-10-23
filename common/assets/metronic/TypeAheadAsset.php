<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class TypeAheadAsset extends AssetBundle
{
    public $sourcePath = '@bower/typeahead.js/dist';
    public $css = [];
    public $js = [
        'typeahead.bundle.min.js',
    ];
    public $depends = [
        \yii\bootstrap\BootstrapAsset::class,
        \yii\bootstrap\BootstrapPluginAsset::class,
        \common\assets\metronic\TypeAheadBootstrapAsset::class,
    ];
}
