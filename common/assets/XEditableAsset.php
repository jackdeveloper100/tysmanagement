<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class XEditableAsset extends AssetBundle
{
    public $sourcePath = '@bower/x-editable/dist/bootstrap3-editable/';
    public $js = [
        'js/bootstrap-editable.min.js'
    ];
    public $css = [
        'css/bootstrap-editable.css'
    ];
    public $depends = [
        JqueryAsset::class
    ];
}
