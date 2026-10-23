<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'core' => frontend\modules\core\Module::class,
        'tracker' => frontend\modules\tracker\Module::class
    ],
    'components' => [
        'user' => [
            'identityClass' => common\models\ClientLogin::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/core/auth/login']
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
                '/' => 'tracker/jobs/index',
                '/tracker/artwork/preview/<id>.<ext>' => '/tracker/artwork/preview',
            ],
        ],
        'request' => [
            'cookieValidationKey' => $_ENV['COOKIE_VALIDATION_KEY'] ?? $_SERVER['COOKIE_VALIDATION_KEY'] ?? getenv('COOKIE_VALIDATION_KEY'),
        ],
    ],
    'params' => $params,
];
