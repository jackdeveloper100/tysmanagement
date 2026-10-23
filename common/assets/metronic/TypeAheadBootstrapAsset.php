<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class TypeAheadBootstrapAsset extends AssetBundle
{
    public $basePath = '@webroot/static/metronic/global/plugins/typeahead';
    public $baseUrl = '@web/static/metronic/global/plugins/typeahead';
    public $css = [
        'typeahead.css'
    ];
    public $js = [];
    public $depends = [];
}
