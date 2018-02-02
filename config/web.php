<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'uAhW_l_LuBQhk2VfzsIYUhtDwCUx9TlU',
            //'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ] 
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'dht',
                'extraPatterns' => [
                    'GET addd' => 'addd',
                    'POST add' => 'add',
                    'POST search' => 'search',
                    'PUT update/<id:\d+>' => 'update',
                    'DELETE delete/<id:\d+>' => 'delete',
                    'POST last' => 'last',
                    'POST get/<id:\d+>' => 'get',
                    'POST datecount' => 'datecount',
                    'POST first' => 'first',
                    'POST firstlastdates' => 'firstlastdates',
                    'GET index' => 'index'

                ]],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'bmp',
                    'extraPatterns' => [
                        'GET test' => 'test',
                        'POST add' => 'add',
                        'PUT update/<id:\d+>' => 'update',
                        'DELETE delete/<id:\d+>' => 'delete',
                        'POST last' => 'last',
                        'POST get/<id:\d+>' => 'get',
                        'POST search' => 'search',
                        'POST datecount' => 'datecount',
                        'POST first' => 'first',
                        'POST sendevent' => 'sendevent',
                        'POST firstlastdates' => 'firstlastdates'
                    ]],
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
