<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $oFrontController;

    public function __construct($application) {
        parent::__construct($application);
        $this->oFrontController = Zend_Controller_Front::getInstance();
        //set time  zone
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    
    }

    protected function _initDb() 
    {

        $resource = $this->getPluginResource('multidb');
        $resource->init();
        $dbOption = $resource->getOptions();
  
        foreach ($dbOption as $dbName => $arrDB) {
            $db = $resource->getDb($dbName);
            $db->setFetchMode(Zend_Db::FETCH_ASSOC);
            $db->query("SET NAMES 'utf8'");
            $db->query("SET CHARACTER SET 'utf8'");
            Zend_Registry::set($dbName, $db);
        }
        unset($dbOption, $resource, $db,$param);
    }

    public function _initRouter() {
        $router = $this->oFrontController->getRouter();
        # add default router
        $routeDefault = new Zend_Controller_Router_Route_Module(
                array(), $this->oFrontController->getDispatcher(), $this->oFrontController->getRequest()
        );

        # Add both language route chained with default route and
        # plain language route
        $router->addRoute('default', $routeDefault);
        # Register plugin to handle language changes
        $LanguageOption = $this->getOption('language');
        $this->oFrontController->registerPlugin(new Plugin_LanguageHandler($LanguageOption));
    }

	
    protected function _initLog()
    {
    	$options = $this->getOption('resources');
    	$logger = Zend_Log::factory($options['log']);
    	$logger->addPriority('USERACTION', 8);
    	$logger->addPriority('DBLOG', 9);
    	Zend_Registry::set('logger', $logger);
    }

}
