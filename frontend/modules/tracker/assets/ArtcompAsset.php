<?php
namespace frontend\modules\tracker\assets;

use backend\assets\BackendAsset;
use common\assets\DropzoneAsset;
use common\assets\XEditableAsset;
use yii\web\AssetBundle;

class ArtcompAsset extends AssetBundle
{
    public $basePath = '@webroot/static';
    public $baseUrl = '@web/static';

    public $css = [
        'css/pages/artcomp.css'
    ];
    public $js = [];
    public $depends = [
        BackendAsset::class
    ];
}
