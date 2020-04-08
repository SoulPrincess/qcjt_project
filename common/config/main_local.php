<?php
return[
    'components'=>[
        //数据库配置
        'db'=>[
            'class'=>'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=qc_data',
            'username'=>'root',
            'password'=>'root',
            'charset'=>'utf8',
            'tablePrefix'=>'t_',

        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        //短信组件，采用短信
        'sms'=>[
            'class'=>'common\components\sms'
        ],
        //自定义生成中文昵称
        'nickname'=>[
            'class'=>'common\components\nickname'
        ],
        //自定义根据ip地址获取物理地址
        'ipaddress'=>[
            'class'=>'common\components\ipaddress'
        ],
        //微信组件
        'wechat'=>[
            'class'=>'common\components\wechat',
            'config'=>[
                'token'=>'',
                'appid'=>'',
                'appsecret'=>'',
                'encodingaeskey'=>'',
            ]
        ]
    ],
];