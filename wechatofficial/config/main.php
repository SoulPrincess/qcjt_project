<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main_local.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'app-wechatofficial',
    'name' => 'alilinet',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'wechatofficial\controllers',
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/jquery-1.10.2.min.js',
                        'js/jquery.jplayer.min.js',
                    ]
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-wechatofficial',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-wechatofficial', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the wechatofficial
            'name' => 'advanced-wechatofficial',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        "urlManager" => [
            "enablePrettyUrl" => true,
            "enableStrictParsing" => false,
            "showScriptName" => false,
            "suffix" => "",
            "rules" => [
                "<controller:\w+>/<id:\d+>"=>"<controller>/view",
                "<controller:\w+>/<action:\w+>"=>"<controller>/<action>"
            ],
        ],
    ],
    'params' => $params,
];
