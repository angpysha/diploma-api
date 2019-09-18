<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'app\modules\v1\Api'
        ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/diploma'
            // 'options' => [
            //     "username" => "",
            //     "password" => ""
            // ]
            ],
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
            'enableSession' => true,
            'loginUrl' => null,
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
                ['class' => 'yii\rest\UrlRule', 'prefix' => '/api', 'controller' => 'v1/bmp',
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
                        'POST firstlastdates' => 'firstlastdates',
                        'GET index' => 'index',
                        'GET charts/<id:\d+>' => 'chart'
                    ]],

                    ['class' => 'yii\rest\UrlRule', 'prefix' => '/api', 'controller' => 'v1/dht', 'extraPatterns' => [
                        'GET addd' => 'addd',
                        'POST,HEAD add' => 'add',
                        'POST search' => 'search',
                        'PUT update/<id:\d+>' => 'update',
                        'DELETE delete/<id:\d+>' => 'delete',
                        'POST last' => 'last',
                        'POST get/<id:\d+>' => 'get',
                        'POST datecount' => 'datecount',
                        'POST first' => 'first',
                        'POST firstlastdates' => 'firstlastdates',
                        'GET index' => 'index',
                        'GET last' => 'last',
                        'GET charts/<id:\d+>' => 'chart'

                    ]],
                    ['class' => 'yii\rest\UrlRule', 'prefix' => '/api', 'controller' => 'v1/sensor', 'extraPatterns' => [
                        'POST add' => 'add',
                        'GET test' => 'test',
                        'GET getall' => 'getall',
                        'POST getbytype' => 'getbytype',
                        'PUT update/<id:\w+>' => 'update',
                        'DELETE delete/<id:\w+>' => 'delete',
                        'GET get/<id:\w+>' => 'get',
                        'POST search' => 'search'
                    ]],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/default', 'extraPatterns' => [

                ]],
//                    'api/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                    'swagger' => 'v1/default/docs',
                    'dhts' => 'v1/dht/index',
                    'bmps' => 'v1/bmp/index',
                    'bmps/charts/<id:\d+>' => 'v1/bmp/charts/<id:\d+>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/default/<action>',
                '' => 'site/index'
//                '<module:\w+>/<controller:\w+>' => '<module>/default',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

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
