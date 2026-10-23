<?php
$smtpDsn = 'null://null';
if (getenv('SMTP_HOST')) {
    $smtpScheme = strtolower((string)getenv('SMTP_ENCRYPTION')) === 'ssl' ? 'smtps' : 'smtp';
    $smtpAuth = getenv('SMTP_USERNAME') !== false && getenv('SMTP_USERNAME') !== ''
        ? rawurlencode(getenv('SMTP_USERNAME')) . ':' . rawurlencode((string)getenv('SMTP_PASSWORD')) . '@'
        : '';
    $smtpPort = getenv('SMTP_PORT') ? ':' . getenv('SMTP_PORT') : '';
    $smtpDsn = $smtpScheme . '://' . $smtpAuth . getenv('SMTP_HOST') . $smtpPort;
}

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
            'class' => yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => (bool)getenv('MAILER_FILE_TRANSFER'),
            'transport' => [
                'dsn' => $smtpDsn,
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
    $config['components']['log'] = [
        'targets' => [
            [
                'class' => \common\components\SentryTarget::class,
                'dsn' => getenv('SENTRY_DSN'),
                'environment' => getenv('YII_ENV'),
                'levels' => ['error', 'warning'],
                'except' => [
                    'yii\web\HttpException:404',
                    'yiiase\InvalidRouteException',
                ]
            ]
        ],
    ];
}

return $config;
