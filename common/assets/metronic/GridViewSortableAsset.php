<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class GridViewSortableAsset extends AssetBundle
{
    public $basePath = '@webroot/static/metronic';
    public $baseUrl = '@web/static/metronic';
    /**
     * @var array JS
     */
    public $js = [
        'global/scripts/sortable.gridview.js',
    ];
    /**
     * @var array depends
     */
    public $depends = [
        \yii\jui\JuiAsset::class,
    ];
}