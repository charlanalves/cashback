<?php
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
        'pagamentoComponent' => [
            'class' => 'common\components\PagamentoComponent',
        ],
        'u' => [
            'class' => 'common\components\UtilComponent',
        ],
        'dataDumpComponent' => [
            'class' => 'common\components\dataDumpComponent',
        ],
        'Iugu' => [
            'class' => 'common\components\IuguComponent',
        ],
         'sendMail' => [
            'class' => 'common\components\SendMailComponent',
        ],
      
    ],
    
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ]
    ],
     'aliases' => [
        '@common' => '../../common',
        '@vendor' => '../../vendor',
        '@assetsPath' => '../../vendor',
        '@dhtmlxImg' => '../../../../vendor/dhtmlx/imgs',
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/login',          
            'site/loginApp',          
            'site/login-app',          
            'site/logout',
            'site/cadastro',
            'estabelecimento/index',
            'estabelecimento/login',
            'estabelecimento/logout',
            'gii/*',
            'admin/*',
            'cliente/*',
            'api/*',
            'administrador/index',
            'administrador/login',
            'administrador/logout',
		    'administrador/*',
    		'transferencias/*',
            'indicacao/index',
            'confirma-email/index',


            'api-empresa/*',
            
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],

];
