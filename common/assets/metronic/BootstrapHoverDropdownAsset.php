<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class BootstrapHoverDropdownAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-hover-dropdown/';
    public $css = [];
    public $js = [
        'bootstrap-hover-dropdown.min.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}