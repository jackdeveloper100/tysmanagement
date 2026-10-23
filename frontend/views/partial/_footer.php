<div class="page-footer">
    <div class="page-footer-inner">
        <?php
        $versionFile = Yii::getAlias('@common/../.version');
        $version = is_file($versionFile) ? trim(file_get_contents($versionFile)) : '2.0.0';
        ?>
        Build <?= $version . (YII_DEBUG ? '_dev' : '') ?>
    </div>
    <div class="scroll-to-top" style="display: block;">
        <i class="icon-arrow-up"></i>
    </div>
</div>