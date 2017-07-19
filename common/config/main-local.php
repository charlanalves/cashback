<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=34.206.36.55;dbname=estalecasdev',
            'username' => 'root',
            'password' => '6%)DT3y_dV,(',
            'charset' => 'utf8',
        ],/*
        'db' => [
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
             'host' => 'smtp.sendgrid.net',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
             'username' => 'apikey',
             'password' => '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',
             'port' => '587', // Port 25 is a very common port too
             'encryption' => 'tls', // It is often used, check your provider or mail server specs
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
            
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],

];
