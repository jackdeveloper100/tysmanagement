<?php
/**
 * Created by Skinka.
 * Date: 21.01.2016 22:38
 */

namespace common\assets\metronic;

use yii\web\AssetBundle;

class Select2BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/select2-bootstrap-theme/dist';
    public $css = [
        'select2-bootstrap.min.css'
    ];
    public $js = [
    ];
    public $depends = [
        \common\assets\metronic\Select2Asset::class,
    ];
}
