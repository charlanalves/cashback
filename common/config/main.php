<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'v' => [
            'class' => 'common\components\ValidationComponent',
        ],
        'u' => [
            'class' => 'common\components\UtilComponent',
        ],
      
    ],
    
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ]
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/login',          
            'site/logout',
            'site/cadastro',
            'gii/*',
            'admin/*',
            'cliente/*'
            
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],

];
