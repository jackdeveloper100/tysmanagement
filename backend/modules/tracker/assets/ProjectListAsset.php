<?php
namespace backend\modules\tracker\assets;

use backend\assets\BackendAsset;
use yii\web\AssetBundle;

class ProjectListAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [
        'css/pages/project_list.css'
    ];
    public $js = [
        'js/pages/project_list.js',
    ];
    public $depends = [
        BackendAsset::class
    ];
}
