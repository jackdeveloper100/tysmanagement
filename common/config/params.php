<?php
return [
    'urlRules' => [
        'frontend' => [
            '/' => 'tracker/jobs/index'
        ]
    ],
    'main_url' => getenv('URL_FRONTEND'),
    'domains' => [
        'frontend' => getenv('DOMAIN_FRONTEND'),
        'backend' => getenv('DOMAIN_BACKEND')
    ],
    'user_folder_url' => getenv('USER_FILES_URL'),
    'uploads_url' => [
        'original' => getenv('UPLOADS_URL'),
        'cache' => getenv('UPLOADS_CACHE_URL')
    ]
];
