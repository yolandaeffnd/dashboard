<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-bo-sireg-plus',
    'name' => 'Dashboard Business Intelligence',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Jakarta',
	'aliases' => [
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => array_merge($db,[
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sireg-plus-bo-unand-oORmoUKQltir6QyaKRw1_hm_r8Orl-gD_tqwert54228bkJhgHuiOWrTtyUiOo99877',
			'baseUrl' => str_replace('/web', '', (new \yii\web\Request)->getBaseUrl()),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
	    'authTimeout' => 5, // auth expire
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.unand.ac.id',
                'username' => '',
                'password' => '',
                'port' => '25',
                'encryption' => '',
            ],
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
        //'db' => require(__DIR__ . '/db.php'),
        //'dbSireg' => require(__DIR__ . '/dbSireg.php'),
        //'dbSiaFh' => require(__DIR__ . '/dbSiaFh.php'),
        //'dbSiaFt' => require(__DIR__ . '/dbSiaFt.php'),
        'session' => [
            'class' => 'yii\web\DbSession',
            // Set the following if you want to use DB component other than default 'db'.
            'db' => 'db',
            // To override default session table, set the following
            'sessionTable' => 'app_session',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<action>' => 'site/<action>',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
        ],
        'terbilang' => [
            'class' => 'app\components\Terbilang',
        ],
        'versionApp' => [
            'class' => 'app\components\AppVersion'
        ],
    ]),
    'params' => $params,
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'aplikasi' => [
            'class' => 'app\modules\aplikasi\Module',
        ],
	'akademik' => [
            'class' => 'app\modules\akademik\Module',
        ],
        'pengolahan' => [
            'class' => 'app\modules\pengolahan\Module',
        ],
        'mahasiswa' => [
            'class' => 'app\modules\mahasiswa\Module',
        ],
	'kepegawaian' => [
            'class' => 'app\modules\kepegawaian\Module',
        ],
        'maping' => [
            'class' => 'app\modules\maping\Module',
        ],
    ]
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
