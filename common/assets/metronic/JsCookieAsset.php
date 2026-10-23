<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class JsCookieAsset extends AssetBundle
{
    public $sourcePath = '@bower/js-cookie/src';
    public $css = [];
    public $js = [
        'js.cookie.js'
    ];
    public $depends = [];
}