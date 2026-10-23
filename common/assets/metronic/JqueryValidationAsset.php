<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class JqueryValidationAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-validation/dist';
    public $css = [];
    public $js = [
        'jquery.validate.min.js',
        'additional-methods.min.js'
    ];
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}