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
            'url' => ['/tracker/jobs/index'],
            'visible' => Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'employee'),
        ],
        [
            'icon' => 'fa fa-clock-o',
            'label' => 'Manage Hours',
            'url' => ['/tracker/tasks/index'],
            'visible' => Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'employee'),
        ],
        [
            'icon' => 'fa fa-cloud-download',
            'label' => 'Reports',
            'url' => ['/accounting/reports/index'],
            'visible' => Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'accountant'),
        ],
        [
            'icon' => 'fa fa-group',
            'label' => 'TYS Users',
            'url' => ['/tys/users/index'],
            'visible' => Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'superuser'),
        ],
        [
            'icon' => 'fa fa-building-o',
            'label' => 'Clients',
            'url' => ['/tys/clients/index'],
            'visible' => Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager'),
        ],
        [
            'icon' => 'fa fa-terminal',
            'label' => 'Domains',
            'url' => ['/tys/domains/all'],
            'visible' => Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager'),
        ]
    ]
]);