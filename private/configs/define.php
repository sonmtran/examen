<?php
# Declare path to STATIC, UPLOAD
define('PATH_HTTPDOC', 	realpath(dirname(__FILE__) . '/httpdocs'));
define('PATH_STATIC', 	PATH_HTTPDOC . '/static');
define('URL_STATIC', 	'./static');

define('PATH_UPLOAD', 	PATH_HTTPDOC . '/upload');
define('URL_UPLOAD',  	'/static/upload/_multiThumbs/');
define('REPLACE_UPLOAD',  	'/static/upload');



# Declare some config
define('PATH_BLOCK',	APPLICATION_PATH . '/blocks');
define('PATH_CONFIG',	APPLICATION_PATH . '/configs');
define('EMAIL_ADMIN', 	"info@ilavietnam.com");

ini_set('post_max_size', 		50000000);
ini_set('upload_max_filesize', 	50000000);


