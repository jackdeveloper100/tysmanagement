<?php
namespace backend\assets;

use yii\web\AssetBundle;

class MomentJsAsset extends AssetBundle
{
    public $sourcePath = '@bower/moment';
    public $css = [];
    public $js = [
        'moment.js'
    ];
    public $depends = [];
}
