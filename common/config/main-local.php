<?php
return [
    'components' => [
        'db' => [
           'class' => 'yii\db\Connection',
           'dsn' => 'mysql:host=52.67.208.141;dbname=estalecasdev',
           'username' => 'root',
           'password' => 'est@l3C@sSuccEss',
           'charset' => 'utf8',
        ],
       /* 'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=estalecasdev',
            'username' => 'root',
            'password' => '@by02016Abio#',
            'charset' => 'utf8',
        ],*/
        'mail' => [
         'class' => 'yii\swiftmailer\Mailer',
         'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'appestalecasmail@gmail.com',
            'password' => 'p@ssw0rd0007',
            'port' => '587',
            'encryption' => 'tls',
        ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]
     
    ],
     'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ]
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'admin/*',    
            '*/*',    
            
            
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],

];
