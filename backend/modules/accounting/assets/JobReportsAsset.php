<?php
namespace backend\modules\accounting\assets;

use backend\assets\BackendAsset;
use yii\web\AssetBundle;

class JobReportsAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';
    public $css = [];
    public $js = [
        'js/pages/job_reports.js',
    ];
    public $depends = [
        BackendAsset::class
    ];
}
