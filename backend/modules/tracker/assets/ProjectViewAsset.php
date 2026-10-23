<?php
namespace backend\modules\tracker\assets;

use backend\assets\BackendAsset;
use yii\web\AssetBundle;

class ProjectViewAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
        'css/pages/project_view.css'
    ];
    public $js = [];
    public $depends = [
        BackendAsset::class
    ];
}
