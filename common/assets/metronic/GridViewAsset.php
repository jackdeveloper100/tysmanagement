<?php
namespace common\assets\metronic;

use yii\web\AssetBundle;

class GridViewAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net-bs';
    public $js = [];
    public $css = [
        'css/dataTables.bootstrap.min.css'
    ];
    public $depends = [];
}