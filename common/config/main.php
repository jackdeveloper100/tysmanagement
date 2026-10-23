<?php
$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'en',
    'timeZone' => 'America/Los_Angeles',
    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'enableSchemaCache' => getenv('DB_SCHEMA_CACHE'),
            'schemaCacheDuration' => getenv('DB_SCHEMA_CACHE_DURATION'),
            'schemaCache' => 'cache'
        ],
        'cache' => [
            'class' => yii\caching\FileCache::class,
            /*'class' => yii\caching\MemCache::class,
            'servers' => [
                [
                    'host' => getenv('MEMCACHE_HOST'),
                    'port' => getenv('MEMCACHE_PORT')
                ]
            ]*/
        ],
        'fileCache' => [
            'class' => yii\caching\FileCache::class
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => getenv('MAILER_FILE_TRANSFER'),
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => getenv('SMTP_ENCRYPTION')
            ]
        ],
        'formatter' => [
            'currencyCode' => 'RUR'
        ],
        'thumbler' => [
            'class' => alexBond\thumbler\Thumbler::class,
            'sourcePath' => '@assets',
            'thumbsPath' => '@assets/cache',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ]
];

if (YII_DEBUG && !YII_ENV_TEST) {
    if (!YII_CONSOLE) {
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug']['class'] = yii\debug\Module::class;
        $config['modules']['debug']['allowedIPs'] = ['*.*.*.*'];
    }

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = yii\gii\Module::class;
    $config['modules']['gii']['allowedIPs'] = ['*.*.*.*'];
}

if (!empty(getenv('SENTRY_DSN'))) {
    $config['components']['sentry'] = [
        'class' => \mito\sentry\Component::class,
        'dsn' => getenv('SENTRY_DSN'),
        'enabled' => !empty(getenv('SENTRY_DSN')),
        'environment' => getenv('YII_ENV'),
    ];
    $config['components']['log'] = [
        'targets' => [
            [
                'class' => \mito\sentry\Target::class,
                'levels' => ['error', 'warning'],
                'except' => [
                    \yii\web\NotFoundHttpException::class,
                    \yii\base\InvalidRouteException::class,
                    'yii\web\HttpException:404'
                ]
            ]
        ],
    ];
}

return $config;
