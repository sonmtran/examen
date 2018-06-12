<?php

class Plugin_LanguageHandler extends Zend_Controller_Plugin_Abstract {

    private $arrConfig = array();
    
    public function __construct($LanguageOption) {
        $this->arrConfig = $LanguageOption;
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $sCurrentRouter = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        Zend_Registry::set('CurrentRoute', $sCurrentRouter);
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    	# add subdomain language
    	$this->addSubDomain();
    	
    	# Detect language before system match router
    	$params = $this->getRouterMath();

    	
    	
    	foreach ($params as $param => $value)
    	{
    		$request->setParam($param, $value);
    	}
    	unset($params, $param, $value);
    	
    	# Use translator
    	$this->callLanguage();
        Zend_Form::setDefaultTranslator(Zend_Registry::get('Zend_Translate'));
        
        # Add rewrite router
        Defined_RouterControl::defineRoute();
        
        # Find and add router if routerMath result is false
        if (! $this->routerMath2($request))
        {
        	
        	$this->findRouter($request);
        }
        else
        {
        	
        	# unset
        	$this->unsetDetectLang();
        } 
    }

    function callLanguage($lang = '') 
    {
        # start setup language
        $oLang =  Defined_Language::getInstant();
        
        $oLang -> setOption($this->arrConfig);
		
        $sLang = $this->getRequest()->language;
        
        # set language by request
        $oLang -> install($sLang);
        # set zend register to translator
        $oLang -> load();
    }
    
    
    private function addSubDomain()
    { 
    	$oFront = Zend_Controller_Front::getInstance();
    	
    	$subdomain = new Zend_Controller_Router_Route_Hostname(
    			':language.'.$oFront->getParam('bootstrap')->getOption('domain_name'),
    			array(
    					'module' => 'default'
    			),
				array(
    	                'language' => '(en)|(vn)'
    	        )
    	);
    	$pathRoute     = new Zend_Controller_Router_Route(
    			':controller/:action/*',
    			array(
    					'controller' => 'index',
    					'action'     => 'index'
    			)
    	);
    	
    	$chainedRoute = $subdomain->chain($pathRoute);
    	$oFront->getRouter()->addRoute('subdomain', $chainedRoute);
    	
    	# khong su dung cookie
    	unset($_COOKIE["lang_index"]);
    }
	
    
    /******* Dai Viet them. Dua vao url phat hien ngon ngu ********/
    private function getRouterMath()
    {
    	$router = Zend_Controller_Front::getInstance()->getRouter();
    	$request = Zend_Controller_Front::getInstance()->getRequest();
    	 
    	// Find the matching route
    	$routeMatched = false;
    	 
    	foreach (array_reverse($router->getRoutes(), true) as $name => $route) {
    		// TODO: Should be an interface method. Hack for 1.0 BC
    		if (method_exists($route, 'isAbstract') && $route->isAbstract()) {
    			continue;
    		}
    		 
    		// TODO: Should be an interface method. Hack for 1.0 BC
    		if (!method_exists($route, 'getVersion') || $route->getVersion() == 1) {
    			$match = $request->getPathInfo();
    		} else {
    			$match = $request;
    		}
    		 
    		if ($params = $route->match($match)) {
    			$routeMatched        = $params;
    			break;
    		}
    	}
    	 
    	return $routeMatched;
    }
    
    
    /**
     * Match when change language hold old url
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return boolean
     */
    private function routerMath2(Zend_Controller_Request_Abstract $request)
    {
    	$router = Zend_Controller_Front::getInstance()->getRouter();
    	$arrRouter = array_reverse($router->getRoutes(), true);
    	
    	unset($arrRouter['default'], $arrRouter['subdomain']);
    
    	
    	foreach ($arrRouter as $route) {
    
    		
    		// TODO: Should be an interface method. Hack for 1.0 BC
    		if (method_exists($route, 'isAbstract') && $route->isAbstract()) {
    			continue;
    		}
    		 
    		// TODO: Should be an interface method. Hack for 1.0 BC
    		if (!method_exists($route, 'getVersion') || $route->getVersion() == 1) {
    			$match = $request->getPathInfo();
    		} else {
    			$match = $request;
    		}
    	
    		//echo "<pre>";
    		//print_r($route);
    		if ($route->match($match)) {	
    			//echo "<pre>";
    			//print_r($route);
    		
    			return true;
    		}
    
    	}
    	 
    	return false;
    }
    
    /**
     * Find and add router (if found)
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    private function findRouter(Zend_Controller_Request_Abstract $request)
    {
    	$translate = null;
    	$arrLang = array('en', 'vi');
    	$path = $request->getPathInfo();
    
    	// xoa ngon ngu hien tai
    	array_diff($arrLang, array(Zend_Registry::get('Zend_Locale')));
    
    	foreach ($arrLang as $lang)
    	{
    
    		$translate = new Zend_Translate(
    				array(
    						'adapter' => 'ini',
    						'content' => APPLICATION_PATH.'/library/Language/'.$lang.'_url.ini',
    						'locale'  => $lang,
    						//'scan' => Zend_Translate::LOCALE_FILENAME
    				)
    		);
    		$path = trim(urldecode($path), '/');
    
    		foreach ($translate->getAdapter()->getMessages($lang) as $sName => $sRegex)
    		{
    			$regex = '#^' . $sRegex . '$#i';
    
    			if (preg_match($regex, $path))
    			{
    				//setcookie('lang_detect', $lang, time() + 3600, '/');
    				Zend_Registry::set('lang_detect', $lang);
    				
    				// Tu tim url ma khong lam thay doi ngon ngu hien tai
    				$router = Zend_Controller_Front::getInstance()->getRouter()->getRoute($sName);
    				$arrMap = array();
    				
    				foreach ($router->getVariables() as $key => $value)
    				{
   						$arrMap[$key + 1] = $value;
    				}
    				//ini_set('display_errors', '1');
    				# add found router
    				Defined_RouterControl::addRouter($sName.'_'.$lang, $translate->_($sRegex), $arrMap, $router->getDefault('module'), $router->getDefault('controller'), $router->getDefault('action'));
    				
    				
    				return;
    			}
    		}
    
    	}
    	
    	$this->unsetDetectLang();
    }
    
    
    private function unsetDetectLang()
    {
    	if (Zend_Registry::getInstance()->offsetExists('lang_detect'))
    	{
    		$lang_detect = Zend_Registry::get('lang_detect');
    		unset($lang_detect);
    	}
    }
}
