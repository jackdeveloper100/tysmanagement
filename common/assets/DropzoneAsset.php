<?php
namespace common\assets;

use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle
{
    public $sourcePath = '@bower/dropzone/dist/min/';
    public $js = [
        'dropzone.min.js'
    ];
    public $css = [
        'dropzone.min.css'
    ];
    public $depends = [];
}
