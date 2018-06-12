<?php

class Defined_Logger
{
	
	public static function write($message, $type = Zend_Log::ERR)
	{
		$logger = Zend_Registry::get('logger');
		$logger->setEventItem('ip', $_SERVER['REMOTE_ADDR']);
		$logger->log($message, $type);
	}

}