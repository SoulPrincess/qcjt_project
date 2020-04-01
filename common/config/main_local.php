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
        //发送邮箱设置
        'mailer'=>[
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,    //这里一定要改成false，不然邮件不会发送
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => '15639277320@163.com',
                'password' => '123456',        //如果是163邮箱，此处要填授权码
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['15639277320@163.com'=>'admin']
            ]
        ]
    ]
];