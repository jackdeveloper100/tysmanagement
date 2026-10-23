<?php
$user = Yii::$app->user->identity;
echo \common\widgets\Nav::widget([
    'options' => [
        'class' => 'page-sidebar-menu',
        'data-keep-expanded' => 'false',
        'data-auto-scroll' => 'false',
        'data-slide-speed' => 200
    ],
    'pageSidebarMenuOptions' => [
        'class' => 'page-sidebar-menu-hover-submenu'
    ],
    'items' => [
        [
            'icon' => 'fa fa-suitcase',
            'label' => 'Projects',
            'url' => ['/tracker/jobs/index']
        ]
    ]
]);