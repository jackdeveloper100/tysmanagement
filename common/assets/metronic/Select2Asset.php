<?php
/**
 * Created by Skinka.
 * Date: 21.01.2016 22:38
 */

namespace common\assets\metronic;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';
    public $css = [
        'css/select2.min.css'
    ];
    public $js = [
        'js/select2.min.js',
        'js/i18n/ru.js',
    ];
    public $depends = [
        \yii\web\JqueryAsset::class,
        \yii\bootstrap\BootstrapPluginAsset::class,
    ];
}
