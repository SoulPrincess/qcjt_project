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
        ],
        'official' => [
            'class' => 'official\Module'
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'qingrui' => [
            'class' => 'qingrui\Module'
        ],
    ],
    "aliases" => [
        '@rbac' => '@backend/modules/rbac',
		'@system' => '@backend/modules/system',
		'@backup' => '@backend/modules/backup',
		'@content' => '@backend/modules/content',
		'@official' => '@backend/modules/official',
		'@qingrui' => '@backend/modules/qingrui',
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
            'flushInterval' => 1,
//            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                ],
//            ],
            'targets' => [
                'db' =>[
                    //'levels' => ['warning'],

                    //'categories' => ['appadmin'],
                    'categories'=>'system.db.*',//只显示关于数据库信息，包括数据库连接，数据库执行语句
                  //  'class'=>'CFileLogRoute',//文件记录日志的形式
                   'class' => 'yii\log\FileTarget',
                    'exportInterval' => 1,
                    # 输出文件
//                    'logPath'=>'@backend',//日志保存路径
                    'logFile' => '@backend/app_'.date('Y-m-d').'.log',//日志保存文件名
//                    'logFile' => '@backend/runtime/logs/appadmin.log',
                  //  'logTable' => '{{%system_log}}',
                    # 你可以通过配置 yii\log\Target::prefix 的属性来自定义格式，这个属性是一个PHP可调用体返回的自定义消息前缀
                    //'prefix' => function ($message) {

                    //  return $message;
                    //},
                    # 除了消息前缀以外，日志目标也可以追加一些上下文信息到每组日志消息中。 默认情况下，这些全局的PHP变量的值被包含在：$_GET, $_POST, $_FILES, $_COOKIE,$_SESSION 和 $_SERVER 中。 你可以通过配置 yii\log\Target::logVars 属性适应这个行为，这个属性是你想要通过日志目标包含的全局变量名称。 举个例子，下面的日志目标配置指明了只有 $_SERVER 变量的值将被追加到日志消息中。
                    # 你可以将 logVars 配置成一个空数组来完全禁止上下文信息包含。或者假如你想要实现你自己提供上下文信息的方式， 你可以重写 yii\log\Target::getContextMessage() 方法。
                    'logVars' => [],
                ],
//                'routes'=>[
//                'class'=>'CFileLogRoute',//文件记录日志的形式
//                'levels'=>'trace',//日志记录级别
//                'categories'=>'system.db.*',//只显示关于数据库信息，包括数据库连接，数据库执行语句
//                'logFile' => 'app_'.date('Y-m-d').'.log',//日志保存文件名
////                'logPath'=>'D:\phpStudy\WWW\phpernote\com\tmpfile\log_db',//日志保存路径
//                'logPath'=>'@backend',//日志保存路径
//                ]
            ]
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
