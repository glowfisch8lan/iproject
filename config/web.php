<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'queue',
//        \insolita\opcache\Bootstrap::class
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@systemViewPath' => '@app/modules/system/views'

    ],

    'modules' => [
            'system' => [
                'class' => 'app\modules\system\Module',
                'layout' => 'main'
            ],
            'metrica' => [
                'class' => 'app\modules\metrica\Module',
                'layout' => '@app/modules/system/views/layouts/main'
            ],
            'staff' => [
                'class' => 'app\modules\staff\Module',
                'layout' => '@app/modules/system/views/layouts/main',
            ],
            'inventory' => [
                'class' => 'app\modules\inventory\Module',
                'layout' => '@app/modules/system/views/layouts/main'
            ],
            'tools' => [
                'class' => 'app\modules\tools\Module',
                'layout' => '@app/modules/system/views/layouts/main'
            ],
            'opcache'=>[
                'class'=>'insolita\opcache\OpcacheModule',
                'as access'=>[
                   'class' => \yii\filters\AccessControl::class,
                               'rules' => [
                                   [
                                       'allow' => true,
                                       //Protect access
                                       'roles' => ['@'],
                                   ],
                               ],
                ],
                'layout' => '@app/modules/system/views/layouts/main'
            ],

    ],
    'language' => 'ru-RU',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'I97ZdigfE06A7OhDlh1_lqLPB9VM8DeL',
        ],
//        'session' => [
//            'class' => 'yii\redis\Session',
//            'redis' => [
//                'hostname' => '172.16.20.83',
//                'port' => 6379,
//                'database' => 4,
//            ]
//        ],
/*        //RBAC settings; У НАС СВОЙ RBAC
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable'       => 'auth_item',
            'itemChildTable'  => 'auth_item_child',
            'assignmentTable' => 'auth_assignment',
            'ruleTable'       => 'auth_rule',
            'defaultRoles'    => ['guest'],
        ],*/

        'assetManager'=>array(  // руководит браузерными скриптами и CSS
            'bundles' => array(
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'

                    ],
                ]

            )
        ),
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\system\models\users\Users',
            'loginUrl' => '/login',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'useFileTransport' => true,
//        ],
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
            'showScriptName' => false,

            'rules' => [
                'my' => 'system',
                'login' => 'system/default/login',
                'logout' => 'system/default/logout'
            ],
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // компонент подключения к БД
            'tableName' => '{{%system_queue}}', // Имя таблицы
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
        ]

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '*'],


                    'class' => \yii\debug\Module::class,
                    'panels' => [
                        'queue' => \yii\queue\debug\Panel::class,
                    ],


    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'job' => [
                'class' => \yii\queue\gii\Generator::class,
            ],
          ],
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '*'],
    ];
}

return $config;
