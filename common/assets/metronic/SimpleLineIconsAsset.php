<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class SimpleLineIconsAsset extends AssetBundle
{
    public $sourcePath = '@bower/simple-line-icons';
    public $css = [
        'css/simple-line-icons.css',
    ];
    public $js = [];
    public $depends = [];
}