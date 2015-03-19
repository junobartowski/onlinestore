<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('PayPal', dirname(__FILE__).'/../extensions/paypal');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Online Store',

	// preloading 'log' component
	'preload'=>array('log'),
	'defaultController' => 'item',
	/*
	'aliases' => array(
        'gumby' => realpath(__DIR__ . '/../extensions/Gumby'), // change this if necessary
    ),
    */

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=onlinestore',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'enableParamLogging' => true,
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
	'params' => array(
		// this is used in contact page
		'projectName' => 'Online Store',
		'administratorName' => 'Online Store Team',
		'adminEmail' => 'webmaster@example.com',
		'defaultURLPrefix' => 'http://localhost/onlinestore/index.php/',
		'activationCodeLength' => 10,
                'buyTokenLength' => 30,
                'maximumFirstNameLength' => 50,
		'maximumLastNameLength' => 50,
		'minimumUsernameLength' => 8,
		'maximumUsernameLength' => 20,
		'minimumPasswordLength' => 8,
		'maximumPasswordLength' => 20,
		'minimumItemNameLength' => 5,
		'maximumItemNameLength' => 50,
		'maximumActivationAttempt' => 5, //set to 0 to turn off checking of maximum attempts
		'minimumItemNameLength' => 5,
		'maximumItemNameLength' => 50,
		'maximumItemPrice' => 10000000,
		'minimumItemDescriptionLength' => 10,
		'maximumItemDescriptionLength' => 10000,
                'maximumDonationAmount' => 10000000,
		'superUserMode' => '0777',
		'groupMode' => '0775',
		'payPalMerchantEmailAddress' => '----- Merchant Email Here -----',
		'payPalAppClientID' => '----- PayPal App ID Here -----',
		'payPalAppSecret' => '----- PayPal App Secret Here -----',
		'payPalReturnURL' => 'item/paypalPayment',
		'payPalCancelURL' => 'item/multiple',
                'payPalDonationReturnURL' => 'donate/paypalPayment',
		'payPalDonationCancelURL' => 'donate/directdonation',
	),
);