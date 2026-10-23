<?php
namespace backend\modules\tracker\assets;

use backend\assets\BackendAsset;
use backend\assets\MomentJsAsset;
use yii\web\AssetBundle;

class UpdateHoursAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
        'css/pages/tasks.css',
    ];
    public $js = [
        'js/pages/update_hours.js',
    ];
    public $depends = [
        BackendAsset::class,
        MomentJsAsset::class
    ];
}
