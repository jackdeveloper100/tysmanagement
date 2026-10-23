<?php
namespace frontend\modules\tracker\assets;

use backend\assets\BackendAsset;
use yii\web\AssetBundle;

class ProjectListAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
    ];
    public $js = [];
    public $depends = [
        BackendAsset::class
    ];
}
