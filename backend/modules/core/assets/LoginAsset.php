<?php
namespace backend\modules\core\assets;

use common\assets\metronic\MetronicCoreAsset;
use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
        'css/pages/login.css'
    ];
    public $js = [
        'js/pages/login.js',
    ];
    public $depends = [
        MetronicCoreAsset::class,
        \common\assets\metronic\BackstretchAsset::class
    ];
}
