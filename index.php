<?php
define('WEBPAGE_ADDRESS', "http://".$_SERVER['SERVER_NAME']);

// Define path to application directory
defined('THEME_PATH')
	|| define('THEME_PATH', WEBPAGE_ADDRESS.'/theme/blackandwhite');

// Define path to application directory
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define path to application directory
defined('APPLICATION_PUBLIC_PATH')
	|| define('APPLICATION_PUBLIC_PATH', realpath(dirname(__FILE__)));

// Define application environment
defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'staging'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PUBLIC_PATH . '/library'),
	realpath(APPLICATION_PUBLIC_PATH . '/library/App/lib'),
	get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Created application, bootstrap, and run
$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
			->run();