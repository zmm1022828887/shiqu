<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'识趣',
        'language' => 'zh_CN',
	// preloading 'log' component
	'preload'=>array(
            'log',
            'bootstrap'
        ),
        'theme' => 'classic',
	// autoloading model and component classes
	'import'=>array(
            'application.models.*',
            'application.components.*','application.helpers.*','ext.select2.Select2',
	),
        'aliases' => array(
            'bootstrap' => 'application.extensions.bootstrap',
        ),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'websit',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
                'manage',
                'front'
	),

        'defaultController'=>'default/index',
	// application components
	'components'=>array(
                'bootstrap'=>array(
                    'class'=>'bootstrap.components.Bootstrap',
                ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
                        //'class' => 'application.components.AuthWebUser',
                        'loginUrl' => array('/default/index'),
		),
                'mailer' => array(
                    'class' => 'application.extensions.mailer.EMailer',
                    'pathViews' => 'application.views.email',
                    'pathLayouts' => 'application.views.email.layouts'
                 ),
                'simpleImage'=>array(
                        'class' => 'ext.simpleimage.CSimpleImage',
                ),
            'image'=>array(
                'class'=>'application.extensions.image.CImageComponent',
                  // GD or ImageMagick
                  'driver'=>'GD',
                  // ImageMagick setup path
                  'params'=>array('directory'=>'/opt/local/bin'),
              ),
//             'authManager' => array(
//                    //'class' => 'TDbAuthManager',
//                    'connectionID' => 'db',
//                    'defaultRoles' => array('authenticated', 'guest'),
//                    'behaviors' => array(
//                        'auth' => array(
//                            //'class' => 'application.components.AuthBehavior',
//                            'admins' => array('admin'),
//                        ),
//                    ),
//                ),
		// uncomment the following to enable URLs in path-format
//		
//		'urlManager'=>array(
//			//'urlFormat'=>'path',
//                        'showScriptName' => true,
////			'rules'=>array(
////				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
////				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
////				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
////			),
//		),
            'urlManager' => array(
            'urlFormat' => 'path',
//                  'showScriptName' => false,
           'showScriptName' => false,
//            'rules' => array(
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
//            ),
        ),
                /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		*/
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=shiqu;port=3305',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'myoa888',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'default/error',
		),
//            'session' => array(
//                'class' => 'system.web.CDbHttpSession',
//                'connectionID' => 'db',
//                'sessionTableName' => 'session',
//            ),
                 'session' => array(
            'class' =>  'system.web.CDbHttpSession',
            'connectionID' => 'db',
            'sessionTableName' => 'session',
            'sessionName' => 'SHIQUSESSID',
            'cookieMode' => 'only',
            'timeout' => 1200
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__) . '/params.php'),
);