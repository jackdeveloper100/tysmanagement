<?php
namespace backend\modules\accounting\assets;

use backend\assets\BackendAsset;
use yii\web\AssetBundle;

class UserReportsAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = ['css/pages/user_hours.css'];
    public $js = [];
    public $depends = [
        BackendAsset::class
    ];
}
