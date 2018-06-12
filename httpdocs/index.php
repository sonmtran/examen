<?php
ini_set('display_errors',1);
error_reporting(15);


# Declare env variable of application
defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV',
		(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
								   :'live'));
								   
// Khai bao duong dan den thu muc chua ung dung
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH',
		realpath(dirname(__DIR__) . '/private'));
	
# Declare path to library
set_include_path(implode(PATH_SEPARATOR, array(
	APPLICATION_PATH . '/library',
	get_include_path(),
)));	

include_once APPLICATION_PATH . '/configs/define.php';

//Goi lop Zend_Applicatiom
require_once 'Zend/Application.php';
$options = PATH_CONFIG . '/application.ini';
$application = new Zend_Application(APPLICATION_ENV, $options);
$application->bootstrap()->run();



