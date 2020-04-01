<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params_local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params_local.php'),
    require(__DIR__ . '/main-local.php')
);

return [
    'id' => 'app-backend',
	'name' => '青成集团后台管理',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        "rbac" => [        
            'class' => 'rbac\Module',
        ],
        "system" => [        
            'class' => 'system\Module',
        ],
        "backup" => [        
            'class' => 'backup\Module',
        ],
        "content"=>[
            'class'=>'content\Module'
        ]
    ],
    "aliases" => [    
        '@rbac' => '@backend/modules/rbac',
		'@system' => '@backend/modules/system',
		'@backup' => '@backend/modules/backup',
		'@content' => '@backend/modules/content',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'rbac\models\User',
            'loginUrl' => array('/rbac/user/login'),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-backend',
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
        "authManager" => [        
            "class" => 'yii\rbac\DbManager',   
            "defaultRoles" => ["guest"],    
        ],
        //url重写
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
    //rbac权限控制
    'as access' => [
        'class' => 'rbac\components\AccessControl',
        'allowActions' => [
            'rbac/user/request-password-reset',
            'rbac/user/reset-password'
        ]
    ],
    'params' => $params,

];
