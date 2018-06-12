<?php 
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => dirname(__FILE__),
        ));

        return $autoloader;
    } 

}