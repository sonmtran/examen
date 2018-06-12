<?php

class Defined_RouterControl {

    static function defineRoute() {
        
        $oView = new Zend_View();
        
        
        

        $skey=$oView->translate('k_home');
        self::addRouter('k_home', $skey, array(),'default', 'index','index');
        
        $skey=$oView->translate('k_success');
        self::addRouter('k_success', $skey, array(1=>'status'),'default', 'index', 'success');

        
        $skey=$oView->translate('k_404');
        self::addRouter('k_404', $skey, array(),'default', 'error', 'notfound');
        
        unset($oView);
    }

	public static  function addRouter($sRouteName, $sRegex, $arrRef, $sModule, $sController, $sAction, $sKey = '') 
    {
        $router 	  =  Zend_Controller_Front::getInstance()->getRouter();
		$sUrlReserved =  str_replace('([a-zA-Z0-9-_]+)', '%s', $sRegex);
		
        $router		  -> addRoute(  $sRouteName, 
        							new Zend_Controller_Router_Route_Regex(
							                $sRegex, 
							                array(  'module' 	 => $sModule,
							                        'controller' => $sController,
							                        'action' 	 => $sAction,
							                        'url-key'	 => $sKey), 
											$arrRef,
											$sUrlReserved
					              	)
       							 );
       							 
    }
}
