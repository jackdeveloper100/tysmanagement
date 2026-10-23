<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'mcborax',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'core' => backend\modules\core\Module::class,
        'tys' => backend\modules\tys\Module::class,
        'tracker' => backend\modules\tracker\Module::class,
        'accounting' => backend\modules\accounting\Module::class,
        'api' => backend\modules\api\Module::class,
        'gridview' => kartik\grid\Module::class
    ],
    'components' => [
        'user' => [
            'identityClass' => common\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/core/auth/login'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'core/error/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'tracker/tasks/index',
                '/tracker/artwork/preview/<id>.<ext>' => '/tracker/artwork/preview',
                '<module>' => '<module>/default/index'
            ],
        ],
        'urlManagerFrontend' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $params['urlRules']['frontend']
        ],
        'request' => [
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY'),
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
                'application/json; charset=UTF-8' => yii\web\JsonParser::class,
            ]
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::class
        ],
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => mihaildev\elfinder\Controller::class,
            'access' => ['@'],
            'disabledCommands' => ['netmount'],
            'roots' => [
                [
                    'baseUrl' => getenv('USER_FILES_URL'),
                    'basePath' => '@frontend/web/static/user_files',
                    'path' => '',
                    'name' => 'Main'
                ]
            ]
        ]
    ],
    'params' => $params,
];
