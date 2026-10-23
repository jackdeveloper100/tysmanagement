<?php
namespace frontend\modules\core\assets;

use yii\web\AssetBundle;

class ErrorAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
        'css/pages/error.css'
    ];
    public $js = [

    ];
    public $depends = [
        \common\assets\metronic\MetronicAsset::class,
    ];
}
