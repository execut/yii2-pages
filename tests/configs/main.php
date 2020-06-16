<?php
$params = [];
return [
    'id' => 'app-test',
    'basePath' => __DIR__ . '/../unit',
    'vendorPath' => __DIR__ . '/../../../execut/',
    'bootstrap' => [],
    'modules' => [],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
            'scriptFile' => __DIR__ .'/index.php',
            'scriptUrl' => '/index.php',
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../assets/',
            'bundles' => []
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                ],
            ]
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'enableSchemaCache' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'params' => $params,
];
